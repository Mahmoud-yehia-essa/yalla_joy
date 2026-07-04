<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        if (!Schema::hasColumn('permissions', 'group_name')) {
            Schema::table('permissions', function (Blueprint $table) {
                $table->string('group_name')->nullable()->after('guard_name');
            });
        }

        $permissions = [
            [
                'name' => 'عرض تصنيفات الافاتار',
                'guard_name' => 'web',
                'group_name' => 'إدارة تصنيفات الافاتار',
                'created_at' => now(),
                'updated_at' => now()
            ],
            [
                'name' => 'إضافة تصنيفات الافاتار',
                'guard_name' => 'web',
                'group_name' => 'إدارة تصنيفات الافاتار',
                'created_at' => now(),
                'updated_at' => now()
            ],
        ];

        foreach ($permissions as $perm) {
            // Check if permission already exists
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
            ->whereIn('name', ['عرض تصنيفات الافاتار', 'إضافة تصنيفات الافاتار'])
            ->pluck('id');

        DB::table('role_has_permissions')->whereIn('permission_id', $permIds)->delete();
        DB::table('permissions')->whereIn('id', $permIds)->delete();
    }
};
