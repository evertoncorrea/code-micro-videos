<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\Genre;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\TestResponse;
use Tests\TestCase;
use Tests\Traits\TestValidations;

class GenreControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations;

    public function testIndex()
    {
        $genre = factory(Genre::class)->create();
        $response = $this->get(route('genres.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$genre->toArray()]);
    }

    public function testShow()
    {
        $genre = factory(Genre::class)->create();
        $response = $this->get(route('genres.show', ['genre' => $genre->id]));

        $response
            ->assertStatus(200)
            ->assertJson($genre->toArray());
    }

    public function testInvalidationData()
    {
        // para obter as chaves das mensagens, olhar no validation.php

        $response = $this->json('POST', route('genres.store'), []);
        $this->assertInvalidationRequired($response);

        $response = $this->json('POST', route('genres.store'), [
            'name' => str_repeat('A', 256),
            'is_active' => 'a'
        ]);
        $this
            ->assertInvalidationMax($response)
            ->assertInvalidationBoolean($response);


        $genre = factory(Genre::class)->create();

        $response = $this->json('PUT',
            route('genres.update', ['genre' => $genre->id]), []);
        $this->assertInvalidationRequired($response);

        $response = $this->json('PUT',
            route('genres.update', ['genre' => $genre->id]), [
                'name' => str_repeat('A', 256),
                'is_active' => 'a'
            ]);
        $this
            ->assertInvalidationMax($response)
            ->assertInvalidationBoolean($response);
    }

    private function assertInvalidationRequired(TestResponse $response) {
        $this->assertInvalidationFields($response, ['name'], 'required', []);
        $response->assertJsonMissingValidationErrors(['is_active']);
        return $this;
    }

    private function assertInvalidationMax(TestResponse $response) {
        $this->assertInvalidationFields($response, ['name'], 'max.string',
                ['max' => 255]);
        return $this;
    }

    private function assertInvalidationBoolean(TestResponse $response) {
        $this->assertInvalidationFields($response, ['is_active'], 'boolean', []);
        return $this;
    }

    public function testStore()
    {
        $response = $this->json('POST', route('genres.store'), [
            'name' => 'test'
        ]);

        $id = $response->json('id');
        $genre = Genre::find($id);

        $response
            ->assertStatus(201)
            ->assertJson($genre->toArray());
        $this->assertTrue($response->json('is_active'));


        $response = $this->json('POST', route('genres.store'), [
            'name' => 'test',
            'is_active' => false
        ]);

        $response->assertJsonFragment(([
            'is_active' => false,
        ]));
    }

    public function testUpdate()
    {
        $genre = factory(genre::class)->create([
            'is_active' => false
        ]);
        $response = $this->json(
            'PUT',
            route('genres.update', ['genre' => $genre->id]),
            [
                'name' => 'test',
                'is_active' => true
            ]);

        $id = $response->json('id');
        $genre = Genre::find($id);

        $response
            ->assertStatus(200)
            ->assertJson($genre->toArray())
            ->assertJsonFragment([
                'is_active' => true
            ]);
    }

    public function testDelete() {
        $genre = factory(Genre::class)->create();
        $response = $this->json(
            'DELETE',
            route('genres.destroy', ['genre' => $genre->id]));
        $response->assertStatus(204);

        $this->assertNull(Genre::find($genre->id));
        $this->assertNotNull(Genre::withTrashed()->find($genre->id));
    }

}
