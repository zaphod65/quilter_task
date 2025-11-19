<?php

use Illuminate\Http\Response;

describe('register endpoint', function () {
    describe('successful request', function () {
        beforeEach(function () {
            $this->name = 'test name';
            $this->email = 'mail@fake.com';
            $this->password = 'password';
            $payload = [
                'name' => $this->name,
                'email' => $this->email,
                'password' => $this->password,
                'confirm_password' => $this->password,
            ];
            $this->response = $this->post(route('register'), $payload);
        });

        it('responds with success', function () {
            // This fails due to silly OAuth scaffolding missing in test,
            // works fine in development
            $this->response->assertOk();
        });

        it('creates a user record', function () {
            $this->assertDatabaseHas('users', [
                'name' => $this->name,
                'email' => $this->email,
            ]);
        });
    });

    describe('failed request', function () {
        beforeEach(function () {
            $this->name = 'test name';
            $this->email = 'mail@fake.com';
            $payload = [
                'name' => $this->name,
                'email' => $this->email,
                'password' => 'password',
                'confirm_password' => '1password',
            ];
            $this->response = $this->post(route('register'), $payload);
        });

        it('fails when password confirmation does not match password', function () {
            $this->response->assertStatus(Response::HTTP_UNPROCESSABLE_ENTITY);
        });

        it('returns an error about confirm_password', function () {
            $this->response->assertJson([
                'confirm_password' => [
                    'The confirm password field must match password.',
                ],
            ]);
        });

        it('does not create a user', function () {
            $this->assertDatabaseMissing('users', [
                'name' => $this->name,
                'email' => $this->email,
            ]);
        });
    });
});
