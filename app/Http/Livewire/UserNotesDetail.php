<?php

namespace App\Http\Livewire;

use App\Models\User;
use App\Models\Note;
use App\Models\Test;
use Livewire\Component;
use Illuminate\View\View;
use Livewire\WithPagination;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;

class UserNotesDetail extends Component
{
    use WithPagination;
    use AuthorizesRequests;

    public User $user;
    public Note $note;
    public $testsForSelect = [];

    public $selected = [];
    public $editing = false;
    public $allSelected = false;
    public $showingModal = false;

    public $modalTitle = 'New Note';

    protected $rules = [
        'note.score' => ['required', 'numeric'],
        'note.test_id' => ['required', 'exists:tests,id'],
    ];

    public function mount(User $user): void
    {
        $this->user = $user;
        $this->testsForSelect = Test::pluck('id', 'id');
        $this->resetNoteData();
    }

    public function resetNoteData(): void
    {
        $this->note = new Note();

        $this->note->test_id = null;

        $this->dispatchBrowserEvent('refresh');
    }

    public function newNote(): void
    {
        $this->editing = false;
        $this->modalTitle = trans('crud.user_notes.new_title');
        $this->resetNoteData();

        $this->showModal();
    }

    public function editNote(Note $note): void
    {
        $this->editing = true;
        $this->modalTitle = trans('crud.user_notes.edit_title');
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

        if (!$this->note->user_id) {
            $this->authorize('create', Note::class);

            $this->note->user_id = $this->user->id;
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

        foreach ($this->user->notes as $note) {
            array_push($this->selected, $note->id);
        }
    }

    public function render(): View
    {
        return view('livewire.user-notes-detail', [
            'notes' => $this->user->notes()->paginate(20),
        ]);
    }
}
