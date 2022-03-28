<?php

namespace Tests\Unit;

use App\Models\User;
use App\Models\UserType;
use Illuminate\Support\Facades\Crypt;
use Tests\TestCase;

class UserTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
    public function test_index()
    {
        $response = $this->get('/system/users');
        $response->assertStatus(200);
    }
    public function test_store()
    {
        $userType = UserType::where('title', 'admin')->first();
        $response = $this->post('/system/users', [
            'email' => 'james@gmail.com',
            'name' => 'James Owusu',
            'gender' => 'male',
            'password' => 'password',
            'phone_number' => '0233445567',
            'user_type_id' => $userType->id
        ]);
        $response->assertStatus(200);
    }
    public function test_update()
    {
        $user = User::all()->first();
        $response = $this->put('/system/users/'.Crypt::encrypt($user->id), [
            'email' => $user->email,
            'name' => 'James Owusu Test',
            'gender' => $user->email,
            'phone_number' => '0433445567',
            'user_type_id' => $user->user_type_id
        ]);
        $response->assertStatus(200);
    }
    public function test_delete()
    {
        $user = User::all()->first();
        $response = $this->delete('/system/users/'.Crypt::encrypt($user->id));
        $response->assertStatus(200);
    }
    public function test_bulk_delete()
    {
        $users = User::all()->take(3);
        $ids = [];
        foreach($users as $user){
            $ids[] = $user->encrypted_id;
        }
        $response = $this->post('/system/user-bulk-delete', $ids);
        $response->assertStatus(200);
    }
    public function test_restore()
    {
        $user = User::onlyTrashed()->first();
        $response = $this->delete('/system/restore-user/'.Crypt::encrypt($user->id));
        $response->assertStatus(200);
    }
}
