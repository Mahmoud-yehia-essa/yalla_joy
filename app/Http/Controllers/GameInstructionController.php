<?php

namespace App\Http\Controllers;

use App\Models\GameInstruction;
use Illuminate\Http\Request;

class GameInstructionController extends Controller
{
    // عرض جميع أقسام وصف وتعريف الألعاب في لوحة التحكم
    public function allGameInstructions(Request $request)
    {
        $target = $request->query('target');

        $query = GameInstruction::query();
        if (!empty($target) && in_array($target, ['session', 'field'])) {
            $query->where('game_target', $target);
        }

        $instructions = $query->orderBy('game_target', 'asc')
            ->orderByRaw('order_by IS NULL ASC')
            ->orderBy('order_by', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.game_instructions.all_game_instructions', compact('instructions', 'target'));
    }

    // صفحة إضافة قسم جديد
    public function addGameInstruction()
    {
        return view('admin.game_instructions.add_game_instruction');
    }

    // حفظ قسم جديد
    public function storeGameInstruction(Request $request)
    {
        $request->validate([
            'game_target' => 'required|in:session,field',
            'section_type' => 'required|in:intro,section',
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'intro' => 'nullable|string',
            'content' => 'required|string',
            'content_en' => 'nullable|string',
            'order_by' => 'nullable|integer|min:1',
        ], [
            'game_target.required' => '⚠️ الرجاء تحديد نوع اللعبة (الجلسة أو الميدان)',
            'game_target.in' => '⚠️ نوع اللعبة غير صحيح',
            'section_type.required' => '⚠️ الرجاء تحديد نوع البطاقة',
            'title.required' => '⚠️ الرجاء كتابة عنوان القسم',
            'title.string' => '⚠️ الرجاء التأكد من كتابة العنوان بشكل صحيح',
            'content.required' => '⚠️ الرجاء كتابة محتوى أو بنود القسم',
            'order_by.integer' => '⚠️ رقم الترتيب يجب أن يكون رقماً صحيحاً',
        ]);

        GameInstruction::create([
            'game_target' => $request->game_target,
            'section_type' => $request->section_type,
            'title' => $request->title,
            'title_en' => $request->title_en,
            'icon' => $request->icon ?? ($request->section_type === 'intro' ? ($request->game_target === 'session' ? 'sports_esports' : 'globe') : 'settings'),
            'intro' => $request->intro,
            'content' => $request->content,
            'content_en' => $request->content_en,
            'order_by' => $request->order_by ?? 1,
            'status' => 'active',
        ]);

        $notification = [
            'message' => 'تم إضافة قسم وصف اللعبة بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.game.instructions')->with($notification);
    }

    // صفحة تعديل القسم
    public function editGameInstruction($id)
    {
        $instruction = GameInstruction::findOrFail($id);
        return view('admin.game_instructions.edit_game_instruction', compact('instruction'));
    }

    // تحديث بيانات القسم
    public function updateGameInstruction(Request $request)
    {
        $id = $request->id;
        $instruction = GameInstruction::findOrFail($id);

        $request->validate([
            'game_target' => 'required|in:session,field',
            'section_type' => 'required|in:intro,section',
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'icon' => 'nullable|string|max:100',
            'intro' => 'nullable|string',
            'content' => 'required|string',
            'content_en' => 'nullable|string',
            'order_by' => 'nullable|integer|min:1',
        ], [
            'game_target.required' => '⚠️ الرجاء تحديد نوع اللعبة (الجلسة أو الميدان)',
            'title.required' => '⚠️ الرجاء كتابة عنوان القسم',
            'content.required' => '⚠️ الرجاء كتابة محتوى أو بنود القسم',
        ]);

        $instruction->update([
            'game_target' => $request->game_target,
            'section_type' => $request->section_type,
            'title' => $request->title,
            'title_en' => $request->title_en,
            'icon' => $request->icon ?? $instruction->icon,
            'intro' => $request->intro,
            'content' => $request->content,
            'content_en' => $request->content_en,
            'order_by' => $request->order_by ?? $instruction->order_by,
        ]);

        $notification = [
            'message' => 'تم تعديل قسم وصف اللعبة بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.game.instructions')->with($notification);
    }

    // حذف القسم
    public function deleteGameInstruction($id)
    {
        GameInstruction::findOrFail($id)->delete();

        $notification = [
            'message' => 'تم حذف القسم بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.game.instructions')->with($notification);
    }

    // إلغاء تفعيل
    public function gameInstructionInactive($id)
    {
        GameInstruction::findOrFail($id)->update(['status' => 'inactive']);

        $notification = [
            'message' => 'تم إلغاء التفعيل',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    // تفعيل
    public function gameInstructionActive($id)
    {
        GameInstruction::findOrFail($id)->update(['status' => 'active']);

        $notification = [
            'message' => 'تم التفعيل بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    // تحديث الترتيب عبر AJAX
    public function updateOrder(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:game_instructions,id',
            'order_by' => 'required|integer|min:1',
        ]);

        $instruction = GameInstruction::findOrFail($request->id);
        $instruction->order_by = $request->order_by;
        $instruction->save();

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث الترتيب بنجاح',
        ]);
    }

    // ==========================================
    // API Endpoints
    // ==========================================

    public function getGameInstructionsApi(Request $request)
    {
        $game_target = $request->game_target ?? $request->target;

        $query = GameInstruction::active()
            ->orderByRaw('order_by IS NULL ASC')
            ->orderBy('order_by', 'asc')
            ->orderBy('id', 'asc');

        if (!empty($game_target) && in_array($game_target, ['session', 'field'])) {
            $query->where('game_target', $game_target);
        }

        $instructions = $query->get();

        // Separate by game_target if not filtered
        $sessionInstructions = $instructions->where('game_target', 'session')->values();
        $fieldInstructions = $instructions->where('game_target', 'field')->values();

        return response()->json([
            'success' => true,
            'message' => 'Game instructions retrieval successful',
            'data' => [
                'session' => $sessionInstructions,
                'field' => $fieldInstructions,
                'all' => $instructions,
            ]
        ], 200);
    }
}
