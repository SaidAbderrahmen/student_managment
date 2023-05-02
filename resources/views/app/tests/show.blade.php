@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('tests.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.tests.show_title')
            </h4>

            <div class="mt-4">
                <div class="mb-4">
                    <h5>@lang('crud.tests.inputs.type')</h5>
                    <span>{{ $test->type ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.tests.inputs.date')</h5>
                    <span>{{ $test->date ?? '-' }}</span>
                </div>
                <div class="mb-4">
                    <h5>@lang('crud.tests.inputs.course_id')</h5>
                    <span>{{ optional($test->course)->title ?? '-' }}</span>
                </div>
            </div>

            <div class="mt-4">
                <a href="{{ route('tests.index') }}" class="btn btn-light">
                    <i class="icon ion-md-return-left"></i>
                    @lang('crud.common.back')
                </a>

                @can('create', App\Models\Test::class)
                <a href="{{ route('tests.create') }}" class="btn btn-light">
                    <i class="icon ion-md-add"></i> @lang('crud.common.create')
                </a>
                @endcan
            </div>
        </div>
    </div>

    @can('view-any', App\Models\Note::class)
    <div class="card mt-4">
        <div class="card-body">
            <h4 class="card-title w-100 mb-2">Notes</h4>

            <livewire:test-notes-detail :test="$test" />
        </div>
    </div>
    @endcan
</div>
@endsection
