@php $editing = isset($note) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.number
            name="score"
            label="Score"
            :value="old('score', ($editing ? $note->score : ''))"
            max="255"
            step="0.01"
            placeholder="Score"
            required
        ></x-inputs.number>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="test_id" label="Test" required>
            @php $selected = old('test_id', ($editing ? $note->test_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Test</option>
            @foreach($tests as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>
</div>
