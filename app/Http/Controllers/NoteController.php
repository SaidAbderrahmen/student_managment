<?php

namespace App\Http\Controllers;

use App\Models\Note;
use App\Models\Test;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\NoteStoreRequest;
use App\Http\Requests\NoteUpdateRequest;

class NoteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Note::class);

        $search = $request->get('search', '');

        $notes = Note::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.notes.index', compact('notes', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Note::class);

        $tests = Test::pluck('id', 'id');

        return view('app.notes.create', compact('tests'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(NoteStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Note::class);

        $validated = $request->validated();

        $note = Note::create($validated);

        return redirect()
            ->route('notes.edit', $note)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Note $note): View
    {
        $this->authorize('view', $note);

        return view('app.notes.show', compact('note'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Note $note): View
    {
        $this->authorize('update', $note);

        $tests = Test::pluck('id', 'id');

        return view('app.notes.edit', compact('note', 'tests'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        NoteUpdateRequest $request,
        Note $note
    ): RedirectResponse {
        $this->authorize('update', $note);

        $validated = $request->validated();

        $note->update($validated);

        return redirect()
            ->route('notes.edit', $note)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Note $note): RedirectResponse
    {
        $this->authorize('delete', $note);

        $note->delete();

        return redirect()
            ->route('notes.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
