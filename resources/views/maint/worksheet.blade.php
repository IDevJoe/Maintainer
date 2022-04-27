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
    </style>
@endsection

@section('content')
    <h5 class="float-end">
        {{ $ws->created_at->toDateString() }}
    </h5>
    <h4>
        SERVICE WORKSHEET
        @if($ws->closed_at != null)
            <span class="badge bg-danger ms-1">Closed</span>
        @endif
    </h4>
    <hr>
    <h5>VEHICLE INFORMATION</h5>
    <div class="row mt-5">
        <div class="col-md text-center">
            <p class="barcode">*{{ $ws->vehicle->vin }}*</p>
        </div>
        <div class="col-md text-center">
            <p class="barcode">*{{ $ws->vehicle->plate }}*</p>
        </div>
    </div>
    <table class="table table-sm mb-5">
        <thead>
            <tr>
                <th>Year</th>
                <th>Make</th>
                <th>Model</th>
                <th>Color</th>
                <th>Mileage</th>
            </tr>
        </thead>
        <tbody>
            <tr>
                <td>{{ $ws->vehicle->year }}</td>
                <td>{{ $ws->vehicle->make }}</td>
                <td>{{ $ws->vehicle->model }}</td>
                <td>{{ $ws->vehicle->color }}</td>
                <td>{{ $ws->vehicle->miles }}mi</td>
            </tr>
        </tbody>
    </table>
    <h5>WORK INFORMATION</h5>
    @if($ws->closed_at == null)
        <form action="{{ route('maint.update', ['worksheet' => $ws]) }}" method="POST" class="mb-5">
            @csrf
            @method('PATCH')
            @include('components.formbuilder', ['form' => \App\Models\ServiceWorksheet::INFO_FORM, 'model' => $ws])
            <div class="d-flex justify-content-center">
                <button class="btn btn-primary">Update Information</button>
            </div>
        </form>
    @else
        <div class="mb-5">
            <div class="row mb-2">
                <div class="col-md">
                    <strong>Type</strong>
                    <br>{{ $ws->type }}
                </div>
                <div class="col-md">
                    <strong>Updated Mileage</strong>
                    <br>{{ $ws->updated_mileage }}
                </div>
                <div class="col-md">
                    <strong>Work Performed By</strong>
                    <br>{{ $ws->work_performed_by }}
                </div>
            </div>
            <div class="row">
                <div class="col-md workdesc">
                    {{ $ws->work_description }}
                </div>
            </div>
        </div>
    @endif
    <h5>ASSOCIATED SERVICES</h5>
    <table class="table table-sm">
        <thead class="table-light">
            <tr>
                <th>Description</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            @if(count($ws->vehicleServices) == 0)
                <tr>
                    <td colspan="2">No attached services</td>
                </tr>
            @endif
            @foreach($ws->vehicleServices as $serv)
                <tr>
                    <td>
                        {{ $serv->description }}
                        @if($serv->due)
                            <span class="badge bg-secondary">Due</span>
                        @endif
                    </td>
                    <td>
                        @if($ws->closed_at == null)
                            <form action="{{ route('maint.remservice', ['worksheet' => $ws, 'service' => $serv]) }}" method="POST">
                                @csrf
                                @method('DELETE')
                                <button class="btn btn-sm btn-outline-danger">Remove</button>
                            </form>
                        @endif
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
    <div class="d-flex justify-content-end mb-5">
        @if($ws->closed_at == null)
            <form action="{{ route('maint.addservice', ['worksheet' => $ws]) }}" method="POST">
                @csrf
                @method('POST')
                <div class="input-group w-auto">
                    <select class="form-select" name="service" aria-label="Service">
                        <option value="-1" selected disabled>Select...</option>
                        @foreach($ws->vehicle->services as $serv)
                            <option value="{{ $serv->id }}">{{ $serv->description }}</option>
                        @endforeach
                    </select>
                    <button class="btn btn-outline-secondary">Add</button>
                </div>
            </form>
            <form action="{{ route('maint.dueservice', ['worksheet' => $ws]) }}" method="POST">
                @csrf
                @method('POST')
                <button class="btn ms-1 btn-outline-secondary" name="action" value="add_all_due">Add all due services</button>
            </form>
        @endif
    </div>
    @if($ws->closed_at == null)
        <h5>FINAL ACTIONS</h5>
        <button class="btn btn-outline-success" name="action" value="close" type="button" onclick="closeSheet()">Close Worksheet</button>
        <button class="btn btn-outline-danger" name="action" value="delete" type="button" onclick="deleteSheet()">Delete Worksheet</button>
        <form action="{{ route('maint.delsheet', ['worksheet' => $ws]) }}" id="delsheet" method="POST">
            @csrf
            @method('DELETE')
        </form>
        <form action="{{ route('maint.closesheet', ['worksheet' => $ws]) }}" id="closesheet" method="POST">
            @csrf
            @method('PUT')
        </form>
    @endif
@endsection

@section('footer')
    <script>
        function deleteSheet() {
            var conf = confirm("Really DELETE this worksheet? This action cannot be reversed.\n\nAll associated data will be lost.\n\n");
            if(!conf) return;
            document.getElementById('delsheet').submit();
        }
        function closeSheet() {
            var conf = confirm("Really CLOSE this worksheet?");
            if(!conf) return;
            document.getElementById('closesheet').submit();
        }
    </script>
@endsection
