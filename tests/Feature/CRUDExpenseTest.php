<?php

namespace Tests\Feature;

use Tests\TestCase;
use App\Models\User;
use App\Models\Role;
use App\Models\Goal;
use App\Models\Event;
use App\Models\Expense;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
class CRUDExpenseTest extends TestCase
{
    use DatabaseTransactions;
    
    public function testCreateExpenseWithAminThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create(['status' => 1]);
        $roleAdminId = Role::where('type', Role::TYPE_SYSTEM)->pluck('id')->first();
        $user->roles()->attach($roleAdminId);
        $this->actingAs($user, 'api');
        $event = factory(Event::class)->create(['user_id' => $user->id]);
        $goal = factory(Goal::class)->create(['event_id' => $event->id]);

        $response = $this->json('POST', route('expense.store'), [
            'type' => [
                0 => [
                    0 => [
                          "event_id" => $event->id,
                          "user_id" => $user->id,
                          "goal_id" => $goal->id,
                          "cost" => 13.54,
                          "time" => '2017-07-30',
                          "type" => 0,
                          "reason" => $faker->sentence(10),
                    ],
                ],
                1 => [
                    0 => [
                          "expense" => [
                            "event_id" => $event->id,
                              "user_id" => $user->id,
                              "goal_id" => $goal->id,
                              "cost" => 13.54,
                              "time" => '2017-07-30',
                              "type" => 1,
                              "reason" => $faker->sentence(10),
                        ],
                          "quantity" => 300,
                          "name" => $faker->word,
                          "quality" => $faker->word,
                    ],
                ],
                "event_id" => $event->id,
            ],
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK);
    }

    public function testCreateExpenseWithOwnerEventThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create(['status' => 1]);
        $roleUser = Role::where('name', Role::ROLE_MEMBER)->pluck('id')->first();
        $user->roles()->attach($roleUser);
        $this->actingAs($user, 'api');
        $event = factory(Event::class)->create(['user_id' => $user->id]);
        $goal = factory(Goal::class)->create(['event_id' => $event->id]);

        $response = $this->json('POST', route('expense.store'), [
            'type' => [
                0 => [
                    0 => [
                          "event_id" => $event->id,
                          "user_id" => $user->id,
                          "goal_id" => $goal->id,
                          "cost" => 13.54,
                          "time" => '2017-07-30',
                          "type" => 0,
                          "reason" => $faker->sentence(10),
                    ],
                ],
                1 => [
                    0 => [
                          "expense" => [
                            "event_id" => $event->id,
                              "user_id" => $user->id,
                              "goal_id" => $goal->id,
                              "cost" => 13.54,
                              "time" => '2017-07-30',
                              "type" => 1,
                              "reason" => $faker->sentence(10),
                        ],
                          "quantity" => 300,
                          "name" => $faker->word,
                          "quality" => $faker->word,
                    ],
                ],
                "event_id" => $event->id,
            ],
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK);
    }

    public function testCreateExpenseNotOwnerEventThenFail()
    {
        $faker = \Faker\Factory::create();
        $user1 = factory(User::class)->create(['status' => 1]);
        $user2 = factory(User::class)->create(['status' => 1]);
        $roleUser = Role::where('name', Role::ROLE_MEMBER)->pluck('id')->first();
        $user1->roles()->attach($roleUser);
        $user2->roles()->attach($roleUser);
        $this->actingAs($user2, 'api');
        $event = factory(Event::class)->create(['user_id' => $user1->id]);
        $goal = factory(Goal::class)->create(['event_id' => $event->id]);

        $response = $this->json('POST', route('expense.store'), [
            'type' => [
                0 => [
                    0 => [
                          "event_id" => $event->id,
                          "user_id" => $user2->id,
                          "goal_id" => $goal->id,
                          "cost" => 13.54,
                          "time" => '2017-07-30',
                          "type" => 0,
                          "reason" => $faker->sentence(10),
                    ],
                ],
                1 => [
                    0 => [
                          "expense" => [
                            "event_id" => $event->id,
                              "user_id" => $user2->id,
                              "goal_id" => $goal->id,
                              "cost" => 13.54,
                              "time" => '2017-07-30',
                              "type" => 1,
                              "reason" => $faker->sentence(10),
                        ],
                          "quantity" => 300,
                          "name" => $faker->word,
                          "quality" => $faker->word,
                    ],
                ],
                "event_id" => $event->id,
            ],
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user2->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(INTERNAL_SERVER_ERROR);
    }

    public function testCreateExpenseDoNotEventThenFail()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create(['status' => 1]);
        $roleUser = Role::where('name', Role::ROLE_MEMBER)->pluck('id')->first();
        $user->roles()->attach($roleUser);
        $this->actingAs($user, 'api');
        $event = factory(Event::class)->create(['user_id' => $user->id]);
        $goal = factory(Goal::class)->create(['event_id' => $event->id]);

        $response = $this->json('POST', route('expense.store'), [
            'type' => [
                0 => [
                    0 => [
                        "event_id" => -1,
                        "user_id" => $user->id,
                        "goal_id" => $goal->id,
                        "cost" => 13.54,
                        "time" => '2017-07-30',
                        "type" => 0,
                        "reason" => $faker->sentence(10),
                    ],
                ],
                1 => [
                    0 => [
                        "expense" => [
                            "event_id" => -1,
                            "user_id" => $user->id,
                            "goal_id" => $goal->id,
                            "cost" => 13.54,
                            "time" => '2017-07-30',
                            "type" => 1,
                            "reason" => $faker->sentence(10),
                            ],
                            "quantity" => 300,
                            "name" => $faker->word,
                            "quality" => $faker->word,
                    ],
                ],
                "event_id" => -1,
            ],
        ], [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(VALIDATOR_ERROR);
    }

    public function testDeleteExpenseWithAdminThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create(['status' => 1]);
        $roleAdminId = Role::where('type', Role::TYPE_SYSTEM)->pluck('id')->first();
        $user->roles()->attach($roleAdminId);
        $this->actingAs($user, 'api');
        $event = factory(Event::class)->create(['user_id' => $user->id]);
        $goal = factory(Goal::class)->create(['event_id' => $event->id]);
        $expense = factory(Expense::class)->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'goal_id' => $goal->id,
        ]);

        $response = $this->json('DELETE', route('expense.destroy', ['expense' => $expense->id]),
        [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK);
    }

    public function testDeleteExpenseWithOwnerEventThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create(['status' => 1]);
        $roleUser = Role::where('name', Role::ROLE_MEMBER)->pluck('id')->first();
        $user->roles()->attach($roleUser);
        $this->actingAs($user, 'api');
        $event = factory(Event::class)->create(['user_id' => $user->id]);
        $goal = factory(Goal::class)->create(['event_id' => $event->id]);
        $expense = factory(Expense::class)->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'goal_id' => $goal->id,
        ]);

        $response = $this->json('DELETE', route('expense.destroy', ['expense' => $expense->id]),
        [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK);
    }

    public function testDeleteExpenseNotOwnerEventThenFail()
    {
        $faker = \Faker\Factory::create();
        $user1 = factory(User::class)->create(['status' => 1]);
        $user2 = factory(User::class)->create(['status' => 1]);
        $roleUser = Role::where('name', Role::ROLE_MEMBER)->pluck('id')->first();
        $user1->roles()->attach($roleUser);
        $user2->roles()->attach($roleUser);
        $this->actingAs($user2, 'api');
        $event = factory(Event::class)->create(['user_id' => $user1->id]);
        $goal = factory(Goal::class)->create(['event_id' => $event->id]);
        $expense = factory(Expense::class)->create([
            'user_id' => $user1->id,
            'event_id' => $event->id,
            'goal_id' => $goal->id,
        ]);

        $response = $this->json('DELETE', route('expense.destroy', ['expense' => $expense->id]),
        [
            'HTTP_Authorization' => 'Bearer ' . $user2->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(INTERNAL_SERVER_ERROR);
    }

    public function testDeleteExpenseWithDoNotExpenseThenFail()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create(['status' => 1]);
        $roleUser = Role::where('name', Role::ROLE_MEMBER)->pluck('id')->first();
        $user->roles()->attach($roleUser);
        $this->actingAs($user, 'api');
        $event = factory(Event::class)->create(['user_id' => $user->id]);
        $goal = factory(Goal::class)->create(['event_id' => $event->id]);
        $expense = factory(Expense::class)->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'goal_id' => $goal->id,
        ]);

        $response = $this->json('DELETE', route('expense.destroy', ['expense' => -1]),
        [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(NOT_FOUND);
    }

    public function testUpdateExpenseWithAdminThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create(['status' => 1]);
        $roleAdminId = Role::where('type', Role::TYPE_SYSTEM)->pluck('id')->first();
        $user->roles()->attach($roleAdminId);
        $this->actingAs($user, 'api');
        $event = factory(Event::class)->create(['user_id' => $user->id]);
        $goal = factory(Goal::class)->create(['event_id' => $event->id]);
        $expense = factory(Expense::class)->create([
            'event_id' => $event->id,
            'goal_id' => $goal->id,
        ]);

        $response = $this->json('PATCH', route('expense.update', ['expense' => $expense->id]), [
            "event_id" => $event->id,
            "user_id" => $user->id,
            "goal_id" => $goal->id,
            "cost" => 13.54,
            "time" => '2017-07-30',
            "type" => 0,
            "reason" => $faker->sentence(10),
        ],
        [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK);
    }

    public function testUpdateExpenseWithOwnerEventThenSuccess()
    {
        $faker = \Faker\Factory::create();
        $user = factory(User::class)->create(['status' => 1]);
        $roleUser = Role::where('name', Role::ROLE_MEMBER)->pluck('id')->first();
        $user->roles()->attach($roleUser);
        $this->actingAs($user, 'api');
        $event = factory(Event::class)->create(['user_id' => $user->id]);
        $goal = factory(Goal::class)->create(['event_id' => $event->id]);
        $expense = factory(Expense::class)->create([
            'user_id' => $user->id,
            'event_id' => $event->id,
            'goal_id' => $goal->id,
        ]);

        $response = $this->json('PATCH', route('expense.update', ['expense' => $expense->id]), [
            "event_id" => $event->id,
            "user_id" => $user->id,
            "goal_id" => $goal->id,
            "cost" => 13.54,
            "time" => '2017-07-30',
            "type" => 1,
            "reason" => $faker->sentence(10),
            "quantity" => 300,
            "name" => $faker->word,
            "quality" => $faker->word,
        ],
        [
            'HTTP_Authorization' => 'Bearer ' . $user->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(CODE_OK);
    }

    public function testUpdateExpenseWithNotOwnerEventThenFail()
    {
        $faker = \Faker\Factory::create();
        $user1 = factory(User::class)->create(['status' => 1]);
        $user2 = factory(User::class)->create(['status' => 1]);
        $roleUser = Role::where('name', Role::ROLE_MEMBER)->pluck('id')->first();
        $user1->roles()->attach($roleUser);
        $user2->roles()->attach($roleUser);
        $this->actingAs($user2, 'api');
        $event = factory(Event::class)->create(['user_id' => $user1->id]);
        $goal = factory(Goal::class)->create(['event_id' => $event->id]);
        $expense = factory(Expense::class)->create([
            'user_id' => $user1->id,
            'event_id' => $event->id,
            'goal_id' => $goal->id,
        ]);

        $response = $this->json('PATCH', route('expense.update', ['expense' => $expense->id]), [
            "event_id" => $event->id,
            "user_id" => $user2->id,
            "goal_id" => $goal->id,
            "cost" => 13.54,
            "time" => '2017-07-30',
            "type" => 1,
            "reason" => $faker->sentence(10),
            "quantity" => 300,
            "name" => $faker->word,
            "quality" => $faker->word,
        ],
        [
            'HTTP_Authorization' => 'Bearer ' . $user2->createToken('myToken')->accessToken,
        ]);

        $response->assertStatus(INTERNAL_SERVER_ERROR);
    }
}
