<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use App\Models\VehicleService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class ServiceController extends Controller
{
    public function create(Request $request, Vehicle $vehicle) {
        if($request->user()->id != $vehicle->user_id)
            abort(403);
        return view('services.form', ['veh' => $vehicle]);
    }

    public function store(Request $request, Vehicle $vehicle) {
        if($request->user()->id != $vehicle->user_id)
            abort(403);
        $this->validate($request, [
            'description' => 'required|string|max:250',
            'frequency_type' => 'required|string|max:5',
            'frequency' => 'required|integer|min:0'
        ]);
        $vehserv = new VehicleService();
        $vehserv->description = $request->post('description');
        $vehserv->frequency_type = $request->post('frequency_type');
        $vehserv->frequency = $request->post('frequency');
        $vehserv->vehicle_id = $vehicle->id;
        $vehserv->save();
        return redirect()->route('vehicles.show', ['vehicle' => $vehicle])->with('service_message', 'Service added.');
    }

    public function edit(Request $request, VehicleService $service) {
        if($request->user()->id != $service->vehicle->user_id)
            abort(403);
        return view('services.form', ['serv' => $service]);
    }

    public function destroy(Request $request, VehicleService $service) {
        if($request->user()->id != $service->vehicle->user_id)
            abort(403);
        $service->delete();
        return redirect()->route('vehicles.show', ['vehicle' => $service->vehicle])->with('service_message', 'Service deleted.');
    }
}
