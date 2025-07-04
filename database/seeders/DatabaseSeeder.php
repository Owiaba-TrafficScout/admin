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

            $tenant = Tenant::create([
                'name' => 'Centre for Transport Studies, UCT',
                'email' => 'obiora.nnene@uct.ac.za'
            ]);

            $tenant->subscriptions()->create([
                'plan_id' => SubscriptionPlan::where('name', 'Enterprise')->first()->id,
                'trial_ends_at' => now()->addMonths(6),
                'start_date' => now(),
                'end_date' => now()->addMonths(6),
                'status_id' => SubscriptionStatus::where('name', 'active')->first()->id,
            ]);
            $user =   User::factory()->create([
                'name' => 'Obiora Nnene',
                'email' => 'obiora.nnene@uct.ac.za',
                'password' => 'obioraproject',
            ]);

            $tenant->users()->syncWithoutDetaching([$user->id => ['tenant_role_id' => config('constants.tenant_roles.admin')]]);
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


            // ========================================
            // CREATE COMPREHENSIVE DATA FOR TENANT ID 1 FOR ANALYSIS
            // ========================================

            // Ensure tenant with ID 1 exists or create it
            $analysisTenant = Tenant::find(1) ?? Tenant::factory()->create([
                'id' => 1,
                'name' => 'Analysis Transport Research Institute',
                'email' => 'analysis@transport.com'
            ]);

            // Create active subscription for analysis tenant
            $analysisSubscription = Subscription::factory()->withTenantId($analysisTenant->id)->withStatusId(1)->create([
                'start_date' => now()->subMonths(6),
                'end_date' => now()->addMonths(6),
            ]);

            // Create comprehensive user base for analysis
            $analysisUsers = [];

            // Create tenant admin
            $analysisUsers['tenant_admin'] = User::factory()->create([
                'name' => 'Analysis Tenant Admin',
                'email' => 'tenant.admin@analysis.com',
                'password' => bcrypt('password')
            ]);

            // Create project managers
            $analysisUsers['project_manager_1'] = User::factory()->create([
                'name' => 'Project Manager Alpha',
                'email' => 'pm.alpha@analysis.com',
                'password' => bcrypt('password')
            ]);

            $analysisUsers['project_manager_2'] = User::factory()->create([
                'name' => 'Project Manager Beta',
                'email' => 'pm.beta@analysis.com',
                'password' => bcrypt('password')
            ]);

            // Create field enumerators
            for ($i = 1; $i <= 8; $i++) {
                $analysisUsers["enumerator_$i"] = User::factory()->create([
                    'name' => "Field Enumerator $i",
                    'email' => "enumerator.$i@analysis.com",
                    'password' => bcrypt('password')
                ]);
            }

            // Associate users with tenant
            $analysisTenant->users()->attach($analysisUsers['tenant_admin']->id, ['tenant_role_id' => 1]);
            $analysisTenant->users()->attach($analysisUsers['project_manager_1']->id, ['tenant_role_id' => 2]);
            $analysisTenant->users()->attach($analysisUsers['project_manager_2']->id, ['tenant_role_id' => 2]);

            for ($i = 1; $i <= 8; $i++) {
                $analysisTenant->users()->attach($analysisUsers["enumerator_$i"]->id, ['tenant_role_id' => 2]);
            }

            // Create comprehensive projects with different characteristics
            $analysisProjects = [];

            // Urban Transport Study - High frequency, short distance trips
            $analysisProjects['urban'] = $analysisTenant->projects()->create([
                'code' => 'UTS2024',
                'name' => 'Urban Transport Study 2024',
                'description' => 'Comprehensive analysis of urban transport patterns in metropolitan areas',
                'start_date' => now()->subMonths(3),
                'end_date' => now()->addMonths(3)
            ]);

            // Rural Transport Study - Low frequency, long distance trips
            $analysisProjects['rural'] = $analysisTenant->projects()->create([
                'code' => 'RTS2024',
                'name' => 'Rural Transport Study 2024',
                'description' => 'Analysis of transport accessibility and patterns in rural communities',
                'start_date' => now()->subMonths(2),
                'end_date' => now()->addMonths(4)
            ]);

            // Public Transport Efficiency Study
            $analysisProjects['public'] = $analysisTenant->projects()->create([
                'code' => 'PTE2024',
                'name' => 'Public Transport Efficiency 2024',
                'description' => 'Evaluation of public transport systems efficiency and user satisfaction',
                'start_date' => now()->subMonths(1),
                'end_date' => now()->addMonths(5)
            ]);

            // Freight Transport Study
            $analysisProjects['freight'] = $analysisTenant->projects()->create([
                'code' => 'FTS2024',
                'name' => 'Freight Transport Analysis 2024',
                'description' => 'Commercial freight movement patterns and logistics efficiency study',
                'start_date' => now()->subWeeks(2),
                'end_date' => now()->addMonths(6)
            ]);

            // Assign project managers and enumerators to projects

            // Urban project: PM1 as admin, 3 enumerators
            $analysisProjects['urban']->users()->attach($analysisUsers['project_manager_1']->id, ['role_id' => 1]);
            $analysisProjects['urban']->users()->attach($analysisUsers['enumerator_1']->id, ['role_id' => 2]);
            $analysisProjects['urban']->users()->attach($analysisUsers['enumerator_2']->id, ['role_id' => 2]);
            $analysisProjects['urban']->users()->attach($analysisUsers['enumerator_3']->id, ['role_id' => 2]);

            // Rural project: PM2 as admin, 2 enumerators
            $analysisProjects['rural']->users()->attach($analysisUsers['project_manager_2']->id, ['role_id' => 1]);
            $analysisProjects['rural']->users()->attach($analysisUsers['enumerator_4']->id, ['role_id' => 2]);
            $analysisProjects['rural']->users()->attach($analysisUsers['enumerator_5']->id, ['role_id' => 2]);

            // Public transport: PM1 as enumerator, 2 dedicated enumerators
            $analysisProjects['public']->users()->attach($analysisUsers['project_manager_1']->id, ['role_id' => 2]);
            $analysisProjects['public']->users()->attach($analysisUsers['enumerator_6']->id, ['role_id' => 2]);
            $analysisProjects['public']->users()->attach($analysisUsers['enumerator_7']->id, ['role_id' => 2]);

            // Freight project: PM2 as enumerator, 1 dedicated enumerator
            $analysisProjects['freight']->users()->attach($analysisUsers['project_manager_2']->id, ['role_id' => 2]);
            $analysisProjects['freight']->users()->attach($analysisUsers['enumerator_8']->id, ['role_id' => 2]);

            // Generate comprehensive trip data for analysis
            $analysisTrips = [];

            // Urban project trips - High frequency, varied patterns
            $urbanProjectUsers = $analysisProjects['urban']->users()->wherePivot('role_id', 2)->get();
            foreach ($urbanProjectUsers as $user) {
                $projectUser = $user->pivot;

                // Create 15-25 trips per enumerator for urban study
                $tripCount = rand(15, 25);
                for ($j = 0; $j < $tripCount; $j++) {
                    $trip = Trip::factory()->create([
                        'project_user_id' => $projectUser->id,
                        'tenant_id' => $analysisTenant->id,
                        'created_at' => now()->subDays(rand(1, 90)),
                    ]);
                    $analysisTrips[] = $trip;

                    // Urban trips: More speeds (frequent stops), moderate stops
                    TripSpeed::factory(rand(8, 15))->create(['trip_id' => $trip->id]);
                    TripStop::factory(rand(3, 8))->create(['trip_id' => $trip->id]);
                }
            }

            // Rural project trips - Lower frequency, longer distances
            $ruralProjectUsers = $analysisProjects['rural']->users()->wherePivot('role_id', 2)->get();
            foreach ($ruralProjectUsers as $user) {
                $projectUser = $user->pivot;

                // Create 8-15 trips per enumerator for rural study
                $tripCount = rand(8, 15);
                for ($j = 0; $j < $tripCount; $j++) {
                    $trip = Trip::factory()->create([
                        'project_user_id' => $projectUser->id,
                        'tenant_id' => $analysisTenant->id,
                        'created_at' => now()->subDays(rand(1, 60)),
                    ]);
                    $analysisTrips[] = $trip;

                    // Rural trips: Fewer speeds (longer distances), fewer stops
                    TripSpeed::factory(rand(5, 12))->create(['trip_id' => $trip->id]);
                    TripStop::factory(rand(2, 5))->create(['trip_id' => $trip->id]);
                }
            }

            // Public transport trips - Regular patterns
            $publicProjectUsers = $analysisProjects['public']->users()->wherePivot('role_id', 2)->get();
            foreach ($publicProjectUsers as $user) {
                $projectUser = $user->pivot;

                // Create 12-20 trips per enumerator for public transport study
                $tripCount = rand(12, 20);
                for ($j = 0; $j < $tripCount; $j++) {
                    $trip = Trip::factory()->create([
                        'project_user_id' => $projectUser->id,
                        'tenant_id' => $analysisTenant->id,
                        'created_at' => now()->subDays(rand(1, 30)),
                    ]);
                    $analysisTrips[] = $trip;

                    // Public transport: Regular speed patterns, predictable stops
                    TripSpeed::factory(rand(6, 10))->create(['trip_id' => $trip->id]);
                    TripStop::factory(rand(4, 7))->create(['trip_id' => $trip->id]);
                }
            }

            // Freight trips - Specialized patterns
            $freightProjectUsers = $analysisProjects['freight']->users()->wherePivot('role_id', 2)->get();
            foreach ($freightProjectUsers as $user) {
                $projectUser = $user->pivot;

                // Create 10-18 trips per enumerator for freight study
                $tripCount = rand(10, 18);
                for ($j = 0; $j < $tripCount; $j++) {
                    $trip = Trip::factory()->create([
                        'project_user_id' => $projectUser->id,
                        'tenant_id' => $analysisTenant->id,
                        'created_at' => now()->subDays(rand(1, 14)),
                    ]);
                    $analysisTrips[] = $trip;

                    // Freight trips: Consistent speeds, strategic stops
                    TripSpeed::factory(rand(4, 8))->create(['trip_id' => $trip->id]);
                    TripStop::factory(rand(2, 4))->create(['trip_id' => $trip->id]);
                }
            }

            // Generate comprehensive payment data
            foreach ($analysisProjects as $project) {
                // Create varied payment scenarios for analysis

                // Paid payments
                Payment::factory(rand(8, 15))->create([
                    'project_id' => $project->id,
                    'payment_status_id' => 1, // Paid
                    'created_at' => now()->subDays(rand(1, 60)),
                ]);

                // Pending payments
                Payment::factory(rand(2, 5))->create([
                    'project_id' => $project->id,
                    'payment_status_id' => 2, // Pending (if exists)
                    'created_at' => now()->subDays(rand(1, 30)),
                ]);

                // Failed payments (for analysis)
                Payment::factory(rand(1, 3))->create([
                    'project_id' => $project->id,
                    'payment_status_id' => 3, // Failed (if exists)
                    'created_at' => now()->subDays(rand(1, 45)),
                ]);
            }

            // Create additional test tenant for comparison
            $testTenant = Tenant::factory()->create(['name' => 'Test Tenant']);
            $testSubscription = Subscription::factory()->withTenantId($testTenant->id)->withStatusId(1)->create();

            // Associate basic users with test tenant
            $testTenant->users()->attach($tenantAdmin->id, ['tenant_role_id' => 1]);
            $testTenant->users()->attach($projectAdmin->id, ['tenant_role_id' => 2]);
            $testTenant->users()->attach($enumerator->id, ['tenant_role_id' => 2]);

            // Create basic projects for test tenant
            $testTenant->projects()->create([
                'code' => 'TEST001',
                'name' => 'Basic Test Project 1',
                'description' => 'Simple test project for basic functionality',
                'start_date' => now(),
                'end_date' => now()->addDays(30)
            ]);

            $testTenant->projects()->create([
                'code' => 'TEST002',
                'name' => 'Basic Test Project 2',
                'description' => 'Second test project for basic functionality',
                'start_date' => now(),
                'end_date' => now()->addDays(30)
            ]);

            // Assign users to test projects
            $testProject1 = $testTenant->projects()->first();
            $testProject1->users()->attach($projectAdmin->id, ['role_id' => 1]);
            $testProject1->users()->attach($enumerator->id, ['role_id' => 2]);

            // Create minimal data for test tenant
            $testProjectUser = $testProject1->users()->first()->pivot;

            Trip::factory(5)->create([
                'project_user_id' => $testProjectUser->id,
                'tenant_id' => $testTenant->id,
            ]);

            Payment::factory(3)->create([
                'project_id' => $testProject1->id,
                'payment_status_id' => 1,
            ]);

            // Generate additional random data for other tenants
            $allTrips = Trip::all();
            Trip::factory(20)->create();
            Payment::factory(30)->create();

            // Create trip details for all existing trips
            foreach ($allTrips as $trip) {
                if (!$trip->speeds()->exists()) {
                    TripSpeed::factory(rand(3, 10))->create(['trip_id' => $trip->id]);
                }
                if (!$trip->stops()->exists()) {
                    TripStop::factory(rand(2, 6))->create(['trip_id' => $trip->id]);
                }
            }
        }
    }
}
