<?php

namespace App\Http\Controllers;

use App\Models\ServiceWorksheet;
use App\Models\Vehicle;
use App\Models\VehicleService;
use Carbon\Carbon;
use Illuminate\Http\Request;

class MaintController extends Controller
{
    public function newWorksheet(Request $request, Vehicle $vehicle) {
        if($vehicle->user_id != $request->user()->id)
            abort(403);
        $ws = new ServiceWorksheet();
        $ws->vehicle_id = $vehicle->id;
        $ws->type = "Routine";
        $ws->save();
        return redirect()->route('maint.showsheet', ['worksheet' => $ws]);
    }

    public function showSheet(Request $request, ServiceWorksheet $worksheet) {
        if($worksheet->vehicle->user_id != $request->user()->id)
            abort(403);
        return view('maint.worksheet', ['ws' => $worksheet]);
    }

    public function update(Request $request, ServiceWorksheet $worksheet) {
        if($worksheet->vehicle->user_id != $request->user()->id)
            abort(403);
        if($worksheet->closed_at != null)
            abort(400);
        $this->validate($request, [
            'type' => 'required|string|max:10',
            'updated_mileage' => 'nullable|integer|min:0',
            'work_performed_by' => 'nullable|string',
            'work_description' => 'nullable|string'
        ]);
        $worksheet->type = $request->post('type');
        $worksheet->updated_mileage = $request->post('updated_mileage');
        $worksheet->work_performed_by = $request->post('work_performed_by');
        $worksheet->work_description = $request->post('work_description');
        $worksheet->save();
        return redirect()->back();
    }

    public function addService(Request $request, ServiceWorksheet $worksheet) {
        if($worksheet->vehicle->user_id != $request->user()->id)
            abort(403);
        if($worksheet->closed_at != null)
            abort(400);
        $this->validate($request, [
            'service' => 'required|string|exists:vehicle_services,id'
        ]);
        if(!$worksheet->vehicleServices->contains($request->post('service'))) {
            $worksheet->vehicleServices()->attach($request->post('service'));
        }
        return redirect()->back();
    }

    public function removeService(Request $request, ServiceWorksheet $worksheet, VehicleService $service) {
        if($worksheet->vehicle->user_id != $request->user()->id)
            abort(403);
        if($worksheet->closed_at != null)
            abort(400);
        $worksheet->vehicleServices()->detach($service->id);
        return redirect()->back();
    }

    public function addDueServices(Request $request, ServiceWorksheet $worksheet) {
        if($worksheet->vehicle->user_id != $request->user()->id)
            abort(403);
        if($worksheet->closed_at != null)
            abort(400);
        foreach($worksheet->vehicle->services as $serv) {
            if(!$serv->due) continue;
            if(!$worksheet->vehicleServices->contains($serv->id)) {
                $worksheet->vehicleServices()->attach($serv->id);
            }
        }
        return redirect()->back();
    }

    public function deleteSheet(Request $request, ServiceWorksheet $worksheet) {
        if($worksheet->vehicle->user_id != $request->user()->id)
            abort(403);
        if($worksheet->closed_at != null)
            abort(400);
        $veh = $worksheet->vehicle; // Preload just in case
        $worksheet->delete();
        return redirect()->route('vehicles.show', ['vehicle' => $veh]);
    }

    public function closeSheet(Request $request, ServiceWorksheet $worksheet) {
        if($worksheet->vehicle->user_id != $request->user()->id)
            abort(403);
        if($worksheet->closed_at != null)
            abort(400);

        // Here we gooooooo
        if($worksheet->updated_mileage == null || $worksheet->work_performed_by == null ||
            $worksheet->work_description == null) {
            return redirect()->back()->withErrors([
                'work_description' => 'All fields must be filled in before closing the worksheet.'
            ]);
        }

        if($worksheet->updated_mileage < $worksheet->vehicle->miles) {
            return redirect()->back()->withErrors([
                'updated_mileage' => 'Mileage must be greater than the current (' . $worksheet->vehicle->miles . 'mi)'
            ]);
        }

        // Update Services
        foreach($worksheet->vehicleServices as $serv) {
            $serv->last_mileage = $worksheet->updated_mileage;
            $serv->last_serviced = Carbon::now();
            $serv->save();
        }

        $veh = $worksheet->vehicle;
        $veh->miles = $worksheet->updated_mileage;
        $veh->save();

        $worksheet->closed_at = Carbon::now();
        $worksheet->save();
        return redirect()->back();
    }
}
