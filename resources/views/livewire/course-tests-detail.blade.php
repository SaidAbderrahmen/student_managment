<div>
    <div class="mb-4">
        @can('create', App\Models\Test::class)
        <button class="btn btn-primary" wire:click="newTest">
            <i class="icon ion-md-add"></i>
            @lang('crud.common.new')
        </button>
        @endcan @can('delete-any', App\Models\Test::class)
        <button
            class="btn btn-danger"
             {{ empty($selected) ? 'disabled' : '' }} 
            onclick="confirm('Are you sure?') || event.stopImmediatePropagation()"
            wire:click="destroySelected"
        >
            <i class="icon ion-md-trash"></i>
            @lang('crud.common.delete_selected')
        </button>
        @endcan
    </div>

    <x-modal id="course-tests-modal" wire:model="showingModal">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">{{ $modalTitle }}</h5>
                <button
                    type="button"
                    class="close"
                    data-dismiss="modal"
                    aria-label="Close"
                >
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>

            <div class="modal-body">
                <div>
                    <x-inputs.group class="col-sm-12">
                        <x-inputs.select
                            name="test.type"
                            label="Type"
                            wire:model="test.type"
                        >
                            <option value="control" {{ $selected == 'control' ? 'selected' : '' }} >Control</option>
                            <option value="exam" {{ $selected == 'exam' ? 'selected' : '' }} >Exam</option>
                        </x-inputs.select>
                    </x-inputs.group>

                    <x-inputs.group class="col-sm-12">
                        <x-inputs.datetime
                            name="test.date"
                            label="Date"
                            wire:model="test.date"
                            max="255"
                        ></x-inputs.datetime>
                    </x-inputs.group>
                </div>
            </div>

            @if($editing) @can('view-any', App\Models\Note::class)
            <div class="card mt-4">
                <div class="card-body">
                    <h6 class="card-title">Notes</h6>

                    <livewire:test-notes-detail :test="$test" />
                </div>
            </div>
            @endcan @endif

            <div class="modal-footer">
                <button
                    type="button"
                    class="btn btn-light float-left"
                    wire:click="$toggle('showingModal')"
                >
                    <i class="icon ion-md-close"></i>
                    @lang('crud.common.cancel')
                </button>

                <button type="button" class="btn btn-primary" wire:click="save">
                    <i class="icon ion-md-save"></i>
                    @lang('crud.common.save')
                </button>
            </div>
        </div>
    </x-modal>

    <div class="table-responsive">
        <table class="table table-borderless table-hover">
            <thead>
                <tr>
                    <th>
                        <input
                            type="checkbox"
                            wire:model="allSelected"
                            wire:click="toggleFullSelection"
                            title="{{ trans('crud.common.select_all') }}"
                        />
                    </th>
                    <th class="text-left">
                        @lang('crud.course_tests.inputs.type')
                    </th>
                    <th class="text-left">
                        @lang('crud.course_tests.inputs.date')
                    </th>
                    <th></th>
                </tr>
            </thead>
            <tbody class="text-gray-600">
                @foreach ($tests as $test)
                <tr class="hover:bg-gray-100">
                    <td class="text-left">
                        <input
                            type="checkbox"
                            value="{{ $test->id }}"
                            wire:model="selected"
                        />
                    </td>
                    <td class="text-left">{{ $test->type ?? '-' }}</td>
                    <td class="text-left">{{ $test->date ?? '-' }}</td>
                    <td class="text-right" style="width: 134px;">
                        <div
                            role="group"
                            aria-label="Row Actions"
                            class="relative inline-flex align-middle"
                        >
                            @can('update', $test)
                            <button
                                type="button"
                                class="btn btn-light"
                                wire:click="editTest({{ $test->id }})"
                            >
                                <i class="icon ion-md-create"></i>
                            </button>
                            @endcan
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr>
                    <td colspan="3">{{ $tests->render() }}</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>
