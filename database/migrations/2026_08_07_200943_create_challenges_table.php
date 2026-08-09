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
       Schema::create('challenges', function (Blueprint $table) {
            $table->id(); // ينشئ حقل id تلقائياً كـ Primary Key
            
            // ربط حقل المرسل بجدول users
            $table->foreignId('user_id_send_invitataion')
                  ->constrained('users')
                  ->cascadeOnDelete();
            
            // ربط حقل المستقبل بجدول users
            $table->foreignId('user_id_resive_invitaion')
                  ->constrained('users')
                  ->cascadeOnDelete();
            
            // حقل التاريخ (استخدمنا dateTime لتسجيل الوقت والتاريخ معاً)
            $table->dateTime('date'); 
            
            // حقل حالة الدعوة (يمكن أن يكون string أو enum)
            $table->string('invitation_statue')->default('pending');
            
            // كود اللعبة
            $table->string('game_code');
            
            $table->timestamps(); // يضيف حقول created_at و updated_at
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('challenges');
    }
};
