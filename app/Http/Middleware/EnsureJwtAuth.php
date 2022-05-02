<?php

namespace App\Http\Middleware;

use App\Models\User;
use Carbon\Carbon;
use Closure;
use Firebase\JWT\JWK;
use Firebase\JWT\JWT;
use Firebase\JWT\Key;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class EnsureJwtAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure(\Illuminate\Http\Request): (\Illuminate\Http\Response|\Illuminate\Http\RedirectResponse)  $next
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next)
    {
        $testmode = false;
        $jwt = $request->header('Cf-Access-Jwt-Assertion');
        if($jwt == null) $jwt = $request->cookie('CF_Authorization');
        if($jwt == null) {
            $jwt = env('JWT_TEST');
            $testmode = true;
        }

        if($jwt == null) {
            return response("No supplied authentication", 401);
        }

        // Verify JWT

        $keyfile = null;
        if(Storage::disk('local')->exists('jwtpub.pem')) {
            $keyfile = Storage::disk('local')->get('jwtpub.pem');
        } else {
            $dlurl = env('JWT_PUBLIC_KEY_LOCATION');
            if($dlurl == null) {
                return response("Authentication Error", 500);
            }
            $keyfile = file_get_contents($dlurl);
            Storage::disk('local')->put('jwtpub.pem', $keyfile);
        }
        $key_js = json_decode($keyfile, true);
        $keys = null;
        if($key_js != null)
            $keys = JWK::parseKeySet($key_js);
        else
            $keys = new Key($keyfile, 'RS256');
        $decoded = null;
        try {
            $decoded = JWT::decode($jwt, $keys);
        } catch(\Exception $e) {
            return response("Supplied authentication is invalid (0)", 403);
        }

        if($decoded->iss != env('JWT_ISSUER')) {
            return response("Supplied authentication is invalid (1)", 403);
        }

        if(array_search(env('JWT_AUDIENCE'), $decoded->aud) === false) {
            return response("Supplied authentication is invalid (2)", 403);
        }

        if($decoded->email != $request->header('Cf-Access-Authenticated-User-Email') && !$testmode) {
            return response("Identity Mismatch", 403);
        } else if($testmode) {
            $request->headers->set('Cf-Access-Authenticated-User-Email', $decoded->email);
            // For parts of the application that may read the header
        }

        $uid = $decoded->sub;

        // Create or get user object
        $user = User::where('id', $uid)->first();

        if($user == null) {
            $user = new User();
            $user->id = $uid;
            $user->email = $decoded->email;
            $user->email_verified_at = Carbon::now();
            $user->name = "New User";
            $user->save();
        }

        // Add parameters to request
        $request->merge([
            'session_expiry' => $decoded->exp,
            'user_email' => $decoded->email,
            'user' => $user
        ]);
        $request->setUserResolver(function() use ($user) {
            return $user;
        });

        // Everything is good here, Jim. Send to the controller.
        return $next($request);
    }
}
