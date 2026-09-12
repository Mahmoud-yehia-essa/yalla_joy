<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (Schema::hasTable('permissions')) {
            $permissions = [
                [
                    'name' => 'عرض الشروط والأحكام',
                    'guard_name' => 'web',
                    'group_name' => 'إدارة الشروط والأحكام',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'إضافة الشروط والأحكام',
                    'guard_name' => 'web',
                    'group_name' => 'إدارة الشروط والأحكام',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'تعديل الشروط والأحكام',
                    'guard_name' => 'web',
                    'group_name' => 'إدارة الشروط والأحكام',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'حذف الشروط والأحكام',
                    'guard_name' => 'web',
                    'group_name' => 'إدارة الشروط والأحكام',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
            ];

            foreach ($permissions as $perm) {
                $existing = DB::table('permissions')
                    ->where('name', $perm['name'])
                    ->where('guard_name', $perm['guard_name'])
                    ->first();

                if (!$existing) {
                    $id = DB::table('permissions')->insertGetId($perm);
                } else {
                    $id = $existing->id;
                }

                if (Schema::hasTable('roles') && Schema::hasTable('role_has_permissions')) {
                    $roles = DB::table('roles')->pluck('id');
                    foreach ($roles as $roleId) {
                        $hasPermission = DB::table('role_has_permissions')
                            ->where('permission_id', $id)
                            ->where('role_id', $roleId)
                            ->exists();

                        if (!$hasPermission) {
                            DB::table('role_has_permissions')->insert([
                                'permission_id' => $id,
                                'role_id' => $roleId
                            ]);
                        }
                    }
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('permissions')) {
            $permIds = DB::table('permissions')
                ->whereIn('name', [
                    'عرض الشروط والأحكام',
                    'إضافة الشروط والأحكام',
                    'تعديل الشروط والأحكام',
                    'حذف الشروط والأحكام'
                ])
                ->pluck('id');

            if (Schema::hasTable('role_has_permissions')) {
                DB::table('role_has_permissions')->whereIn('permission_id', $permIds)->delete();
            }
            DB::table('permissions')->whereIn('id', $permIds)->delete();
        }
    }
};
