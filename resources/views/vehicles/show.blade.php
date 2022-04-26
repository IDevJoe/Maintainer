@extends('templates.dashboard')

@section('header')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+128+Text&display=swap" rel="stylesheet">
    <style>
        .barcode {
            font-family: 'Libre Barcode 128 Text', cursive;
            font-size: 2em;
        }
    </style>
@endsection

@section('content')
    <h1>
        {{ $veh->year }}
        {{ $veh->make }}
        {{ $veh->model }}
        ({{ $veh->color }})
    </h1>
    <hr>
    <h4>VEHICLE INFORMATION</h4>
    <div class="row mb-4">
        <div class="col-md">
            <strong>MILEAGE</strong>
            <br />{{ $veh->miles }}
        </div>
        <div class="col-md">
            <strong>VIN</strong>
            <br /><span class="barcode">&#204;{{ $veh->vin }}{!! \App\Code128::generateChecksum($veh->vin) !!}&#206;</span>
        </div>
        <div class="col-md">
            <strong>PLATE</strong>
            <br /><span class="barcode">&#204;{{ $veh->plate }}{!! \App\Code128::generateChecksum($veh->plate) !!}&#206;</span>
        </div>
        <div class="col-md-12 mt-2">
            <a href="{{ route('vehicles.edit', ['vehicle' => $veh]) }}" class="btn btn-outline-secondary btn-sm">Update Information</a>
        </div>
    </div>
    <h4>SERVICE INFORMATION</h4>
    <div class="d-flex justify-content-end mb-2">
        <a href="{{ route('services.create', ['vehicle' => $veh]) }}" class="btn btn-sm btn-outline-primary">Add Service</a>
    </div>
    @if($smsg != null)
        <div class="alert alert-info mb-2">
            {{ $smsg }}
        </div>
    @endif
    <table class="table table-sm">
        <thead class="table-light">
            <tr>
                <th>Description</th>
                <th>Frequency</th>
                <th>Last Mileage</th>
                <th>Last Serviced</th>
                <th>Next Due</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($veh->services as $serv)
                <tr>
                    <td>{{ $serv->description }}</td>
                    <td>{{ $serv->frequency }} {{ $serv->frequency_type == "mi" ? "miles" : "days" }}</td>
                    <td>{{ $serv->last_mileage }}</td>
                    <td>{{ $serv->last_serviced }}</td>
                    <td>{{ $serv->next_service }}</td>
                    <td><a href="{{ route('services.edit', ['service' => $serv]) }}" class="btn btn-outline-secondary btn-sm">Edit</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
@endsection
