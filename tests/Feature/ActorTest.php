<?php

namespace Tests\Feature;

use App\Models\Actor;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;

class ActorTest extends TestCase
{
    use RefreshDatabase;

    public function test_can_view_actor_form(): void
    {
        $response = $this->get('/create');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Actors/Create'));
    }

    public function test_can_view_actors_index(): void
    {
        $response = $this->get('/');

        $response->assertStatus(200);
        $response->assertInertia(fn ($page) => $page->component('Actors/Index'));
    }

    public function test_can_submit_actor_with_valid_data(): void
    {
        $actorData = [
            'email' => 'test@example.com',
            'description' => 'John Doe is a 30-year-old male actor from 123 Main St, New York. He is 6 feet tall and weighs 180 pounds.',
        ];

        $response = $this->post('/actors', $actorData);

        $response->assertRedirect('/');
        $this->assertDatabaseHas('actors', [
            'email' => 'test@example.com',
        ]);
    }

    public function test_validation_requires_email(): void
    {
        $response = $this->post('/actors', [
            'description' => 'Some description',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_validation_requires_description(): void
    {
        $response = $this->post('/actors', [
            'email' => 'test@example.com',
        ]);

        $response->assertSessionHasErrors(['description']);
    }

    public function test_email_must_be_unique(): void
    {
        Actor::create([
            'email' => 'test@example.com',
            'description' => 'First description',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address' => '123 Main St',
        ]);

        $response = $this->post('/actors', [
            'email' => 'test@example.com',
            'description' => 'Second description',
        ]);

        $response->assertSessionHasErrors(['email']);
    }

    public function test_description_must_be_unique(): void
    {
        Actor::create([
            'email' => 'first@example.com',
            'description' => 'Same description',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'address' => '123 Main St',
        ]);

        $response = $this->post('/actors', [
            'email' => 'second@example.com',
            'description' => 'Same description',
        ]);

        $response->assertSessionHasErrors(['description']);
    }

    public function test_api_prompt_validation_endpoint(): void
    {
        $response = $this->get('/api/actors/prompt-validation');

        $response->assertStatus(200);
        $response->assertJson([
            'message' => 'text_prompt'
        ]);
    }
}
