<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_coupons', function (Blueprint $table) {
            

            $table->id();
            
            $table->string('code')->unique(); // كود الكوبون (مثل: GAME50)
            
            // نوع الكوبون لتحديد آلية العمل:
            // 'percentage' -> خصم نسبة مئوية
            // 'free_games' -> ألعاب مجانية مباشرة
            // 'package_bonus' -> ألعاب إضافية عند شراء باقة معينة
            $table->enum('type', ['percentage', 'free_games', 'package_bonus']);
            
            // القيم الخاصة بكل نوع (تكون قابلة للحذف Nullable لأنها تعتمد على النوع المختار)
            $table->decimal('discount_percentage', 5, 2)->nullable(); // نسبة الخصم (مثلاً: 15.50%)
            $table->integer('free_games_count')->nullable(); // عدد الألعاب المجانية المباشرة أو المكافأة
            
            // ربط الكوبون بباقة شراء معينة من جدول تكلفة شراء الألعاب
            $table->foreignId('game_purchases_id')
                  ->nullable() // nullable لأنه ليس كل الكوبونات مرتبطة بباقة
                  ->constrained('game_purchases')
                  ->onDelete('set null'); // إذا حذفت الباقة، يبقى الكوبون ولكن يلغى ربطه

            // حقول إضافية هامة لإدارة الكوبونات مستقبلاً
            $table->integer('usage_limit')->nullable(); // الحد الأقصى لاستخدام الكوبون
            $table->integer('used_count')->default(0); // كم مرة تم استخدام الكوبون فعلياً
            $table->timestamp('expires_at')->nullable(); // تاريخ انتهاء صلاحية الكوبون
            $table->boolean('is_active')->default(true); // حالة الكوبون (نشط / معطل)
            
            $table->timestamps(); // تاريخ الإنشاء والتحديث


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('game_coupons');
    }
};
