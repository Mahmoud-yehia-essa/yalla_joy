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
        Schema::create('terms_and_conditions', function (Blueprint $table) {
            $table->id();
            $table->string('title');
            $table->string('title_en')->nullable();
            $table->longText('content');
            $table->longText('content_en')->nullable();
            $table->integer('order_by')->default(1)->nullable();
            $table->enum('status', ['active', 'inactive'])->default('active');
            $table->timestamps();
        });

        // Pre-seed default data
        $now = now();
        $sections = [
            [
                'title' => 'الشروط والأحكام لتطبيق فيك تحدي ؟',
                'title_en' => 'Terms and Conditions for Feek Tahadi?',
                'content' => "(آخر تحديث: مارس 2026)\n\n" .
                    "1. مقدمة\n" .
                    "مرحبا بك في تطبيق فيك تحدي ؟ (\"التطبيق\" أو \"المنصة\")، المملوك والمشغل من قبل شركة بروفورمانس إنك كويت للاستشارات ذ.م.م (\"الشركة\").\n\n" .
                    "باستخدامك للتطبيق أو تسجيلك فيه، فإنك تقر بأنك:\n" .
                    "• قرأت هذه الشروط والأحكام\n" .
                    "• فهمتها\n" .
                    "• وافقت على الالتزام بها\n" .
                    "إذا لم توافق على هذه الشروط، يجب عليك التوقف عن استخدام التطبيق فوراً.\n\n" .
                    "2. طبيعة التطبيق\n" .
                    "تطبيق فيك تحدي؟ هو تطبيق تفاعلي قائم على الأسئلة والأجوبة يهدف إلى:\n" .
                    "• تنمية المعرفة والثقافة العامة\n" .
                    "• تعزيز التفكير السريع\n" .
                    "• تقديم تجربة تعليمية وترفيهية\n\n" .
                    "2.1 لعبة قائمة على المهارة\n" .
                    "يعتمد التطبيق حصرياً على المعرفة والمهارة الذهنية. تعتمد النتائج بنسبة 100% على سرعة ودقة إجابة المستخدم، ولا يلعب الحظ أو الصدفة أو السحب العشوائي أي دور في تحديد الفائزين.\n\n" .
                    "2.2 عدم وجود عنصر حظ أو مقامرة\n" .
                    "لا يتضمن التطبيق أي: سحب عشوائي، قرعة، رهان، أو نظام احتمالات مالية. لا يُعد التطبيق مقامرة أو نشاطاً مالياً بأي شكل.\n\n" .
                    "3. الأهلية والفئات العمرية\n" .
                    "• الأطفال: من عمر 6 إلى 12 سنة\n" .
                    "• المراهقون: من عمر 13 إلى 17 سنة\n" .
                    "• البالغون: 18 سنة فما فوق\n\n" .
                    "لا يُسمح لأي طفل باستخدام التطبيق أو إنشاء حساب مستقل إلا بعد الحصول على موافقة صريحة من ولي الأمر.",
                'content_en' => "(Last Updated: March 2026)\n\n" .
                    "1. Introduction\n" .
                    "Welcome to the \"Feek Tahadi?\" application (the \"App\" or the \"Platform\"), owned and operated by Performance Inc. Kuwait Consulting W.L.L. (the \"Company\").\n\n" .
                    "By using or registering on the App, you acknowledge that you have:\n" .
                    "• Read these Terms & Conditions\n" .
                    "• Understood them\n" .
                    "• Agreed to be bound by them\n" .
                    "If you do not agree to these Terms, you must immediately cease using the App.\n\n" .
                    "2. Nature of the App\n" .
                    "\"Feek Tahadi?\" is an interactive Q&A-based application designed to:\n" .
                    "• Enhance knowledge and general culture\n" .
                    "• Promote quick thinking\n" .
                    "• Provide an educational and entertaining experience\n\n" .
                    "2.1 Skill-Based Game\n" .
                    "The App relies exclusively on knowledge and cognitive skills. Outcomes depend 100% on accuracy and speed; chance plays no role.\n\n" .
                    "3. Eligibility & Age Groups\n" .
                    "• Children: 6-12 years\n" .
                    "• Teenagers: 13-17 years\n" .
                    "• Adults: 18+\n\n" .
                    "No child may use the App without explicit parental consent.",
                'order_by' => 1,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'تسجيل الأطفال وموافقة ولي الأمر',
                'title_en' => 'Registration & Parental Consent',
                'content' => "4.1 شرط الموافقة المسبقة\n" .
                    "عند تسجيل مستخدم يقل عمره عن 13 عاماً، يجب إدخال بريد إلكتروني صحيح لولي الأمر. لا يتم تفعيل الحساب قبل موافقة ولي الأمر.\n\n" .
                    "4.2 آلية الموافقة\n" .
                    "إدخال بريد ولي الأمر -> إرسال إشعار يتضمن الشروط والسياسات -> إرسال رمز موافقة فريد -> إدخال الرمز داخل التطبيق.\n\n" .
                    "4.3 الأثر القانوني للموافقة\n" .
                    "يعد إدخال رمز الموافقة إقراراً قانونياً من ولي الأمر وموافقة على استخدام الطفل للتطبيق وجمع الحد الأدنى من البيانات.\n\n" .
                    "5. الحسابات واستخدام التطبيق\n" .
                    "يلتزم المستخدم بتقديم معلومات صحيحة وتحمل مسؤولية حماية بيانات الدخول. لا يجوز مشاركة الحساب أو بيعه أو نقله.\n\n" .
                    "6. النقاط والعملات الرمزية\n" .
                    "يحصل المستخدم على نقاط وعملة رمزية افتراضية غير نقدية ولا تمثل قيمة مالية. لا تعد النقاط حقاً مالياً مكتسباً ويجوز للمنصة تعديلها أو إلغاؤها في حالات مكافحة الغش أو التحديثات.",
                'content_en' => "4.1 Prior Consent Requirement\n" .
                    "For users under 13, a valid parent email must be provided. The account will not be activated until approval.\n\n" .
                    "4.2 Consent Process\n" .
                    "Enter email -> Receive notification -> Receive unique code -> Enter code in App.\n\n" .
                    "4.3 Legal Effect of Consent\n" .
                    "Entering the code constitutes legal acknowledgment and consent for data collection and child usage.\n\n" .
                    "5. Accounts & Use of the App\n" .
                    "Users must provide accurate info and protect their credentials. Accounts cannot be shared or sold.\n\n" .
                    "6. Points & Virtual Currency\n" .
                    "Users earn virtual, non-cash currency with no monetary value. The Platform may modify or reset balances for balancing or anti-cheating purposes.",
                'order_by' => 2,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'الكوبونات واللعب النزيه',
                'title_en' => 'Coupons & Fair Play',
                'content' => "7.1 طبيعة الكوبونات\n" .
                    "يمكن استبدال العملة الرمزية بكوبونات عينية مقدمة من رعاة مستقلين. الكوبونات غير نقدية وغير قابلة للتحويل. يقر المستخدم بأن شراء العملات الرمزية يُعد عقداً نافذاً فورياً يسقط حقه في العدول عن الشراء وفقاً لقانون حماية المستهلك الكويتي.\n\n" .
                    "7.2 شروط الرعاة\n" .
                    "تخضع الكوبونات لشروط الجهة الراعية وقد تتضمن تاريخ انتهاء أو قيوداً جغرافية.\n\n" .
                    "8. اللعب النزيه ومكافحة الغش\n" .
                    "يحظر استخدام برامج آلية، إنشاء حسابات متعددة، أو التلاعب بالنتائج. في حال المخالفة، يحق للمنصة تعليق الحساب أو حذفه نهائياً.\n\n" .
                    "9. الملكية الفكرية\n" .
                    "جميع محتويات التطبيق هي ملك للشركة أو مرخصة لها ولا يجوز نسخها دون إذن.\n\n" .
                    "10. حدود المسؤولية\n" .
                    "يُقدم التطبيق \"كما هو\". لا تتحمل المنصة مسؤولية الأعطال التقنية أو خدمات الرعاة. سجلات الشركة هي المرجع النهائي لتحديد النتائج.",
                'content_en' => "7.1 Nature of Coupons\n" .
                    "Currency may be redeemed for in-kind coupons from independent sponsors. Users waive their 14-day refund right as per Kuwaiti Consumer Protection Law.\n\n" .
                    "7.2 Sponsor Terms\n" .
                    "Coupons are subject to sponsor-specific conditions and geographic restrictions.\n\n" .
                    "8. Fair Play & Anti-Cheating\n" .
                    "Automated tools, multiple accounts, or result manipulation are prohibited. Violations lead to account termination.\n\n" .
                    "9. Intellectual Property\n" .
                    "All App content is owned by or licensed to the Company and may not be reused without permission.\n\n" .
                    "10. Limitation of Liability\n" .
                    "The App is provided \"as is.\" The Company's server records are the final and binding reference for results.",
                'order_by' => 3,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
            [
                'title' => 'الأحكام العامة والتواصل',
                'title_en' => 'General Terms & Contact',
                'content' => "11. تعليق أو إنهاء الحساب\n" .
                    "يحق للمنصة تعليق الحساب في حال مخالفة الشروط أو الاشتباه بالغش.\n\n" .
                    "12. الخصوصية\n" .
                    "يخضع جمع البيانات لسياسة خصوصية مستقلة تشكل جزءاً لا يتجزأ من هذه الشروط.\n\n" .
                    "13. التعديلات على الشروط\n" .
                    "تحتفظ المنصة بحقها في تعديل الشروط في أي وقت، واستمرار الاستخدام يعني القبول بها.\n\n" .
                    "14. القانون الحاكم\n" .
                    "تخضع هذه الشروط لقوانين دولة الكويت ويتم تسوية النزاعات في المحاكم الكويتية.\n\n" .
                    "15. متاجر التطبيقات\n" .
                    "لا تعد Apple أو Google جهات راعية أو مسؤولة عن التطبيق أو المسابقات.\n\n" .
                    "16. معلومات التواصل\n" .
                    "الجهة المشغلة: شركة بروفورمانس إنك كويت للاستشارات ذ.م.م\n" .
                    "العنوان: شرق، برج شروق 2 الدور 8، دولة الكويت\n" .
                    "البريد الإلكتروني: info@feektahadi.com",
                'content_en' => "11. Account Suspension\n" .
                    "The Platform may terminate accounts for Term violations or suspected cheating.\n\n" .
                    "12. Privacy\n" .
                    "Data collection is governed by a separate Privacy Policy.\n\n" .
                    "13. Amendments\n" .
                    "Continued use constitutes acceptance of updated terms.\n\n" .
                    "14. Governing Law\n" .
                    "Governed by the laws of the State of Kuwait.\n\n" .
                    "15. App Stores\n" .
                    "Apple and Google are not sponsors or responsible for the App or competitions.\n\n" .
                    "16. Contact Information\n" .
                    "Operator: Performance Inc. Kuwait Consulting W.L.L.\n"
                    . "Address: Sharq, Shorooq Tower 2, Floor 8, Kuwait\n" .
                    "Email: info@feektahadi.com",
                'order_by' => 4,
                'status' => 'active',
                'created_at' => $now,
                'updated_at' => $now,
            ],
        ];

        DB::table('terms_and_conditions')->insert($sections);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('terms_and_conditions');
    }
};
