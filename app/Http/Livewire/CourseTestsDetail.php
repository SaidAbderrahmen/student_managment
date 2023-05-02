<?php

namespace App\Http\Livewire;

use App\Models\Test;
use App\Models\Course;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class CourseTestsDetail extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public Course $course;
    public Test $test;

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Test';

    protected $rules = [
        'test.type' => ['required', 'in:control,exam'],
        'test.date' => ['required', 'date'],
    ];

    public function mount(Course $course): void
    {
        $this->course = $course;
        $this->resetTestData();
    }

    public function resetTestData(): void
    {
        $this->test = new Test();

        $this->test->date = null;
        $this->test->type = 'exam';

        $this->dispatchBrowserEvent('refresh');
    }

    public function newTest(): void
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.course_tests.new_title');
        $this->resetTestData();

        $this->showModal();
    }

    public function editTest(Test $test): void
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.course_tests.edit_title');
        $this->test = $test;

        $this->dispatchBrowserEvent('refresh');

        $this->showModal();
    }

    public function showModal(): void
    {
        $this->resetErrorBag();
        $this->showingModal = true;
    }

    public function hideModal(): void
    {
        $this->showingModal = false;
    }

    public function save(): void
    {
        $this->validate();

        if (!$this->test->course_id) {
            $this->authorize('create', Test::class);

            $this->test->course_id = $this->course->id;
        } else {
            $this->authorize('update', $this->test);
        }

        $this->test->save();

        $this->hideModal();
    }

    public function destroySelected(): void
    {
        $this->authorize('delete-any', Test::class);

        Test::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->allSelected = false;

        $this->resetTestData();
    }

    public function toggleFullSelection(): void
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->course->tests as $test) {
            array_push($this->selected, $test->id);
        }
    }

    public function render(): View
    {
        return view('livewire.course-tests-detail', [
            'tests' => $this->course->tests()->paginate(20),
        ]);
    }
}
