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
        $now = now();

        $instructions = [
            // --- 1. ترفيهي أطفال (Kids Entertainment) ---
            [
                'game_target' => 'kids_entertainment',
                'section_type' => 'intro',
                'title' => 'المقدمة والتعريف',
                'title_en' => 'Introduction',
                'icon' => 'sentiment_very_satisfied',
                'intro' => null,
                'content' => 'قسم ترفيهي مميز مخصص للأطفال، يهدف إلى إضفاء جو من المرح والتسلية مع محتوى تفاعلي آمن وشيق يناسب اهتماماتهم وأعمارهم.',
                'content_en' => 'A special entertainment section for kids, designed to create fun and excitement with safe and engaging interactive content.',
                'order_by' => 1,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'game_target' => 'kids_entertainment',
                'section_type' => 'section',
                'title' => 'طريقة اللعب والتفاعل',
                'title_en' => 'Gameplay & Interaction',
                'icon' => 'sports_esports',
                'intro' => null,
                'content' => "يقدم هذا النمط أسئلة ترفيهية تفاعلية بأسلوب سهل وبسيط.\nتعتمد الأسئلة على رسومات جذابة وعناصر ملونة تحفز انتباه الطفل.\nيتاح للطفل وقت كافٍ للتفكير واختيار الإجابة الصحيحة بكل متعة وسهولة.\nتجربة لعب مرحة ومليئة بالتشويق والتسلية الهادفة.",
                'content_en' => "Interactive fun questions in a simple and child-friendly style.\nVibrant graphics and colorful elements to stimulate focus.\nAmple time given to think and select the correct answer easily.",
                'order_by' => 2,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'game_target' => 'kids_entertainment',
                'section_type' => 'section',
                'title' => 'نظام النقاط والمكافآت',
                'title_en' => 'Points & Rewards System',
                'icon' => 'analytics',
                'intro' => null,
                'content' => "يحصل الطفل على نقاط وتشجيع فوري ومؤثرات مرحة عند كل إجابة صحيحة.\nتساعد المكافآت المستمرة على تحفيز روح التحدي الإيجابي لدى الصغار.\nإمكانية متابعة التقدم والاحتفال بالإنجازات الرائعة مع العائلة والأصدقاء.",
                'content_en' => "Instant points, encouraging cheer, and playful animations for correct answers.\nContinuous rewards fostering positive challenge and enthusiasm.",
                'order_by' => 3,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // --- 2. تعليم و تسلية (Kids Education & Fun) ---
            [
                'game_target' => 'kids_education',
                'section_type' => 'intro',
                'title' => 'المقدمة والتعريف',
                'title_en' => 'Introduction',
                'icon' => 'school',
                'intro' => null,
                'content' => 'قسم يجمع بين متعة اللعب واكتساب المعرفة، مصمم لتنمية الذكاء وصقل المهارات الذهنية للأطفال في شتى مجالات التعلم المبسط.',
                'content_en' => 'A section combining the joy of play with knowledge gain, designed to nurture intelligence and develop mental skills for kids.',
                'order_by' => 1,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'game_target' => 'kids_education',
                'section_type' => 'section',
                'title' => 'المجالات والمحتوى التعليمي',
                'title_en' => 'Educational Content & Fields',
                'icon' => 'menu_book',
                'intro' => null,
                'content' => "يشمل مواضيع متنوعة في الحساب، العلوم، اللغات، والمعلومات العامة.\nتُصاغ الأسئلة بطريقة مبتكرة لتبسيط المفاهيم العلمية واللغوية.\nيساعد المحتوى على إثراء حصيلة الطفل المعرفية وتوسيع مداركه الفكرية.",
                'content_en' => "Covers varied topics in math, science, languages, and general knowledge.\nQuestions innovatively framed to simplify concepts for young minds.\nEnriches child's vocabulary and intellectual curiosity.",
                'order_by' => 2,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'game_target' => 'kids_education',
                'section_type' => 'section',
                'title' => 'التدرج وتنمية القدرات الذهنية',
                'title_en' => 'Progress & Mental Skills',
                'icon' => 'psychology',
                'intro' => null,
                'content' => "تتدرج التحديات لتعزيز سرعة البديهة والتركيز والتفكير المنطقي لدى الطفل.\nتمنح اللعبة تجربة استكشاف شيقة بدون أي ضغوط وبأسلوب تفاعلي محبب.\nوسيلة مثالية للجمع بين التعلم والترفيه في وقت واحد.",
                'content_en' => "Gradual challenges fostering quick wit, focus, and logical thinking.\nStress-free interactive learning environment.\nAn ideal way to combine education and entertainment simultaneously.",
                'order_by' => 3,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('game_instructions')->insert($instructions);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::table('game_instructions')
            ->whereIn('game_target', ['kids_entertainment', 'kids_education'])
            ->delete();
    }
};
