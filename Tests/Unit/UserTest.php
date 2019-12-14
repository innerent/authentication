<?php

namespace Innerent\Authentication\Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseTransactions;
use Innerent\Foundation\Models\LegalDocument;
use Innerent\Authentication\Models\User;
use Tests\TestCase;

class UserTest extends TestCase
{
    use DatabaseTransactions;

    private $authUser;

    function setUp(): void
    {
        parent::setUp();

        $this->authUser = factory(User::class)->create();

        $this->authUser->givePermissionTo([
            'user_create',
            'user_read',
            'user_update',
            'user_destroy',
        ]);
    }

    public function testCreateUser()
    {
        $data = factory(User::class)->make()->toArray();

        $data['password'] = 'password';
        $data['password_confirmation'] = 'password';

        $data['roles'] = [1, 2];

        $data['documents'] = factory(LegalDocument::class, 2)->make()->toArray();

        unset($data['email_verified_at']);

        $response = $this->actingAs($this->authUser, 'api')->json('post', route('innerent.users.store'), $data);

        $response->assertStatus(201);
    }

    public function testListUsers()
    {
        $this->actingAs($this->authUser, 'api')->json('get', route('innerent.users.index'))->assertStatus(200);
    }

    public function testShowUser()
    {
        $this->actingAs($this->authUser, 'api')->json('get', route('innerent.users.show', ['user' => $this->authUser->uuid]))->assertStatus(200);
    }

    public function testUpdateUser()
    {
        $newData = factory(User::class)->create()->toArray();

        unset($newData['email_verified_at']);

        $newData['uuid'] = $this->authUser->uuid;
        $newData['email'] = $newData['email'] . 'sd';

        unset($newData['created_at']);

        $response = $this->actingAs($this->authUser, 'api')->json('put', route('innerent.users.update', ['user' => $this->authUser->uuid]), $newData);

        $response->assertJsonFragment($newData)->assertStatus(200);
    }

    public function testDeleteUser()
    {
        $this->actingAs($this->authUser, 'api')
            ->json('delete', route('innerent.users.show', ['user' => $this->authUser->uuid]))
            ->assertStatus(204);
    }

    public function testShowOwnUser()
    {
        $user = factory(User::class)->create();

        $this->actingAs($user, 'api')->json('get', route('innerent.users.show', ['user' => $this->authUser->uuid]))->assertStatus(403);

        $this->actingAs($user, 'api')->json('get', route('innerent.users.show', ['user' => $user->uuid]))->assertStatus(200);
    }
}
