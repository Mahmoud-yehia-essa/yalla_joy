<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Update existing permission group names
        DB::table('permissions')
            ->where('group_name', 'إدارة تصنيفات الافاتار')
            ->update(['group_name' => 'إدارة الأفاتار']);

        // 2. Insert new permissions for avatar items
        $permissions = [
            [
                'name' => 'عرض عناصر الافاتار',
                'guard_name' => 'web',
                'group_name' => 'إدارة الأفاتار',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'إضافة عناصر الافاتار',
                'guard_name' => 'web',
                'group_name' => 'إدارة الأفاتار',
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

            // Assign to role 14 (المدير العام) and 16 (المشرف العام) if they exist
            foreach ([14, 16] as $roleId) {
                $roleExists = DB::table('roles')->where('id', $roleId)->exists();
                if ($roleExists) {
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

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $permIds = DB::table('permissions')
            ->whereIn('name', ['عرض عناصر الافاتار', 'إضافة عناصر الافاتار'])
            ->pluck('id');

        DB::table('role_has_permissions')->whereIn('permission_id', $permIds)->delete();
        DB::table('permissions')->whereIn('id', $permIds)->delete();

        // Revert group name
        DB::table('permissions')
            ->where('group_name', 'إدارة الأفاتار')
            ->update(['group_name' => 'إدارة تصنيفات الافاتار']);
    }
};
