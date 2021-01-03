<?php

namespace Tests\Feature\Http\Controllers\Api;

use App\Models\CastMember;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;
use Tests\Traits\TestSaves;
use Tests\Traits\TestValidations;

class CastMemberControllerTest extends TestCase
{
    use DatabaseMigrations, TestValidations, TestSaves;

    private $obj;

    protected function setUp(): void
    {
        parent::setUp();
        $this->obj = factory(CastMember::class)->create(['type' => CastMember::TYPE_DIRECTOR]);
    }

    public function testIndex()
    {
        $response = $this->get(route('cast_member.index'));

        $response
            ->assertStatus(200)
            ->assertJson([$this->obj->toArray()]);
    }

    public function testShow()
    {
        $response = $this->get(route('cast_member.show', ['cast_member' => $this->obj->id]));

        $response
            ->assertStatus(200)
            ->assertJson($this->obj->toArray());
    }

    public function testInvalidationData()
    {
        // para obter as chaves das mensagens, olhar no validation.php
        
        $data = ['type' => ''];
        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'required');

        $data = ['name' => ''];
        $this->assertInvalidationInStoreAction($data, 'required');
        $this->assertInvalidationInUpdateAction($data, 'required');

        $data = ['name' => str_repeat('A', 256)];
        $this->assertInvalidationInStoreAction($data, 'max.string', ['max' => 255]);
        $this->assertInvalidationInUpdateAction($data, 'max.string', ['max' => 255]);

		$data = ['type' => 'x'];
        $this->assertInvalidationInStoreAction($data, 'in');
        $this->assertInvalidationInUpdateAction($data, 'in');

        $data = ['is_active' => 'a'];
        $this->assertInvalidationInStoreAction($data, 'boolean');
        $this->assertInvalidationInUpdateAction($data, 'boolean');
    }

    public function testStore()
    {
        $data = ['name' => 'test', 'type' => CastMember::TYPE_ACTOR];
        $response = $this->assertStore($data, $data + ['is_active' => true, 'deleted_at' => null]);
        $response->assertJsonStructure(['created_at', 'updated_at']);

        $data = ['name' => 'test_2', 'type' => CastMember::TYPE_DIRECTOR];
        $this->assertStore($data, $data);
    }

    public function testUpdate()
    {
        $data = [
            'name' => 'test',
            'type' => CastMember::TYPE_ACTOR,
            'is_active' => true
        ];
        $response = $this->assertUpdate($data, $data + ['deleted_at' => null]);
        $response->assertJsonStructure(['created_at', 'updated_at']);
    }

    public function testDestroy() {
        $response = $this->json(
            'DELETE',
            route('cast_member.destroy', ['cast_member' => $this->obj->id]));
        $response->assertStatus(204);

        $this->assertNull(CastMember::find($this->obj->id));
        $this->assertNotNull(CastMember::withTrashed()->find($this->obj->id));
    }

    protected function routeStore()
    {
        return route('cast_member.store');
    }
    protected function routeUpdate()
    {
        return route('cast_member.update', ['cast_member' => $this->obj->id]);
    }

    protected function model()
    {
        return CastMember::class;
    }
}
