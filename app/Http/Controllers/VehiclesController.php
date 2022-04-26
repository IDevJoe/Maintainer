<?php

namespace App\Http\Controllers;

use App\Models\Vehicle;
use Illuminate\Http\Request;

class VehiclesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        return view('vehicles.index', ['vehs' => $request->user()->vehicles]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('vehicles.form');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
            'year' => 'required|integer',
            'make' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'vin' => 'required|string|size:17',
            'plate' => 'required|string|max:10',
            'miles' => 'required|integer'
        ]);
        $veh = new Vehicle();
        $veh->year = $request->post('year');
        $veh->make = $request->post('make');
        $veh->model = $request->post('model');
        $veh->color = $request->post('color');
        $veh->vin = $request->post('vin');
        $veh->plate = $request->post('plate');
        $veh->miles = $request->post('miles');
        $veh->user_id = $request->user()->id;
        $veh->save();
        return redirect()->route('vehicles.show', ['vehicle' => $veh]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function show(Request $request, Vehicle $vehicle)
    {
        if($vehicle->user_id != $request->user()->id)
            abort(403);
        return view('vehicles.show', ['veh' => $vehicle, 'smsg' => $request->session()->get('service_message')]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function edit(Request $request, Vehicle $vehicle)
    {
        if($vehicle->user_id != $request->user()->id)
            abort(403);
        return view('vehicles.form', ['veh' => $vehicle]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Vehicle $vehicle)
    {
        $this->validate($request, [
            'year' => 'required|integer',
            'make' => 'required|string|max:50',
            'model' => 'required|string|max:50',
            'color' => 'required|string|max:50',
            'vin' => 'required|string|size:17',
            'plate' => 'required|string|max:10'
        ]);
        if($request->post('miles') != $vehicle->miles) {
            return redirect()->back()->withErrors(['miles' => 'Mileage cannot be updated through this form.'])->withInput();
        }

        $vehicle->year = $request->post('year');
        $vehicle->make = $request->post('make');
        $vehicle->model = $request->post('model');
        $vehicle->color = $request->post('color');
        $vehicle->vin = $request->post('vin');
        $vehicle->plate = $request->post('plate');
        $vehicle->save();

        return redirect()->route('vehicles.show', ['vehicle' => $vehicle]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Vehicle  $vehicle
     * @return \Illuminate\Http\Response
     */
    public function destroy(Vehicle $vehicle)
    {
        //
    }
}
