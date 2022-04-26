@extends('templates.dashboard')

@section('content')

    @if(isset($serv))
        <h2>Modifying service</h2>
        <h4 class="text-muted">{{ $serv->vehicle->year }} {{ $serv->vehicle->make }} {{ $serv->vehicle->model }} ({{ $serv->vehicle->color }})</h4>
    @else
        <h1>Creating service</h1>
        <h4 class="text-muted">{{ $veh->year }} {{ $veh->make }} {{ $veh->model }} ({{ $veh->color }})</h4>
    @endif
    <form action="{{ isset($serv) ? '' : route('services.store', ['vehicle' => $veh]) }}" method="POST">
        @csrf
        @method(isset($serv) ? 'PATCH' : 'POST')
        @include('components.formbuilder', ['form' => \App\Models\VehicleService::SERVICE_FORM, 'model' => isset($serv) ? $serv : null])
        <div class="d-flex justify-content-end">
            <a href="{{ route('vehicles.show', ['vehicle' => isset($serv) ? $serv->vehicle : $veh]) }}"
               class="btn btn-outline-secondary me-1">Cancel</a>
            <button class="btn btn-primary">{{ isset($serv) ? 'Modify Service' : 'Add Service' }}</button>
        </div>
    </form>
    @isset($serv)
        <hr>
        <h5>DELETE SERVICE</h5>
        <p>This action is not reversable. Service records will not be deleted.</p>
        <form action="{{ route('services.destroy', ['service' => $serv]) }}" method="POST">
            @csrf
            @method('DELETE')
            <button class="btn btn-outline-danger">Delete</button>
        </form>
    @endisset
@endsection
