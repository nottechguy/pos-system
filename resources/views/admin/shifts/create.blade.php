@extends('layouts.dashboard')

@section('content')
<div class="form-panel" data-jscontroller="ShiftUIController">
    <div class="panel-header">
        <h2>Open Cash Shift</h2>
    </div>

    <form action="{{ route('admin.shifts.store') }}" method="POST">
        @csrf

        <div class="form-row">
            <div class="form-col">
                <div class="form-group">
                    <label for="terminal_id">Select Terminal *</label>
                    <select name="terminal_id" id="terminal_id" class="form-control @error('terminal_id') is-invalid @enderror" required>
                        <option value="">-- Choose a Terminal --</option>
                        @foreach($terminals as $terminal)
                            <option value="{{ $terminal->id }}" {{ old('terminal_id') == $terminal->id ? 'selected' : '' }}>
                                {{ $terminal->name }} ({{ $terminal->code }})
                            </option>
                        @endforeach
                    </select>
                    @error('terminal_id') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
            
            <div class="form-col">
                <div class="form-group">
                    <label for="opening_cash">Opening Cash Amount *</label>
                    <input type="number" step="0.01" min="0" name="opening_cash" id="opening_cash" class="form-control @error('opening_cash') is-invalid @enderror" value="{{ old('opening_cash', '0.00') }}" required autocomplete="off">
                    @error('opening_cash') <span class="invalid-feedback">{{ $message }}</span> @enderror
                </div>
            </div>
        </div>

        <div class="form-group">
            <label for="notes">Opening Notes (Optional)</label>
            <textarea name="notes" id="notes" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
            @error('notes') <span class="invalid-feedback">{{ $message }}</span> @enderror
        </div>

        <div class="form-group form-group-send">
            <x-bmdc-button type="submit" variant="raised">Open Shift & Continue to POS</x-bmdc-button>
        </div>
    </form>
</div>
@endsection