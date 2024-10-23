<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarType;
use App\Models\License;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionStatus;
use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\Trip;
use App\Models\TripSpeed;
use App\Models\TripStatus;
use App\Models\TripStop;
use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call(SubscriptionStatusSeeder::class);
        $tenants = Tenant::factory(10)->create();
        SubscriptionPlan::factory(3)->create();

        $tenants->each(function ($tenant) {
            Subscription::factory(3)->withTenantId($tenant->id)->withStatusId(1)->create();
        });


        $this->call(RoleSeeder::class);
        $this->call(TenantRoleSeeder::class);

        TenantUser::factory(10)->create();


        //create users with emails project@gmail.com, system@gmail.com and enumerator@gmail.com
        User::factory()->create(['email' => 'project@gmail.com', 'password' => bcrypt('password')]);
        User::factory()->create(['email' => 'system@gmail.com', 'password' => bcrypt('password')]);
        User::factory()->create(['email' => 'enumerator@gmail.com', 'password' => bcrypt('password')]);
        CarType::factory(10)->create();

        TripStatus::factory(3)->create();

        Trip::factory(10)->create();
        TripSpeed::factory(10)->create();
        TripStop::factory(10)->create();



        PaymentStatus::factory(4)->create();

        Payment::factory(20)->create();


        //create user
        $user = User::factory()->create(['email' => 'test@gmail.com', 'password' => bcrypt('password')]);
        $user2 = User::factory()->create(['email' => 'second@gmail.com', 'password' => bcrypt('password')]);
        $user3 = User::factory()->create(['email' => 'user@gmail.com', 'password' => bcrypt('password')]);

        //create tenant
        $tenant = Tenant::factory()->create(['name' => 'Test Tenant']);

        //create a subscription
        $subscription = Subscription::factory()->withTenantId($tenant->id)->withStatusId(1)->create();



        //associate user with tenant
        $tenant->users()->attach($user->id, ['tenant_role_id' => 1]);
        $tenant->users()->attach($user2->id, ['tenant_role_id' => 2]);
        $tenant->users()->attach($user3->id, ['tenant_role_id' => 2]);

        // create projects
        $tenant->projects()->create(['name' => 'Test Project 1', 'description' => 'This is a test project', 'start_date' => now(), 'end_date' => now()->addDays(30)]);
        $tenant->projects()->create(['name' => 'Test Project 2', 'description' => 'This is a test project', 'start_date' => now(), 'end_date' => now()->addDays(30)]);

        //asign $user2 to project 1 as admin
        $tenant->projects()->first()->users()->attach($user2->id, ['role_id' => 1]);

        //asign $user3 to project 1 as enumerator
        $tenant->projects()->first()->users()->attach($user3->id, ['role_id' => 2]);
        //assign $user2 to project 2 as enumerator
        $tenant->projects->get(1)->users()->attach($user2->id, ['role_id' => 2]);

        // get project user for project 1 user2
        $projectUser = $tenant->projects->first()->users->first()->pivot;

        //get project user for project 1 user3
        $projectUser2 = $tenant->projects->first()->users->get(1)->pivot;

        //create trips
        Trip::factory(4)->create([
            'project_user_id' => $projectUser->id,
            'tenant_id' => $tenant->id,
            'trip_status_id' => 1,
        ]);

        //create trips
        Trip::factory(4)->create([
            'project_user_id' => $projectUser2->id,
            'tenant_id' => $tenant->id,
            'trip_status_id' => 1,
        ]);

        //create payments
        Payment::factory(4)->create([
            'project_id' => $tenant->projects->first()->id,
            'payment_status_id' => 1,
        ]);
    }
}
