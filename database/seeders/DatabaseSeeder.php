<?php

namespace Database\Seeders;

use App\Models\Car;
use App\Models\CarType;
use App\Models\Payment;
use App\Models\PaymentStatus;
use App\Models\Project;
use App\Models\ProjectUser;
use App\Models\Subscription;
use App\Models\SubscriptionPlan;
use App\Models\SubscriptionStatus;
use App\Models\Tenant;
use App\Models\TenantUser;
use App\Models\Trip;
use App\Models\TripSpeed;
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
        if (app()->environment('production')) {
            // Only run these seeders in production
            $this->call(SubscriptionStatusSeeder::class);
            $this->call(SubscriptionPlanSeeder::class);
            $this->call(RoleSeeder::class);
            $this->call(TenantRoleSeeder::class);
            $this->call(PaymentStatusSeeder::class);
        } else {
            $tenants = Tenant::factory(10)->create();
            $users = User::factory(10)->create();
            //create users with emails project@gmail.com, system@gmail.com and enumerator@gmail.com
            $projectAdmin = User::factory()->create(['email' => 'project@gmail.com', 'password' => bcrypt('password')]);
            $tenantAdmin = User::factory()->create(['email' => 'system@gmail.com', 'password' => bcrypt('password')]);
            $enumerator = User::factory()->create(['email' => 'enumerator@gmail.com', 'password' => bcrypt('password')]);

            $this->call(SubscriptionStatusSeeder::class);
            $this->call(SubscriptionPlanSeeder::class);
            $this->call(RoleSeeder::class);
            $this->call(TenantRoleSeeder::class);
            $this->call(CarTypeSeeder::class);
            $this->call(PaymentStatusSeeder::class);


            //create cars
            Car::factory(10)->create();
            //create subscriptions for each tenant
            $tenants->each(function ($tenant) {
                Subscription::factory(3)->withTenantId($tenant->id)->withStatusId(1)->create();
            });

            $projects = Project::factory(10)->create();

            // TenantUser::factory(10)->create();
            // ProjectUser::factory(10)->create();


            //create tenant
            $tenant = Tenant::factory()->create(['name' => 'Test Tenant']);

            //create a subscription
            $subscription = Subscription::factory()->withTenantId($tenant->id)->withStatusId(1)->create();



            //associate user with tenant
            $tenant->users()->attach($tenantAdmin->id, ['tenant_role_id' => 1]);
            $tenant->users()->attach($projectAdmin->id, ['tenant_role_id' => 2]);
            $tenant->users()->attach($enumerator->id, ['tenant_role_id' => 2]);

            // create projects
            $tenant->projects()->create(['code' => 'code1', 'name' => 'Test Project 1', 'description' => 'This is a test project', 'start_date' => now(), 'end_date' => now()->addDays(30)]);
            $tenant->projects()->create(['code' => 'code2', 'name' => 'Test Project 2', 'description' => 'This is a test project', 'start_date' => now(), 'end_date' => now()->addDays(30)]);
            $tenant->projects()->create(['code' => 'code3', 'name' => 'Test Project 3', 'description' => 'This is a test project', 'start_date' => now(), 'end_date' => now()->addDays(30)]);

            //asign $projectAdmin to project 1 as admin
            $tenant->projects()->first()->users()->attach($projectAdmin->id, ['role_id' => 1]);

            //asign $enumerator to project 1 as enumerator
            $tenant->projects()->first()->users()->attach($enumerator->id, ['role_id' => 2]);

            //assign $projectAdmin to project 2 as enumerator
            $tenant->projects->get(1)->users()->attach($projectAdmin->id, ['role_id' => 2]);

            //asign $enumerator as enumerator to project 2 and project 3
            $tenant->projects->get(1)->users()->attach($enumerator->id, ['role_id' => 2]);
            $tenant->projects->get(2)->users()->attach($enumerator->id, ['role_id' => 2]);

            // get project user for project 1 user2
            $projectUser = $tenant->projects->first()->users->first()->pivot;

            //get project user for project 1 user3
            $projectUser2 = $tenant->projects->first()->users->get(1)->pivot;

            //create trips
            Trip::factory(4)->create([
                'project_user_id' => $projectUser->id,
                'tenant_id' => $tenant->id,

            ]);

            //create trips
            Trip::factory(4)->create([
                'project_user_id' => $projectUser2->id,
                'tenant_id' => $tenant->id,

            ]);

            //create payments
            Payment::factory(4)->create([
                'project_id' => $tenant->projects->first()->id,
                'payment_status_id' => 1,
            ]);

            $trips = Trip::all();
            Trip::factory(10)->create();
            Payment::factory(20)->create();
            //create trip speeds and stops for each trip
            foreach ($trips as $trip) {
                TripSpeed::factory(3)->create(['trip_id' => $trip->id]);
                TripStop::factory(3)->create(['trip_id' => $trip->id]);
            }
        }
    }
}
