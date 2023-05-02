<?php

namespace Tests\Feature\Controllers;

use App\Models\User;
use App\Models\Test;

use App\Models\Course;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Foundation\Testing\RefreshDatabase;

class TestControllerTest extends TestCase
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
    public function it_displays_index_view_with_tests(): void
    {
        $tests = Test::factory()
            ->count(5)
            ->create();

        $response = $this->get(route('tests.index'));

        $response
            ->assertOk()
            ->assertViewIs('app.tests.index')
            ->assertViewHas('tests');
    }

    /**
     * @test
     */
    public function it_displays_create_view_for_test(): void
    {
        $response = $this->get(route('tests.create'));

        $response->assertOk()->assertViewIs('app.tests.create');
    }

    /**
     * @test
     */
    public function it_stores_the_test(): void
    {
        $data = Test::factory()
            ->make()
            ->toArray();

        $response = $this->post(route('tests.store'), $data);

        $this->assertDatabaseHas('tests', $data);

        $test = Test::latest('id')->first();

        $response->assertRedirect(route('tests.edit', $test));
    }

    /**
     * @test
     */
    public function it_displays_show_view_for_test(): void
    {
        $test = Test::factory()->create();

        $response = $this->get(route('tests.show', $test));

        $response
            ->assertOk()
            ->assertViewIs('app.tests.show')
            ->assertViewHas('test');
    }

    /**
     * @test
     */
    public function it_displays_edit_view_for_test(): void
    {
        $test = Test::factory()->create();

        $response = $this->get(route('tests.edit', $test));

        $response
            ->assertOk()
            ->assertViewIs('app.tests.edit')
            ->assertViewHas('test');
    }

    /**
     * @test
     */
    public function it_updates_the_test(): void
    {
        $test = Test::factory()->create();

        $course = Course::factory()->create();

        $data = [
            'type' => 'exam',
            'date' => $this->faker->dateTime(),
            'course_id' => $course->id,
        ];

        $response = $this->put(route('tests.update', $test), $data);

        $data['id'] = $test->id;

        $this->assertDatabaseHas('tests', $data);

        $response->assertRedirect(route('tests.edit', $test));
    }

    /**
     * @test
     */
    public function it_deletes_the_test(): void
    {
        $test = Test::factory()->create();

        $response = $this->delete(route('tests.destroy', $test));

        $response->assertRedirect(route('tests.index'));

        $this->assertModelMissing($test);
    }
}
