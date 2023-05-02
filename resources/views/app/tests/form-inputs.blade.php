@php $editing = isset($test) @endphp

<div class="row">
    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="type" label="Type">
            @php $selected = old('type', ($editing ? $test->type : 'exam')) @endphp
            <option value="control" {{ $selected == 'control' ? 'selected' : '' }} >Control</option>
            <option value="exam" {{ $selected == 'exam' ? 'selected' : '' }} >Exam</option>
        </x-inputs.select>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.datetime
            name="date"
            label="Date"
            value="{{ old('date', ($editing ? optional($test->date)->format('Y-m-d\TH:i:s') : '')) }}"
            required
        ></x-inputs.datetime>
    </x-inputs.group>

    <x-inputs.group class="col-sm-12">
        <x-inputs.select name="course_id" label="Course" required>
            @php $selected = old('course_id', ($editing ? $test->course_id : '')) @endphp
            <option disabled {{ empty($selected) ? 'selected' : '' }}>Please select the Course</option>
            @foreach($courses as $value => $label)
            <option value="{{ $value }}" {{ $selected == $value ? 'selected' : '' }} >{{ $label }}</option>
            @endforeach
        </x-inputs.select>
    </x-inputs.group>
</div>
