<?php

/*
|--------------------------------------------------------------------------
| Test Case
|--------------------------------------------------------------------------
|
| The closure you provide to your test functions is always bound to a specific PHPUnit test
| case class. By default, that class is "PHPUnit\Framework\TestCase". Of course, you may
| need to change it using the "uses()" function to bind a different classes or traits.
|
*/

use App\Models\Batch;
use App\Models\Course;
use App\Models\Member;
use App\Models\Teacher;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Spatie\Permission\Models\Role;

uses(Tests\TestCase::class)->in('Feature');

/*
|--------------------------------------------------------------------------
| Expectations
|--------------------------------------------------------------------------
|
| When you're writing tests, you often need to check that values meet certain conditions. The
| "expect()" function gives you access to a set of "expectations" methods that you can use
| to assert different things. Of course, you may extend the Expectation API at any time.
|
*/

expect()->extend('toBeOne', function () {
    return $this->toBe(1);
});

/*
|--------------------------------------------------------------------------
| Functions
|--------------------------------------------------------------------------
|
| While Pest is very powerful out-of-the-box, you may have some testing code specific to your
| project that you don't want to repeat in every file. Here you can also expose helpers as
| global functions to help you to reduce the number of lines of code in your test files.
|
*/

function actingAs(Authenticatable $user, string $driver = null)
{
    return test()->actingAs($user, $driver);
}

function createTeacher()
{
    $role = Role::firstOrCreate(['name' => 'teacher']);
    $user = createUser(($role));

    return User::find($user->id);
}

function createUser($roleName)
{
    $user = User::factory()->create();
    if ($roleName) {
        if ($roleName == 'teacher') {
            Teacher::factory()->for($user)->create();
        }
        if ($roleName == 'member') {
            Member::factory()->for($user)->create();
        }
        $role = Role::firstOrCreate(['name' => $roleName]);
        $user->assignRole($role);
    }

    return User::find($user->id);
}

function createBatch($user, $batch = null)
{
    $batch = Batch::factory()->for(Course::factory())
        ->create($batch);

    $batch->teachers()->attach($user->teacher->id);

    return $batch;
}
