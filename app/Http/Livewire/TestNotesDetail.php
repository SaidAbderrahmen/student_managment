<?php

namespace App\Http\Livewire;

use App\Models\Test;
use App\Models\Note;
use App\Models\User;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Spatie\Permission\Models\Role;


class TestNotesDetail extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public Test $test;
    public Note $note;
    public $usersForSelect = [];

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Note';

    protected $rules = [
        'note.score' => ['required', 'numeric'],
        'note.user_id' => ['required', 'exists:users,id'],
    ];

    public function mount(Test $test): void
    {
        $this->test = $test;
        $studentRole = Role::where('name', 'student')->first();
        $this->usersForSelect = $studentRole->users()->pluck('name', 'id');
        $this->resetNoteData();
    }

    public function resetNoteData(): void
    {
        $this->note = new Note();

        $this->note->user_id = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newNote(): void
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.test_notes.new_title');
        $this->resetNoteData();

        $this->showModal();
    }

    public function editNote(Note $note): void
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.test_notes.edit_title');
        $this->note = $note;

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

        if (!$this->note->test_id) {
            $this->authorize('create', Note::class);

            $this->note->test_id = $this->test->id;
        } else {
            $this->authorize('update', $this->note);
        }

        $this->note->save();

        $this->hideModal();
    }

    public function destroySelected(): void
    {
        $this->authorize('delete-any', Note::class);

        Note::whereIn('id', $this->selected)->delete();

        $this->selected = [];
        $this->allSelected = false;

        $this->resetNoteData();
    }

    public function toggleFullSelection(): void
    {
        if (!$this->allSelected) {
            $this->selected = [];
            return;
        }

        foreach ($this->test->notes as $note) {
            array_push($this->selected, $note->id);
        }
    }

    public function render(): View
    {
        return view('livewire.test-notes-detail', [
            'notes' => $this->test->notes()->paginate(20),
        ]);
    }
}
