<?php

namespace App\Http\Controllers;

use App\Models\Answer;
use App\Models\Category;
use App\Models\GameCoin;
use App\Models\GameType;
use App\Models\Question;
use App\Models\MainCategory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\AnswerQuestionOnline;
use Illuminate\Support\Facades\Auth;



class QuestionController extends Controller
{



    public function fillterQuestionSearch(Request $request)
{
    $query = Question::query();

    // فلترة game_type_id
    if ($request->game_type_id !== "non") {
        $query->where('game_type_id', $request->game_type_id);
    }

    // فلترة main_category_id
    if ($request->main_category_id !== "non") {
        $query->where('main_category_id', $request->main_category_id);
    }

    // فلترة category_id (id)
    if ($request->category_id !== "non") {
        $query->where('id', $request->category_id);
    }

    // فلترة الفصل الدراسي term
    if ($request->term !== "non") {
        $query->where('term', $request->term);
    }

        // 🔥 فلترة بالسنة (created_at)
    if ($request->year !== "non") {
        $query->whereYear('created_at', $request->year);
    }

    // البحث بالعنوان
    if ($request->search && $request->search !== "") {
        $query->where(function($q) use ($request) {
            $q->where('qu_title', 'LIKE', "%{$request->search}%")
              ->orWhere('qu_title_en', 'LIKE', "%{$request->search}%");
        });
    }

    $questions = $query->get();

    return view('admin.question.all_question_filter', compact('questions'));
}



//      public function fillterQuestionSearch(Request $request)
//     {


//  $query = Question::query();

//     // فلترة game_type_id
//     if ($request->game_type_id !== "non") {
//         $query->where('game_type_id', $request->game_type_id);
//     }

//     // فلترة main_category_id
//     if ($request->main_category_id !== "non") {
//         $query->where('main_category_id', $request->main_category_id);
//     }

//     // فلترة category_id (id)
//     if ($request->category_id !== "non") {
//         $query->where('id', $request->category_id);
//     }

//     if ($request->search && $request->search !== "") {
//         $query->where(function($q) use ($request) {
//             $q->where('qu_title', 'LIKE', "%{$request->search}%")
//               ->orWhere('qu_title_en', 'LIKE', "%{$request->search}%");
//         });
//     }

//     $questions = $query->get();

//             return view('admin.question.all_question_filter',compact('questions'));


//     // return $results;

//     }

       public function fillterQuestion()
    {

        $category = Category::latest()->get();

        $gameType = GameType::latest()->get();




        return view('admin.question.filter_question',compact('category','gameType'));
    }
    public function addQuestion()
    {

        $category = Category::latest()->get();

        $gameType = GameType::latest()->get();


                $gameCoin = GameCoin::latest()->get();


        return view('admin.question.add_question',compact('category','gameType','gameCoin'));
    }

    public function editQuestion($id)
    {
        $question = Question::findOrFail($id);
        // $category = Category::latest()->get();

                        $gameCoin = GameCoin::latest()->get();


                        $gameType = GameType::latest()->get();

        $main_category = MainCategory::where('game_type_id',$question->game_type_id)->get();

                $category = Category::where('main_category_id',$question->main_category_id)->get();


// return $main_category;

        return view('admin.question.edit_question',compact('question','category','gameType','main_category','gameCoin'));
    }


    /*
    public function addQuestionStore(Request $request)
    {
        // Validate the inputs conditionally
        $request->validate([
            'qu_title' => 'required|string|max:255',
            'qu_points' => 'required|integer',
            'questions_type' => 'required|string',
            'questionsـfile' => [
                'nullable', // If no file is uploaded, it's fine
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->questions_type == 'صورة') {
                        // If the question type is image, validate it as an image file
                        if (!$request->hasFile('questionsـfile') || !$request->file('questionsـfile')->isValid() || !in_array($request->questionsـfile->extension(), ['jpg', 'jpeg', 'png'])) {
                            return $fail('Please upload a valid image file for the question.');
                        }
                    } elseif ($request->questions_type == 'ملف صوتي') {
                        // If the question type is audio, validate it as an audio file
                        if (!$request->hasFile('questionsـfile') || !$request->file('questionsـfile')->isValid() || !in_array($request->questionsـfile->extension(), ['mp3', 'wav'])) {
                            return $fail('Please upload a valid audio file for the question.');
                        }
                    }
                },
            ],
            'answer_title' => 'required|string|max:255',
            'answer_type' => 'required|string',
            'answerـfile' => [
                'nullable', // If no file is uploaded, it's fine
                function ($attribute, $value, $fail) use ($request) {
                    if ($request->answer_type == 'صورة') {
                        // If the answer type is image, validate it as an image file
                        if (!$request->hasFile('answerـfile') || !$request->file('answerـfile')->isValid() || !in_array($request->answerـfile->extension(), ['jpg', 'jpeg', 'png'])) {
                            return $fail('Please upload a valid image file for the answer.');
                        }
                    } elseif ($request->answer_type == 'ملف صوتي') {
                        // If the answer type is audio, validate it as an audio file
                        if (!$request->hasFile('answerـfile') || !$request->file('answerـfile')->isValid() || !in_array($request->answerـfile->extension(), ['mp3', 'wav'])) {
                            return $fail('Please upload a valid audio file for the answer.');
                        }
                    }
                },
            ],
        ]);

        // Handle file upload for the question
        $questionFilePath = null;
        if ($request->hasFile('questionsـfile')) {
            $questionFile = $request->file('questionsـfile');

            if ($request->questions_type == 'image') {
                // If it's an image, store it in the 'questions/images' folder
                $cleanName = \Illuminate\Support\Str::slug(pathinfo($questionFile->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $questionFile->getClientOriginalExtension();
                $questionFilePath = $questionFile->storeAs('questions/images', $cleanName, 'public');
            } elseif ($request->questions_type == 'sound') {
                // If it's a sound file, store it in the 'questions/sounds' folder
                $cleanName = \Illuminate\Support\Str::slug(pathinfo($questionFile->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $questionFile->getClientOriginalExtension();
                $questionFilePath = $questionFile->storeAs('questions/sounds', $cleanName, 'public');
            }
        }

        // Store the question data with the correct file path
        $question = Question::create([
            'qu_title' => $request->qu_title,
            'qu_points' => $request->qu_points,
            'questions_type' => $request->questions_type,
            'qu_image' => $request->questions_type == 'image' ? $questionFilePath : null,
            'qu_sound' => $request->questions_type == 'sound' ? $questionFilePath : null,
        ]);

        // Handle file upload for the answer
        $answerFilePath = null;
        if ($request->hasFile('answerـfile')) {
            $answerFile = $request->file('answerـfile');

            if ($request->answer_type == 'image') {
                // If it's an image, store it in the 'answers/images' folder
                $cleanName = \Illuminate\Support\Str::slug(pathinfo($answerFile->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $answerFile->getClientOriginalExtension();
                $answerFilePath = $answerFile->storeAs('answers/images', $cleanName, 'public');
            } elseif ($request->answer_type == 'sound') {
                // If it's a sound file, store it in the 'answers/sounds' folder
                $cleanName = \Illuminate\Support\Str::slug(pathinfo($answerFile->getClientOriginalName(), PATHINFO_FILENAME)) . '_' . time() . '.' . $answerFile->getClientOriginalExtension();
                $answerFilePath = $answerFile->storeAs('answers/sounds', $cleanName, 'public');
            }
        }

        // Create the answer and link it to the question using 'question_id'
        Answer::create([
            'question_id' => $question->id, // Linking the answer to the question
            'answer_title' => $request->answer_title,
            'answer_type' => $request->answer_type,
            'answer_image' => $request->answer_type == 'image' ? $answerFilePath : null,
            'answer_sound' => $request->answer_type == 'sound' ? $answerFilePath : null,
        ]);

        // Redirect or return response
        return redirect()->route('all');
    }
        */


        /*
        public function addQuestionStore(Request $request)
        {
            // Validate inputs with custom Arabic messages
            $request->validate([
                'qu_title' => 'required|string|max:255',
                'qu_points' => 'required|integer',
                'questions_type' => 'required|string|in:text,image,sound',
                'questionsـfile' => 'nullable|file|max:30048', // 2MB max file size
                'answer_title' => 'required|string|max:255',
                'answer_type' => 'required|string|in:text,image,sound',
                'answerـfile' => 'nullable|file|max:30048',
                'category_id' => 'required|not_in:non', // التحقق من اختيار فئة صالحة

            ], [
                'qu_title.required' => 'يرجى إدخال عنوان السؤال.',
                'qu_title.string' => 'عنوان السؤال يجب أن يكون نصًا.',
                'qu_title.max' => 'عنوان السؤال يجب أن لا يتجاوز 255 حرفًا.',

                'qu_points.required' => 'يرجى إدخال نقاط السؤال.',
                'qu_points.integer' => 'نقاط السؤال يجب أن تكون عددًا صحيحًا.',

                'questions_type.required' => 'يرجى اختيار نوع السؤال.',
                'questions_type.in' => 'نوع السؤال يجب أن يكون نصي، صورة أو ملف صوتي.',

                'questionsـfile.file' => 'يرجى رفع ملف صالح.',
                'questionsـfile.max' => 'حجم الملف يجب أن لا يتجاوز 2 ميجابايت.',

                'answer_title.required' => 'يرجى إدخال عنوان الإجابة.',
                'answer_title.string' => 'عنوان الإجابة يجب أن يكون نصًا.',
                'answer_title.max' => 'عنوان الإجابة يجب أن لا يتجاوز 255 حرفًا.',

                'answer_type.required' => 'يرجى اختيار نوع الإجابة.',
                'answer_type.in' => 'نوع الإجابة يجب أن يكون نصي، صورة أو ملف صوتي.',

                'answerـfile.file' => 'يرجى رفع ملف صالح للإجابة.',
                'answerـfile.max' => 'حجم الملف يجب أن لا يتجاوز 2 ميجابايت.',

    'category_id.required' => 'الرجاء اختيار الفئة',
    'category_id.not_in' => 'الرجاء اختيار الفئة',
            ]);

            // Initialize variables for files
            $questionImage = null;
            $questionSound = null;

            // Handle file upload for question
            if ($request->questions_type !== 'text' && $request->hasFile('questionsـfile')) {
                $questionFile = $request->file('questionsـfile');
                $extension = strtolower($questionFile->getClientOriginalExtension());
                $filename = date('YmdHi') . '_' . uniqid() . '.' . $extension;

                // Validate file type based on selected question type
                if ($request->questions_type == 'image' && in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    $questionFile->move(public_path('upload/questions/images'), $filename);
                    $questionImage = $filename;
                } elseif ($request->questions_type == 'sound' && in_array($extension, ['mp3', 'wav'])) {
                    $questionFile->move(public_path('upload/questions/sounds'), $filename);
                    $questionSound = $filename;
                } else {

                    $notification = array(
                        'message' => 'نوع الملف غير صالح لنوع السؤال المحدد.',
                        'alert-type' => 'error'
                    );


                    return back()->with($notification);
                }
            }

            // Store the question in the database
            $question = Question::create([
                'qu_title' => $request->qu_title,
                'category_id'=>$request->category_id,
                'qu_points' => $request->qu_points,
                'questions_type' => $request->questions_type,
                'qu_image' => $questionImage,
                'qu_sound' => $questionSound,
            ]);

            // Initialize variables for files
            $answerImage = null;
            $answerSound = null;

            // Handle file upload for answer
            if ($request->answer_type !== 'text' && $request->hasFile('answerـfile')) {
                $answerFile = $request->file('answerـfile');
                $extension = strtolower($answerFile->getClientOriginalExtension());
                $filename = date('YmdHi') . '_' . uniqid() . '.' . $extension;

                // Validate file type based on selected answer type
                if ($request->answer_type == 'image' && in_array($extension, ['jpg', 'jpeg', 'png'])) {
                    $answerFile->move(public_path('upload/answers/images'), $filename);
                    $answerImage = $filename;
                } elseif ($request->answer_type == 'sound' && in_array($extension, ['mp3', 'wav'])) {
                    $answerFile->move(public_path('upload/answers/sounds'), $filename);
                    $answerSound = $filename;
                } else {

                    $notification = array(
                        'message' => 'نوع الملف غير صالح لنوع الإجابة المحدد.',
                        'alert-type' => 'error'
                    );


                    return back()->with($notification);
                }
            }

            // Store the answer in the database
            Answer::create([
                'question_id' => $question->id,
                'answer_title' => $request->answer_title,
                'answer_type' => $request->answer_type,
                'answer_image' => $answerImage,
                'answer_sound' => $answerSound,
            ]);

            return redirect()->route('all.question')->with('success', 'تمت إضافة السؤال والإجابة بنجاح.');
        }
*/

public function addQuestionStore(Request $request)
{
    $request->validate([


                'game_type_id' => 'required|not_in:non',
                'main_category_id' => 'required|not_in:non',
                // 'game_coin_id' => 'required|not_in:non',

                // 'coins_number' => 'required|integer',


        'qu_title' => 'nullable|string|max:255',
                'qu_title_en' => 'nullable|string|max:255',

        // 'qu_points' => 'required|integer',
        // 'qu_points_online' => 'required|integer',

        'questions_type' => 'required|string|in:text,image,sound,video',
        'time_counter' => 'nullable|integer',
         'time_counter_online' => 'nullable|integer',

        'questionsـfile' => 'nullable|file|max:30720', // Increased size for videos
        'answer_title' => 'nullable|string|max:255',
                'answer_title_en' => 'nullable|string|max:255',

        'answer_type' => 'required|string|in:text,image,sound,video',
        'answerـfile' => 'nullable|file|max:30720',
        'category_id' => 'required|not_in:non',
        'time_counter' => 'nullable|integer',



                'answer_title_one' => 'nullable|string|max:255',
                'answer_title_two' => 'nullable|string|max:255',
                 'answer_title_three' => 'nullable|string|max:255',
                'answer_title_four' => 'nullable|string|max:255',



                  'answer_title_one_en' => 'nullable|string|max:255',
                'answer_title_two_en' => 'nullable|string|max:255',
                 'answer_title_three_en' => 'nullable|string|max:255',
                'answer_title_four_en' => 'nullable|string|max:255',






    ], [
        'qu_title.required' => 'يرجى إدخال عنوان السؤال.',
        'qu_title.string' => 'عنوان السؤال يجب أن يكون نصًا.',
        'qu_title.max' => 'عنوان السؤال يجب أن لا يتجاوز 255 حرفًا.',

          'qu_title_en.required' => ' يرجى إدخال عنوان السؤال بالانجليزية.',
        'qu_title_en.string' => 'عنوان السؤال يجب أن يكون نصًا.',
        'qu_title_en.max' => 'عنوان السؤال يجب أن لا يتجاوز 255 حرفًا.',

        'qu_points.required' => 'يرجى إدخال نقاط السؤال.',
        'qu_points.integer' => 'نقاط السؤال يجب أن تكون عددًا صحيحًا.',
        'time_counter.integer' => 'الرجاء التأكد ان القيمة عدد صحيح',


        // 'coins_number.required' => 'يرجى إدخال عدد العملات.',
        // 'coins_number.integer' => 'الرجاء التأكد ان القيمة عدد صحيح',


        'qu_points_online.required' => 'يرجى إدخال نقاط سؤال OnLine.',
        'qu_points_online.integer' => 'نقاط السؤال يجب أن تكون عددًا صحيحًا.',
        'time_counter_online.integer' => 'الرجاء التأكد ان القيمة عدد صحيح',

        'questions_type.required' => 'يرجى اختيار نوع السؤال.',
        'questions_type.in' => 'نوع السؤال يجب أن يكون نصي، صورة، صوتي أو فيديو.',

        'questionsـfile.file' => 'يرجى رفع ملف صالح.',
        'questionsـfile.max' => 'حجم الملف يجب أن لا يتجاوز 30 ميجابايت.',

        'answer_title.required' => 'يرجى إدخال عنوان الإجابة.',
        'answer_title.string' => 'عنوان الإجابة يجب أن يكون نصًا.',
        'answer_title.max' => 'عنوان الإجابة يجب أن لا يتجاوز 255 حرفًا.',


         'answer_title_en.required' => 'يرجى إدخال عنوان الإجابة بالانجليزية. ',
        'answer_title_en.string' => 'عنوان الإجابة يجب أن يكون نصًا.',
        'answer_title_en.max' => 'عنوان الإجابة يجب أن لا يتجاوز 255 حرفًا.',

        'answer_type.required' => 'يرجى اختيار نوع الإجابة.',
        'answer_type.in' => 'نوع الإجابة يجب أن يكون نصي، صورة، صوتي أو فيديو.',

        'answerـfile.file' => 'يرجى رفع ملف صالح للإجابة.',
        'answerـfile.max' => 'حجم الملف يجب أن لا يتجاوز 30 ميجابايت.',

        'category_id.required' => 'الرجاء اختيار الفئة الفرعية.',
        'category_id.not_in' => 'الرجاء اختيار الفئة الفرعية.',


        //  'game_type_id.required' => 'الرجاء اختيار نوع اللعبة.',
        // 'game_type_id.not_in' => 'الرجاء اختيار نوع اللعبة.',
        //  'game_coin_id.required' => 'الرجاء اختيار نوع العملة.',
        //  'game_coin_id.not_in' => 'الرجاء اختيار نوع العملة.',



         'main_category_id.required' => 'الرجاء اختيار الفئة الرئيسية.',
        'main_category_id.not_in' => 'الرجاء اختيار الفئة الرئيسية.',



                 'answer_title_one.required' => 'يرجى إدخال عنوان الإجابة .',

                 'answer_title_two.required' => 'يرجى إدخال عنوان الإجابة .',
                 'answer_title_three.required' => 'يرجى إدخال عنوان الإجابة .',
                 'answer_title_four.required' => 'يرجى إدخال عنوان الإجابة .',


                'answer_title_one_en.required' => 'يرجى إدخال عنوان الإجابة بالانجليزية. ',
                'answer_title_two_en.required' => 'يرجى إدخال عنوان الإجابة بالانجليزية. ',
                'answer_title_three_en.required' => 'يرجى إدخال عنوان الإجابة بالانجليزية. ',
                'answer_title_four_en.required' => 'يرجى إدخال عنوان الإجابة بالانجليزية. ',


    ]);


    DB::beginTransaction(); // Start transaction
    try {
        // Initialize variables for question files
        $questionImage = $questionSound = $questionVideo = null;

        if ($request->questions_type !== 'text' && $request->hasFile('questionsـfile')) {
            $questionFile = $request->file('questionsـfile');
            $extension = strtolower($questionFile->getClientOriginalExtension());
            $filename = date('YmdHi') . '_' . uniqid() . '.' . $extension;

            if ($request->questions_type == 'image' && in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $questionFile->move(public_path('upload/questions/images'), $filename);
                $questionImage = $filename;
            } elseif ($request->questions_type == 'sound' && in_array($extension, ['mp3', 'wav'])) {
                $questionFile->move(public_path('upload/questions/sounds'), $filename);
                $questionSound = $filename;
            } elseif ($request->questions_type == 'video' && in_array($extension, ['mp4', 'avi', 'mov'])) {
                $questionFile->move(public_path('upload/questions/videos'), $filename);
                $questionVideo = $filename;
            } else {
                $notification = array(
                    'message' => 'نوع الملف غير صالح لنوع السؤال المحدد.',
                    'alert-type' => 'error'
                );


                return back()->with($notification);
            }
        }

        // Create question
        $question = Question::create([
            'qu_title' => $request->filled('qu_title') ? $request->qu_title : 'non',

                        'qu_title_en' => $request->filled('qu_title_en') ? $request->qu_title_en : 'non',

            'game_type_id' => $request->game_type_id,

            'main_category_id' => $request->main_category_id,
            'category_id' => $request->category_id,

            'category_id' => $request->category_id,
            'qu_points' => $request->qu_points,
                        'qu_points_online' => $request->qu_points_online,

            'questions_type' => $request->questions_type,
            'time_counter' => $request->time_counter,
            'time_counter_online' => $request->time_counter_online,

            'qu_image' => $questionImage,
            'qu_sound' => $questionSound,
            'qu_video' => $questionVideo,
            // 'coins_number' => $request->coins_number,
            // 'game_coin_id' => $request->game_coin_id,

              'qu_hint' => $request->qu_hint,
            'qu_hint_en' => $request->qu_hint_en,
            'user_id' => Auth::user()->id,



        ]);


        if (!$question) {
            throw new \Exception('فشل في إنشاء السؤال.');
        }

        // Initialize variables for answer files
        $answerImage = $answerSound = $answerVideo = null;

        if ($request->answer_type !== 'text' && $request->hasFile('answerـfile')) {
            $answerFile = $request->file('answerـfile');
            $extension = strtolower($answerFile->getClientOriginalExtension());
            $filename = date('YmdHi') . '_' . uniqid() . '.' . $extension;

            if ($request->answer_type == 'image' && in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $answerFile->move(public_path('upload/answers/images'), $filename);
                $answerImage = $filename;
            } elseif ($request->answer_type == 'sound' && in_array($extension, ['mp3', 'wav'])) {
                $answerFile->move(public_path('upload/answers/sounds'), $filename);
                $answerSound = $filename;
            } elseif ($request->answer_type == 'video' && in_array($extension, ['mp4', 'avi', 'mov'])) {
                $answerFile->move(public_path('upload/answers/videos'), $filename);
                $answerVideo = $filename;
            } else {
                throw new \Exception('نوع الملف غير صالح لنوع الإجابة المحدد.');
            }
        }

        // Create answer for local
        $answer = Answer::create([
            'question_id' => $question->id,
            'answer_title' => $request->filled('answer_title') ? $request->answer_title : 'non',
                        'answer_title_en' => $request->filled('answer_title_en') ? $request->answer_title_en : 'non',

            'answer_type' => $request->answer_type,
            'answer_image' => $answerImage,
            'answer_sound' => $answerSound,
            'answer_video' => $answerVideo,
        ]);






        /// Answer online 2

         // Initialize variables for answer files
        $answerImageTwo = $answerSoundTwo = $answerVideoTwo = null;

        if ($request->answer_type_two !== 'text' && $request->hasFile('answer_file_two')) {
            $answerFileTwo = $request->file('answer_file_two');
            $extension = strtolower($answerFileTwo->getClientOriginalExtension());
            $filenameTwo = date('YmdHi') . '_' . uniqid() . '.' . $extension;

            if ($request->answer_type_two == 'image' && in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $answerFileTwo->move(public_path('upload/answers/online/images'), $filenameTwo);
                $answerImageTwo = $filenameTwo;
            } elseif ($request->answer_type_two == 'sound' && in_array($extension, ['mp3', 'wav'])) {
                $answerFileTwo->move(public_path('upload/answers/online/sounds'), $filenameTwo);
                $answerSoundTwo = $filenameTwo;
            } elseif ($request->answer_type_two == 'video' && in_array($extension, ['mp4', 'avi', 'mov'])) {
                $answerFileTwo->move(public_path('upload/answers/online/videos'), $filenameTwo);
                $answerVideoTwo = $filenameTwo;
            } else {
                throw new \Exception('نوع الملف غير صالح لنوع الإجابة المحدد.');
            }
        }


         /// Answer online 3

         // Initialize variables for answer files
        $answerImageThree = $answerSoundThree = $answerVideoThree = null;

        if ($request->answer_type_three !== 'text' && $request->hasFile('answer_file_three')) {
            $answerFileThree = $request->file('answer_file_three');
            $extension = strtolower($answerFileThree->getClientOriginalExtension());
            $filenameThree = date('YmdHi') . '_' . uniqid() . '.' . $extension;

            if ($request->answer_type_three == 'image' && in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $answerFileThree->move(public_path('upload/answers/online/images'), $filenameThree);
                $answerImageThree = $filenameThree;
            } elseif ($request->answer_type_three == 'sound' && in_array($extension, ['mp3', 'wav'])) {
                $answerFileThree->move(public_path('upload/answers/online/sounds'), $filenameThree);
                $answerSoundThree = $filenameThree;
            } elseif ($request->answer_type_three == 'video' && in_array($extension, ['mp4', 'avi', 'mov'])) {
                $answerFileThree->move(public_path('upload/answers/online/videos'), $filenameThree);
                $answerVideoThree = $filenameThree;
            } else {
                throw new \Exception('نوع الملف غير صالح لنوع الإجابة المحدد.');
            }
        }


          /// Answer online 4

         // Initialize variables for answer files
        $answerImageFour = $answerSoundFour = $answerVideoFour = null;

        if ($request->answer_type_four !== 'text' && $request->hasFile('answer_file_four')) {
            $answerFileFour = $request->file('answer_file_four');
            $extension = strtolower($answerFileFour->getClientOriginalExtension());
            $filenameFour = date('YmdHi') . '_' . uniqid() . '.' . $extension;

            if ($request->answer_type_four == 'image' && in_array($extension, ['jpg', 'jpeg', 'png'])) {
                $answerFileFour->move(public_path('upload/answers/online/images'), $filenameFour);
                $answerImageFour = $filenameFour;
            } elseif ($request->answer_type_four == 'sound' && in_array($extension, ['mp3', 'wav'])) {
                $answerFileFour->move(public_path('upload/answers/online/sounds'), $filenameFour);
                $answerSoundFour = $filenameFour;
            } elseif ($request->answer_type_four == 'video' && in_array($extension, ['mp4', 'avi', 'mov'])) {
                $answerFileFour->move(public_path('upload/answers/online/videos'), $filenameFour);
                $answerVideoFour = $filenameFour;
            } else {
                throw new \Exception('نوع الملف غير صالح لنوع الإجابة المحدد.');
            }
        }



       $answersData = [
    [
        'ar' => $request->filled('answer_title_one') ? $request->answer_title_one : 'non',
        'en' => $request->filled('answer_title_one_en') ? $request->answer_title_one_en : 'non',
        'is_correct' => true,
            'answer_type' => $request->answer_type,
             'answer_image' => $answerImage,
            'answer_sound' => $answerSound,
            'answer_video' => $answerVideo,

    ],
    [
        'ar' => $request->filled('answer_title_two') ? $request->answer_title_two : 'non',
        'en' => $request->filled('answer_title_two_en') ? $request->answer_title_two_en : 'non',
        'is_correct' => false,
          'answer_type' => $request->answer_type_two,
             'answer_image' => $answerImageTwo,
            'answer_sound' => $answerSoundTwo,
            'answer_video' => $answerVideoTwo,
    ],
    [
        // Three
        'ar' => $request->filled('answer_title_three') ? $request->answer_title_three : 'non',
        'en' => $request->filled('answer_title_three_en') ? $request->answer_title_three_en : 'non',
        'is_correct' => false,
         'answer_type' => $request->answer_type_three,
             'answer_image' => $answerImageThree,
            'answer_sound' => $answerSoundThree,
            'answer_video' => $answerVideoThree,
    ],
    [
    // Four
        'ar' => $request->filled('answer_title_four') ? $request->answer_title_four : 'non',
        'en' => $request->filled('answer_title_four_en') ? $request->answer_title_four_en : 'non',
        'is_correct' => false,
        'answer_type' => $request->answer_type_four,
             'answer_image' => $answerImageFour,
            'answer_sound' => $answerSoundFour,
            'answer_video' => $answerVideoFour,
    ],
];


// Loop to create the 4 answers for online questions
foreach ($answersData as $ans) {
    AnswerQuestionOnline::create([
        'question_id'      => $question->id,
        'answer_title'     => $ans['ar'],
        'answer_title_en'  => $ans['en'],
        'is_correct'       => $ans['is_correct'],
        'answer_type'      => $ans['answer_type'],
        'answer_image'     => $ans['answer_image'],
        'answer_sound'     => $ans['answer_sound'],
        'answer_video'     => $ans['answer_video'],
    ]);
}

        //    $answerOnline = AnswerQuestionOnline::create([
        //     'question_id' => $question->id,
        //     'answer_title' => "",
        //     'answer_title_en' => "",
        //     'is_correct' => 0,
        //     'answer_type' => $request->answer_type,
        //     'answer_image' => $answerImage,
        //     'answer_sound' => $answerSound,
        //     'answer_video' => $answerVideo,
        // ]);





        if (!$answer) {
            throw new \Exception('فشل في إنشاء الإجابة.');
        }

        DB::commit(); // Commit transaction
        $notification = array(
            'message' =>  'تمت إضافة السؤال الاجابات بنجاح.',
            'alert-type' => 'success'
        );

        return redirect()->route('all.question')->with($notification);

    } catch (\Exception $e) {
        DB::rollBack(); // Rollback transaction if an error occurs

        return back()->with('error', $e->getMessage());
    }
}



        /**
         * صفحة التحقق من صور الأسئلة والإجابات
         * - الطلب الأول: يحمّل الإحصائيات فقط بسرعة عبر COUNT queries
         * - AJAX: يجلب الصفوف دفعة دفعة (lazy loading) مع فحص الملفات
         */
        public function verifyQuestionImages(Request $request)
        {
            // إذا كان الطلب AJAX → إرجاع دفعة من الصفوف مع فحص الملفات
            if ($request->ajax()) {
                return $this->verifyQuestionImagesAjax($request);
            }

            // =============================================
            // الطلب الأول: إحصائيات سريعة فقط (بدون file_exists)
            // =============================================
            $search = $request->input('search', '');
            $filterType = $request->input('filter_type', '');
            $categoryId = $request->input('category_id', '');

            $baseQuery = Question::with(['answers' => function($q) {
                $q->where('answer_type', 'image')->select('id','question_id','answer_type','answer_image');
            }])->orderBy('id', 'asc');

            if ($search) {
                $baseQuery->where(function($q) use ($search) {
                    $q->where('qu_title', 'like', "%{$search}%")
                      ->orWhere('qu_title_en', 'like', "%{$search}%");
                });
            }

            if ($categoryId) {
                $baseQuery->where('category_id', $categoryId);
            }

            // إحصائيات سريعة من قاعدة البيانات فقط (بدون فحص الملفات)
            $totalQuestions    = (clone $baseQuery)->count();
            $imageQuestions    = (clone $baseQuery)->where('questions_type', 'image')->count();
            
            $totalAnswerImagesQuery = \App\Models\Answer::where('answer_type', 'image');
            if ($categoryId) {
                $totalAnswerImagesQuery->whereHas('question', function($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            }
            $totalAnswerImages = $totalAnswerImagesQuery->count();

            $stats = [
                'total_questions'       => $totalQuestions,
                'image_questions'       => $imageQuestions,
                'total_answer_images'   => $totalAnswerImages,
                // هذه القيم ستُحدَّث عبر AJAX بعد الانتهاء من فحص جميع الدفعات
                'question_images_found'   => '—',
                'question_images_missing' => '—',
                'question_images_no_path' => '—',
                'answer_images_found'     => '—',
                'answer_images_missing'   => '—',
                'answer_images_no_path'   => '—',
            ];

            $categories = \App\Models\Category::orderBy('category_name', 'asc')->get();

            return view('admin.question.verify_images', compact('stats', 'search', 'filterType', 'categories'));
        }

        /**
         * AJAX endpoint: يُرجع دفعة من الصفوف مع نتيجة فحص الملفات
         */
        public function verifyQuestionImagesAjax(Request $request)
        {
            $perPage    = 50; // حجم الدفعة - زيادة لتقليل عدد الطلبات
            $page       = max(1, (int) $request->input('page', 1));
            $search     = $request->input('search', '');
            $filterType = $request->input('filter_type', '');
            $categoryId = $request->input('category_id', '');

            $query = Question::with(['category', 'answers'])->orderBy('id', 'asc');

            if ($search) {
                $query->where(function($q) use ($search) {
                    $q->where('qu_title', 'like', "%{$search}%")
                      ->orWhere('qu_title_en', 'like', "%{$search}%");
                });
            }

            if ($categoryId) {
                $query->where('category_id', $categoryId);
            }

            $paginator  = $query->paginate($perPage, ['*'], 'page', $page);
            $questions  = $paginator->items();

            // فحص الملفات لهذه الدفعة فقط
            $rows = [];
            $batchStats = [
                'question_images_found'   => 0,
                'question_images_missing' => 0,
                'question_images_no_path' => 0,
                'answer_images_found'     => 0,
                'answer_images_missing'   => 0,
                'answer_images_no_path'   => 0,
            ];

            foreach ($questions as $question) {
                // فحص صورة السؤال
                $qStatus = null;
                if ($question->questions_type === 'image') {
                    if ($question->qu_image) {
                        $exists  = file_exists(public_path('upload/questions/images/' . $question->qu_image));
                        $qStatus = $exists ? 'found' : 'missing';
                    } else {
                        $qStatus = 'no_path';
                    }
                    $batchStats['question_images_' . ($qStatus === 'found' ? 'found' : ($qStatus === 'missing' ? 'missing' : 'no_path'))]++;
                }

                // فحص صور الإجابات
                $answersData = [];
                foreach ($question->answers as $answer) {
                    $aStatus = null;
                    if ($answer->answer_type === 'image') {
                        if ($answer->answer_image) {
                            $exists  = file_exists(public_path('upload/answers/images/' . $answer->answer_image));
                            $aStatus = $exists ? 'found' : 'missing';
                        } else {
                            $aStatus = 'no_path';
                        }
                        $batchStats['answer_images_' . ($aStatus === 'found' ? 'found' : ($aStatus === 'missing' ? 'missing' : 'no_path'))]++;
                    }
                    $answersData[] = [
                        'id'           => $answer->id,
                        'answer_type'  => $answer->answer_type,
                        'answer_title' => $answer->answer_title ?? '',
                        'answer_image' => $answer->answer_image,
                        'image_status' => $aStatus,
                    ];
                }

                // تحديد ما إذا كان هناك مشاكل
                $hasQuestionIssue = in_array($qStatus, ['missing', 'no_path']);
                $hasAnswerIssue   = collect($answersData)->filter(fn($a) => in_array($a['image_status'], ['missing', 'no_path']))->count() > 0;

                $rows[] = [
                    'id'                     => $question->id,
                    'qu_title'               => $question->qu_title,
                    'qu_title_en'            => $question->qu_title_en,
                    'questions_type'         => $question->questions_type,
                    'qu_image'               => $question->qu_image,
                    'category_name'          => optional($question->category)->category_name ?? '—',
                    'question_image_status'  => $qStatus,
                    'answers'                => $answersData,
                    'has_question_issue'     => $hasQuestionIssue,
                    'has_answer_issue'       => $hasAnswerIssue,
                    'has_any_issue'          => $hasQuestionIssue || $hasAnswerIssue,
                ];
            }

            // تطبيق الفلتر على مستوى PHP بعد فحص الملفات
            if ($filterType === 'question_missing') {
                $rows = array_filter($rows, fn($r) => $r['question_image_status'] === 'missing');
            } elseif ($filterType === 'question_no_path') {
                $rows = array_filter($rows, fn($r) => $r['question_image_status'] === 'no_path');
            } elseif ($filterType === 'answer_missing') {
                $rows = array_filter($rows, fn($r) => $r['has_answer_issue']);
            } elseif ($filterType === 'has_issues') {
                $rows = array_filter($rows, fn($r) => $r['has_any_issue']);
            } elseif ($filterType === 'all_ok') {
                $rows = array_filter($rows, fn($r) => !$r['has_any_issue']);
            }

            $html = view('admin.question.partials.verify_images_rows', [
                'rows'       => array_values($rows),
                'startIndex' => ($page - 1) * $perPage,
            ])->render();

            return response()->json([
            'html'       => $html,
                'next_page'  => $paginator->hasMorePages() ? $page + 1 : null,
                'batch_stats'=> $batchStats,
                'total'      => $paginator->total(),
                'last_page'  => $paginator->lastPage(),
                'current_page' => $page,
            ]);
        }

        public function sanitizeQuestionImagesAjax(Request $request)
        {
            $categoryId = $request->input('category_id', '');

            $qQuery = Question::where('questions_type', 'image')
                ->whereNotNull('qu_image')
                ->where('qu_image', '!=', '');
            
            $aQuery = \App\Models\Answer::where('answer_type', 'image')
                ->whereNotNull('answer_image')
                ->where('answer_image', '!=', '');

            if ($categoryId) {
                $qQuery->where('category_id', $categoryId);
                $aQuery->whereHas('question', function($q) use ($categoryId) {
                    $q->where('category_id', $categoryId);
                });
            }

            $questions = $qQuery->get()->groupBy('qu_image');
            $answers = $aQuery->get()->groupBy('answer_image');

            $qDir = public_path('upload/questions/images/');
            $aDir = public_path('upload/answers/images/');

            $totalRenamed = 0;

            // 1. Sanitize Questions
            if (\Illuminate\Support\Facades\File::isDirectory($qDir)) {
                foreach ($questions as $oldName => $group) {
                    $decoded = rawurldecode($oldName);
                    $dirname = pathinfo($decoded, PATHINFO_DIRNAME);
                    $basename = pathinfo($decoded, PATHINFO_FILENAME);
                    $extension = pathinfo($decoded, PATHINFO_EXTENSION);

                    $cleanBasename = \Illuminate\Support\Str::slug($basename);
                    if (empty($cleanBasename)) {
                        $cleanBasename = 'img_' . uniqid();
                    }

                    $sanitizedName = $cleanBasename . ($extension ? '.' . strtolower($extension) : '');
                    $sanitized = ($dirname && $dirname !== '.') ? $dirname . '/' . $sanitizedName : $sanitizedName;

                    if ($decoded === $sanitized && $oldName === $sanitized) {
                        continue;
                    }

                    $physicalPath = $this->findPhysicalFileForAjax($qDir, $oldName);
                    
                    if ($physicalPath) {
                        $parentDir = dirname($physicalPath);
                        if (!is_writable($parentDir)) {
                            @chmod($parentDir, 0775);
                        }

                        $newName = $sanitized;
                        $counter = 1;
                        while (\Illuminate\Support\Facades\File::exists($qDir . $newName) && $newName !== $oldName) {
                            $newName = ($dirname && $dirname !== '.') 
                                ? $dirname . '/' . $cleanBasename . '-' . $counter . ($extension ? '.' . strtolower($extension) : '')
                                : $cleanBasename . '-' . $counter . ($extension ? '.' . strtolower($extension) : '');
                            $counter++;
                        }

                        $targetParentDir = dirname($qDir . $newName);
                        \Illuminate\Support\Facades\File::ensureDirectoryExists($targetParentDir);
                        if (!is_writable($targetParentDir)) {
                            @chmod($targetParentDir, 0775);
                        }

                        if (\Illuminate\Support\Facades\File::move($physicalPath, $qDir . $newName)) {
                            Question::where('qu_image', $oldName)->update(['qu_image' => $newName]);
                            $totalRenamed++;
                        }
                    } else {
                        // File missing on disk, update DB reference anyway to the sanitized name
                        Question::where('qu_image', $oldName)->update(['qu_image' => $sanitized]);
                    }
                }
            }

            // 2. Sanitize Answers
            if (\Illuminate\Support\Facades\File::isDirectory($aDir)) {
                foreach ($answers as $oldName => $group) {
                    $decoded = rawurldecode($oldName);
                    $dirname = pathinfo($decoded, PATHINFO_DIRNAME);
                    $basename = pathinfo($decoded, PATHINFO_FILENAME);
                    $extension = pathinfo($decoded, PATHINFO_EXTENSION);

                    $cleanBasename = \Illuminate\Support\Str::slug($basename);
                    if (empty($cleanBasename)) {
                        $cleanBasename = 'ans_' . uniqid();
                    }

                    $sanitizedName = $cleanBasename . ($extension ? '.' . strtolower($extension) : '');
                    $sanitized = ($dirname && $dirname !== '.') ? $dirname . '/' . $sanitizedName : $sanitizedName;

                    if ($decoded === $sanitized && $oldName === $sanitized) {
                        continue;
                    }

                    $physicalPath = $this->findPhysicalFileForAjax($aDir, $oldName);
                    
                    if ($physicalPath) {
                        $parentDir = dirname($physicalPath);
                        if (!is_writable($parentDir)) {
                            @chmod($parentDir, 0775);
                        }

                        $newName = $sanitized;
                        $counter = 1;
                        while (\Illuminate\Support\Facades\File::exists($aDir . $newName) && $newName !== $oldName) {
                            $newName = ($dirname && $dirname !== '.') 
                                ? $dirname . '/' . $cleanBasename . '-' . $counter . ($extension ? '.' . strtolower($extension) : '')
                                : $cleanBasename . '-' . $counter . ($extension ? '.' . strtolower($extension) : '');
                            $counter++;
                        }

                        $targetParentDir = dirname($aDir . $newName);
                        \Illuminate\Support\Facades\File::ensureDirectoryExists($targetParentDir);
                        if (!is_writable($targetParentDir)) {
                            @chmod($targetParentDir, 0775);
                        }

                        if (\Illuminate\Support\Facades\File::move($physicalPath, $aDir . $newName)) {
                            \App\Models\Answer::where('answer_image', $oldName)->update(['answer_image' => $newName]);
                            $totalRenamed++;
                        }
                    } else {
                        // File missing on disk, update DB reference anyway to the sanitized name
                        \App\Models\Answer::where('answer_image', $oldName)->update(['answer_image' => $sanitized]);
                    }
                }
            }

            return response()->json([
                'success' => true,
                'message' => 'تم ضبط وتصحيح ' . $totalRenamed . ' اسم صورة بنجاح في قاعدة البيانات وعلى السيرفر!',
            ]);
        }

        protected function findPhysicalFileForAjax($dir, $filename)
        {
            $decoded = rawurldecode($filename);
            $paths = [
                $dir . $filename,
                $dir . $decoded
            ];

            if (class_exists('Normalizer')) {
                $paths[] = $dir . \Normalizer::normalize($filename, \Normalizer::FORM_D);
                $paths[] = $dir . \Normalizer::normalize($filename, \Normalizer::FORM_C);
                $paths[] = $dir . \Normalizer::normalize($decoded, \Normalizer::FORM_D);
                $paths[] = $dir . \Normalizer::normalize($decoded, \Normalizer::FORM_C);
            }

            foreach (array_unique($paths) as $path) {
                if (\Illuminate\Support\Facades\File::exists($path)) {
                    return $path;
                }
            }

            return null;
        }


        public function allQuestion(Request $request)
        {
            $query = Question::with(['category', 'mainCategory', 'gameType', 'answers', 'answerQuestionOnlines']);

            // Filter by Category
            if ($request->filled('category_id') && $request->category_id !== 'all') {
                $query->where('category_id', $request->category_id);
            }

            // Filter by Question Type
            if ($request->filled('questions_type') && $request->questions_type !== 'all') {
                $query->where('questions_type', $request->questions_type);
            }

            // Filter by Answer Type
            if ($request->filled('answer_type') && $request->answer_type !== 'all') {
                $query->whereHas('answers', function($q) use ($request) {
                    $q->where('answer_type', $request->answer_type);
                });
            }

            // Filter by Points
            if ($request->filled('points') && $request->points !== 'all') {
                $query->where('qu_points', $request->points);
            }

            // Search
            if ($request->filled('search')) {
                $search = $request->search;
                $query->where(function($q) use ($search) {
                    $q->where('qu_title', 'like', "%{$search}%")
                      ->orWhere('qu_title_en', 'like', "%{$search}%")
                      ->orWhereHas('category', function($q2) use ($search) {
                          $q2->where('category_name', 'like', "%{$search}%");
                      });
                });
            }

            // Sort
            if ($request->filled('sort_by') && $request->sort_by === 'oldest') {
                $query->orderBy('id', 'asc');
            } else {
                $query->orderBy('id', 'desc');
            }

            $perPage = 20;
            $scrollToId = $request->get('scroll_to');
            $questions = $query->paginate($perPage);

            if ($request->ajax()) {
                $html = view('admin.question.partials.questions_rows', compact('questions'))->render();
                return response()->json([
                    'html' => $html,
                    'next_page' => $questions->nextPageUrl()
                ]);
            }

            $categories = Category::with('mainCategory')->orderBy('category_name', 'asc')->get();

            return view('admin.question.all_question', compact('questions', 'categories', 'scrollToId'));
        }

        public function updateTimingsByPoints(Request $request)
        {
            $request->validate([
                'points' => 'required|in:200,400,600',
                'time_counter' => 'nullable|integer',
                'time_counter_online' => 'nullable|integer',
            ]);

            $points = $request->points;

            Question::where('qu_points', $points)->update([
                'time_counter' => $request->time_counter,
                'time_counter_online' => $request->time_counter_online,
            ]);

            return response()->json([
                'success' => true,
                'message' => 'تم تعديل توقيتات الأسئلة ذات القيمة ' . $points . ' بنجاح.'
            ]);
        }



//         public function editQuestionStore(Request $request)
//         {



//              $request->validate([


//                 'game_type_id' => 'required|not_in:non',
//                 'main_category_id' => 'required|not_in:non',


//         'qu_title' => 'required|string|max:255',
//                 'qu_title_en' => 'required|string|max:255',

//         'qu_points' => 'required|integer',
//         'qu_points_online' => 'required|integer',

//         'questions_type' => 'required|string|in:text,image,sound,video',
//         'time_counter' => 'nullable|integer',
//          'time_counter_online' => 'nullable|integer',

//         'questionsـfile' => 'nullable|file|max:30720', // Increased size for videos
//         'answer_title' => 'required|string|max:255',
//                 'answer_title_en' => 'required|string|max:255',

//         'answer_type' => 'required|string|in:text,image,sound,video',
//         'answerـfile' => 'nullable|file|max:30720',
//         'category_id' => 'required|not_in:non',
//         'time_counter' => 'nullable|integer',



//                 'answer_title_one' => 'required|string|max:255',
//                 'answer_title_two' => 'required|string|max:255',
//                  'answer_title_three' => 'required|string|max:255',
//                 'answer_title_four' => 'required|string|max:255',



//                   'answer_title_one_en' => 'required|string|max:255',
//                 'answer_title_two_en' => 'required|string|max:255',
//                  'answer_title_three_en' => 'required|string|max:255',
//                 'answer_title_four_en' => 'required|string|max:255',






//     ], [
//         'qu_title.required' => 'يرجى إدخال عنوان السؤال.',
//         'qu_title.string' => 'عنوان السؤال يجب أن يكون نصًا.',
//         'qu_title.max' => 'عنوان السؤال يجب أن لا يتجاوز 255 حرفًا.',

//           'qu_title_en.required' => ' يرجى إدخال عنوان السؤال بالانجليزية.',
//         'qu_title_en.string' => 'عنوان السؤال يجب أن يكون نصًا.',
//         'qu_title_en.max' => 'عنوان السؤال يجب أن لا يتجاوز 255 حرفًا.',

//         'qu_points.required' => 'يرجى إدخال نقاط السؤال.',
//         'qu_points.integer' => 'نقاط السؤال يجب أن تكون عددًا صحيحًا.',
//         'time_counter.integer' => 'الرجاء التأكد ان القيمة عدد صحيح',


//         'qu_points_online.required' => 'يرجى إدخال نقاط سؤال OnLine.',
//         'qu_points_online.integer' => 'نقاط السؤال يجب أن تكون عددًا صحيحًا.',
//         'time_counter_online.integer' => 'الرجاء التأكد ان القيمة عدد صحيح',

//         'questions_type.required' => 'يرجى اختيار نوع السؤال.',
//         'questions_type.in' => 'نوع السؤال يجب أن يكون نصي، صورة، صوتي أو فيديو.',

//         'questionsـfile.file' => 'يرجى رفع ملف صالح.',
//         'questionsـfile.max' => 'حجم الملف يجب أن لا يتجاوز 30 ميجابايت.',

//         'answer_title.required' => 'يرجى إدخال عنوان الإجابة.',
//         'answer_title.string' => 'عنوان الإجابة يجب أن يكون نصًا.',
//         'answer_title.max' => 'عنوان الإجابة يجب أن لا يتجاوز 255 حرفًا.',


//          'answer_title_en.required' => 'يرجى إدخال عنوان الإجابة بالانجليزية. ',
//         'answer_title_en.string' => 'عنوان الإجابة يجب أن يكون نصًا.',
//         'answer_title_en.max' => 'عنوان الإجابة يجب أن لا يتجاوز 255 حرفًا.',

//         'answer_type.required' => 'يرجى اختيار نوع الإجابة.',
//         'answer_type.in' => 'نوع الإجابة يجب أن يكون نصي، صورة، صوتي أو فيديو.',

//         'answerـfile.file' => 'يرجى رفع ملف صالح للإجابة.',
//         'answerـfile.max' => 'حجم الملف يجب أن لا يتجاوز 30 ميجابايت.',

//         'category_id.required' => 'الرجاء اختيار الفئة الفرعية.',
//         'category_id.not_in' => 'الرجاء اختيار الفئة الفرعية.',


//          'game_type_id.required' => 'الرجاء اختيار نوع اللعبة.',
//         'game_type_id.not_in' => 'الرجاء اختيار نوع اللعبة.',



//          'main_category_id.required' => 'الرجاء اختيار الفئة الرئيسية.',
//         'main_category_id.not_in' => 'الرجاء اختيار الفئة الرئيسية.',



//                  'answer_title_one.required' => 'يرجى إدخال عنوان الإجابة .',

//                  'answer_title_two.required' => 'يرجى إدخال عنوان الإجابة .',
//                  'answer_title_three.required' => 'يرجى إدخال عنوان الإجابة .',
//                  'answer_title_four.required' => 'يرجى إدخال عنوان الإجابة .',


//                 'answer_title_one_en.required' => 'يرجى إدخال عنوان الإجابة بالانجليزية. ',
//                 'answer_title_two_en.required' => 'يرجى إدخال عنوان الإجابة بالانجليزية. ',
//                 'answer_title_three_en.required' => 'يرجى إدخال عنوان الإجابة بالانجليزية. ',
//                 'answer_title_four_en.required' => 'يرجى إدخال عنوان الإجابة بالانجليزية. ',


//     ]);







//             // return "done";
//             $question_id = $request->question_id;
//             $answer_id = $request->answer_id;



// $answer_id_one = $request->answer_id_one;
// $answer_id_two = $request->answer_id_two;
// $answer_id_three = $request->answer_id_three;
// $answer_id_four = $request->answer_id_four;








//             // if is exsite get old qustion image
//             $old_question_image = $request->old_question_image;


//            // if is exsite get old answer image
//             $old_answer_image = $request->old_answer_image;



//                 // if is exsite get old qustion sound
//                 $old_question_sound = $request->old_question_sound;
//                 // if is exsite get old answer sound
//                 $old_answer_sound = $request->old_answer_sound;





//                   // if is exsite get old qustion video
//                 $old_question_video = $request->old_question_video;
//                 // if is exsite get old answer sound
//                 $old_answer_video = $request->old_answer_video;










//             $question = Question::findOrFail($question_id);


//              $question->category_id = $request->category_id;

//             $question->game_type_id = $request->game_type_id;

//             $question->main_category_id = $request->main_category_id;


//                          $question->qu_title = $request->qu_title;
//                         $question->qu_title_en = $request->qu_title_en;



//              $question->time_counter = $request->time_counter;

//              $question->time_counter_online = $request->time_counter_online;




//              if($old_question_video == "")
//              {
//                  $questionVideo = null;

//              }
//              else
//              {
//                  $questionVideo = $old_question_video;


//              }


//                 // Initialize variables for files

//                 if($old_question_image == "")
//                 {
//                     $questionImage = null;

//                 }
//                 else
//                 {
//                     $questionImage = $old_question_image;


//                 }



//                 if($old_question_sound == "")
//                 {
//                     $questionSound = null;

//                 }
//                 else
//                 {
//                     $questionSound = $old_question_sound;


//                 }

//                 // Handle file upload for question
//                 if ($request->questions_type !== 'text' && $request->hasFile('questionsـfile')) {


//                     $questionFile = $request->file('questionsـfile');
//                     $extension = strtolower($questionFile->getClientOriginalExtension());
//                     $filename = date('YmdHi') . '_' . uniqid() . '.' . $extension;

//                     // Validate file type based on selected question type
//                     if ($request->questions_type == 'image' && in_array($extension, ['jpg', 'jpeg', 'png'])) {
//                         $questionFile->move(public_path('upload/questions/images'), $filename);
//                         $questionImage = $filename;


//                         $path = 'upload/questions/images/'.$old_question_image;
//                         $pathSound = 'upload/questions/sounds/'.$old_question_sound;
//                         $pathVideo = 'upload/questions/videos/'.$old_question_video;


//                             if (file_exists($path) && $old_question_image != "" ) {
//                                             unlink($path);
//                                 }

//                                 if (file_exists($pathSound) && $old_question_sound != "" ) {
//                                     unlink($pathSound);
//                         }

//                         if (file_exists($pathVideo) && $old_question_video != "" ) {
//                             unlink($pathVideo);
//                 }


//                                 $question->qu_image = $questionImage;


//                     }


//                     else if ($request->questions_type == 'video' && in_array($extension, ['mp4', 'mov'])) {
//                         $questionFile->move(public_path('upload/questions/videos'), $filename);
//                         $questionVideo = $filename;


//                         $path = 'upload/questions/images/'.$old_question_image;
//                         $pathSound = 'upload/questions/sounds/'.$old_question_sound;
//                         $pathVideo = 'upload/questions/videos/'.$old_question_video;


//                             if (file_exists($path) && $old_question_image != "" ) {
//                                             unlink($path);
//                                 }

//                                 if (file_exists($pathSound) && $old_question_sound != "" ) {
//                                     unlink($pathSound);
//                         }

//                         if (file_exists($pathVideo) && $old_question_video != "" ) {
//                             unlink($pathVideo);
//                 }


//                                 $question->qu_video = $questionVideo;


//                     }

//                     else if ($request->questions_type == 'sound' && in_array($extension, ['mp3', 'wav'])) {




//                         $path = 'upload/questions/images/'.$old_question_image;
//                         $pathSound = 'upload/questions/sounds/'.$old_question_sound;
//                         $pathVideo = 'upload/questions/videos/'.$old_question_video;


//                             if (file_exists($path) && $old_question_image != "" ) {
//                                             unlink($path);
//                                 }

//                                 if (file_exists($pathSound) && $old_question_sound != "" ) {
//                                     unlink($pathSound);
//                         }

//                         if (file_exists($pathVideo) && $old_question_video != "" ) {
//                             unlink($pathVideo);
//                 }

//                         $questionFile->move(public_path('upload/questions/sounds'), $filename);
//                         $questionSound = $filename;

//                         $question->qu_sound = $questionSound;

//                     } else {

//                         $notification = array(
//                             'message' => 'نوع الملف غير صالح لنوع السؤال المحدد.',
//                             'alert-type' => 'error'
//                         );


//                         return back()->with($notification);
//                     }
//                 }

//                 if($request->questions_type == 'text')
//                 {



//                     $path = 'upload/questions/images/'.$old_question_image;
//                     $pathSound = 'upload/questions/sounds/'.$old_question_sound;
//                     $pathVideo = 'upload/questions/videos/'.$old_question_video;


//                         if (file_exists($path) && $old_question_image != "" ) {
//                                         unlink($path);
//                             }

//                             if (file_exists($pathSound) && $old_question_sound != "" ) {
//                                 unlink($pathSound);
//                     }
//                     if (file_exists($pathVideo) && $old_question_video != "" ) {
//                         unlink($pathVideo);
//             }

//                 }



//                 $question->qu_points = $request->qu_points;


//                  $question->qu_points_online = $request->qu_points_online;


//                 $question->questions_type = $request->questions_type;

//                 $question->save();





//                 //// this for answer
//                 $answer = Answer::findOrFail($answer_id);


//                 $answer->answer_title = $request->answer_title;
//                 $answer->answer_title_en = $request->answer_title_en;





//         $answer_one = AnswerQuestionOnline::findOrFail($answer_id_one);
//         $answer_two = AnswerQuestionOnline::findOrFail($answer_id_two);
//         $answer_three = AnswerQuestionOnline::findOrFail($answer_id_three);
//         $answer_four = AnswerQuestionOnline::findOrFail($answer_id_four);


//                 $answer_one->answer_title = $request->answer_title_one;
//                 $answer_two->answer_title = $request->answer_title_two;
//                 $answer_three->answer_title = $request->answer_title_three;
//                 $answer_four->answer_title = $request->answer_title_four;


//                 $answer_one->answer_title_en = $request->answer_title_one_en;
//                 $answer_two->answer_title_en = $request->answer_title_two_en;
//                 $answer_three->answer_title_en = $request->answer_title_three_en;
//                 $answer_four->answer_title_en = $request->answer_title_four_en;

//                     $answer_one->save();
//                     $answer_two->save();
//                     $answer_three->save();
//                     $answer_four->save();



//       // Initialize variables for files
//       $answerImage = null;
//       $answerSound = null;
//       $answerVideo = null;



//       if($old_answer_video == "")
//       {
//           $answerVideo = null;

//       }
//       else
//       {
//           $answerVideo = $old_answer_video;


//       }

//       if($old_answer_image == "")
//       {
//           $answerImage = null;

//       }
//       else
//       {
//           $answerImage = $old_answer_image;


//       }



//       if($old_answer_sound == "")
//       {
//           $answerSound = null;

//       }
//       else
//       {
//           $answerSound = $old_answer_sound;


//       }


//                 /////


//                     // Handle file upload for answer
//             if ($request->answer_type !== 'text' && $request->hasFile('answerـfile')) {
//                 $answerFile = $request->file('answerـfile');
//                 $extension = strtolower($answerFile->getClientOriginalExtension());
//                 $filename = date('YmdHi') . '_' . uniqid() . '.' . $extension;

//                 // Validate file type based on selected answer type
//                 if ($request->answer_type == 'image' && in_array($extension, ['jpg', 'jpeg', 'png'])) {
//                     $answerFile->move(public_path('upload/answers/images'), $filename);
//                     $answerImage = $filename;


//                     $path = 'upload/answers/images/'.$old_answer_image;
//                     $pathSound = 'upload/answers/sounds/'.$old_answer_sound;
//                     $pathVideo = 'upload/answers/videos/'.$old_answer_video;


//                         if (file_exists($path) && $old_answer_image != "" ) {
//                                         unlink($path);
//                             }

//                             if (file_exists($pathSound) && $old_answer_sound != "" ) {
//                                 unlink($pathSound);
//                     }
//                     if (file_exists($pathVideo) && $old_answer_video != "" ) {
//                         unlink($pathVideo);
//                                  }

//                             $answer->answer_image = $answerImage;



//                 }

//                else if ($request->answer_type == 'video' && in_array($extension, ['mp4', 'mov'])) {
//                     $answerFile->move(public_path('upload/answers/videos'), $filename);
//                     $answerVideo = $filename;


//                     $path = 'upload/answers/images/'.$old_answer_image;
//                     $pathSound = 'upload/answers/sounds/'.$old_answer_sound;
//                     $pathVideo = 'upload/answers/videos/'.$old_answer_video;


//                         if (file_exists($path) && $old_answer_image != "" ) {
//                                         unlink($path);
//                             }

//                             if (file_exists($pathSound) && $old_answer_sound != "" ) {
//                                 unlink($pathSound);
//                     }
//                     if (file_exists($pathVideo) && $old_answer_video != "" ) {
//                         unlink($pathVideo);
//                                  }

//                             $answer->answer_video = $answerVideo;



//                 }



//                 else if ($request->answer_type == 'sound' && in_array($extension, ['mp3', 'wav'])) {
//                     $answerFile->move(public_path('upload/answers/sounds'), $filename);
//                     $answerSound = $filename;


//                     $path = 'upload/answers/images/'.$old_answer_image;
//                     $pathSound = 'upload/answers/sounds/'.$old_answer_sound;
//                     $pathVideo = 'upload/answers/videos/'.$old_answer_video;


//                         if (file_exists($path) && $old_answer_image != "" ) {
//                                         unlink($path);
//                             }

//                             if (file_exists($pathSound) && $old_answer_sound != "" ) {
//                                 unlink($pathSound);
//                     }
//                     if (file_exists($pathVideo) && $old_answer_video != "" ) {
//                         unlink($pathVideo);
//                                  }

//                             $answer->answer_sound = $answerSound;



//                 } else {

//                     $notification = array(
//                         'message' => 'نوع الملف غير صالح لنوع الإجابة المحدد.',
//                         'alert-type' => 'error'
//                     );


//                     return back()->with($notification);
//                 }
//             }


//             if($request->answer_type == 'text')
//             {



//                 $path = 'upload/answers/images/'.$old_answer_image;
//                 $pathSound = 'upload/answers/sounds/'.$old_answer_sound;
//                 $pathVideo = 'upload/answers/videos/'.$old_answer_video;


//                     if (file_exists($path) && $old_answer_image != "" ) {
//                             unlink($path);
//                         }

//                         if (file_exists($pathSound) && $old_answer_sound != "" ) {
//                             unlink($pathSound);
//                 }   if (file_exists($pathVideo) && $old_answer_video != "" ) {
//                             unlink($pathVideo);
//                              }

//             }


//                 /////

//                 $answer->answer_type = $request->answer_type;

//                 $answer->save();



//                 $notification = array(
//                     'message' => 'تم تعديل السؤال',
//                     'alert-type' => 'success'
//                 );


//                 return redirect()->route('all.question')->with($notification);;

//                 // return redirect()->back()->with($notification);;


















//         }

public function editQuestionStore(Request $request)
{
    $request->validate([
        'question_id' => 'required|exists:questions,id',
        'answer_id' => 'required|exists:answers,id',
        'answer_id_one' => 'required|exists:answer_question_onlines,id',
        'answer_id_two' => 'required|exists:answer_question_onlines,id',
        'answer_id_three' => 'required|exists:answer_question_onlines,id',
        'answer_id_four' => 'required|exists:answer_question_onlines,id',

        'game_type_id' => 'required|not_in:non',
        'main_category_id' => 'required|not_in:non',
        'category_id' => 'required|not_in:non',

        'qu_title' => 'nullable|string|max:255',
        'qu_title_en' => 'nullable|string|max:255',

        'qu_points' => 'required|integer',
        'qu_points_online' => 'required|integer',

        'questions_type' => 'required|string|in:text,image,sound,video',
        'time_counter' => 'nullable|integer',
        'time_counter_online' => 'nullable|integer',

        'questionsـfile' => 'nullable|file|max:30720', // Increased size for videos

        'answer_title' => 'nullable|string|max:255',
        'answer_title_en' => 'nullable|string|max:255',
        'answer_type' => 'required|string|in:text,image,sound,video',
        'answerـfile' => 'nullable|file|max:30720',

        'answer_title_one' => 'nullable|string|max:255',
        'answer_title_two' => 'nullable|string|max:255',
        'answer_title_three' => 'nullable|string|max:255',
        'answer_title_four' => 'nullable|string|max:255',

        'answer_title_one_en' => 'nullable|string|max:255',
        'answer_title_two_en' => 'nullable|string|max:255',
        'answer_title_three_en' => 'nullable|string|max:255',
        'answer_title_four_en' => 'nullable|string|max:255',
    ]);

    // استبدال القيم الفارغة بـ 'non' للحقول الاختيارية
    $nullableFields = [
        'qu_title', 'qu_title_en',
        'qu_hint', 'qu_hint_en',
        'answer_title', 'answer_title_en',
        'answer_title_one', 'answer_title_one_en',
        'answer_title_two', 'answer_title_two_en',
        'answer_title_three', 'answer_title_three_en',
        'answer_title_four', 'answer_title_four_en',
    ];
    $mergeData = [];
    foreach ($nullableFields as $field) {
        if (empty(trim($request->input($field, '')))) {
            $mergeData[$field] = 'non';
        }
    }
    if (!empty($mergeData)) {
        $request->merge($mergeData);
    }

    DB::beginTransaction();
    try {
        $question = Question::findOrFail($request->question_id);

        // حفظ بيانات السؤال
        $question->update([
            'qu_title' => $request->qu_title,
            'qu_title_en' => $request->qu_title_en,
            'game_type_id' => $request->game_type_id,
            'main_category_id' => $request->main_category_id,
            'category_id' => $request->category_id,
            'qu_points' => $request->qu_points,
            'qu_points_online' => $request->qu_points_online,
            'questions_type' => $request->questions_type,
            'time_counter' => $request->time_counter,
            'time_counter_online' => $request->time_counter_online,
               'coins_number' => $request->coins_number,
            'game_coin_id' => $request->game_coin_id,

              'qu_hint' => $request->qu_hint,
            'qu_hint_en' => $request->qu_hint_en,
        ]);

        // ملفات السؤال
        $questionImage = $questionSound = $questionVideo = null;

        if ($request->questions_type !== 'text' && $request->hasFile('questionsـfile')) {
            $questionFile = $request->file('questionsـfile');
            $ext = strtolower($questionFile->getClientOriginalExtension());
            $filename = date('YmdHi') . '_' . uniqid() . '.' . $ext;

            if ($request->questions_type == 'image' && in_array($ext, ['jpg','jpeg','png'])) {
                $questionFile->move(public_path('upload/questions/images'), $filename);
                $questionImage = $filename;
            } elseif ($request->questions_type == 'sound' && in_array($ext, ['mp3','wav'])) {
                $questionFile->move(public_path('upload/questions/sounds'), $filename);
                $questionSound = $filename;
            } elseif ($request->questions_type == 'video' && in_array($ext, ['mp4','avi','mov'])) {
                $questionFile->move(public_path('upload/questions/videos'), $filename);
                $questionVideo = $filename;
            } else {
                return back()->with(['message'=>'نوع الملف غير صالح لنوع السؤال المحدد.','alert-type'=>'error']);
            }

            // حذف الملفات القديمة
            if ($question->qu_image && file_exists(public_path('upload/questions/images/' . $question->qu_image))) unlink(public_path('upload/questions/images/' . $question->qu_image));
            if ($question->qu_sound && file_exists(public_path('upload/questions/sounds/' . $question->qu_sound))) unlink(public_path('upload/questions/sounds/' . $question->qu_sound));
            if ($question->qu_video && file_exists(public_path('upload/questions/videos/' . $question->qu_video))) unlink(public_path('upload/questions/videos/' . $question->qu_video));

            $question->update([
                'qu_image' => $questionImage,
                'qu_sound' => $questionSound,
                'qu_video' => $questionVideo,
            ]);
        }

        // تعديل الإجابة Local
        $answer = Answer::findOrFail($request->answer_id);
        // $answerImage = $answerSound = $answerVideo = null;


        $answerImage = $request->old_answer_image;
        $answerSound = $request->old_answer_sound;
        $answerVideo = $request->old_answer_video;


        if ($request->answer_type !== 'text' && $request->hasFile('answerـfile')) {
            $answerFile = $request->file('answerـfile');
            $ext = strtolower($answerFile->getClientOriginalExtension());
            $filename = date('YmdHi') . '_' . uniqid() . '.' . $ext;

            if ($request->answer_type == 'image' && in_array($ext, ['jpg','jpeg','png'])) {
                $answerFile->move(public_path('upload/answers/images'), $filename);
                $answerImage = $filename;
            } elseif ($request->answer_type == 'sound' && in_array($ext, ['mp3','wav'])) {
                $answerFile->move(public_path('upload/answers/sounds'), $filename);
                $answerSound = $filename;
            } elseif ($request->answer_type == 'video' && in_array($ext, ['mp4','avi','mov'])) {
                $answerFile->move(public_path('upload/answers/videos'), $filename);
                $answerVideo = $filename;
            } else {
                return back()->with(['message'=>'نوع الملف غير صالح لنوع الإجابة المحدد.','alert-type'=>'error']);
            }

            // حذف الملفات القديمة
            if ($answer->answer_image && file_exists(public_path('upload/answers/images/' . $answer->answer_image))) unlink(public_path('upload/answers/images/' . $answer->answer_image));
            if ($answer->answer_sound && file_exists(public_path('upload/answers/sounds/' . $answer->answer_sound))) unlink(public_path('upload/answers/sounds/' . $answer->answer_sound));
            if ($answer->answer_video && file_exists(public_path('upload/answers/videos/' . $answer->answer_video))) unlink(public_path('upload/answers/videos/' . $answer->answer_video));
        }

        $answer->update([
            'answer_title' => $request->answer_title,
            'answer_title_en' => $request->answer_title_en,
            'answer_type' => $request->answer_type,
            'answer_image' => $answerImage,
            'answer_sound' => $answerSound,
            'answer_video' => $answerVideo,
        ]);

        // تعديل Online Answers
$onlineAnswers = [
    ['model' => AnswerQuestionOnline::findOrFail($request->answer_id_one), 'title' => 'answer_title_one', 'title_en' => 'answer_title_one_en', 'file' => 'answer_file_one', 'type' => 'answer_type', 'is_correct' => true],
    ['model' => AnswerQuestionOnline::findOrFail($request->answer_id_two), 'title' => 'answer_title_two', 'title_en' => 'answer_title_two_en', 'file' => 'answer_file_two', 'type' => 'answer_type_two', 'is_correct' => false],
    ['model' => AnswerQuestionOnline::findOrFail($request->answer_id_three), 'title' => 'answer_title_three', 'title_en' => 'answer_title_three_en', 'file' => 'answer_file_three', 'type' => 'answer_type_three', 'is_correct' => false],
    ['model' => AnswerQuestionOnline::findOrFail($request->answer_id_four), 'title' => 'answer_title_four', 'title_en' => 'answer_title_four_en', 'file' => 'answer_file_four', 'type' => 'answer_type_four', 'is_correct' => false],
];

foreach ($onlineAnswers as $index => $item) {
    $model = $item['model'];
    $fileInput = $item['file'];
    $typeInput = $item['type'];

    // استخدم القيم القديمة من الفورم
    $answerImage = $request->input('old_answer_image_' . ($index + 1));
    $answerSound = $request->input('old_answer_sound_' . ($index + 1));
    $answerVideo = $request->input('old_answer_video_' . ($index + 1));

    // لو تم رفع ملف جديد
    if ($request->hasFile($fileInput)) {
        $file = $request->file($fileInput);
        $ext = strtolower($file->getClientOriginalExtension());
        $filename = date('YmdHi') . '_' . uniqid() . '.' . $ext;

        if ($request->input($typeInput) == 'image' && in_array($ext, ['jpg','jpeg','png'])) {
            $file->move(public_path('upload/answers/online/images'), $filename);
            if ($answerImage && file_exists(public_path('upload/answers/online/images/' . $answerImage))) {
                unlink(public_path('upload/answers/online/images/' . $answerImage));
            }
            $answerImage = $filename;

        } elseif ($request->input($typeInput) == 'sound' && in_array($ext, ['mp3','wav'])) {
            $file->move(public_path('upload/answers/online/sounds'), $filename);
            if ($answerSound && file_exists(public_path('upload/answers/online/sounds/' . $answerSound))) {
                unlink(public_path('upload/answers/online/sounds/' . $answerSound));
            }
            $answerSound = $filename;

        } elseif ($request->input($typeInput) == 'video' && in_array($ext, ['mp4','avi','mov'])) {
            $file->move(public_path('upload/answers/online/videos'), $filename);
            if ($answerVideo && file_exists(public_path('upload/answers/online/videos/' . $answerVideo))) {
                unlink(public_path('upload/answers/online/videos/' . $answerVideo));
            }
            $answerVideo = $filename;

        } else {
            return back()->with(['message' => 'نوع الملف غير صالح لنوع الإجابة المحدد.', 'alert-type' => 'error']);
        }
    }

    // تحديث السجلات
    $model->update([
        'answer_title' => $request->input($item['title']),
        'answer_title_en' => $request->input($item['title_en']),
        'answer_type' => $request->input($typeInput),
        'answer_image' => $answerImage,
        'answer_sound' => $answerSound,
        'answer_video' => $answerVideo,
        'is_correct' => $item['is_correct'],
    ]);
}



        DB::commit();
        return redirect()->route('all.question', ['scroll_to' => $request->question_id])->with(['message' => 'تم تعديل السؤال بنجاح', 'alert-type' => 'success']);

    } catch (\Exception $e) {
        DB::rollBack();
        return back()->with(['message' => $e->getMessage(), 'alert-type' => 'error']);
    }
}



        public function deleteQuestion($id)
        {

            $question = Question::findOrFail($id);

            $old_question_image = $question->qu_image ?? "";
            $old_question_sound = $question->qu_sound ?? "";



            $old_answer_image = $question->answers->first()->answer_image ?? "";

            $old_answer_sound = $question->answers->first()->answer_sound ?? "";

            $path = 'upload/answers/images/'.$old_answer_image;
            $pathSound = 'upload/answers/sounds/'.$old_answer_sound;

            if (file_exists($path) && $old_answer_image != "" ) {
                unlink($path);
                    }

    if (file_exists($pathSound) && $old_answer_sound != "" ) {
        unlink($pathSound);
}




$pathQ = 'upload/questions/images/'.$old_question_image;
$pathSoundQ = 'upload/questions/sounds/'.$old_question_sound;


    if (file_exists($pathQ) && $old_question_image != "" ) {
                    unlink($pathQ);
        }

        if (file_exists($pathSoundQ) && $old_question_sound != "" ) {
            unlink($pathSoundQ);
}


            $question->delete();
            $notification = array(
                'message' => 'تم حذف السؤال',
                'alert-type' => 'success'
            );
            return redirect()->route('all.question')->with($notification);
        }

        public function deleteMultipleQuestions(Request $request)
        {
            $ids = $request->ids;
            if ($ids && is_array($ids)) {
                foreach ($ids as $id) {
                    $question = Question::find($id);
                    if ($question) {
                        $old_question_image = $question->qu_image ?? "";
                        $old_question_sound = $question->qu_sound ?? "";
                        $old_question_video = $question->qu_video ?? "";

                        $old_answer_image = $question->answers->first()->answer_image ?? "";
                        $old_answer_sound = $question->answers->first()->answer_sound ?? "";
                        $old_answer_video = $question->answers->first()->answer_video ?? "";

                        $path = 'upload/answers/images/'.$old_answer_image;
                        $pathSound = 'upload/answers/sounds/'.$old_answer_sound;
                        $pathVideo = 'upload/answers/videos/'.$old_answer_video;

                        if (file_exists($path) && $old_answer_image != "") { unlink($path); }
                        if (file_exists($pathSound) && $old_answer_sound != "") { unlink($pathSound); }
                        if (file_exists($pathVideo) && $old_answer_video != "") { unlink($pathVideo); }

                        $pathQ = 'upload/questions/images/'.$old_question_image;
                        $pathSoundQ = 'upload/questions/sounds/'.$old_question_sound;
                        $pathVideoQ = 'upload/questions/videos/'.$old_question_video;

                        if (file_exists($pathQ) && $old_question_image != "") { unlink($pathQ); }
                        if (file_exists($pathSoundQ) && $old_question_sound != "") { unlink($pathSoundQ); }
                        if (file_exists($pathVideoQ) && $old_question_video != "") { unlink($pathVideoQ); }

                        $question->delete();
                    }
                }
                return response()->json(['success' => 'تم حذف الأسئلة المحددة بنجاح!']);
            }
            return response()->json(['error' => 'لم يتم تحديد أي أسئلة!'], 400);
        }


        /// Api



        /* Good Function to get Qustions
        public function getQuestionApi($id)
        {




            $qu = Question::where('category_id', $id)
            ->inRandomOrder() // Get random order
            ->take(6) // Limit to 6 questions
            ->get() // Fetch results
            ->shuffle() // Extra shuffle for randomness
            ->map(function ($question) {
                $question->is_user_answer = false;
                $question->who_answer = 0; // Add field and set to false
                // Add field and set to false
                return $question;
            });

        return response()->json($qu);
        }
        */



        /*
        public function getQuestionApi($id)
{
    // Fetch questions for each qu_points category
    $questions_200 = Question::where('category_id', $id)
        ->where('qu_points', 200)
        ->inRandomOrder()
        ->take(2)
        ->get();

    $questions_400 = Question::where('category_id', $id)
        ->where('qu_points', 400)
        ->inRandomOrder()
        ->take(2)
        ->get();

    $questions_600 = Question::where('category_id', $id)
        ->where('qu_points', 600)
        ->inRandomOrder()
        ->take(2)
        ->get();

    // Merge all question collections
    $qu = $questions_200->merge($questions_400)->merge($questions_600);

    // If less than 6 questions, fill from other available questions
    if ($qu->count() < 6) {
        $extra_questions = Question::where('category_id', $id)
            ->whereNotIn('id', $qu->pluck('id')) // Exclude already selected
            ->inRandomOrder()
            ->take(6 - $qu->count())
            ->get();

        $qu = $qu->merge($extra_questions);
    }

    // Shuffle again for extra randomness
    $qu = $qu->shuffle()->map(function ($question) {
        $question->is_user_answer = false;
        $question->who_answer = 0;
        return $question;
    });

    return response()->json($qu);
}
    */



    /*
    public function getQuestionApi($id)
{
    $questions_200 = Question::where('category_id', $id)
        ->where('qu_points', 200)
        ->inRandomOrder()
        ->take(2)
        ->get();

    $questions_400 = Question::where('category_id', $id)
        ->where('qu_points', 400)
        ->inRandomOrder()
        ->take(2)
        ->get();

    $questions_600 = Question::where('category_id', $id)
        ->where('qu_points', 600)
        ->inRandomOrder()
        ->take(2)
        ->get();

    // Merge all question collections
    $qu = $questions_200->merge($questions_400)->merge($questions_600);

    // Shuffle again for extra randomness
    $qu = $qu->shuffle()->map(function ($question) {
        $question->is_user_answer = false;
        $question->who_answer = 0;
        return $question;
    });

    return response()->json($qu);
}
*/


public function getQuestionApi(Request $request, $id)
{
    $term = $request->query('term');

    // Fetch 2 random questions for each qu_points category
    $query_200 = Question::where('category_id', $id)->where('qu_points', 200);
    $query_400 = Question::where('category_id', $id)->where('qu_points', 400);
    $query_600 = Question::where('category_id', $id)->where('qu_points', 600);

    if ($term === null || $term === 'null' || $term === '' || $term == 1) {
        $query_200->where(function ($q) {
            $q->whereNull('term')->orWhere('term', 1);
        });
        $query_400->where(function ($q) {
            $q->whereNull('term')->orWhere('term', 1);
        });
        $query_600->where(function ($q) {
            $q->whereNull('term')->orWhere('term', 1);
        });
    } else {
        $query_200->where('term', $term);
        $query_400->where('term', $term);
        $query_600->where('term', $term);
    }

    $questions_200 = $query_200->inRandomOrder()->take(2)->get();
    $questions_400 = $query_400->inRandomOrder()->take(2)->get();
    $questions_600 = $query_600->inRandomOrder()->take(2)->get();

    // Merge in the required order: 200, 200, 400, 400, 600, 600
    $qu = $questions_200->merge($questions_400)->merge($questions_600);

    // If less than 6 questions, fill missing ones from other available questions
    // if ($qu->count() < 6) {
    //     $extra_questions = Question::where('category_id', $id)
    //         ->whereNotIn('id', $qu->pluck('id')) // Exclude already selected
    //         ->inRandomOrder()
    //         ->take(6 - $qu->count())
    //         ->get();

    //     $qu = $qu->merge($extra_questions);
    // }

    // Map and return in the correct order
    $qu = $qu->map(function ($question) {
        $question->is_user_answer = false;
        $question->who_answer = 0;
        return $question;
    });

    return response()->json($qu->values()); // Ensure proper indexing
}








        // public function getQuestionAnswerApi($id)
        // {

        //     $answer = Answer::where('question_id', $id)->get()->first();

        // return response()->json($answer);
        // }



         public function getQuestionAnswerApi(Request $request)
        {


        $id = $request->id;

            $answer = Answer::where('question_id', $id)->get()->first();

        return response()->json($answer);
        }



        //   public function getQuestionAnswerOnlineApi(Request $request)
        // {
        // $id = $request->id;

        //     $answer = AnswerQuestionOnline::where('question_id', $id)->get();

        // return response()->json($answer);
        // }


        public function getQuestionAnswerOnlineApi(Request $request)
{
    $id = $request->id;

    // جلب الإجابات بترتيب ثابت (مثلاً حسب id)
    $answers = AnswerQuestionOnline::where('question_id', $id)
        ->orderBy('id')
        ->get();

    // لو في إجابات
    if ($answers->isNotEmpty()) {
        // أول إجابة هي الصحيحة
        $answers->first()->is_correct = 1;

        // باقي الإجابات خطأ
        $answers->skip(1)->each(function ($answer) {
            $answer->is_correct = 0;
        });
    }

    // ترتيب عشوائي قبل الإرجاع
    $answers = $answers->shuffle()->values();

    return response()->json($answers);
}





// public function createGameSessionQuestions(Request $request)
// {

// // return "ss";
//     $sessionId = $request->input('session_id');
//         $categoryId = $request->input('category_id');


//     if (!$sessionId) {
//         return response()->json(['error' => 'session_id is required'], 422);
//     }

//     // هل الأسئلة محفوظة مسبقًا لهذه الجلسة؟
//     $existingQuestions = \DB::table('game_session_questions')
//         ->where('session_id', $sessionId)
//         ->orderBy('order')
//         ->pluck('question_id');

//     if ($existingQuestions->isNotEmpty()) {
//         // جلب نفس الأسئلة
//         $questions = Question::whereIn('id', $existingQuestions)
//             ->get()
//             ->sortBy(function ($q) use ($existingQuestions) {
//                 return $existingQuestions->search($q->id);
//             })
//             ->values();
//     } else {
//         // توليد أسئلة جديدة لأول مرة
//         $questions_200 = Question::where('category_id', $categoryId)
//             ->where('qu_points', 200)
//             ->inRandomOrder()
//             ->take(2)
//             ->get();

//         $questions_400 = Question::where('category_id', $categoryId)
//             ->where('qu_points', 400)
//             ->inRandomOrder()
//             ->take(2)
//             ->get();

//         $questions_600 = Question::where('category_id', $categoryId)
//             ->where('qu_points', 600)
//             ->inRandomOrder()
//             ->take(2)
//             ->get();

//         $questions = $questions_200
//             ->merge($questions_400)
//             ->merge($questions_600)
//             ->values();

//         // حفظ الأسئلة للجلسة
//         foreach ($questions as $index => $question) {
//             \DB::table('game_session_questions')->insert([
//                 'session_id' => $sessionId,
//                 'question_id' => $question->id,
//                 'order' => $index,
//                 'created_at' => now(),
//                 'updated_at' => now(),
//             ]);
//         }
//     }

//     // إضافة القيم الافتراضية
//     $questions = $questions->map(function ($question) {
//         $question->is_user_answer = false;
//         $question->who_answer = 0;
//         return $question;
//     });

//     return response()->json($questions);
// }






// public function createGameSessionQuestions(Request $request)
// {
//     // $sessionId = $request->input('session_id');

//         $sessionId = $request->input('session_id');
//         $categoryId = $request->input('category_id');

//     if (!$sessionId) {
//         return response()->json(['error' => 'session_id is required'], 422);
//     }

//     // 1️⃣ هل هذه الفئة لها أسئلة محفوظة في نفس الجلسة؟
//     $questionIds = DB::table('game_session_question_onlines')
//         ->where('session_id', $sessionId)
//         ->where('category_id', $categoryId)
//         ->orderBy('question_order')
//         ->pluck('question_id');

//     if ($questionIds->isNotEmpty()) {

//         // 2️⃣ جلب نفس الأسئلة بنفس الترتيب
//         $questions = Question::whereIn('id', $questionIds)
//             ->get()
//             ->sortBy(fn ($q) => $questionIds->search($q->id))
//             ->values();

//     } else {

//         // 3️⃣ إنشاء أسئلة جديدة لهذا التصنيف فقط
//         $questions_200 = Question::where('category_id', $categoryId)
//             ->where('qu_points', 200)
//             ->inRandomOrder()
//             ->take(2)
//             ->get();

//         $questions_400 = Question::where('category_id', $categoryId)
//             ->where('qu_points', 400)
//             ->inRandomOrder()
//             ->take(2)
//             ->get();

//         $questions_600 = Question::where('category_id', $categoryId)
//             ->where('qu_points', 600)
//             ->inRandomOrder()
//             ->take(2)
//             ->get();

//         $questions = $questions_200
//             ->merge($questions_400)
//             ->merge($questions_600)
//             ->values();

//         // 4️⃣ حفظها للجلسة + التصنيف
//         foreach ($questions as $index => $question) {
//             DB::table('game_session_question_onlines')->insert([
//                 'session_id'     => $sessionId,
//                 'category_id'    => $categoryId,
//                 'question_id'    => $question->id,
//                 'question_order' => $index,
//                 'created_at'     => now(),
//                 'updated_at'     => now(),
//             ]);
//         }
//     }

//     // 5️⃣ قيم افتراضية
//     $questions = $questions->map(function ($question) {
//         $question->is_user_answer = false;
//         $question->who_answer = 0;
//         return $question;
//     });

//     return response()->json($questions);
// }


/* very important function to get quest

public function createGameSessionQuestions(Request $request)
{
    // $sessionId = $request->input('session_id');

        $sessionId = $request->input('session_id');
        $categoryId = $request->input('category_id');

    if (!$sessionId) {
        return response()->json(['error' => 'session_id is required'], 422);
    }

    // 1️⃣ هل هذه الفئة لها أسئلة محفوظة في نفس الجلسة؟
    $questionIds = DB::table('game_session_question_onlines')
        ->where('session_id', $sessionId)
        ->where('category_id', $categoryId)
        ->orderBy('question_order')
        ->pluck('question_id');

    if ($questionIds->isNotEmpty()) {

        // 2️⃣ جلب نفس الأسئلة بنفس الترتيب
        $questions = Question::whereIn('id', $questionIds)
            ->get()
            ->sortBy(fn ($q) => $questionIds->search($q->id))
            ->values();

    } else {

        // 3️⃣ إنشاء أسئلة جديدة لهذا التصنيف فقط
        $questions_200 = Question::where('category_id', $categoryId)
            ->where('qu_points', 200)
            ->inRandomOrder()
            ->take(1)
            ->get();

        $questions_400 = Question::where('category_id', $categoryId)
            ->where('qu_points', 400)
            ->inRandomOrder()
            ->take(1)
            ->get();

        $questions_600 = Question::where('category_id', $categoryId)
            ->where('qu_points', 600)
            ->inRandomOrder()
            ->take(1)
            ->get();

        $questions = $questions_200
            ->merge($questions_400)
            ->merge($questions_600)
            ->values();

        // 4️⃣ حفظها للجلسة + التصنيف
        foreach ($questions as $index => $question) {
            DB::table('game_session_question_onlines')->insert([
                'session_id'     => $sessionId,
                'category_id'    => $categoryId,
                'question_id'    => $question->id,
                'question_order' => $index,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
        }
    }

    // 5️⃣ قيم افتراضية
    $questions = $questions->map(function ($question) {
        $question->is_user_answer = false;
        $question->who_answer = 0;
        return $question;
    });

    return response()->json($questions);
}


very important function to get qustions 
*/ 



public function createGameSessionQuestions(Request $request)
{
    $sessionId = $request->input('session_id');
    $categoryInput = $request->input('category_id');

    if (!$sessionId) {
        return response()->json(['error' => 'session_id is required'], 422);
    }

    // 1. جلب قائمة الفئات المسجلة في الجلسة لحساب العدد الإجمالي المتوقع (6 أسئلة لكل فئة)
    $existingSessionQuestions = DB::table('game_session_question_onlines')
        ->where('session_id', $sessionId)
        ->orderBy('id')
        ->get();

    // 2. محاولة جلب جميع الفئات المحددة للجلسة من جدول online_game_categories
    $categoryIds = [];
    $gameInfo = DB::table('online_game_infos')
        ->where('game_session_name', $sessionId)
        ->orWhere('id', $sessionId)
        ->first();

    if ($gameInfo) {
        $categoryIds = DB::table('online_game_categories')
            ->where('online_game_info_id', $gameInfo->id)
            ->pluck('category_id')
            ->toArray();
    }

    // في حال لم نجد فئات مسجلة مسبقاً، نعتمد على المدخلات المباشرة من الطلب
    if (empty($categoryIds)) {
        if (is_string($categoryInput)) {
            $decoded = json_decode($categoryInput, true);
            if (is_array($decoded)) {
                $categoryIds = $decoded;
            } elseif (strpos($categoryInput, ',') !== false) {
                $categoryIds = explode(',', $categoryInput);
            } else {
                $categoryIds = [$categoryInput];
            }
        } elseif (is_array($categoryInput)) {
            $categoryIds = $categoryInput;
        } else {
            $categoryIds = [$categoryInput];
        }
    }

    $categoryIds = array_map('trim', $categoryIds);
    $categoryIds = array_filter($categoryIds, function($val) {
        return $val !== null && $val !== '';
    });
    $categoryIds = array_values($categoryIds);
    if (empty($categoryIds)) {
        $categoryIds = [$categoryInput ?: 1];
    }

    // الإجمالي المتوقع = 6 أسئلة × عدد الفئات
    $expectedTotal = count($categoryIds) * 6;

    // الحماية: إذا تم إنشاء الجلسة بالكامل مسبقاً، نُرجع ما يخص الفئة المطلوبة فقط
    if ($existingSessionQuestions->count() >= $expectedTotal) {
        $filteredSessionQuestions = $existingSessionQuestions;
        if ($categoryInput) {
            $filteredSessionQuestions = $existingSessionQuestions->filter(function ($item) use ($categoryInput) {
                return (string)$item->category_id === (string)$categoryInput;
            });
        }

        $responseQuestionIds = $filteredSessionQuestions->pluck('question_id')->toArray();

        $questions = Question::with('answers')->whereIn('id', $responseQuestionIds)
            ->get()
            ->sortBy(fn ($q) => array_search($q->id, $responseQuestionIds))
            ->values();

        $questions = $questions->map(function ($question) {
            $question->is_user_answer = false;
            $question->who_answer = 0;
            return $question;
        });

        return response()->json($questions);
    }

    // تنظيف الجلسة من أي أسئلة قديمة غير مكتملة للبدء من جديد
    if ($existingSessionQuestions->count() > 0) {
        DB::table('game_session_question_onlines')->where('session_id', $sessionId)->delete();
    }

    // 3. بناء قائمة تتبع تكرار الفئات (للتعامل مع الفئات المكررة في اختيارات اللاعبين)
    //    مثال: إذا اختار اللاعبان نفس الفئة مرتين → نجلب 6 أسئلة مختلفة لكل تكرار
    $categoryCounts = []; // عدد مرات ظهور كل فئة في قائمة الاختيارات
    foreach ($categoryIds as $catId) {
        $categoryCounts[$catId] = ($categoryCounts[$catId] ?? 0) + 1;
    }

    $sessionQuestionIds = []; // الأسئلة المُضافة حتى الآن (لتجنب التكرار)
    $currentOrder = 1;
    $insertedQuestions = collect();

    // 4. لكل فئة (حتى لو مكررة): جلب 6 أسئلة موزعة (2×200 + 2×400 + 2×600)
    $processedCatOccurrences = []; // لتتبع كم مرة عالجنا كل فئة

    $userId = $request->input('user_id') ?? ($request->user() ? $request->user()->id : null);
    $userPlayedQuestionIds = [];
    if ($userId) {
        $userPlayedQuestionIds = DB::table('questions_registers')
            ->join('games', 'games.id', '=', 'questions_registers.game_id')
            ->where('games.user_id_created', $userId)
            ->pluck('questions_registers.question_id')
            ->toArray();
    }

    $getTierQuestions = function($catId, $points, $count) use (&$sessionQuestionIds, $userPlayedQuestionIds) {
        $baseQuery = Question::where('category_id', $catId)
            ->where('qu_points', $points)
            ->whereNotIn('id', $sessionQuestionIds);

        if (!empty($userPlayedQuestionIds)) {
            $unplayed = (clone $baseQuery)->whereNotIn('id', $userPlayedQuestionIds)->inRandomOrder()->take($count)->get();
            if ($unplayed->count() < $count) {
                $needed = $count - $unplayed->count();
                $excludeIds = array_merge($sessionQuestionIds, $unplayed->pluck('id')->toArray());
                $playedFallback = Question::where('category_id', $catId)
                    ->where('qu_points', $points)
                    ->whereNotIn('id', $excludeIds)
                    ->inRandomOrder()
                    ->take($needed)
                    ->get();
                return $unplayed->merge($playedFallback);
            }
            return $unplayed;
        }

        return $baseQuery->inRandomOrder()->take($count)->get();
    };

    foreach ($categoryIds as $catId) {
        $processedCatOccurrences[$catId] = ($processedCatOccurrences[$catId] ?? 0) + 1;

        // جلب أسئلة الدرجات المتنوعة مع تفضيل الأسئلة غير الملعوبة
        $q200 = $getTierQuestions($catId, 200, 2);
        $q400 = $getTierQuestions($catId, 400, 2);
        $q600 = $getTierQuestions($catId, 600, 2);

        // دمج الأسئلة بالترتيب: 200، 200، 400، 400، 600، 600
        $catQuestions = $q200->merge($q400)->merge($q600);

        // في حال نقص (فئة بها أسئلة أقل من 6): نكمل من أسئلة الفئة نفسها بأي درجة
        if ($catQuestions->count() < 6) {
            $extra = Question::where('category_id', $catId)
                ->whereNotIn('id', array_merge($sessionQuestionIds, $catQuestions->pluck('id')->toArray()))
                ->inRandomOrder()
                ->take(6 - $catQuestions->count())
                ->get();
            $catQuestions = $catQuestions->merge($extra);
        }

        foreach ($catQuestions as $q) {
            DB::table('game_session_question_onlines')->insert([
                'session_id'     => $sessionId,
                'category_id'    => $catId,
                'question_id'    => $q->id,
                'question_order' => $currentOrder++,
                'created_at'     => now(),
                'updated_at'     => now(),
            ]);
            $sessionQuestionIds[] = $q->id;
            $insertedQuestions->push((object)[
                'question_id' => $q->id,
                'category_id' => $catId,
            ]);
        }
    }

    // 5. تصفية الأسئلة لإرجاع ما يخص الفئة المطلوبة في هذا الاستدعاء فقط
    $filteredQuestions = $insertedQuestions;
    if ($categoryInput) {
        $filteredQuestions = $insertedQuestions->filter(function ($item) use ($categoryInput) {
            return (string)$item->category_id === (string)$categoryInput;
        });
    }
    $filteredQuestionIds = $filteredQuestions->pluck('question_id')->toArray();

    $questions = Question::with('answers')->whereIn('id', $filteredQuestionIds)
        ->get()
        ->sortBy(fn ($q) => array_search($q->id, $filteredQuestionIds))
        ->values();

    $questions = $questions->map(function ($question) {
        $question->is_user_answer = false;
        $question->who_answer = 0;
        return $question;
    });

    return response()->json($questions);
}




// public function getGameSessionQuestions(Request $request)
// {
//     $sessionId = $request->input('session_id');

//     if (!$sessionId) {
//         return response()->json(['error' => 'session_id is required'], 422);
//     }

//     // 1️⃣ جلب كل question_ids الخاصة بالجلسة مرتبة
//     $questionIds = DB::table('game_session_question_onlines')
//         ->where('session_id', $sessionId)
//         ->orderBy('category_id')
//         ->orderBy('question_order')
//         ->pluck('question_id');

//     if ($questionIds->isEmpty()) {
//         return response()->json([]);
//     }

//     // 2️⃣ جلب الأسئلة بنفس الترتيب
//     $questions = Question::whereIn('id', $questionIds)
//         ->get()
//         ->sortBy(fn ($q) => $questionIds->search($q->id))
//         ->values();

//     // 3️⃣ إضافة القيم الافتراضية
//     $questions = $questions->map(function ($question) {
//         $question->is_user_answer = false;
//         $question->who_answer = 0;
//         return $question;
//     });

//     return response()->json($questions);
// }

public function getGameSessionQuestions(Request $request)
{
    $sessionId = $request->input('session_id');

    if (!$sessionId) {
        return response()->json([
            'status' => false,
            'message' => 'session_id is required',
            'data' => null
        ], 422);
    }

    $questions = DB::table('game_session_question_onlines as gsq')
        ->join('questions as q', 'gsq.question_id', '=', 'q.id')
        ->join('categories as c', 'gsq.category_id', '=', 'c.id')
        ->where('gsq.session_id', $sessionId)
        ->orderBy('gsq.id') // الترتيب بالـ id لضمان نفس ترتيب الإدخال
        ->select(
            'q.*',
            'gsq.category_id',
            'gsq.question_order',
            'c.category_name',
            'c.category_name_en',
            'c.category_photo'
        )
        // لا يوجد حد ثابت للأسئلة - العدد يعتمد على عدد الفئات المختارة (6 × عدد الفئات)
        ->get();

    if ($questions->isEmpty()) {
        return response()->json([
            'status' => false,
            'message' => 'No questions found for this session',
            'data' => []
        ]);
    }

    return response()->json([
        'status' => true,
        'message' => 'Game session questions fetched successfully',
        'data' => $questions
    ]);
}

public function getQuestionOnlineApi(Request $request )
{

$id =  $request->id;
    // Fetch 2 random questions for each qu_points category
    $questions_200 = Question::where('category_id', $id)
        ->where('qu_points', 200)
        ->inRandomOrder()
        ->take(2)
        ->get();

    $questions_400 = Question::where('category_id', $id)
        ->where('qu_points', 400)
        ->inRandomOrder()
        ->take(2)
        ->get();

    $questions_600 = Question::where('category_id', $id)
        ->where('qu_points', 600)
        ->inRandomOrder()
        ->take(2)
        ->get();

    // Merge in the required order: 200, 200, 400, 400, 600, 600
    $qu = $questions_200->merge($questions_400)->merge($questions_600);

    // If less than 6 questions, fill missing ones from other available questions
    // if ($qu->count() < 6) {
    //     $extra_questions = Question::where('category_id', $id)
    //         ->whereNotIn('id', $qu->pluck('id')) // Exclude already selected
    //         ->inRandomOrder()
    //         ->take(6 - $qu->count())
    //         ->get();

    //     $qu = $qu->merge($extra_questions);
    // }

    // Map and return in the correct order
    $qu = $qu->map(function ($question) {
        $question->is_user_answer = false;
        $question->who_answer = 0;
        return $question;
    });

    return response()->json($qu->values()); // Ensure proper indexing
}





}
