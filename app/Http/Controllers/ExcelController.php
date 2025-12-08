<?php

namespace App\Http\Controllers;

use App\Models\Answer;


use App\Models\Question;
use Illuminate\Http\Request;
use Illuminate\Support\Collection;
use App\Models\AnswerQuestionOnline;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Maatwebsite\Excel\HeadingRowImport;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Imports\HeadingRowFormatter;


class ExcelController extends Controller
{

public function index()
    {
        return view('admin.excel.index');
    }




public function import(Request $request)
{
    $request->validate([
        'excel_file' => 'required|mimes:xlsx,xls,csv'
    ]);

    $data = Excel::toCollection(new class implements ToCollection {
        public function collection(Collection $rows)
        {
            return $rows;
        }
    }, $request->file('excel_file'));

    $rows = $data[0];

    // استخراج الهيدر كـ Collection
    $headers = $rows[0];

    // إزالة صف الهيدر من البيانات
    unset($rows[0]);

    // ربط الهيدر مع كل صف وتحويلها إلى associative array
    $formatted = $rows->map(function ($row) use ($headers) {
        return $headers->combine($row);
    });

    return view('admin.excel.index', [
        'rows' => $formatted,
        'headers' => $headers   // ← هنا أضفنا الهيدر
    ]);
}

// public function approved(Request $request)
// {
//     $rows = json_decode($request->rows, true);

//     foreach ($rows as $row) {
//         // echo $row['qu_title'] . "<br>";

// $questions_type = "text";

// if($row['qu_image'] != "" )
// {
//     $questions_type = "image";
// }

// else if($row['qu_sound'] != "" )
// {
//     $questions_type = "sound";
// }

// else if($row['qu_video'] != "" )
// {
//     $questions_type = "video";
// }




//            $question = Question::create([
//             'qu_title' => $row['qu_title'],

//                         'qu_title_en' => $row['qu_title_en'],

//             'game_type_id' => $row['game_type_id'],

//             'main_category_id' => $row['main_category_id'],
//             'category_id' => $row['category_id'],

//             'qu_points' => $row['qu_points'],
//             'qu_points_online' => $row['qu_points_online'],

//             'questions_type' => $questions_type,
//             'time_counter' => $row['time_counter'],
//             'time_counter_online' => $row['time_counter_online'],

//             'qu_image' => $row['qu_image'],
//             'qu_sound' => $row['qu_sound'],
//             'qu_video' => $row['qu_video'],
//             // 'coins_number' => $request->coins_number,
//             // 'game_coin_id' => $request->game_coin_id,

//               'qu_hint' => $row['qu_hint'],
//             'qu_hint_en' => $row['qu_hint_en'],
//             'user_id' => Auth::user()->id,



//         ]);




//         $answer_type = "text";

// if($row['answer_image'] != "" )
// {
//     $answer_type = "image";
// }

// else if($row['answer_sound'] != "" )
// {
//     $answer_type = "sound";
// }

// else if($row['answer_video'] != "" )
// {
//     $answer_type = "video";
// }



//  $answer_type_two = "text";

// if($row['answer_image_two'] != "" )
// {
//     $answer_type_two = "image";
// }

// else if($row['answer_sound_two'] != "" )
// {
//     $answer_type_two = "sound";
// }

// else if($row['answer_video_two'] != "" )
// {
//     $answer_type_two = "video";
// }




// $answer_type_three = "text";

// if($row['answer_image_three'] != "" )
// {
//     $answer_type_three = "image";
// }

// else if($row['answer_sound_three'] != "" )
// {
//     $answer_type_three = "sound";
// }

// else if($row['answer_video_three'] != "" )
// {
//     $answer_type_three = "video";
// }



// $answer_type_four = "text";

// if($row['answer_image_four'] != "" )
// {
//     $answer_type_four = "image";
// }

// else if($row['answer_sound_four'] != "" )
// {
//     $answer_type_four = "sound";
// }

// else if($row['answer_video_four'] != "" )
// {
//     $answer_type_four = "video";
// }

// // Create answer for local
//         $answer = Answer::create([
//             'question_id' => $question->id,
//             'answer_title' => $row['answer_title'],
//             'answer_title_en' => $row['answer_title_en'],

//             'answer_type' => $answer_type_three,
//             'answer_image' => $row['answer_image'],
//             'answer_sound' => $row['answer_sound'],
//             'answer_video' => $row['answer_video'],
//         ]);




//          AnswerQuestionOnline::create([
//              'question_id' => $question->id,
//             'answer_title' => $row['answer_title'],
//             'answer_title_en' => $row['answer_title_en'],

//             'answer_type' => $answer_type,
//             'answer_image' => $row['answer_image'],
//             'answer_sound' => $row['answer_sound'],
//             'answer_video' => $row['answer_video'],
//     ]);

//          AnswerQuestionOnline::create([
//          'question_id' => $question->id,
//             'answer_title' => $row['answer_title_two'],
//             'answer_title_en' => $row['answer_title_en_two'],

//             'answer_type' => $answer_type_two,
//             'answer_image' => $row['answer_image_two'],
//             'answer_sound' => $row['answer_sound_two'],
//             'answer_video' => $row['answer_video_two'],
//     ]);



//           AnswerQuestionOnline::create([
//          'question_id' => $question->id,
//             'answer_title' => $row['answer_title_three'],
//             'answer_title_en' => $row['answer_title_en_three'],

//             'answer_type' => $answer_type_three,
//             'answer_image' => $row['answer_image_three'],
//             'answer_sound' => $row['answer_sound_three'],
//             'answer_video' => $row['answer_video_three'],
//     ]);


//         AnswerQuestionOnline::create([
//          'question_id' => $question->id,
//             'answer_title' => $row['answer_title_four'],
//             'answer_title_en' => $row['answer_title_en_four'],

//             'answer_type' => $answer_type_four,
//             'answer_image' => $row['answer_image_four'],
//             'answer_sound' => $row['answer_sound_four'],
//             'answer_video' => $row['answer_video_four'],
//     ]);




//     }

//      $notification = array(
//             'message' =>  'تمت إضافة الأسئلة بنجاح.',
//             'alert-type' => 'success'
//         );

//         return redirect()->route('all.question')->with($notification);
// }


public function approved(Request $request)
{
    ////////////////////////////////////////
    // 1) رفع ملف ZIP + فك الضغط
    ////////////////////////////////////////

    if ($request->hasFile('zip_file')) {

        $zipFile = $request->file('zip_file');
        $zipPath = $zipFile->getRealPath();

        $extractPath = public_path('upload/');

        // إنشاء مجلد إذا غير موجود
        if (!file_exists($extractPath)) {
            mkdir($extractPath, 0777, true);
        }

        $zip = new \ZipArchive;

        if ($zip->open($zipPath) === TRUE) {
            $zip->extractTo($extractPath);
            $zip->close();
        } else {
            return back()->withErrors('فشل فك الضغط من ملف ZIP');
        }
    }

    ////////////////////////////////////////
    // 2) نفس كودك كما هو بدون أي تعديل
    ////////////////////////////////////////

    $rows = json_decode($request->rows, true);

    foreach ($rows as $row) {

        $questions_type = "text";

        if($row['qu_image'] != "" )
        {
            $questions_type = "image";
        }
        else if($row['qu_sound'] != "" )
        {
            $questions_type = "sound";
        }
        else if($row['qu_video'] != "" )
        {
            $questions_type = "video";
        }

        $question = Question::create([
            'qu_title' => $row['qu_title'],
            'qu_title_en' => $row['qu_title_en'],
            'game_type_id' => $row['game_type_id'],
            'main_category_id' => $row['main_category_id'],
            'category_id' => $row['category_id'],
            'qu_points' => $row['qu_points'],
            'qu_points_online' => $row['qu_points_online'],
            'questions_type' => $questions_type,
            'time_counter' => $row['time_counter'],
            'time_counter_online' => $row['time_counter_online'],
            'qu_image' => $row['qu_image'],
            'qu_sound' => $row['qu_sound'],
            'qu_video' => $row['qu_video'],
            'qu_hint' => $row['qu_hint'],
            'qu_hint_en' => $row['qu_hint_en'],
            'term' => $row['term'],

            'user_id' => Auth::user()->id,
        ]);


        $answer_type = "text";
        if($row['answer_image'] != "" ) $answer_type = "image";
        else if($row['answer_sound'] != "" ) $answer_type = "sound";
        else if($row['answer_video'] != "" ) $answer_type = "video";

        $answer_type_two = "text";
        if($row['answer_image_two'] != "" ) $answer_type_two = "image";
        else if($row['answer_sound_two'] != "" ) $answer_type_two = "sound";
        else if($row['answer_video_two'] != "" ) $answer_type_two = "video";

        $answer_type_three = "text";
        if($row['answer_image_three'] != "" ) $answer_type_three = "image";
        else if($row['answer_sound_three'] != "" ) $answer_type_three = "sound";
        else if($row['answer_video_three'] != "" ) $answer_type_three = "video";

        $answer_type_four = "text";
        if($row['answer_image_four'] != "" ) $answer_type_four = "image";
        else if($row['answer_sound_four'] != "" ) $answer_type_four = "sound";
        else if($row['answer_video_four'] != "" ) $answer_type_four = "video";

        // Create answer for local
        $answer = Answer::create([
            'question_id' => $question->id,
            'answer_title' => $row['answer_title'],
            'answer_title_en' => $row['answer_title_en'],
            'answer_type' => $answer_type_three,
            'answer_image' => $row['answer_image'],
            'answer_sound' => $row['answer_sound'],
            'answer_video' => $row['answer_video'],
        ]);

        AnswerQuestionOnline::create([
            'question_id' => $question->id,
            'answer_title' => $row['answer_title'],
            'answer_title_en' => $row['answer_title_en'],
            'answer_type' => $answer_type,
            'answer_image' => $row['answer_image'],
            'answer_sound' => $row['answer_sound'],
            'answer_video' => $row['answer_video'],
        ]);

        AnswerQuestionOnline::create([
            'question_id' => $question->id,
            'answer_title' => $row['answer_title_two'],
            'answer_title_en' => $row['answer_title_en_two'],
            'answer_type' => $answer_type_two,
            'answer_image' => $row['answer_image_two'],
            'answer_sound' => $row['answer_sound_two'],
            'answer_video' => $row['answer_video_two'],
        ]);

        AnswerQuestionOnline::create([
            'question_id' => $question->id,
            'answer_title' => $row['answer_title_three'],
            'answer_title_en' => $row['answer_title_en_three'],
            'answer_type' => $answer_type_three,
            'answer_image' => $row['answer_image_three'],
            'answer_sound' => $row['answer_sound_three'],
            'answer_video' => $row['answer_video_three'],
        ]);

        AnswerQuestionOnline::create([
            'question_id' => $question->id,
            'answer_title' => $row['answer_title_four'],
            'answer_title_en' => $row['answer_title_en_four'],
            'answer_type' => $answer_type_four,
            'answer_image' => $row['answer_image_four'],
            'answer_sound' => $row['answer_sound_four'],
            'answer_video' => $row['answer_video_four'],
        ]);

    }

    $notification = array(
        'message' =>  'تمت إضافة الأسئلة بنجاح.',
        'alert-type' => 'success'
    );

    return redirect()->route('all.question')->with($notification);
}


}
