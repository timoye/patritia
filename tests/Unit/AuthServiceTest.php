<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\DatabaseMigrations;
use Tests\TestCase;

class AuthServiceTest extends TestCase
{
    use DatabaseMigrations;
    public function testRequiredFieldsForRegistration(){
        $data=[];
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'name' => ['The name field is required.'],
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }
    public function testRegistrationForEmailWithAccount(){
        \App\User::updateOrCreate(['name'=>'Tim','email'=>'tim@gmail.com','password'=>bcrypt('password')]);
        $data=[
            'name'=>'Tim',
            'email'=>'tim@gmail.com',
            'password'=>'password'
        ];
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email has already been taken.'],
                ]
            ]);
    }
    public function testSuccessfulRegistration(){
        $data=[
            'name'=>'Tim',
            'email'=>'timothy@gmail.com',
            'password'=>'password'
        ];
        $response = $this->postJson('/api/register', $data);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'token'
            ]);
    }

    public function testLoginWithEmailThatDoesNotExist()
    {
        $response = $this->postJson('/api/login', ['email' => 'timd@gmail.com','password'=>'password']);
        $response->assertStatus(403)
            ->assertExactJson([
                'status'=> 'fail',
                'message'=> 'unauthenticated'
            ]);
    }
    public function testLoginWithWrongCredentials()
    {
        $response = $this->postJson('/api/login', ['email' => 'tim@gmail.com','password'=>'password222']);
        $response->assertStatus(403)
            ->assertExactJson([
                'status'=> 'fail',
                'message'=> 'unauthenticated'
            ]);
    }
    public function testRequiredFieldsForLogin(){
        $data=[];
        $response = $this->postJson('/api/login', $data,['Accept' => 'application/json']);
        $response->assertStatus(422)
            ->assertJson([
                'message' => 'The given data was invalid.',
                'errors' => [
                    'email' => ['The email field is required.'],
                    'password' => ['The password field is required.'],
                ]
            ]);
    }
    public function testSuccessfulLogin(){
        \App\User::updateOrCreate(['name'=>'Tim','email'=>'tim@gmail.com','password'=>bcrypt('password')]);
        $data=[
            'email'=>'tim@gmail.com',
            'password'=>'password'
        ];
        $response = $this->postJson('/api/login', $data);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'token'
            ]);
    }

    public function testAccessRenewTokenWithoutAccessToken()
    {
        $response = $this->getJson('/api/renew-token');
        $response->assertStatus(401)
            ->assertExactJson([
                'message'=> 'Unauthenticated.'
            ]);
    }
    public function testSuccessfulRenewToken(){
        $user=\App\User::updateOrCreate(['name'=>'Tim','email'=>'tim@gmail.com','password'=>bcrypt('password')]);
        $token = $user->createToken('test-app')->plainTextToken;
        $response = $this->getJson('/api/renew-token', ['Authorization' => 'Bearer '.$token]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'status',
                'message',
                'token'
            ]);
    }

    public function testAccessUserDataWithoutAccessToken()
    {
        $response = $this->getJson('/api/user-data');
        $response->assertStatus(401)
            ->assertExactJson([
                'message'=> 'Unauthenticated.'
            ]);
    }
    public function testSuccessfulUserData(){
        $user=\App\User::updateOrCreate(['name'=>'Tim','email'=>'tim@gmail.com','password'=>bcrypt('password')]);
        $token = $user->createToken('test-app')->plainTextToken;
        $response = $this->getJson('/api/user-data', ['Authorization' => 'Bearer '.$token]);
        $response->assertStatus(200)
            ->assertJsonStructure([
                'id',
                'name',
                'email',
                'email_verified_at',
                'created_at',
                'updated_at'
            ]);
    }

}
