<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\UserGame;
use Illuminate\Http\Request;
use App\Models\questionsByUsers;
use App\Models\UserQuestionAnswer;
use Illuminate\Support\Facades\Auth;
use Intervention\Image\ImageManager;
use App\Notifications\UserGameNotification;
use Illuminate\Support\Facades\Notification;
use Intervention\Image\Drivers\Gd\Driver; // استخدم GD driver

class UserGameController extends Controller
{
    // =====================
    // تغيير حالة اللعبة
    // =====================
    public function publishGame($id)
    {
        $game = UserGame::findOrFail($id);
        $game->update(['status' => 'published']);
        return redirect()->back()->with('success', 'تم نشر اللعبة بنجاح');
    }

    public function cancelGame($id)
    {
        $game = UserGame::findOrFail($id);
        $game->update(['status' => 'canceled']);
        return redirect()->back()->with('info', 'تم إلغاء اللعبة');
    }

    public function suspendGame($id)
    {
        $game = UserGame::findOrFail($id);
        $game->update(['status' => 'suspended']);
        return redirect()->back()->with('warning', 'تم تعليق اللعبة');
    }

    // =====================
    // عرض كل الألعاب
    // =====================
    public function allUserGames()
    {
        $games = UserGame::where('user_id', Auth::id())->latest()->get();

                $games = UserGame::latest()->get();

        return view('admin.user_games.all_user_games', compact('games'));
    }

    // =====================
    // إضافة لعبة جديدة
    // =====================
    public function addUserGame()
    {
        return view('admin.user_games.add_user_game');
    }

    public function storeUserGame(Request $request)
    {
        $game = new UserGame();
        $game->user_id = Auth::id();
        $game->name = $request->name;
        $game->des = $request->des;
        $game->privacy = $request->privacy ?? 'privacy';
        $game->status = 'pending';

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            $path = public_path('upload/user_games/');
            if (!file_exists($path)) mkdir($path, 0777, true);

            $imageManager = new ImageManager(new Driver());
            // $imageResized = $imageManager->read($image)->resize(400, 400);
            $imageResized = $imageManager->read($image);

            $imageResized->save($path . $name_gen);

            $game->photo = 'upload/user_games/' . $name_gen;
        }

        $game->save();

        $usersForNotification = User::where('role','admin')->get();



        Notification::send($usersForNotification,new UserGameNotification($game));

        return redirect()->route('all.user.games')->with('success', 'تم إنشاء اللعبة بنجاح وبانتظار المراجعة.');
    }

    // =====================
    // تعديل اللعبة
    // =====================
    public function editUserGame($id)
    {
        $game = UserGame::findOrFail($id);
        return view('admin.user_games.edit_user_game', compact('game'));
    }

    public function updateUserGame(Request $request)
    {
        $game = UserGame::findOrFail($request->id);
        $game->update([
            'name' => $request->name,
            'des' => $request->des,
            'privacy' => $request->privacy,
        ]);

        if ($request->hasFile('photo')) {
            $image = $request->file('photo');
            $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

            $path = public_path('upload/user_games/');
            if (!file_exists($path)) mkdir($path, 0777, true);

            $imageManager = new ImageManager(new Driver());
            // $imageResized = $imageManager->read($image)->resize(400, 400);
                        $imageResized = $imageManager->read($image);

            $imageResized->save($path . $name_gen);

            $game->photo = 'upload/user_games/' . $name_gen;
            $game->save();
        }

        return redirect()->route('all.user.games')->with('success', 'تم تحديث اللعبة بنجاح.');
    }

    // =====================
    // حذف اللعبة
    // =====================
    public function deleteUserGame($id)
    {
        $game = UserGame::findOrFail($id);

        if ($game->photo && file_exists(public_path($game->photo))) {
            unlink(public_path($game->photo));
        }

        $game->delete();
        return back()->with('success', 'تم حذف اللعبة.');
    }

    // =====================
    // عرض أسئلة اللعبة
    // =====================
    public function userGameQuestions($id)
    {
        $game = UserGame::findOrFail($id);
        $questions = questionsByUsers::where('user_game_id', $id)->latest()->get();
        return view('admin.user_games.questions.index', compact('game', 'questions'));
    }

    // =====================
    // إضافة سؤال جديد
    // =====================
    public function addUserGameQuestion($id)
    {
        $game = UserGame::findOrFail($id);
        return view('admin.user_games.questions.add', compact('game'));
    }

    // public function storeUserGameQuestion(Request $request)
    // {
    //     $question = new questionsByUsers();
    //     // $question->user_id = Auth::id();
    //     $question->user_game_id = $request->user_game_id;
    //     $question->qu_title = $request->qu_title;
    //     $question->qu_points = $request->qu_points;
    //     $question->time_counter = $request->time_counter;
    //     $question->questions_type = $request->questions_type;

    //     if ($request->hasFile('qu_image')) {
    //         $image = $request->file('qu_image');
    //         $name_gen = hexdec(uniqid()) . '.' . $image->getClientOriginalExtension();

    //         $path = public_path('upload/user_questions/');
    //         if (!file_exists($path)) mkdir($path, 0777, true);

    //         $imageManager = new ImageManager(new Driver());
    //         // $imageResized = $imageManager->read($image)->resize(400, 400);

    //                     $imageResized = $imageManager->read($image);

    //         $imageResized->save($path . $name_gen);

    //         $question->qu_image = 'upload/user_questions/' . $name_gen;
    //     }

    //     $question->save();

    //     return redirect()->route('user.game.questions', $question->user_game_id)
    //         ->with('success', 'تمت إضافة السؤال بنجاح.');
    // }





    ///Edit


    // =====================
// تعديل سؤال اللعبة
// =====================
// public function editUserGameQuestion($id)
// {
//     $question = questionsByUsers::with('answers')->findOrFail($id);
//     $game = $question->userGame; // افترض عندك علاقة userGame
//     return view('admin.user_games.questions.edit', compact('question', 'game'));
// }

public function editUserGameQuestion($id)
{
    $question = questionsByUsers::findOrFail($id); // السؤال
    $game = UserGame::findOrFail($question->user_game_id); // اللعبة المرتبطة بالسؤال

    $answers = UserQuestionAnswer::where('questions_by_user_id', $question->id)->get();

    return view('admin.user_games.questions.edit', compact('question', 'game', 'answers'));
}


public function updateUserGameQuestion(Request $request, $id)
{
    $question = questionsByUsers::findOrFail($id);
    $question->qu_title = $request->qu_title;
    $question->qu_points = $request->qu_points;
    $question->time_counter = $request->time_counter;
    $question->questions_type = $request->questions_type;

    // رفع الملفات حسب نوع السؤال
    if ($request->hasFile('qu_image')) {
        if ($question->qu_image && file_exists(public_path($question->qu_image))) {
            unlink(public_path($question->qu_image));
        }
        $question->qu_image = $this->uploadFile($request->file('qu_image'), 'upload/user_questions/images/');
    }

    if ($request->hasFile('qu_video')) {
        if ($question->qu_video && file_exists(public_path($question->qu_video))) {
            unlink(public_path($question->qu_video));
        }
        $question->qu_video = $this->uploadFile($request->file('qu_video'), 'upload/user_questions/videos/');
    }

    if ($request->hasFile('qu_sound')) {
        if ($question->qu_sound && file_exists(public_path($question->qu_sound))) {
            unlink(public_path($question->qu_sound));
        }
        $question->qu_sound = $this->uploadFile($request->file('qu_sound'), 'upload/user_questions/sounds/');
    }

    $question->save();

    // تحديث الإجابات
    if ($request->answers && is_array($request->answers)) {
        foreach ($request->answers as $i => $ans) {
            $answer = isset($ans['id']) ? UserQuestionAnswer::find($ans['id']) : new UserQuestionAnswer();
            $answer->questions_by_user_id = $question->id;
            $answer->answer_title = $ans['answer_title'] ?? null;
            $answer->answer_type = $ans['answer_type'] ?? 'text';

            if (isset($ans['answer_image']) && $ans['answer_image'] instanceof \Illuminate\Http\UploadedFile) {
                if ($answer->answer_image && file_exists(public_path($answer->answer_image))) {
                    unlink(public_path($answer->answer_image));
                }
                $answer->answer_image = $this->uploadFile($ans['answer_image'], 'upload/user_answers/images/');
            }

            if (isset($ans['answer_video']) && $ans['answer_video'] instanceof \Illuminate\Http\UploadedFile) {
                if ($answer->answer_video && file_exists(public_path($answer->answer_video))) {
                    unlink(public_path($answer->answer_video));
                }
                $answer->answer_video = $this->uploadFile($ans['answer_video'], 'upload/user_answers/videos/');
            }

            if (isset($ans['answer_sound']) && $ans['answer_sound'] instanceof \Illuminate\Http\UploadedFile) {
                if ($answer->answer_sound && file_exists(public_path($answer->answer_sound))) {
                    unlink(public_path($answer->answer_sound));
                }
                $answer->answer_sound = $this->uploadFile($ans['answer_sound'], 'upload/user_answers/sounds/');
            }

            $answer->save();
        }
    }

    return redirect()->route('user.game.questions', $question->user_game_id)
                     ->with('success', 'تم تحديث السؤال والإجابات بنجاح.');
}


    // End Edit
    public function storeUserGameQuestion(Request $request)
{
    $question = new questionsByUsers();
    $question->user_game_id = $request->user_game_id;
    // $question->user_id = Auth::id();
    $question->qu_title = $request->qu_title;
    $question->qu_points = $request->qu_points;
    $question->time_counter = $request->time_counter;
    $question->questions_type = $request->questions_type;

    // رفع الملفات حسب نوع السؤال
    if ($request->hasFile('qu_image')) {
        $question->qu_image = $this->uploadFile($request->file('qu_image'), 'upload/user_questions/images/');
    }

    if ($request->hasFile('qu_video')) {
        $question->qu_video = $this->uploadFile($request->file('qu_video'), 'upload/user_questions/videos/');
    }

    if ($request->hasFile('qu_sound')) {
        $question->qu_sound = $this->uploadFile($request->file('qu_sound'), 'upload/user_questions/sounds/');
    }

    $question->save();

    // حفظ الإجابات
    if ($request->answers && is_array($request->answers)) {
        foreach ($request->answers as $ans) {
            $answer = new UserQuestionAnswer();
            $answer->questions_by_user_id = $question->id;
            $answer->answer_title = $ans['answer_title'] ?? null;
            $answer->answer_type = $ans['answer_type'] ?? 'text';

            if (isset($ans['answer_image']) && $ans['answer_image'] instanceof \Illuminate\Http\UploadedFile) {
                $answer->answer_image = $this->uploadFile($ans['answer_image'], 'upload/user_answers/images/');
            }

            if (isset($ans['answer_video']) && $ans['answer_video'] instanceof \Illuminate\Http\UploadedFile) {
                $answer->answer_video = $this->uploadFile($ans['answer_video'], 'upload/user_answers/videos/');
            }

            if (isset($ans['answer_sound']) && $ans['answer_sound'] instanceof \Illuminate\Http\UploadedFile) {
                $answer->answer_sound = $this->uploadFile($ans['answer_sound'], 'upload/user_answers/sounds/');
            }

            $answer->save();
        }
    }

    return redirect()->route('user.game.questions', $question->user_game_id)
        ->with('success', 'تمت إضافة السؤال والإجابات بنجاح.');
}

// دالة رفع الملفات
private function uploadFile($file, $path)
{
    if (!file_exists(public_path($path))) {
        mkdir(public_path($path), 0777, true);
    }
    $name = hexdec(uniqid()) . '.' . $file->getClientOriginalExtension();
    $file->move(public_path($path), $name);
    return $path . $name;
}



    // =====================
    // حذف سؤال
    // =====================
    public function deleteUserGameQuestion($id)
    {
        $question = questionsByUsers::findOrFail($id);

        if ($question->qu_image && file_exists(public_path($question->qu_image))) {
            unlink(public_path($question->qu_image));
        }

        $question->delete();
        return back()->with('success', 'تم حذف السؤال.');
    }
}
