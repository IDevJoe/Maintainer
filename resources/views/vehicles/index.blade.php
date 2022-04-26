@extends('templates.dashboard')

@section('content')
    <div class="row">
        <div class="col align-items-center d-flex">
            <div>
                {{ count($vehs) }} found.
            </div>
        </div>
        <div class="col-auto">
            <div class="float-end">
                <a href="{{ route('vehicles.create') }}" class="btn btn-primary">Add Vehicle</a>
            </div>
        </div>
    </div>
    <table class="table w-100 mt-3">
        <thead class="table-light">
            <tr>
                <th>Year</th>
                <th>Make</th>
                <th>Model</th>
                <th>Color</th>
                <th>Plate</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if(count($vehs) == 0)
                <tr>
                    <td colspan="6">No vehicles. You should <a href="{{ route('vehicles.create') }}">add one</a>.</td>
                </tr>
            @endif
            @foreach($vehs as $veh)
                <tr>
                    <td>{{ $veh->year }}</td>
                    <td>{{ $veh->make }}</td>
                    <td>{{ $veh->model }}</td>
                    <td>{{ $veh->color }}</td>
                    <td>{{ $veh->plate }}</td>
                    <td>
                        <a href="{{ route('vehicles.show', ['vehicle' => $veh]) }}" class="btn btn-sm btn-primary">View</a>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
