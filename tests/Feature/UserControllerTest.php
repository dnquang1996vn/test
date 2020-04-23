<?php

use Laravel\Lumen\Testing\DatabaseMigrations;

class UserControllerTest extends TestCase
{
    use DatabaseMigrations;
    /**
     * A basic test example.
     *
     * @return void
     */
    public function test_get_user()
    {
        factory(\App\Models\User::class, 3)->create();

        $response = $this->json('get','/user');

//        dd($response->response->getContent());

        $response->receiveJson([
        'data' => \App\Models\User::all()->toArray()
    ]);

    }
}
