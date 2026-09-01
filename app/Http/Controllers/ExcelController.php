<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Question;
use App\Models\GameType;
use App\Models\MainCategory;
use App\Models\Category;
use App\Models\AnswerQuestionOnline;
use App\Exports\CategoryQuestionsExport;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\Concerns\ToCollection;

class ExcelController extends Controller
{
    /**
     * عرض الصفحة الرئيسية لرفع واستيراد وتصدير وتعديل Excel
     */
    public function index(Request $request)
    {
        $gameTypes = GameType::latest()->get();

        return view('admin.excel.index', [
            'gameTypes'  => $gameTypes,
            'activeStep' => session('active_step', 1)
        ]);
    }

    /**
     * تصدير أسئلة فئة معينة إلى Excel مع تضمين qu_id و ans_id و IDs الإجابات لتعديلها
     */
    public function exportCategory(Request $request)
    {
        if (!$request->filled('category_id') || $request->category_id === 'non') {
            return back()->withErrors(['يرجى اختيار نوع اللعبة والفئة الرئيسية والفئة الفرعية أولاً قبل تنزيل ملف Excel.']);
        }

        $catName = 'questions';
        $cat = Category::find($request->category_id);
        if ($cat) {
            $catName = preg_replace('/[^A-Za-z0-9_\-\x{0600}-\x{06FF}]/u', '_', $cat->category_name);
        }

        $fileName = 'edit_' . $catName . '_' . date('Y_m_d_His') . '.xlsx';

        return Excel::download(new CategoryQuestionsExport($request), $fileName);
    }

    /**
     * جلب أسئلة الفئة المحددة للتعديل المباشر في نفس الصفحة (AJAX)
     */
    public function loadCategoryQuestions(Request $request)
    {
        $query = Question::with(['answers', 'answerQuestionOnlines']);

        if ($request->filled('category_id') && $request->category_id !== 'non') {
            $query->where('category_id', $request->category_id);
        } elseif ($request->filled('main_category_id') && $request->main_category_id !== 'non') {
            $query->where('main_category_id', $request->main_category_id);
        } elseif ($request->filled('game_type_id') && $request->game_type_id !== 'non') {
            $query->where('game_type_id', $request->game_type_id);
        } else {
            return response()->json([
                'success'   => false,
                'message'   => 'يرجى اختيار الفئة أو نوع اللعبة لعرض الأسئلة.',
                'questions' => []
            ]);
        }

        $questions = $query->orderBy('id', 'asc')->get();

        $totalMediaNeeded = 0;
        $totalMediaFound  = 0;
        $totalMediaMissing = 0;

        $formatted = $questions->map(function($q) use (&$totalMediaNeeded, &$totalMediaFound, &$totalMediaMissing) {
            $localAns = $q->answers->first();
            $onlines  = $q->answerQuestionOnlines->values();

            // فحص وسائط السؤال
            $qImgExists = $q->qu_image ? file_exists(public_path('upload/questions/images/' . $q->qu_image)) : false;
            $qSndExists = $q->qu_sound ? file_exists(public_path('upload/questions/sounds/' . $q->qu_sound)) : false;
            $qVidExists = $q->qu_video ? file_exists(public_path('upload/questions/videos/' . $q->qu_video)) : false;

            if ($q->qu_image) { $totalMediaNeeded++; if ($qImgExists) $totalMediaFound++; else $totalMediaMissing++; }
            if ($q->qu_sound) { $totalMediaNeeded++; if ($qSndExists) $totalMediaFound++; else $totalMediaMissing++; }
            if ($q->qu_video) { $totalMediaNeeded++; if ($qVidExists) $totalMediaFound++; else $totalMediaMissing++; }

            // وسائط الإجابة 1
            $a1_img = $localAns ? $localAns->answer_image : (isset($onlines[0]) ? $onlines[0]->answer_image : '');
            $a1_snd = $localAns ? $localAns->answer_sound : (isset($onlines[0]) ? $onlines[0]->answer_sound : '');
            $a1_vid = $localAns ? $localAns->answer_video : (isset($onlines[0]) ? $onlines[0]->answer_video : '');
            $a1ImgExists = $a1_img ? file_exists(public_path('upload/answers/images/' . $a1_img)) : false;
            $a1SndExists = $a1_snd ? file_exists(public_path('upload/answers/sounds/' . $a1_snd)) : false;
            $a1VidExists = $a1_vid ? file_exists(public_path('upload/answers/videos/' . $a1_vid)) : false;
            if ($a1_img) { $totalMediaNeeded++; if ($a1ImgExists) $totalMediaFound++; else $totalMediaMissing++; }
            if ($a1_snd) { $totalMediaNeeded++; if ($a1SndExists) $totalMediaFound++; else $totalMediaMissing++; }
            if ($a1_vid) { $totalMediaNeeded++; if ($a1VidExists) $totalMediaFound++; else $totalMediaMissing++; }

            // وسائط الإجابة 2
            $a2_img = isset($onlines[1]) ? $onlines[1]->answer_image : '';
            $a2_snd = isset($onlines[1]) ? $onlines[1]->answer_sound : '';
            $a2_vid = isset($onlines[1]) ? $onlines[1]->answer_video : '';
            $a2ImgExists = $a2_img ? file_exists(public_path('upload/answers/images/' . $a2_img)) : false;
            $a2SndExists = $a2_snd ? file_exists(public_path('upload/answers/sounds/' . $a2_snd)) : false;
            $a2VidExists = $a2_vid ? file_exists(public_path('upload/answers/videos/' . $a2_vid)) : false;
            if ($a2_img) { $totalMediaNeeded++; if ($a2ImgExists) $totalMediaFound++; else $totalMediaMissing++; }
            if ($a2_snd) { $totalMediaNeeded++; if ($a2SndExists) $totalMediaFound++; else $totalMediaMissing++; }
            if ($a2_vid) { $totalMediaNeeded++; if ($a2VidExists) $totalMediaFound++; else $totalMediaMissing++; }

            // وسائط الإجابة 3
            $a3_img = isset($onlines[2]) ? $onlines[2]->answer_image : '';
            $a3_snd = isset($onlines[2]) ? $onlines[2]->answer_sound : '';
            $a3_vid = isset($onlines[2]) ? $onlines[2]->answer_video : '';
            $a3ImgExists = $a3_img ? file_exists(public_path('upload/answers/images/' . $a3_img)) : false;
            $a3SndExists = $a3_snd ? file_exists(public_path('upload/answers/sounds/' . $a3_snd)) : false;
            $a3VidExists = $a3_vid ? file_exists(public_path('upload/answers/videos/' . $a3_vid)) : false;
            if ($a3_img) { $totalMediaNeeded++; if ($a3ImgExists) $totalMediaFound++; else $totalMediaMissing++; }
            if ($a3_snd) { $totalMediaNeeded++; if ($a3SndExists) $totalMediaFound++; else $totalMediaMissing++; }
            if ($a3_vid) { $totalMediaNeeded++; if ($a3VidExists) $totalMediaFound++; else $totalMediaMissing++; }

            // وسائط الإجابة 4
            $a4_img = isset($onlines[3]) ? $onlines[3]->answer_image : '';
            $a4_snd = isset($onlines[3]) ? $onlines[3]->answer_sound : '';
            $a4_vid = isset($onlines[3]) ? $onlines[3]->answer_video : '';
            $a4ImgExists = $a4_img ? file_exists(public_path('upload/answers/images/' . $a4_img)) : false;
            $a4SndExists = $a4_snd ? file_exists(public_path('upload/answers/sounds/' . $a4_snd)) : false;
            $a4VidExists = $a4_vid ? file_exists(public_path('upload/answers/videos/' . $a4_vid)) : false;
            if ($a4_img) { $totalMediaNeeded++; if ($a4ImgExists) $totalMediaFound++; else $totalMediaMissing++; }
            if ($a4_snd) { $totalMediaNeeded++; if ($a4SndExists) $totalMediaFound++; else $totalMediaMissing++; }
            if ($a4_vid) { $totalMediaNeeded++; if ($a4VidExists) $totalMediaFound++; else $totalMediaMissing++; }

            return [
                'qu_id'               => $q->id,
                'ans_id'              => $localAns ? $localAns->id : '',
                'online_ans_id_1'     => isset($onlines[0]) ? $onlines[0]->id : '',
                'online_ans_id_2'     => isset($onlines[1]) ? $onlines[1]->id : '',
                'online_ans_id_3'     => isset($onlines[2]) ? $onlines[2]->id : '',
                'online_ans_id_4'     => isset($onlines[3]) ? $onlines[3]->id : '',
                'game_type_id'        => $q->game_type_id,
                'main_category_id'    => $q->main_category_id,
                'category_id'         => $q->category_id,
                'qu_hint'             => $q->qu_hint ?? '',
                'qu_hint_en'          => $q->qu_hint_en ?? '',
                'qu_title'            => $q->qu_title ?? '',
                'qu_title_en'         => $q->qu_title_en ?? '',
                'qu_image'            => $q->qu_image ?? '',
                'qu_image_exists'     => $qImgExists,
                'qu_sound'            => $q->qu_sound ?? '',
                'qu_sound_exists'     => $qSndExists,
                'qu_video'            => $q->qu_video ?? '',
                'qu_video_exists'     => $qVidExists,
                'qu_points'           => $q->qu_points ?? 0,
                'qu_points_online'    => $q->qu_points_online ?? ($q->qu_points ?? 0),
                'time_counter'        => $q->time_counter ?? 30,
                'time_counter_online' => $q->time_counter_online ?? ($q->time_counter ?? 30),
                'answer_title'        => $localAns ? $localAns->answer_title : (isset($onlines[0]) ? $onlines[0]->answer_title : ''),
                'answer_title_en'     => $localAns ? $localAns->answer_title_en : (isset($onlines[0]) ? $onlines[0]->answer_title_en : ''),
                'answer_image'        => $a1_img,
                'answer_image_exists' => $a1ImgExists,
                'answer_sound'        => $a1_snd,
                'answer_sound_exists' => $a1SndExists,
                'answer_video'        => $a1_vid,
                'answer_video_exists' => $a1VidExists,
                'answer_title_two'    => isset($onlines[1]) ? $onlines[1]->answer_title : '',
                'answer_title_en_two' => isset($onlines[1]) ? $onlines[1]->answer_title_en : '',
                'answer_image_two'    => $a2_img,
                'answer_image_two_exists' => $a2ImgExists,
                'answer_sound_two'    => $a2_snd,
                'answer_sound_two_exists' => $a2SndExists,
                'answer_video_two'    => $a2_vid,
                'answer_video_two_exists' => $a2VidExists,
                'answer_title_three'    => isset($onlines[2]) ? $onlines[2]->answer_title : '',
                'answer_title_en_three' => isset($onlines[2]) ? $onlines[2]->answer_title_en : '',
                'answer_image_three'    => $a3_img,
                'answer_image_three_exists' => $a3ImgExists,
                'answer_sound_three'    => $a3_snd,
                'answer_sound_three_exists' => $a3SndExists,
                'answer_video_three'    => $a3_vid,
                'answer_video_three_exists' => $a3VidExists,
                'answer_title_four'    => isset($onlines[3]) ? $onlines[3]->answer_title : '',
                'answer_title_en_four' => isset($onlines[3]) ? $onlines[3]->answer_title_en : '',
                'answer_image_four'    => $a4_img,
                'answer_image_four_exists' => $a4ImgExists,
                'answer_sound_four'    => $a4_snd,
                'answer_sound_four_exists' => $a4SndExists,
                'answer_video_four'    => $a4_vid,
                'answer_video_four_exists' => $a4VidExists,
                'term'                => $q->term ?? '',
            ];
        });

        return response()->json([
            'success'            => true,
            'questions'          => $formatted,
            'count'              => $formatted->count(),
            'total_media_needed' => $totalMediaNeeded,
            'total_media_found'  => $totalMediaFound,
            'total_media_missing'=> $totalMediaMissing,
        ]);
    }

    /**
     * حفظ التعديلات المباشرة على أسئلة الفئة من الصفحة مباشرة (AJAX)
     */
    public function saveCategoryQuestions(Request $request)
    {
        @ini_set('memory_limit', '1024M');
        @ini_set('max_execution_time', '600');
        @set_time_limit(600);

        $questionsData = $request->input('questions');
        if (is_string($questionsData)) {
            $questionsData = json_decode($questionsData, true);
        }

        if (empty($questionsData) || !is_array($questionsData)) {
            return response()->json([
                'success' => false,
                'message' => 'لا توجد بيانات أسئلة للحفظ.'
            ], 422);
        }

        $defaultGameType = $request->input('game_type_id');
        $defaultMainCat  = $request->input('main_category_id');
        $defaultCat      = $request->input('category_id');

        DB::beginTransaction();
        try {
            $updatedCount  = 0;
            $insertedCount = 0;

            foreach ($questionsData as $row) {
                $qu_image = !empty($row['qu_image']) ? trim($row['qu_image']) : null;
                $qu_sound = !empty($row['qu_sound']) ? trim($row['qu_sound']) : null;
                $qu_video = !empty($row['qu_video']) ? trim($row['qu_video']) : null;

                $questions_type = 'text';
                if ($qu_image) $questions_type = 'image';
                elseif ($qu_sound) $questions_type = 'sound';
                elseif ($qu_video) $questions_type = 'video';

                $game_type_id     = (!empty($row['game_type_id']) && $row['game_type_id'] !== 'non') ? $row['game_type_id'] : ($defaultGameType !== 'non' ? $defaultGameType : null);
                $main_category_id = (!empty($row['main_category_id']) && $row['main_category_id'] !== 'non') ? $row['main_category_id'] : ($defaultMainCat !== 'non' ? $defaultMainCat : null);
                $category_id      = (!empty($row['category_id']) && $row['category_id'] !== 'non') ? $row['category_id'] : ($defaultCat !== 'non' ? $defaultCat : null);

                $quId = !empty($row['qu_id']) ? (int)$row['qu_id'] : null;
                $existingQuestion = $quId ? Question::find($quId) : null;

                if ($existingQuestion) {
                    // تحديث السؤال
                    $existingQuestion->update([
                        'qu_title'            => !empty($row['qu_title']) ? trim($row['qu_title']) : $existingQuestion->qu_title,
                        'qu_title_en'         => !empty($row['qu_title_en']) ? trim($row['qu_title_en']) : $existingQuestion->qu_title_en,
                        'game_type_id'        => $game_type_id ?? $existingQuestion->game_type_id,
                        'main_category_id'    => $main_category_id ?? $existingQuestion->main_category_id,
                        'category_id'         => $category_id ?? $existingQuestion->category_id,
                        'qu_points'           => isset($row['qu_points']) ? (int)$row['qu_points'] : $existingQuestion->qu_points,
                        'qu_points_online'    => isset($row['qu_points_online']) ? (int)$row['qu_points_online'] : (isset($row['qu_points']) ? (int)$row['qu_points'] : $existingQuestion->qu_points_online),
                        'questions_type'      => $questions_type,
                        'time_counter'        => isset($row['time_counter']) ? (int)$row['time_counter'] : $existingQuestion->time_counter,
                        'time_counter_online' => isset($row['time_counter_online']) ? (int)$row['time_counter_online'] : (isset($row['time_counter']) ? (int)$row['time_counter'] : $existingQuestion->time_counter_online),
                        'qu_image'            => $qu_image,
                        'qu_sound'            => $qu_sound,
                        'qu_video'            => $qu_video,
                        'qu_hint'             => !empty($row['qu_hint']) ? trim($row['qu_hint']) : null,
                        'qu_hint_en'          => !empty($row['qu_hint_en']) ? trim($row['qu_hint_en']) : null,
                        'term'                => !empty($row['term']) ? trim($row['term']) : null,
                    ]);

                    $question = $existingQuestion;
                    $updatedCount++;
                } else {
                    // إنشاء سؤال جديد
                    $question = Question::create([
                        'qu_title'            => !empty($row['qu_title']) ? trim($row['qu_title']) : 'non',
                        'qu_title_en'         => !empty($row['qu_title_en']) ? trim($row['qu_title_en']) : 'non',
                        'game_type_id'        => $game_type_id,
                        'main_category_id'    => $main_category_id,
                        'category_id'         => $category_id,
                        'qu_points'           => !empty($row['qu_points']) ? (int)$row['qu_points'] : 0,
                        'qu_points_online'    => !empty($row['qu_points_online']) ? (int)$row['qu_points_online'] : (!empty($row['qu_points']) ? (int)$row['qu_points'] : 0),
                        'questions_type'      => $questions_type,
                        'time_counter'        => !empty($row['time_counter']) ? (int)$row['time_counter'] : 30,
                        'time_counter_online' => !empty($row['time_counter_online']) ? (int)$row['time_counter_online'] : (!empty($row['time_counter']) ? (int)$row['time_counter'] : 30),
                        'qu_image'            => $qu_image,
                        'qu_sound'            => $qu_sound,
                        'qu_video'            => $qu_video,
                        'qu_hint'             => !empty($row['qu_hint']) ? trim($row['qu_hint']) : null,
                        'qu_hint_en'          => !empty($row['qu_hint_en']) ? trim($row['qu_hint_en']) : null,
                        'term'                => !empty($row['term']) ? trim($row['term']) : null,
                        'user_id'             => Auth::id() ?? 1,
                    ]);

                    $insertedCount++;
                }

                // الإجابة الأولى
                $ans1_img = !empty($row['answer_image']) ? trim($row['answer_image']) : null;
                $ans1_snd = !empty($row['answer_sound']) ? trim($row['answer_sound']) : null;
                $ans1_vid = !empty($row['answer_video']) ? trim($row['answer_video']) : null;
                $ans1_type = 'text';
                if ($ans1_img) $ans1_type = 'image';
                elseif ($ans1_snd) $ans1_type = 'sound';
                elseif ($ans1_vid) $ans1_type = 'video';

                $ansId = !empty($row['ans_id']) ? (int)$row['ans_id'] : null;
                $localAns = $ansId ? Answer::find($ansId) : $question->answers()->first();
                $localAnsData = [
                    'question_id'     => $question->id,
                    'answer_title'    => !empty($row['answer_title']) ? trim($row['answer_title']) : 'non',
                    'answer_title_en' => !empty($row['answer_title_en']) ? trim($row['answer_title_en']) : 'non',
                    'answer_type'     => $ans1_type,
                    'answer_image'    => $ans1_img,
                    'answer_sound'    => $ans1_snd,
                    'answer_video'    => $ans1_vid,
                ];

                if ($localAns) {
                    $localAns->update($localAnsData);
                } else {
                    Answer::create($localAnsData);
                }

                // Online 1
                $onlineId1 = !empty($row['online_ans_id_1']) ? (int)$row['online_ans_id_1'] : null;
                $onl1 = $onlineId1 ? AnswerQuestionOnline::find($onlineId1) : $question->answerQuestionOnlines()->skip(0)->first();
                $onl1Data = [
                    'question_id'     => $question->id,
                    'answer_title'    => !empty($row['answer_title']) ? trim($row['answer_title']) : 'non',
                    'answer_title_en' => !empty($row['answer_title_en']) ? trim($row['answer_title_en']) : 'non',
                    'answer_type'     => $ans1_type,
                    'answer_image'    => $ans1_img,
                    'answer_sound'    => $ans1_snd,
                    'answer_video'    => $ans1_vid,
                ];
                if ($onl1) { $onl1->update($onl1Data); } else { AnswerQuestionOnline::create($onl1Data); }

                // Online 2
                $ans2_img = !empty($row['answer_image_two']) ? trim($row['answer_image_two']) : null;
                $ans2_snd = !empty($row['answer_sound_two']) ? trim($row['answer_sound_two']) : null;
                $ans2_vid = !empty($row['answer_video_two']) ? trim($row['answer_video_two']) : null;
                $ans2_type = 'text';
                if ($ans2_img) $ans2_type = 'image';
                elseif ($ans2_snd) $ans2_type = 'sound';
                elseif ($ans2_vid) $ans2_type = 'video';

                $onlineId2 = !empty($row['online_ans_id_2']) ? (int)$row['online_ans_id_2'] : null;
                $onl2 = $onlineId2 ? AnswerQuestionOnline::find($onlineId2) : $question->answerQuestionOnlines()->skip(1)->first();
                $onl2Data = [
                    'question_id'     => $question->id,
                    'answer_title'    => !empty($row['answer_title_two']) ? trim($row['answer_title_two']) : 'non',
                    'answer_title_en' => !empty($row['answer_title_en_two']) ? trim($row['answer_title_en_two']) : 'non',
                    'answer_type'     => $ans2_type,
                    'answer_image'    => $ans2_img,
                    'answer_sound'    => $ans2_snd,
                    'answer_video'    => $ans2_vid,
                ];
                if ($onl2) { $onl2->update($onl2Data); } else { AnswerQuestionOnline::create($onl2Data); }

                // Online 3
                $ans3_img = !empty($row['answer_image_three']) ? trim($row['answer_image_three']) : null;
                $ans3_snd = !empty($row['answer_sound_three']) ? trim($row['answer_sound_three']) : null;
                $ans3_vid = !empty($row['answer_video_three']) ? trim($row['answer_video_three']) : null;
                $ans3_type = 'text';
                if ($ans3_img) $ans3_type = 'image';
                elseif ($ans3_snd) $ans3_type = 'sound';
                elseif ($ans3_vid) $ans3_type = 'video';

                $onlineId3 = !empty($row['online_ans_id_3']) ? (int)$row['online_ans_id_3'] : null;
                $onl3 = $onlineId3 ? AnswerQuestionOnline::find($onlineId3) : $question->answerQuestionOnlines()->skip(2)->first();
                $onl3Data = [
                    'question_id'     => $question->id,
                    'answer_title'    => !empty($row['answer_title_three']) ? trim($row['answer_title_three']) : 'non',
                    'answer_title_en' => !empty($row['answer_title_en_three']) ? trim($row['answer_title_en_three']) : 'non',
                    'answer_type'     => $ans3_type,
                    'answer_image'    => $ans3_img,
                    'answer_sound'    => $ans3_snd,
                    'answer_video'    => $ans3_vid,
                ];
                if ($onl3) { $onl3->update($onl3Data); } else { AnswerQuestionOnline::create($onl3Data); }

                // Online 4
                $ans4_img = !empty($row['answer_image_four']) ? trim($row['answer_image_four']) : null;
                $ans4_snd = !empty($row['answer_sound_four']) ? trim($row['answer_sound_four']) : null;
                $ans4_vid = !empty($row['answer_video_four']) ? trim($row['answer_video_four']) : null;
                $ans4_type = 'text';
                if ($ans4_img) $ans4_type = 'image';
                elseif ($ans4_snd) $ans4_type = 'sound';
                elseif ($ans4_vid) $ans4_type = 'video';

                $onlineId4 = !empty($row['online_ans_id_4']) ? (int)$row['online_ans_id_4'] : null;
                $onl4 = $onlineId4 ? AnswerQuestionOnline::find($onlineId4) : $question->answerQuestionOnlines()->skip(3)->first();
                $onl4Data = [
                    'question_id'     => $question->id,
                    'answer_title'    => !empty($row['answer_title_four']) ? trim($row['answer_title_four']) : 'non',
                    'answer_title_en' => !empty($row['answer_title_en_four']) ? trim($row['answer_title_en_four']) : 'non',
                    'answer_type'     => $ans4_type,
                    'answer_image'    => $ans4_img,
                    'answer_sound'    => $ans4_snd,
                    'answer_video'    => $ans4_vid,
                ];
                if ($onl4) { $onl4->update($onl4Data); } else { AnswerQuestionOnline::create($onl4Data); }
            }

            DB::commit();

            return response()->json([
                'success' => true,
                'message' => "تم بنجاح حفظ التعديلات! (تحديث {$updatedCount} سؤال قائم، وإضافة {$insertedCount} سؤال جديد).",
                'updated_count'  => $updatedCount,
                'inserted_count' => $insertedCount
            ]);

        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json([
                'success' => false,
                'message' => 'حدث خطأ أثناء الحفظ: ' . $e->getMessage()
            ], 500);
        }
    }

    /**
     * حذف سؤال محدد من جدول التعديل المباشر
     */
    public function deleteCategoryQuestion($id)
    {
        $question = Question::find($id);
        if (!$question) {
            return response()->json(['success' => false, 'message' => 'السؤال غير موجود.'], 404);
        }

        DB::beginTransaction();
        try {
            $question->answers()->delete();
            $question->answerQuestionOnlines()->delete();
            $question->delete();
            DB::commit();

            return response()->json(['success' => true, 'message' => 'تم حذف السؤال وإجاباته بنجاح.']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['success' => false, 'message' => 'تعذر حذف السؤال: ' . $e->getMessage()], 500);
        }
    }

    /**
     * استيراد ملف Excel ومعاينة البيانات وتطبيق التصنيفات المحددة وكشف التحديث مقابل الإضافة
     */
    public function import(Request $request)
    {
        @ini_set('memory_limit', '1024M');
        @ini_set('max_execution_time', '600');
        @set_time_limit(600);

        $request->validate([
            'excel_file' => 'required|file',
        ], [
            'excel_file.required' => 'يرجى اختيار ملف Excel للرفع.',
            'excel_file.file'     => 'يجب رفع ملف صالح.'
        ]);

        $game_type_id     = $request->game_type_id;
        $main_category_id = $request->main_category_id;
        $category_id      = $request->category_id;

        $selectedGameType     = ($game_type_id && $game_type_id !== 'non') ? GameType::find($game_type_id) : null;
        $selectedMainCategory = ($main_category_id && $main_category_id !== 'non') ? MainCategory::find($main_category_id) : null;
        $selectedCategory     = ($category_id && $category_id !== 'non') ? Category::find($category_id) : null;

        $data = Excel::toCollection(new class implements ToCollection {
            public function collection(Collection $rows)
            {
                return $rows;
            }
        }, $request->file('excel_file'));

        if ($data->isEmpty() || $data[0]->count() < 2) {
            return back()->withErrors(['الملف فارغ أو لا يحتوي على بيانات صالحة.']);
        }

        $rows = $data[0];

        // استخراج الهيدر
        $headers = $rows[0]->map(function($h) {
            return trim($h ?? '');
        })->toArray();

        // إزالة صف الهيدر
        unset($rows[0]);

        $stats = [
            'total_questions'     => 0,
            'update_count'        => 0,
            'insert_count'        => 0,
            'type_text'           => 0,
            'type_image'          => 0,
            'type_sound'          => 0,
            'type_video'          => 0,
            'question_media_need' => 0,
            'answer_media_need'   => 0,
            'media_found'         => 0,
            'media_missing'       => 0,
        ];

        $referencedMedia = [
            'questions' => [],
            'answers'   => [],
        ];

        // ربط الهيدر وتطبيق الفئات المحددة وتحديد نوع العملية (تعديل أو إضافة)
        $formatted = $rows->map(function ($row) use ($headers, $game_type_id, $main_category_id, $category_id, &$stats, &$referencedMedia) {
            $rowData = [];
            foreach ($headers as $index => $headerName) {
                if (empty($headerName)) continue;
                $val = $row[$index] ?? '';
                $rowData[$headerName] = is_string($val) ? trim($val) : $val;
            }

            // تحديد ما إذا كان الصف لتحديث سؤال قائم أو إضافة جديدة
            $quId = !empty($rowData['qu_id']) ? (int)$rowData['qu_id'] : null;
            $rowAction = 'create';
            if ($quId && Question::where('id', $quId)->exists()) {
                $rowAction = 'update';
                $stats['update_count']++;
            } else {
                $stats['insert_count']++;
            }
            $rowData['_row_action'] = $rowAction;

            // تطبيق التصنيفات المختارة من القوائم المنسدلة إن وجدت
            if ($game_type_id && $game_type_id !== 'non') {
                $rowData['game_type_id'] = $game_type_id;
            }
            if ($main_category_id && $main_category_id !== 'non') {
                $rowData['main_category_id'] = $main_category_id;
            }
            if ($category_id && $category_id !== 'non') {
                $rowData['category_id'] = $category_id;
            }

            // تحديد نوع السؤال
            $qu_image = !empty($rowData['qu_image']) ? trim($rowData['qu_image']) : '';
            $qu_sound = !empty($rowData['qu_sound']) ? trim($rowData['qu_sound']) : '';
            $qu_video = !empty($rowData['qu_video']) ? trim($rowData['qu_video']) : '';

            $qType = 'text';
            if (!empty($qu_image)) {
                $qType = 'image';
                $stats['type_image']++;
                $stats['question_media_need']++;
                $exists = file_exists(public_path('upload/questions/images/' . $qu_image));
                if ($exists) $stats['media_found']++; else $stats['media_missing']++;
                $referencedMedia['questions'][$qu_image] = [
                    'name'   => $qu_image,
                    'type'   => 'image',
                    'path'   => 'upload/questions/images/' . $qu_image,
                    'exists' => $exists
                ];
            } elseif (!empty($qu_sound)) {
                $qType = 'sound';
                $stats['type_sound']++;
                $stats['question_media_need']++;
                $exists = file_exists(public_path('upload/questions/sounds/' . $qu_sound));
                if ($exists) $stats['media_found']++; else $stats['media_missing']++;
                $referencedMedia['questions'][$qu_sound] = [
                    'name'   => $qu_sound,
                    'type'   => 'sound',
                    'path'   => 'upload/questions/sounds/' . $qu_sound,
                    'exists' => $exists
                ];
            } elseif (!empty($qu_video)) {
                $qType = 'video';
                $stats['type_video']++;
                $stats['question_media_need']++;
                $exists = file_exists(public_path('upload/questions/videos/' . $qu_video));
                if ($exists) $stats['media_found']++; else $stats['media_missing']++;
                $referencedMedia['questions'][$qu_video] = [
                    'name'   => $qu_video,
                    'type'   => 'video',
                    'path'   => 'upload/questions/videos/' . $qu_video,
                    'exists' => $exists
                ];
            } else {
                $stats['type_text']++;
            }

            $rowData['_detected_type'] = $qType;

            // فحص وسائط الإجابات
            $answerMediaFields = [
                ['img' => 'answer_image',       'snd' => 'answer_sound',       'vid' => 'answer_video'],
                ['img' => 'answer_image_two',   'snd' => 'answer_sound_two',   'vid' => 'answer_video_two'],
                ['img' => 'answer_image_three', 'snd' => 'answer_sound_three', 'vid' => 'answer_video_three'],
                ['img' => 'answer_image_four',  'snd' => 'answer_sound_four',  'vid' => 'answer_video_four'],
            ];

            foreach ($answerMediaFields as $fieldSet) {
                $aImg = !empty($rowData[$fieldSet['img']]) ? trim($rowData[$fieldSet['img']]) : '';
                $aSnd = !empty($rowData[$fieldSet['snd']]) ? trim($rowData[$fieldSet['snd']]) : '';
                $aVid = !empty($rowData[$fieldSet['vid']]) ? trim($rowData[$fieldSet['vid']]) : '';

                if (!empty($aImg)) {
                    $stats['answer_media_need']++;
                    $exists = file_exists(public_path('upload/answers/images/' . $aImg));
                    if ($exists) $stats['media_found']++; else $stats['media_missing']++;
                    $referencedMedia['answers'][$aImg] = [
                        'name'   => $aImg,
                        'type'   => 'image',
                        'path'   => 'upload/answers/images/' . $aImg,
                        'exists' => $exists
                    ];
                }
                if (!empty($aSnd)) {
                    $stats['answer_media_need']++;
                    $exists = file_exists(public_path('upload/answers/sounds/' . $aSnd));
                    if ($exists) $stats['media_found']++; else $stats['media_missing']++;
                    $referencedMedia['answers'][$aSnd] = [
                        'name'   => $aSnd,
                        'type'   => 'sound',
                        'path'   => 'upload/answers/sounds/' . $aSnd,
                        'exists' => $exists
                    ];
                }
                if (!empty($aVid)) {
                    $stats['answer_media_need']++;
                    $exists = file_exists(public_path('upload/answers/videos/' . $aVid));
                    if ($exists) $stats['media_found']++; else $stats['media_missing']++;
                    $referencedMedia['answers'][$aVid] = [
                        'name'   => $aVid,
                        'type'   => 'video',
                        'path'   => 'upload/answers/videos/' . $aVid,
                        'exists' => $exists
                    ];
                }
            }

            $stats['total_questions']++;

            return $rowData;
        })->values();

        $gameTypes = GameType::latest()->get();

        return view('admin.excel.index', [
            'rows'                 => $formatted,
            'headers'              => $headers,
            'stats'                => $stats,
            'gameTypes'            => $gameTypes,
            'selectedGameType'     => $selectedGameType,
            'selectedMainCategory' => $selectedMainCategory,
            'selectedCategory'     => $selectedCategory,
            'game_type_id'         => $game_type_id,
            'main_category_id'     => $main_category_id,
            'category_id'          => $category_id,
            'referencedMedia'      => $referencedMedia,
            'activeStep'           => 2
        ]);
    }

    /**
     * رفع ملفات ZIP الخاصة بالأسئلة والإجابات وفك ضغطها بأمان فائق (يدعم كلاً من الـ AJAX ونماذج الويب)
     */
    public function uploadMedia(Request $request)
    {
        @ini_set('memory_limit', '1024M');
        @ini_set('max_execution_time', '600');
        @ini_set('max_input_time', '600');
        @set_time_limit(600);

        // فحص ما إذا كان حجم الـ POST تجاوز الحد الأقصى للسيرفر
        if (empty($_FILES) && empty($_POST) && isset($_SERVER['REQUEST_METHOD']) && strtolower($_SERVER['REQUEST_METHOD']) === 'post') {
            $postMax = ini_get('post_max_size');
            $msg = "حجم الملفات المرفوعة أكبر من الحد الأقصى المسموح به في إعدادات السيرفر ($postMax). يرجى تقليل حجم ملف الـ ZIP أو رفعه على أجزاء.";
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $msg], 422);
            }
            return back()->withErrors([$msg]);
        }

        // فحص امتدادات ملفات الـ ZIP يدوياً لتجنب مشاكل mime types
        if ($request->hasFile('zip_questions')) {
            $ext = strtolower($request->file('zip_questions')->getClientOriginalExtension());
            if ($ext !== 'zip') {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'يجب أن يكون ملف وسائط الأسئلة مضغوطاً بصيغة ZIP.'], 422);
                }
                return back()->withErrors(['يجب أن يكون ملف وسائط الأسئلة مضغوطاً بصيغة ZIP.']);
            }
        }

        if ($request->hasFile('zip_answers')) {
            $ext = strtolower($request->file('zip_answers')->getClientOriginalExtension());
            if ($ext !== 'zip') {
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => 'يجب أن يكون ملف وسائط الإجابات مضغوطاً بصيغة ZIP.'], 422);
                }
                return back()->withErrors(['يجب أن يكون ملف وسائط الإجابات مضغوطاً بصيغة ZIP.']);
            }
        }

        if (!$request->hasFile('zip_questions') && !$request->hasFile('zip_answers')) {
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success' => false,
                    'message' => 'الرجاء اختيار ملف ZIP واحد على الأقل للرفع.'
                ], 422);
            }
            return back()->withErrors(['الرجاء اختيار ملف ZIP واحد على الأقل للرفع.']);
        }

        try {
            $stats = [
                'questions' => ['extracted' => 0, 'replaced' => [], 'errors' => []],
                'answers'   => ['extracted' => 0, 'replaced' => [], 'errors' => []],
            ];

            // 1) فك ضغط وسائط الأسئلة
            if ($request->hasFile('zip_questions')) {
                $stats['questions'] = $this->extractZipMedia(
                    $request->file('zip_questions'),
                    public_path('upload/questions/'),
                    'questions'
                );
            }

            // 2) فك ضغط وسائط الإجابات
            if ($request->hasFile('zip_answers')) {
                $stats['answers'] = $this->extractZipMedia(
                    $request->file('zip_answers'),
                    public_path('upload/answers/'),
                    'answers'
                );
            }

            $totalExtracted = $stats['questions']['extracted'] + $stats['answers']['extracted'];
            $allReplaced    = array_merge($stats['questions']['replaced'], $stats['answers']['replaced']);
            $replacedCount  = count($allReplaced);

            $allErrors = array_merge($stats['questions']['errors'], $stats['answers']['errors']);
            if ($totalExtracted === 0 && count($allErrors) > 0) {
                $errMsg = implode(' - ', $allErrors);
                if ($request->ajax() || $request->wantsJson()) {
                    return response()->json(['success' => false, 'message' => $errMsg], 422);
                }
                return back()->withErrors([$errMsg]);
            }

            $msg = "تم فك ضغط واستخراج {$totalExtracted} ملف وسائط بنجاح.";
            if ($replacedCount > 0) {
                $msg .= " (تنبيه: تم استبدال {$replacedCount} ملف/مجلد موجود مسبقاً بنفس الاسم).";
            }

            // استجابة AJAX إذا كان الطلب من المحرر المباشر
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json([
                    'success'         => true,
                    'message'         => $msg,
                    'total_extracted' => $totalExtracted,
                    'replaced_count'  => $replacedCount,
                    'replaced_files'  => $allReplaced,
                    'stats'           => $stats
                ]);
            }

            // تحديث حالة وسائط الجلسة إن وجدت
            if (session()->has('import_success')) {
                $sess = session('import_success');
                if (!empty($sess['question_media'])) {
                    foreach ($sess['question_media'] as &$m) {
                        $m['exists'] = file_exists(public_path($m['path']));
                    }
                }
                if (!empty($sess['answer_media'])) {
                    foreach ($sess['answer_media'] as &$m) {
                        $m['exists'] = file_exists(public_path($m['path']));
                    }
                }
                session()->flash('import_success', $sess);
            }

            session()->flash('media_upload_stats', $stats);
            session()->flash('upload_completed', true);
            session()->flash('active_step', 4);

            $notification = [
                'message'    => $msg,
                'alert-type' => 'success'
            ];

            return redirect()->route('excel.index')->with($notification);

        } catch (\Throwable $e) {
            $err = 'حدث خطأ أثناء فك ضغط ملفات الوسائط: ' . $e->getMessage();
            if ($request->ajax() || $request->wantsJson()) {
                return response()->json(['success' => false, 'message' => $err], 500);
            }
            return back()->withErrors([$err]);
        }
    }

    /**
     * اعتماد وحفظ الأسئلة في قاعدة البيانات داخل Transaction (يدعم التحديث والإضافة معاً)
     */
    public function approved(Request $request)
    {
        @ini_set('memory_limit', '1024M');
        @ini_set('max_execution_time', '600');
        @set_time_limit(600);

        $rows = json_decode($request->rows, true);

        if (empty($rows) || !is_array($rows)) {
            return back()->withErrors(['لا توجد بيانات صالحة للاعتماد.']);
        }

        DB::beginTransaction();
        try {
            $insertedCount = 0;
            $updatedCount = 0;
            $questionMediaList = [];
            $answerMediaList = [];

            foreach ($rows as $row) {
                // 1) تحديد نوع السؤال والوسائط
                $qu_image = !empty($row['qu_image']) ? trim($row['qu_image']) : null;
                $qu_sound = !empty($row['qu_sound']) ? trim($row['qu_sound']) : null;
                $qu_video = !empty($row['qu_video']) ? trim($row['qu_video']) : null;

                $questions_type = 'text';
                if ($qu_image) {
                    $questions_type = 'image';
                    $questionMediaList[] = ['name' => $qu_image, 'type' => 'image', 'path' => 'upload/questions/images/' . $qu_image];
                } elseif ($qu_sound) {
                    $questions_type = 'sound';
                    $questionMediaList[] = ['name' => $qu_sound, 'type' => 'sound', 'path' => 'upload/questions/sounds/' . $qu_sound];
                } elseif ($qu_video) {
                    $questions_type = 'video';
                    $questionMediaList[] = ['name' => $qu_video, 'type' => 'video', 'path' => 'upload/questions/videos/' . $qu_video];
                }

                $game_type_id     = (!empty($row['game_type_id']) && $row['game_type_id'] !== 'non') ? $row['game_type_id'] : ($request->game_type_id !== 'non' ? $request->game_type_id : null);
                $main_category_id = (!empty($row['main_category_id']) && $row['main_category_id'] !== 'non') ? $row['main_category_id'] : ($request->main_category_id !== 'non' ? $request->main_category_id : null);
                $category_id      = (!empty($row['category_id']) && $row['category_id'] !== 'non') ? $row['category_id'] : ($request->category_id !== 'non' ? $request->category_id : null);

                // فحص ما إذا كان هناك qu_id لتحديث السؤال القائم
                $quId = !empty($row['qu_id']) ? (int)$row['qu_id'] : null;
                $existingQuestion = $quId ? Question::find($quId) : null;

                if ($existingQuestion) {
                    // ============================================
                    // أ) تحديث السؤال القائم (UPDATE)
                    // ============================================
                    $existingQuestion->update([
                        'qu_title'            => !empty($row['qu_title']) ? trim($row['qu_title']) : $existingQuestion->qu_title,
                        'qu_title_en'         => !empty($row['qu_title_en']) ? trim($row['qu_title_en']) : $existingQuestion->qu_title_en,
                        'game_type_id'        => $game_type_id ?? $existingQuestion->game_type_id,
                        'main_category_id'    => $main_category_id ?? $existingQuestion->main_category_id,
                        'category_id'         => $category_id ?? $existingQuestion->category_id,
                        'qu_points'           => isset($row['qu_points']) ? (int)$row['qu_points'] : $existingQuestion->qu_points,
                        'qu_points_online'    => isset($row['qu_points_online']) ? (int)$row['qu_points_online'] : (isset($row['qu_points']) ? (int)$row['qu_points'] : $existingQuestion->qu_points_online),
                        'questions_type'      => $questions_type,
                        'time_counter'        => isset($row['time_counter']) ? (int)$row['time_counter'] : $existingQuestion->time_counter,
                        'time_counter_online' => isset($row['time_counter_online']) ? (int)$row['time_counter_online'] : (isset($row['time_counter']) ? (int)$row['time_counter'] : $existingQuestion->time_counter_online),
                        'qu_image'            => $qu_image,
                        'qu_sound'            => $qu_sound,
                        'qu_video'            => $qu_video,
                        'qu_hint'             => !empty($row['qu_hint']) ? trim($row['qu_hint']) : null,
                        'qu_hint_en'          => !empty($row['qu_hint_en']) ? trim($row['qu_hint_en']) : null,
                        'term'                => !empty($row['term']) ? trim($row['term']) : null,
                    ]);

                    $question = $existingQuestion;
                    $updatedCount++;
                } else {
                    // ============================================
                    // ب) إنشاء سؤال جديد (INSERT)
                    // ============================================
                    $question = Question::create([
                        'qu_title'            => !empty($row['qu_title']) ? trim($row['qu_title']) : 'non',
                        'qu_title_en'         => !empty($row['qu_title_en']) ? trim($row['qu_title_en']) : 'non',
                        'game_type_id'        => $game_type_id,
                        'main_category_id'    => $main_category_id,
                        'category_id'         => $category_id,
                        'qu_points'           => !empty($row['qu_points']) ? (int)$row['qu_points'] : 0,
                        'qu_points_online'    => !empty($row['qu_points_online']) ? (int)$row['qu_points_online'] : (!empty($row['qu_points']) ? (int)$row['qu_points'] : 0),
                        'questions_type'      => $questions_type,
                        'time_counter'        => !empty($row['time_counter']) ? (int)$row['time_counter'] : 30,
                        'time_counter_online' => !empty($row['time_counter_online']) ? (int)$row['time_counter_online'] : (!empty($row['time_counter']) ? (int)$row['time_counter'] : 30),
                        'qu_image'            => $qu_image,
                        'qu_sound'            => $qu_sound,
                        'qu_video'            => $qu_video,
                        'qu_hint'             => !empty($row['qu_hint']) ? trim($row['qu_hint']) : null,
                        'qu_hint_en'          => !empty($row['qu_hint_en']) ? trim($row['qu_hint_en']) : null,
                        'term'                => !empty($row['term']) ? trim($row['term']) : null,
                        'user_id'             => Auth::id() ?? 1,
                    ]);

                    $insertedCount++;
                }

                // 2) معالجة الإجابات (تحديث أو إنشاء)
                // الإجابة الأولى (الصحيحة والمحلية)
                $ans1_img = !empty($row['answer_image']) ? trim($row['answer_image']) : null;
                $ans1_snd = !empty($row['answer_sound']) ? trim($row['answer_sound']) : null;
                $ans1_vid = !empty($row['answer_video']) ? trim($row['answer_video']) : null;
                $ans1_type = 'text';
                if ($ans1_img) {
                    $ans1_type = 'image';
                    $answerMediaList[] = ['name' => $ans1_img, 'type' => 'image', 'path' => 'upload/answers/images/' . $ans1_img];
                } elseif ($ans1_snd) {
                    $ans1_type = 'sound';
                    $answerMediaList[] = ['name' => $ans1_snd, 'type' => 'sound', 'path' => 'upload/answers/sounds/' . $ans1_snd];
                } elseif ($ans1_vid) {
                    $ans1_type = 'video';
                    $answerMediaList[] = ['name' => $ans1_vid, 'type' => 'video', 'path' => 'upload/answers/videos/' . $ans1_vid];
                }

                // تحديث أو إنشاء الإجابة المحلية
                $ansId = !empty($row['ans_id']) ? (int)$row['ans_id'] : null;
                $localAns = $ansId ? Answer::find($ansId) : $question->answers()->first();
                $localAnsData = [
                    'question_id'     => $question->id,
                    'answer_title'    => !empty($row['answer_title']) ? trim($row['answer_title']) : 'non',
                    'answer_title_en' => !empty($row['answer_title_en']) ? trim($row['answer_title_en']) : 'non',
                    'answer_type'     => $ans1_type,
                    'answer_image'    => $ans1_img,
                    'answer_sound'    => $ans1_snd,
                    'answer_video'    => $ans1_vid,
                ];

                if ($localAns) {
                    $localAns->update($localAnsData);
                } else {
                    Answer::create($localAnsData);
                }

                // الإجابة الأونلاين 1
                $onlineId1 = !empty($row['online_ans_id_1']) ? (int)$row['online_ans_id_1'] : null;
                $onl1 = $onlineId1 ? AnswerQuestionOnline::find($onlineId1) : $question->answerQuestionOnlines()->skip(0)->first();
                $onl1Data = [
                    'question_id'     => $question->id,
                    'answer_title'    => !empty($row['answer_title']) ? trim($row['answer_title']) : 'non',
                    'answer_title_en' => !empty($row['answer_title_en']) ? trim($row['answer_title_en']) : 'non',
                    'answer_type'     => $ans1_type,
                    'answer_image'    => $ans1_img,
                    'answer_sound'    => $ans1_snd,
                    'answer_video'    => $ans1_vid,
                ];
                if ($onl1) {
                    $onl1->update($onl1Data);
                } else {
                    AnswerQuestionOnline::create($onl1Data);
                }

                // الإجابة الأونلاين 2
                $ans2_img = !empty($row['answer_image_two']) ? trim($row['answer_image_two']) : null;
                $ans2_snd = !empty($row['answer_sound_two']) ? trim($row['answer_sound_two']) : null;
                $ans2_vid = !empty($row['answer_video_two']) ? trim($row['answer_video_two']) : null;
                $ans2_type = 'text';
                if ($ans2_img) {
                    $ans2_type = 'image';
                    $answerMediaList[] = ['name' => $ans2_img, 'type' => 'image', 'path' => 'upload/answers/images/' . $ans2_img];
                } elseif ($ans2_snd) {
                    $ans2_type = 'sound';
                    $answerMediaList[] = ['name' => $ans2_snd, 'type' => 'sound', 'path' => 'upload/answers/sounds/' . $ans2_snd];
                } elseif ($ans2_vid) {
                    $ans2_type = 'video';
                    $answerMediaList[] = ['name' => $ans2_vid, 'type' => 'video', 'path' => 'upload/answers/videos/' . $ans2_vid];
                }

                $onlineId2 = !empty($row['online_ans_id_2']) ? (int)$row['online_ans_id_2'] : null;
                $onl2 = $onlineId2 ? AnswerQuestionOnline::find($onlineId2) : $question->answerQuestionOnlines()->skip(1)->first();
                $onl2Data = [
                    'question_id'     => $question->id,
                    'answer_title'    => !empty($row['answer_title_two']) ? trim($row['answer_title_two']) : 'non',
                    'answer_title_en' => !empty($row['answer_title_en_two']) ? trim($row['answer_title_en_two']) : 'non',
                    'answer_type'     => $ans2_type,
                    'answer_image'    => $ans2_img,
                    'answer_sound'    => $ans2_snd,
                    'answer_video'    => $ans2_vid,
                ];
                if ($onl2) {
                    $onl2->update($onl2Data);
                } else {
                    AnswerQuestionOnline::create($onl2Data);
                }

                // الإجابة الأونلاين 3
                $ans3_img = !empty($row['answer_image_three']) ? trim($row['answer_image_three']) : null;
                $ans3_snd = !empty($row['answer_sound_three']) ? trim($row['answer_sound_three']) : null;
                $ans3_vid = !empty($row['answer_video_three']) ? trim($row['answer_video_three']) : null;
                $ans3_type = 'text';
                if ($ans3_img) {
                    $ans3_type = 'image';
                    $answerMediaList[] = ['name' => $ans3_img, 'type' => 'image', 'path' => 'upload/answers/images/' . $ans3_img];
                } elseif ($ans3_snd) {
                    $ans3_type = 'sound';
                    $answerMediaList[] = ['name' => $ans3_snd, 'type' => 'sound', 'path' => 'upload/answers/sounds/' . $ans3_snd];
                } elseif ($ans3_vid) {
                    $ans3_type = 'video';
                    $answerMediaList[] = ['name' => $ans3_vid, 'type' => 'video', 'path' => 'upload/answers/videos/' . $ans3_vid];
                }

                $onlineId3 = !empty($row['online_ans_id_3']) ? (int)$row['online_ans_id_3'] : null;
                $onl3 = $onlineId3 ? AnswerQuestionOnline::find($onlineId3) : $question->answerQuestionOnlines()->skip(2)->first();
                $onl3Data = [
                    'question_id'     => $question->id,
                    'answer_title'    => !empty($row['answer_title_three']) ? trim($row['answer_title_three']) : 'non',
                    'answer_title_en' => !empty($row['answer_title_en_three']) ? trim($row['answer_title_en_three']) : 'non',
                    'answer_type'     => $ans3_type,
                    'answer_image'    => $ans3_img,
                    'answer_sound'    => $ans3_snd,
                    'answer_video'    => $ans3_vid,
                ];
                if ($onl3) {
                    $onl3->update($onl3Data);
                } else {
                    AnswerQuestionOnline::create($onl3Data);
                }

                // الإجابة الأونلاين 4
                $ans4_img = !empty($row['answer_image_four']) ? trim($row['answer_image_four']) : null;
                $ans4_snd = !empty($row['answer_sound_four']) ? trim($row['answer_sound_four']) : null;
                $ans4_vid = !empty($row['answer_video_four']) ? trim($row['answer_video_four']) : null;
                $ans4_type = 'text';
                if ($ans4_img) {
                    $ans4_type = 'image';
                    $answerMediaList[] = ['name' => $ans4_img, 'type' => 'image', 'path' => 'upload/answers/images/' . $ans4_img];
                } elseif ($ans4_snd) {
                    $ans4_type = 'sound';
                    $answerMediaList[] = ['name' => $ans4_snd, 'type' => 'sound', 'path' => 'upload/answers/sounds/' . $ans4_snd];
                } elseif ($ans4_vid) {
                    $ans4_type = 'video';
                    $answerMediaList[] = ['name' => $ans4_vid, 'type' => 'video', 'path' => 'upload/answers/videos/' . $ans4_vid];
                }

                $onlineId4 = !empty($row['online_ans_id_4']) ? (int)$row['online_ans_id_4'] : null;
                $onl4 = $onlineId4 ? AnswerQuestionOnline::find($onlineId4) : $question->answerQuestionOnlines()->skip(3)->first();
                $onl4Data = [
                    'question_id'     => $question->id,
                    'answer_title'    => !empty($row['answer_title_four']) ? trim($row['answer_title_four']) : 'non',
                    'answer_title_en' => !empty($row['answer_title_en_four']) ? trim($row['answer_title_en_four']) : 'non',
                    'answer_type'     => $ans4_type,
                    'answer_image'    => $ans4_img,
                    'answer_sound'    => $ans4_snd,
                    'answer_video'    => $ans4_vid,
                ];
                if ($onl4) {
                    $onl4->update($onl4Data);
                } else {
                    AnswerQuestionOnline::create($onl4Data);
                }
            }

            DB::commit();

            // فحص حالة الوسائط المرجعية
            $qMediaUnique = collect($questionMediaList)->unique('name')->map(function($m) {
                $m['exists'] = file_exists(public_path($m['path']));
                return $m;
            })->values()->all();

            $aMediaUnique = collect($answerMediaList)->unique('name')->map(function($m) {
                $m['exists'] = file_exists(public_path($m['path']));
                return $m;
            })->values()->all();

            $hasMedia = (count($qMediaUnique) > 0 || count($aMediaUnique) > 0);

            session()->flash('import_success', [
                'saved_count'    => $insertedCount + $updatedCount,
                'inserted_count' => $insertedCount,
                'updated_count'  => $updatedCount,
                'question_media' => $qMediaUnique,
                'answer_media'   => $aMediaUnique,
                'has_media'      => $hasMedia,
            ]);

            if (!$hasMedia) {
                session()->flash('upload_completed', true);
                session()->flash('active_step', 4);
            } else {
                session()->flash('active_step', 3);
            }

            $msg = "تمت العملية بنجاح! ";
            if ($insertedCount > 0) $msg .= "تمت إضافة {$insertedCount} سؤال جديد. ";
            if ($updatedCount > 0) $msg .= "تم تحديث {$updatedCount} سؤال قائم.";

            $notification = [
                'message'    => $msg,
                'alert-type' => 'success'
            ];

            return redirect()->route('excel.index')->with($notification);

        } catch (\Exception $e) {
            DB::rollBack();
            return back()->withErrors(['حدث خطأ أثناء حفظ الأسئلة: ' . $e->getMessage()]);
        }
    }

    /**
     * دالة مساعدة لفك ضغط الوسائط بذكاء وأمان فائق
     */
    private function extractZipMedia($zipFile, $targetRoot, $section)
    {
        @ini_set('memory_limit', '1024M');
        @ini_set('max_execution_time', '600');
        @set_time_limit(600);

        $extracted = 0;
        $replaced = [];
        $errors = [];

        // التأكد من وجود المجلدات الأساسية
        foreach (['images', 'sounds', 'videos'] as $sub) {
            $dir = $targetRoot . $sub;
            if (!file_exists($dir)) {
                @mkdir($dir, 0755, true);
            }
        }

        try {
            $zip = new \ZipArchive;
            $openResult = $zip->open($zipFile->getRealPath());
            if ($openResult !== TRUE) {
                return [
                    'extracted' => 0,
                    'replaced'  => [],
                    'errors'    => ['تعذر فتح وقراءة ملف ZIP (رمز الخطأ: ' . $openResult . '). تأكد من سلامة الملف المضغوط.']
                ];
            }

            $imageExts = ['jpg', 'jpeg', 'png', 'webp', 'gif', 'jfif', 'svg', 'bmp'];
            $soundExts = ['mp3', 'wav', 'ogg', 'aac', 'm4a', 'flac'];
            $videoExts = ['mp4', 'avi', 'mov', 'mkv', 'webm', 'wmv'];

            for ($i = 0; $i < $zip->numFiles; $i++) {
                $entryName = $zip->getNameIndex($i);

                // تجاهل المجلدات المجردة وملفات النظام المؤقتة
                if (substr($entryName, -1) === '/' ||
                    str_starts_with($entryName, '__MACOSX/') ||
                    str_contains($entryName, '.DS_Store') ||
                    str_contains($entryName, 'Thumbs.db')) {
                    continue;
                }

                // تنظيف المسار
                $cleanEntry = ltrim(str_replace('\\', '/', $entryName), '/');
                if (empty($cleanEntry)) continue;

                // إزالة اسم القسم الجذري إذا وجد داخل الـ ZIP (مثل questions/images/... أو answers/images/...)
                if (str_starts_with($cleanEntry, $section . '/')) {
                    $cleanEntry = substr($cleanEntry, strlen($section) + 1);
                }

                $ext = strtolower(pathinfo($cleanEntry, PATHINFO_EXTENSION));
                $destinationPath = '';

                // توجيه الملف حسب المجلد أو الامتداد
                if (str_starts_with($cleanEntry, 'images/')) {
                    $destinationPath = $targetRoot . $cleanEntry;
                } elseif (str_starts_with($cleanEntry, 'sounds/')) {
                    $destinationPath = $targetRoot . $cleanEntry;
                } elseif (str_starts_with($cleanEntry, 'videos/')) {
                    $destinationPath = $targetRoot . $cleanEntry;
                } else {
                    // إذا لم يحتوي المسار على بادئة المجلد، يتم توجيهه حسب نوع الامتداد
                    if (in_array($ext, $imageExts)) {
                        $destinationPath = $targetRoot . 'images/' . $cleanEntry;
                    } elseif (in_array($ext, $soundExts)) {
                        $destinationPath = $targetRoot . 'sounds/' . $cleanEntry;
                    } elseif (in_array($ext, $videoExts)) {
                        $destinationPath = $targetRoot . 'videos/' . $cleanEntry;
                    } else {
                        $destinationPath = $targetRoot . 'images/' . $cleanEntry;
                    }
                }

                // إنشاء المجلد الأب إذا لم يكن موجوداً
                $parentDir = dirname($destinationPath);
                if (!file_exists($parentDir)) {
                    @mkdir($parentDir, 0755, true);
                }

                // كشف الاستبدال إذا كان الملف موجوداً مسبقاً
                $isExisting = file_exists($destinationPath);

                // استخراج المحتوى عبر Stream لتقليل استهلاك الذاكرة
                $stream = $zip->getStream($entryName);
                if ($stream) {
                    $destStream = @fopen($destinationPath, 'w');
                    if ($destStream) {
                        stream_copy_to_stream($stream, $destStream);
                        fclose($destStream);
                        fclose($stream);

                        $extracted++;
                        if ($isExisting) {
                            $replaced[] = basename($cleanEntry);
                        }
                    } else {
                        fclose($stream);
                        $errors[] = "تعذر استخراج الملف: " . $cleanEntry;
                    }
                }
            }

            $zip->close();

            return [
                'extracted' => $extracted,
                'replaced'  => array_unique($replaced),
                'errors'    => $errors,
            ];
        } catch (\Throwable $e) {
            return [
                'extracted' => 0,
                'replaced'  => [],
                'errors'    => ['حدث استثناء أثناء فك ضغط ملف ZIP: ' . $e->getMessage()]
            ];
        }
    }

    /**
     * تحميل ملف النموذج الاسترشادي
     */
    public function downloadSample()
    {
        $samplePath = public_path('upload/innov.xlsx');
        if (file_exists($samplePath)) {
            return response()->download($samplePath, 'innov_sample_questions.xlsx');
        }

        return back()->withErrors(['ملف النموذج الاسترشادي غير موجود حالياً في upload/innov.xlsx.']);
    }
}
