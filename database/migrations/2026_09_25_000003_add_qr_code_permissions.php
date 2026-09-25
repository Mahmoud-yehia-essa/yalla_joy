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
                    'name' => 'عرض QR Code',
                    'guard_name' => 'web',
                    'group_name' => 'إدارة QR Code',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'إضافة QR Code',
                    'guard_name' => 'web',
                    'group_name' => 'إدارة QR Code',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'تعديل QR Code',
                    'guard_name' => 'web',
                    'group_name' => 'إدارة QR Code',
                    'created_at' => now(),
                    'updated_at' => now()
                ],
                [
                    'name' => 'حذف QR Code',
                    'guard_name' => 'web',
                    'group_name' => 'إدارة QR Code',
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
                    $adminRole = DB::table('roles')->where('name', 'Super Admin')->orWhere('name', 'admin')->get();
                    foreach ($adminRole as $r) {
                        $exists = DB::table('role_has_permissions')
                            ->where('permission_id', $id)
                            ->where('role_id', $r->id)
                            ->exists();

                        if (!$exists) {
                            DB::table('role_has_permissions')->insert([
                                'permission_id' => $id,
                                'role_id' => $r->id
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
            $permNames = ['عرض QR Code', 'إضافة QR Code', 'تعديل QR Code', 'حذف QR Code'];
            $permIds = DB::table('permissions')->whereIn('name', $permNames)->pluck('id');

            if (Schema::hasTable('role_has_permissions')) {
                DB::table('role_has_permissions')->whereIn('permission_id', $permIds)->delete();
            }

            DB::table('permissions')->whereIn('name', $permNames)->delete();
        }
    }
};
