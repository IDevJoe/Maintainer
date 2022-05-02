@extends('templates.dashboard')

@section('header')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Libre+Barcode+39+Text&display=swap" rel="stylesheet">
    <style>
        .barcode {
            font-family: 'Libre Barcode 39 Text', cursive;
            font-size: 2em;
        }
        .workdesc {
            white-space: pre-line;
        }
        .pb {
            page-break-after: always;
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
            <br /><span class="barcode">*{{ $veh->vin }}*</span>
        </div>
        <div class="col-md">
            <strong>PLATE</strong>
            <br /><span class="barcode">*{{ $veh->plate }}*</span>
        </div>
        <div class="col-md-12 mt-2 d-print-none">
            <a href="{{ route('vehicles.edit', ['vehicle' => $veh]) }}" class="btn btn-outline-secondary btn-sm">Update Information</a>
        </div>
    </div>
    <h4>ACTIVE WORKSHEETS</h4>
    <div class="mb-4 pb">
        <div class="d-flex justify-content-end mb-2 d-print-none">
            <form action="{{ route('maint.create', ['vehicle' => $veh]) }}" method="POST">
                @csrf
                @method('POST')
                <button class="btn btn-sm btn-outline-primary">Create Worksheet</button>
            </form>
        </div>
        <table class="table table-sm">
            <thead class="table-light">
                <tr>
                    <th>Created On</th>
                    <th>Type</th>
                    <th>Description</th>
                    <th class="d-print-none">Actions</th>
                </tr>
            </thead>
            <tbody>
                @if($veh->worksheets()->where('closed_at', null)->count() == 0)
                    <tr>
                        <td colspan="4">No active worksheets.</td>
                    </tr>
                @endif
                @foreach($veh->worksheets()->where('closed_at', null)->get() as $ws)
                    <tr>
                        <td>{{ $ws->created_at->toDateString() }}</td>
                        <td>{{ $ws->type }}</td>
                        <td class="workdesc">{{ $ws->work_description }}</td>
                        <td class="d-print-none">
                            <a href="{{ route('maint.showsheet', ['worksheet' => $ws]) }}"
                               class="btn btn-sm btn-outline-secondary">View</a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    <h4>SERVICE INFORMATION</h4>
    <div class="d-flex justify-content-end mb-2 d-print-none">
        <a href="{{ route('services.create', ['vehicle' => $veh]) }}" class="btn btn-sm btn-outline-primary">Add Service</a>
    </div>
    @if($smsg != null)
        <div class="alert alert-info mb-2">
            {{ $smsg }}
        </div>
    @endif
    <table class="table table-sm mb-5 pb">
        <thead class="table-light">
            <tr>
                <th>Description</th>
                <th>Frequency</th>
                <th>Last Mileage</th>
                <th>Last Serviced</th>
                <th>Next Due</th>
                <th class="d-print-none">Actions</th>
            </tr>
        </thead>
        <tbody>
            @foreach($veh->services as $serv)
                <tr>
                    <td>{{ $serv->description }}</td>
                    <td>{{ $serv->frequency }} {{ $serv->frequency_type == "mi" ? "miles" : "days" }}</td>
                    <td>{{ $serv->last_mileage }}</td>
                    <td>{{ $serv->last_serviced }}</td>
                    <td>
                        {{ $serv->next_service }}
                        @if($serv->due)
                            <span class="badge bg-danger ml-1">Due</span>
                        @endif
                    </td>
                    <td class="d-print-none"><a href="{{ route('services.edit', ['service' => $serv]) }}" class="btn btn-outline-secondary btn-sm">Edit</a></td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <h4>WORK HISTORY</h4>
    <table class="table table-sm">
        <thead class="table-light">
        <tr>
            <th>Date</th>
            <th>Work Details</th>
            <th class="d-print-none">Actions</th>
        </tr>
        </thead>
        <tbody>
        @if($veh->worksheets()->where('closed_at', '!=', null)->count() == 0)
            <tr>
                <td colspan="3">No active worksheets.</td>
            </tr>
        @endif
        @foreach($veh->worksheets()->where('closed_at', '!=', null)->orderBy('closed_at', 'desc')->limit(30)->get() as $ws)
            <tr>
                <td>{{ $ws->closed_at }}</td>
                <td>
                    <strong>{{ $ws->type }}</strong> work performed by <strong>{{ $ws->work_performed_by }}</strong> at <strong>{{ $ws->updated_mileage }}</strong>mi
                    <div class="workdesc">{{ $ws->work_description }}</div>
                </td>
                <td class="d-print-none">
                    <a href="{{ route('maint.showsheet', ['worksheet' => $ws]) }}"
                       class="btn btn-sm btn-outline-secondary">View</a>
                </td>
            </tr>
        @endforeach
        </tbody>
    </table>
@endsection
