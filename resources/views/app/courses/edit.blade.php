@extends('layouts.app')

@section('content')
<div class="container">
    <div class="card">
        <div class="card-body">
            <h4 class="card-title">
                <a href="{{ route('courses.index') }}" class="mr-4"
                    ><i class="icon ion-md-arrow-back"></i
                ></a>
                @lang('crud.courses.edit_title')
            </h4>

            <x-form
                method="PUT"
                action="{{ route('courses.update', $course) }}"
                class="mt-4"
            >
                @include('app.courses.form-inputs')

                <div class="mt-4">
                    <a
                        href="{{ route('courses.index') }}"
                        class="btn btn-light"
                    >
                        <i class="icon ion-md-return-left text-primary"></i>
                        @lang('crud.common.back')
                    </a>

                    <a
                        href="{{ route('courses.create') }}"
                        class="btn btn-light"
                    >
                        <i class="icon ion-md-add text-primary"></i>
                        @lang('crud.common.create')
                    </a>

                    <button type="submit" class="btn btn-primary float-right">
                        <i class="icon ion-md-save"></i>
                        @lang('crud.common.update')
                    </button>
                </div>
            </x-form>
        </div>
    </div>

    @can('view-any', App\Models\Test::class)
    <div class="card mt-4">
        <div class="card-body">
            <h4 class="card-title w-100 mb-2">Tests</h4>

            <livewire:course-tests-detail :course="$course" />
        </div>
    </div>
    @endcan
</div>
@endsection
