<?php

use App\Repositories\UserRepositoryInterface;
use Laravel\Lumen\Testing\DatabaseMigrations;
use Faker\Factory as Faker;

/**
 * @property \Illuminate\Database\Eloquent\Collection|\Illuminate\Database\Eloquent\Model|mixed users
 * @property UserRepositoryInterface userRepository
 */
class UserRepositoryTest extends TestCase
{
    use DatabaseMigrations;

    protected function setUp() : void
    {
        parent::setUp();

        $this->users = factory(\App\Models\User::class, 10)->create();
        $this->userRepository = app(UserRepositoryInterface::class);
    }

    public function test_get_all_user()
    {
        $users = $this->userRepository->all();
        $this->assertEquals(10, $users->count());
        $this->assertEquals(10, $users->count());
    }

    public function test_destroy_a_user()
    {
        $this->userRepository->deleteById(1);
        $this->assertEquals($this->userRepository->count(), 9);

        $user = $this->userRepository->first()->delete();
        $this->assertEquals($this->userRepository->count(), 8);
    }

    public function test_search_a_user()
    {
        $user = $this->userRepository->find(2);

        $searchUser = $this->userRepository->findByColumn( 'name', $user->name);
        $this->assertEquals($user->id, $searchUser->id);

        $searchUser = $this->userRepository->where('name', $user->name)->first();
        $this->assertEquals($user->id, $searchUser->id);
    }

    public function test_limit_user()
    {
        $users = $this->userRepository->limit(4);
        $this->assertEquals(4, $users->count());
    }

    public function test_create_user()
    {
        $newUserData = [
            'name' => Faker::create()->name,
            'email' => Faker::create()->email,
        ];

//        $newUsers = $this->userRepository->getQuery()->create($newUserData);
//        $this->seeInDatabase('users', $newUserData);

        $newUsers = $this->userRepository->create($newUserData);
        $this->seeInDatabase('users', $newUserData);


    }
}
