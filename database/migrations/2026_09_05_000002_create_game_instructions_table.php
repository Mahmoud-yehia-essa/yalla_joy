<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('game_instructions', function (Blueprint $table) {
            $table->id();
            $table->string('game_target'); // 'session' or 'field'
            $table->string('section_type')->default('section'); // 'intro' or 'section'
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->string('icon')->nullable();
            $table->text('intro')->nullable();
            $table->longText('content')->nullable();
            $table->longText('content_en')->nullable();
            $table->integer('order_by')->default(1)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // Pre-seed default data for Session game and Field game
        $now = now();
        $instructions = [
            // --- 1. لعبة الجلسة (Session Game) ---
            [
                'game_target' => 'session',
                'section_type' => 'intro',
                'title' => 'المقدمة والتعريف',
                'title_en' => 'Introduction',
                'icon' => 'sports_esports',
                'intro' => null,
                'content' => 'يتيح هذا الوضع للاعبين الاستمتاع باللعبة، سواء في أجواء عائلية أو مع الأصدقاء، مع تجربة تفاعلية تعتمد على التحدي والمعرفة وسرعة البديهة.',
                'content_en' => null,
                'order_by' => 1,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'game_target' => 'session',
                'section_type' => 'section',
                'title' => 'آلية اللعب ونظام الأدوار',
                'title_en' => 'Gameplay & Turns System',
                'icon' => 'settings',
                'intro' => null,
                'content' => "يقوم المستخدم بإنشاء لعبة جديدة من حسابه على تطبيق \"فيك تحدي؟\".\nيتم تشكيل فريقين لبدء اللعب والتنافس المباشر.\nتتكون المباراة الواحدة من 6 فئات، وكل فئة تحتوي على 6 أسئلة.\nفي بداية اللعبة: يختار كل فريق 3 فئات من أصل 6 (بمجموع 6 فئات في المباراة).\nيتم اللعب بنظام التناوب (Round-based)؛ كل فريق لديه دور كامل ثم ينتقل للآخر.\nيملك الفريق صاحب الدور حرية اختيار الفئة ومستوى السؤال (200 / 400 / 600).\nكل سؤال يُلعب مرة واحدة فقط في المباراة ولا يمكن تكراره.",
                'content_en' => null,
                'order_by' => 2,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'game_target' => 'session',
                'section_type' => 'section',
                'title' => 'نظام الأسئلة والمستويات والوقت',
                'title_en' => 'Questions, Levels & Timing',
                'icon' => 'timer',
                'intro' => "تقسم الأسئلة إلى 3 مستويات من الصعوبة وبأنواع مختلفة (نصي، صورة، فيديو، صوتي):\n• سهل: 200 نقطة (30 ثانية)\n• متوسط: 400 نقطة (45 ثانية)\n• صعب: 600 نقطة (60 ثانية)",
                'content' => "يبدأ عداد الوقت فور عرض السؤال مباشرة على الشاشة.\nيجب على الفريق الإجابة بشكل صحيح قبل انتهاء الوقت المحدد.\nفي حال انتهاء الوقت أو إجابة الفريق الأول (صاحب السؤال) بشكل خاطئ، يتم منح الفريق الثاني 15 ثانية إضافية لمحاولة الإجابة على نفس السؤال كفرصة للسرقة.",
                'content_en' => null,
                'order_by' => 3,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'game_target' => 'session',
                'section_type' => 'section',
                'title' => 'آلية احتساب الإجابة والنتائج',
                'title_en' => 'Scoring & Results',
                'icon' => 'analytics',
                'intro' => 'تُطرح الأسئلة أولاً على الفريق صاحب الدور، وتُحتسب النقاط كالتالي:',
                'content' => "أولوية النقاط تكون للفريق صاحب الدور في حال إجابة الطرفين بشكل صحيح.\nيتم عرض نفس السؤال للفريقين بدون أي تغيير.\nيتم كشف الإجابة الصحيحة بعد انتهاء دور الفريقين بالكامل.\nلا يتم خصم أي نقاط من رصيد الفريق عند الإجابة الخاطئة.",
                'content_en' => null,
                'order_by' => 4,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'game_target' => 'session',
                'section_type' => 'section',
                'title' => 'بداية وتحديد الفريق البادئ',
                'title_en' => 'Match Start & Turn Determination',
                'icon' => 'play_circle',
                'intro' => null,
                'content' => "قبل البداية، يقوم اللاعبون باختيار الفئات (3 لكل فريق) واختيار 3 وسائل مساعدة من أصل 10 وسائل متاحة (ويمكن تكرار الوسائل بين الفريقين).\nيتم تسمية الفريقين ثم تبدأ المباراة مباشرة.\nيتم تحديد الفريق الذي يبدأ اللعب تلقائياً بواسطة النظام.\nيظهر اسم الفريق الحالي في أعلى الشاشة للإشارة إلى دوره، وهو من يختار السؤال.\nبعد انتهاء دوره، ينتقل الدور مباشرة إلى الفريق الآخر.\nطريقة استخدام وسائل المساعدة: بعضها يُستخدم قبل الدخول للسؤال، وبعضها الآخر يُستخدم أثناء السؤال.",
                'content_en' => null,
                'order_by' => 5,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'game_target' => 'session',
                'section_type' => 'section',
                'title' => 'نهاية اللعبة وجوائز الفوز والتدرج',
                'title_en' => 'Game End, Rewards & Ranking',
                'icon' => 'emoji_events',
                'intro' => null,
                'content' => "تنتهي المباراة بعد الإجابة على جميع الأسئلة (6 فئات × 6 أسئلة = 36 سؤالاً).\nيتم عرض مجموع النقاط للفريقين وتحديد الفائز وهو صاحب أعلى نقاط.\nفي حال التعادل: يتم إضافة سؤال سرعة (Flash Round) ومن يجيب عليه أولاً يفوز، أو يلعب ممثلان عن الفريقين (حجرة ورقة مقص) أو (صورة وكتابة) لتحديد الفائز بالدور.\nالفائز بمجموع النقاط يحصل على عملة افتراضية من اللعبة حسب سياسة الرتب والمستويات.\nتضاف العملة لحساب منشئ اللعبة على تطبيق \"فيك تحدي؟\" ويستطيع الشراء بها من متجر اللعبة.\nكلما ارتقى المستخدم في الرتب والمستويات، يتغير لقبه (مثال: من \"متعلم\" إلى \"باحث\" ثم \"الذكي\") ويحصل على عملات فضية وذهبية إضافية.\nيمكن استخدام العملات في تطوير الأفاتار، شراء تعليقات ساخرة حصرية ومؤثرات وإيموجيات مضحكة للخصم الخاسر، أو استبدالها بكوبونات من متجر الكوبونات.",
                'content_en' => null,
                'order_by' => 6,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],

            // --- 2. لعبة الميدان (Field Game) ---
            [
                'game_target' => 'field',
                'section_type' => 'intro',
                'title' => 'المقدمة والتعريف',
                'title_en' => 'Introduction',
                'icon' => 'globe',
                'intro' => null,
                'content' => 'يتيح هذا الوضع للاعبين الاستمتاع باللعبة أونلاين محلياً أو إقليمياً، بوضع تنافسي مباشر بين لاعبين أو فرق. يعتمد بشكل أساسي على الدقة، التخطيط، وإدارة الوقت، حيث يواجه اللاعبون نفس الأسئلة في نفس الوقت ضمن نظام عادل ومتزامن.',
                'content_en' => null,
                'order_by' => 1,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'game_target' => 'field',
                'section_type' => 'section',
                'title' => 'آلية اللعب العامة',
                'title_en' => 'General Gameplay Rules',
                'icon' => 'sports_esports',
                'intro' => null,
                'content' => "يتم اللعب مباشرة وبشكل تنافسي (لاعب ضد لاعب 1v1).\nتتكون المباراة الواحدة من 6 فئات، وكل فئة تحتوي على 6 أسئلة.\nقبل بدء المباراة: يختار كل لاعب/فريق 3 فئات من أصل 6.\nيتم دمج الفئات المختارة لتشكيل مجموعة الأسئلة في المباراة (6 فئات بمجموع 36 سؤالاً).",
                'content_en' => null,
                'order_by' => 2,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'game_target' => 'field',
                'section_type' => 'section',
                'title' => 'طرق الدخول للمنافسة',
                'title_en' => 'Joining & Matchmaking Methods',
                'icon' => 'people',
                'intro' => null,
                'content' => "1. البحث العشوائي (Random Matchmaking): يتم البحث بشكل تلقائي عن لاعب عشوائي غير معروف عبر الخادم، وعند العثور عليه، يتم نقلكما معاً لبدء المباراة مباشرة.\n2. دعوة صديق (Private Match): يمكن للمستخدم إنشاء لعبة خاصة برمز (كود) مخصص أو رابط دعوة مباشر، ومشاركته مع أصدقائه للانضمام إلى نفس المباراة والتحدي المباشر.",
                'content_en' => null,
                'order_by' => 3,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'game_target' => 'field',
                'section_type' => 'section',
                'title' => 'نظام الأسئلة والوقت المخصص',
                'title_en' => 'Questions, Levels & Timing',
                'icon' => 'timer',
                'intro' => "تتدرج مستويات صعوبة الأسئلة (نصي، صورة، فيديو، صوتي) مع نقاط ووقت محدد لكل مستوى:\n• سهل: 200 نقطة (30 ثانية)\n• متوسط: 400 نقطة (45 ثانية)\n• صعب: 600 نقطة (60 ثانية)",
                'content' => 'يتم طرح الأسئلة بنظام الوقت التنازلي ويجب الإجابة قبل انتهاء المهلة المحددة.',
                'content_en' => null,
                'order_by' => 4,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'game_target' => 'field',
                'section_type' => 'section',
                'title' => 'نظام الإجابة واحتساب النقاط',
                'title_en' => 'Answer System & Scoring',
                'icon' => 'analytics',
                'intro' => null,
                'content' => "يتم عرض نفس السؤال لكلا اللاعبين/الفريقين في نفس الوقت تماماً.\nلكل سؤال وقت محدد وتنازلي حسب مستوى صعوبته.\nكل طرف يختار إجابته بشكل مستقل وسري خلال الوقت دون معرفة خيار الطرف الآخر.\nفي حال عدم اختيار أي إجابة قبل انتهاء الوقت، يُعتبر السؤال بدون إجابة ولا تُحتسب أي نقاط للطرف المتأخر.\nبعد انتهاء وقت السؤال: يتم كشف الإجابة الصحيحة لكلا الطرفين في نفس اللحظة وتُحتسب النقاط لكل طرف بناءً على صحة إجابته.\nلا يتم خصم أي نقاط من رصيد اللاعب عند الإجابة الخاطئة.",
                'content_en' => null,
                'order_by' => 5,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'game_target' => 'field',
                'section_type' => 'section',
                'title' => 'ترتيب ونظام عرض الأسئلة',
                'title_en' => 'Questions Display Order',
                'icon' => 'sort',
                'intro' => null,
                'content' => "يتم عرض الأسئلة بشكل تلقائي بالكامل بواسطة النظام دون تدخل أو اختيار من اللاعبين.\nتأتي الأسئلة عشوائياً من بين الـ 6 فئات التي تم اختيارها ودمجها مسبقاً.\nيتم التدرج تلقائياً داخل كل فئة من الأسئلة السهلة إلى المتوسطة ثم الصعبة.\nتُعرض الأسئلة بشكل متتابع، سؤالاً بعد سؤال، فور انتهاء وقت السؤال السابق.",
                'content_en' => null,
                'order_by' => 6,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'game_target' => 'field',
                'section_type' => 'section',
                'title' => 'نهاية اللعبة وجوائز التحدي والتدرج',
                'title_en' => 'Game End, Rewards & Ranking',
                'icon' => 'emoji_events',
                'intro' => null,
                'content' => "تنتهي المباراة بعد الإجابة على كافة الأسئلة (36 سؤالاً).\nيتم عرض مجموع النقاط الكلي لكل طرف وتحديد الفائز وهو صاحب أعلى نقاط.\nفي حال التعادل: يتم إضافة سؤال سرعة (Flash Round) إضافي ومن يجيب عليه أولاً بشكل صحيح يفوز بالمباراة.\nالفائز بمجموع النقاط يحصل على عملة افتراضية من اللعبة تُضاف تلقائياً لحسابه.\nيمكن استخدام العملات الافتراضية المكتسبة لتطوير الأفاتار، شراء إيموجيات وتأثيرات مضحكة أو تعليقات حصرية لعرقلة الخصوم، أو استبدالها بكوبونات.\nحتى اللاعب الخاسر يحصل على عملة فضية واحدة كجائزة ترضية ودعم لتشجيعه على المحاولة.\nكلما ارتقيت في مستويات ورتب اللعب، يتغير لقبك (مثل: متعلم، باحث، ذكي) مع جوائز فضية وذهبية متعددة.",
                'content_en' => null,
                'order_by' => 7,
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
        Schema::dropIfExists('game_instructions');
    }
};
