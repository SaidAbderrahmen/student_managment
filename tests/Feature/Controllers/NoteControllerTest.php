<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Note;

use App\Models\Test;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class NoteControllerTest extends TestCase
{
    use RefreshDatabase, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();

        $this->actingAs(
            User::factory()->create(['email' => 'admin@admin.com'])
        );

        $this->seed(\Database\Seeders\PermissionsSeeder::class);

        $this->withoutExceptionHandling();
    }

    /**
     * @test
     */
    public function it_displays_index_view_with_notes(): void
    {
        $notes = Note::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('notes.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.notes.index')
            ->assertViewHas('notes');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_note(): void
    {
        $response = $this->get(route('notes.create'));

        $response->assertOk()->assertViewIs('app.notes.create');
    }

    /**
     * @test
     */
    public function it_stores_the_note(): void
    {
        $data = Note::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('notes.store'), $data);

        $this->assertDatabaseHas('notes', $data);

        $note = Note::latest('id')->first();

        $response->assertRedirect(route('notes.edit', $note));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_note(): void
    {
        $note = Note::factory()->create();

        $response = $this->get(route('notes.show', $note));

        $response
            ->assertOk()
            ->assertViewIs('app.notes.show')
            ->assertViewHas('note');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_note(): void
    {
        $note = Note::factory()->create();

        $response = $this->get(route('notes.edit', $note));

        $response
            ->assertOk()
            ->assertViewIs('app.notes.edit')
            ->assertViewHas('note');
    }

    /**
     * @test
     */
    public function it_updates_the_note(): void
    {
        $note = Note::factory()->create();

        $test = Test::factory()->create();
        $user = User::factory()->create();

        $data = [
            'score' => $this->faker->numberBetween(0, 20),
            'test_id' => $test->id,
            'user_id' => $user->id,
        ];

        $response = $this->put(route('notes.update', $note), $data);

        $data['id'] = $note->id;

        $this->assertDatabaseHas('notes', $data);

        $response->assertRedirect(route('notes.edit', $note));
    }

    /**
     * @test
     */
    public function it_deletes_the_note(): void
    {
        $note = Note::factory()->create();

        $response = $this->delete(route('notes.destroy', $note));

        $response->assertRedirect(route('notes.index'));

        $this->assertModelMissing($note);
    }
}
