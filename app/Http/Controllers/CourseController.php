<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Course;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;
use App\Http\Requests\CourseStoreRequest;
use App\Http\Requests\CourseUpdateRequest;

class CourseController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request): View
    {
        $this->authorize('view-any', Course::class);

        $search = $request->get('search', '');

        $courses = Course::search($search)
            ->latest()
            ->paginate(5)
            ->withQueryString();

        return view('app.courses.index', compact('courses', 'search'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request): View
    {
        $this->authorize('create', Course::class);

        $users = User::pluck('name', 'id');

        return view('app.courses.create', compact('users'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(CourseStoreRequest $request): RedirectResponse
    {
        $this->authorize('create', Course::class);

        $validated = $request->validated();

        $course = Course::create($validated);

        return redirect()
            ->route('courses.edit', $course)
            ->withSuccess(__('crud.common.created'));
    }

    /**
     * Display the specified resource.
     */
    public function show(Request $request, Course $course): View
    {
        $this->authorize('view', $course);

        return view('app.courses.show', compact('course'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Request $request, Course $course): View
    {
        $this->authorize('update', $course);

        $users = User::pluck('name', 'id');

        return view('app.courses.edit', compact('course', 'users'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(
        CourseUpdateRequest $request,
        Course $course
    ): RedirectResponse {
        $this->authorize('update', $course);

        $validated = $request->validated();

        $course->update($validated);

        return redirect()
            ->route('courses.edit', $course)
            ->withSuccess(__('crud.common.saved'));
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Request $request, Course $course): RedirectResponse
    {
        $this->authorize('delete', $course);

        $course->delete();

        return redirect()
            ->route('courses.index')
            ->withSuccess(__('crud.common.removed'));
    }
}
