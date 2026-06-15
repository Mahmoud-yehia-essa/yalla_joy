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
        Schema::create('problem_reports', function (Blueprint $table) {
           

            $table->id(); // المعرف التلقائي للبلاغ
            
            // نوع المشكلة باستخدام ENUM بناءً على الخيارات المتاحة لديك
            $table->enum('issue_type', [
                'question_error',    // خطأ في السؤال
                'answer_error',      // خطأ في الإجابة
                'inappropriate_content' // محتوى غير لائق
            ]);
            
            // ملاحظات إضافية (nullable لأن المستخدم قد لا يكتب ملاحظات ويكتفي بتحديد النوع)
            $table->text('additional_notes')->nullable(); 
            
            // (اختياري ولكن مهم جداً) ربط البلاغ بالمستخدم الذي أبلغ عن المشكلة
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            
            $table->foreignId('question_id')->constrained()->onDelete('cascade'); // Foreign key to questions table
            
            // (اختياري) حقل لمتابعة حالة البلاغ (جدول مرن للمستقبل)
            $table->enum('status', ['pending', 'resolved', 'ignored'])->default('pending');

            $table->timestamps(); // تاريخ الإنشاء (وقت الإبلاغ) وتاريخ التحديث


        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('problem_reports');
    }
};
