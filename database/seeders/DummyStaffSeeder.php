<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Illuminate\Support\Str;

/** Seeds a small org tree and sets users_txid for each user. */
class DummyStaffSeeder extends Seeder
{
    public function run(): void
    {
        // helper to txid
        $tx = fn() => (string) Str::uuid();

        $super = User::firstOrCreate(
            ['email' => 'dev@lthr.test'],
            ['name' => 'Super Admin', 'password' => bcrypt('Super@12345'), 'department'=>'IT', 'position'=>'Developer', 'employment_status'=>'active', 'users_txid'=>$tx()]
        );
        $super->syncRoles(['super-admin']);

        $admin = User::firstOrCreate(
            ['email' => 'admin@lthr.test'],
            ['name' => 'Admin', 'password' => bcrypt('Admin@12345'), 'department'=>'HR', 'position'=>'HR Admin', 'employment_status'=>'active', 'users_txid'=>$tx()]
        );
        $admin->syncRoles(['admin']);

        $manager = User::firstOrCreate(
            ['email' => 'manager@lthr.test'],
            ['name' => 'Manager One', 'password' => bcrypt('Manager@12345'), 'department'=>'Operations', 'position'=>'Ops Manager', 'employment_status'=>'active', 'users_txid'=>$tx()]
        );
        $manager->syncRoles(['manager']);

        $leader = User::firstOrCreate(
            ['email' => 'leader@lthr.test'],
            ['name' => 'Leader One', 'password' => bcrypt('Leader@12345'), 'department'=>'Operations', 'position'=>'Team Lead', 'employment_status'=>'active', 'manager_id'=>$manager->id, 'users_txid'=>$tx()]
        );
        $leader->syncRoles(['leader']);

        $u1 = User::factory()->create([
            'email' => 'user1@lthr.test',
            'manager_id' => $leader->id,
            'department' => 'Operations',
            'position' => 'Staff',
            'users_txid' => $tx(),
        ]);
        $u1->syncRoles(['user']);

        $u2 = User::factory()->create([
            'email' => 'user2@lthr.test',
            'manager_id' => $leader->id,
            'department' => 'Operations',
            'position' => 'Staff',
            'users_txid' => $tx(),
        ]);
        $u2->syncRoles(['user']);
    }
}