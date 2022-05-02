@foreach($form as $row)
    <div class="row">
        @foreach(array_keys($row) as $colname)
            @php
                $col = $row[$colname];
            @endphp
            <div class="col-md mb-3">
                @if(!isset($col['hidelabel']) || !$col['hidelabel'])
                    <label for="{{ $colname }}" class="form-label">{{ $col['label'] }}</label>
                @endif
                @php
                    $colval = old($colname) ? old($colname) : (isset($model) ? $model->{$colname} : ($col['value'] ?? null));
                    if($colval instanceof \Carbon\Carbon && $col['type'] == 'date') $colval = $colval->toDateString();
                @endphp
                @if(isset($col['type']) && $col['type'] == 'select')
                    <select id="{{ $colname }}" name="{{ $colname }}" {{ isset($col['readonly']) && $col['readonly'] ? 'readonly' : null }} {{ isset($col['disabled']) && $col['disabled'] ? 'disabled' : null }} {{ isset($col['required']) && $col['required'] ? 'required' : null }} class="form-select{{ $errors->has($colname) ? ' is-invalid' : '' }}">
                        @foreach(array_keys($col['options']) as $key)
                            <option value="{{ $key }}"{{ $colval == $key ? ' selected' : null}}>{{ $col['options'][$key] }}</option>
                        @endforeach
                    </select>
                @elseif(isset($col['type']) && $col['type'] == 'checkbox')
                    <div class="form-check{{ $errors->has($colname) ? ' is-invalid' : '' }}">
                        <input class="form-check-input" type="checkbox" {{ $colval || $colval == 'on' ? 'checked' : null }} id="{{ $colname }}" name="{{ $colname }}"  {{ isset($col['readonly']) && $col['readonly'] ? 'readonly' : null }} {{ isset($col['disabled']) && $col['disabled'] ? 'disabled' : null }} {{ isset($col['required']) && $col['required'] ? 'required' : null }}>
                        <label class="form-check-label" for="{{ $colname }}">
                            {{ $col['label'] }}
                        </label>
                    </div>
                @elseif(isset($col['type']) && $col['type'] == 'textarea')
                    <textarea class="form-control{{ $errors->has($colname) ? ' is-invalid' : '' }}" rows="5" id="{{ $colname }}" name="{{ $colname }}" {{ isset($col['readonly']) && $col['readonly'] ? 'readonly' : null }} {{ isset($col['disabled']) && $col['disabled'] ? 'disabled' : null }} {{ isset($col['required']) && $col['required'] ? 'required' : null }}>{{ $colval }}</textarea>
                @else
                    <input id="{{ $colname }}" type="{{ $col['type'] ?? 'text'  }}" autocomplete="off" value="{{ $colval }}" name="{{ $colname }}" {{ isset($col['readonly']) && $col['readonly'] ? 'readonly' : null }} {{ isset($col['disabled']) && $col['disabled'] ? 'disabled' : null }} {{ isset($col['required']) && $col['required'] ? 'required' : null }} class="form-control{{ $errors->has($colname) ? ' is-invalid' : '' }}" />
                @endif
                @if($errors->has($colname))
                    <small class="invalid-feedback">{{ $errors->first($colname) }}</small>
                @elseif(isset($col['help']))
                    <small class="text-muted">{{ $col['help'] }}</small>
                @endif
            </div>
        @endforeach
    </div>
@endforeach
