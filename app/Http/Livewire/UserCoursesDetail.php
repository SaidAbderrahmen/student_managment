<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Course;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserCoursesDetail extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public User $user;
    public Course $course;

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Course';

    protected $rules = [
        'course.title' => ['required', 'max:255', 'string'],
    ];

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->resetCourseData();
    }

    public function resetCourseData(): void
    {
        $this->course = new Course();

        $this->dispatchBrowserEvent('refresh');
    }

    public function newCourse(): void
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.user_courses.new_title');
        $this->resetCourseData();

        $this->showModal();
    }

    public function editCourse(Course $course): void
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.user_courses.edit_title');
        $this->course = $course;

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

        if (!$this->course->user_id) {
            $this->authorize('create', Course::class);

            $this->course->user_id = $this->user->id;
        } else {
            $this->authorize('update', $this->course);
        }

        $this->course->save();

        $this->hideModal();
    }

    public function destroySelected(): void
    {
        $this->authorize('delete-any', Course::class);

        Course::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->allSelected = false;

        $this->resetCourseData();
    }

    public function toggleFullSelection(): void
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->user->courses as $course) {
            array_push($this->selected, $course->id);
        }
    }

    public function render(): View
    {
        return view('livewire.user-courses-detail', [
            'courses' => $this->user->courses()->paginate(20),
        ]);
    }
}
