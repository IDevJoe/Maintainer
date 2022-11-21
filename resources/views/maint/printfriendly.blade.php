@extends('templates.base')

@section('base_header')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39+Text&display=swap" rel="stylesheet">
    <style>
        .barcode {
            font-family: 'Libre Barcode 39 Text', cursive;
            font-size: 2em;
        }
    </style>
@endsection

@section('base_content')
    <div class="rawtext">
<h4 class="m-0 p-0">SERVICE WORKSHEET {{ $ws->created_at->toDateString() }}</h4>----------------------------------------------------------------

<u>VEHICLE INFORMATION</u>
{{ $ws->vehicle->year }} {{ $ws->vehicle->make }} {{ $ws->vehicle->model }} ({{ $ws->vehicle->color }})
<strong>VIN</strong> {{ $ws->vehicle->vin }}     <strong>PLATE</strong> {{ $ws->vehicle->plate }}      <strong>MILES</strong> {{ $ws->vehicle->miles }}

<u>WORK INFORMATION</u>
<strong>TYPE</strong> {{ $ws->type }}     <strong>NEW MILEAGE</strong> {{ $ws->updated_mileage == "" ? "________" : $ws->updated_mileage }}     <strong>WORK PERFORMED BY</strong> {{ $ws->work_performed_by == "" ? "________" : $ws->work_performed_by }}

{{ $ws->work_description }}

<u>ASSOCIATED SERVICES</u>@foreach($ws->vehicleServices as $serv)

- {{ $serv->description }}@if($serv->due) [DUE] @endif @endforeach
</div>
    <div class="row mt-5">
        <div class="col-md text-center">
            <p class="barcode">*{{ $ws->vehicle->vin }}*</p>
        </div>
        <div class="col-md text-center">
            <p class="barcode">*{{ $ws->vehicle->plate }}*</p>
        </div>
    </div>
    <a href="{{ route('maint.showsheet', ['worksheet' => $ws]) }}" class="btn btn-primary d-print-none">Back</a>
@endsection
