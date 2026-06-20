<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Illuminate\Support\Facades\DB;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        DB::beginTransaction();
        try {

            $permissions = [

                        // Dashboard
                        [ 'name' => 'dashboard', 'guard_name' => 'web', 'is_active' => 1 ],

                        // Transactions
                        [ 'name' => 'transactions_index', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'transactions_create', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'transactions_show', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'transactions_edit', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'transactions_destroy', 'guard_name' => 'web', 'is_active' => 1 ],

                        // Admins
                        [ 'name' => 'users_index', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'users_create', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'users_show', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'users_edit', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'users_destroy', 'guard_name' => 'web', 'is_active' => 1 ],
                       
                        // Roles
                        [ 'name' => 'roles_index', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'roles_create', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'roles_edit', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'roles_destroy', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'roles_assign', 'guard_name' => 'web', 'is_active' => 1 ],

                        // Settings
                        [ 'name' => 'settings_configuration_index', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'settings_configuration_edit', 'guard_name' => 'web', 'is_active' => 1 ],

                        [ 'name' => 'settings_security_index', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'settings_security_edit', 'guard_name' => 'web', 'is_active' => 1 ],

                        [ 'name' => 'settings_profile_show', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'settings_profile_edit', 'guard_name' => 'web', 'is_active' => 1 ],

                        //Activities
                        [ 'name' => 'activities_index', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'activities_show', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'activities_destroy', 'guard_name' => 'web', 'is_active' => 1 ],
                        [ 'name' => 'activities_export', 'guard_name' => 'web', 'is_active' => 1 ],

                    ];

                foreach ($permissions as $permission) {
                    Permission::updateOrCreate([
                        'name' => $permission['name'],
                        'guard_name' => $permission['guard_name'],
                    ]);
                }

            DB::commit();
        } catch (\Throwable $e) {
            DB::rollBack();
            throw $e;
        }
    }
}
