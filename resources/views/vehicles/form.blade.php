@extends('templates.dashboard')

@section('content')
    <h1>{{ isset($veh) ? 'Edit' : 'Add' }} a vehicle</h1>
    <a href="{{ isset($veh) ? route('vehicles.show', ['vehicle' => $veh]) : route('vehicles.index') }}" class="btn btn-outline-secondary mb-2">Back</a>
    <form action="{{ isset($veh) ? route('vehicles.update', ['vehicle' => $veh]) : route('vehicles.store') }}" method="POST">
        @csrf
        @method(isset($veh) ? 'PATCH' : 'POST')
        @include('components.formbuilder', ['form' => \App\Models\Vehicle::VEHICLE_FORM, 'model' => isset($veh) ? $veh : null])
        <div class="d-flex justify-content-end">
            <button class="btn btn-primary">{{ isset($veh) ? 'Edit' : 'Add' }} Vehicle</button>
        </div>
    </form>

@endsection
