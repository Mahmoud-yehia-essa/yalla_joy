<?php

namespace App\Http\Controllers;

use App\Models\Proverb;
use App\Models\RankingNew;
use Illuminate\Http\Request;
use File;

class ProverbController extends Controller
{
    public function allProverb()
    {
        $proverbs = Proverb::with('rankingNew')->latest()->get();
        return view('admin.proverb.all_proverb', compact('proverbs'));
    }

    public function addProverb()
    {
        $rankings = RankingNew::all();
        return view('admin.proverb.add_proverb', compact('rankings'));
    }

    public function storeProverb(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'type' => 'required|in:positive,negative',
            'ranking_new_id' => 'required|exists:rankings_new,id',
        ], [
            'title.required' => '⚠️ الرجاء اضافة العبارة',
            'title_en.required' => '⚠️ الرجاء اضافة العبارة بالانجليزية',
            'type.required' => '⚠️ الرجاء تحديد نوع العبارة',
            'ranking_new_id.required' => '⚠️ الرجاء اختيار الرتبة',
        ]);

        $audio_ar_url = null;
        if ($request->file('audio_ar')) {
            $file = $request->file('audio_ar');
            $name_gen = hexdec(uniqid()) . '_ar.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/proverbs_audio'), $name_gen);
            $audio_ar_url = 'upload/proverbs_audio/' . $name_gen;
        }

        $audio_en_url = null;
        if ($request->file('audio_en')) {
            $file = $request->file('audio_en');
            $name_gen = hexdec(uniqid()) . '_en.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/proverbs_audio'), $name_gen);
            $audio_en_url = 'upload/proverbs_audio/' . $name_gen;
        }

        Proverb::create([
            'title' => $request->title,
            'title_en' => $request->title_en,
            'type' => $request->type,
            'ranking_new_id' => $request->ranking_new_id,
            'audio_ar' => $audio_ar_url,
            'audio_en' => $audio_en_url,
        ]);

        $notification = [
            'message' => 'تم اضافة العبارة بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.proverb')->with($notification);
    }

    public function editProverb($id)
    {
        $proverb = Proverb::findOrFail($id);
        $rankings = RankingNew::all();
        return view('admin.proverb.edit_proverb', compact('proverb', 'rankings'));
    }

    public function updateProverb(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_en' => 'required|string|max:255',
            'type' => 'required|in:positive,negative',
            'ranking_new_id' => 'required|exists:rankings_new,id',
        ], [
            'title.required' => '⚠️ الرجاء اضافة العبارة',
            'title_en.required' => '⚠️ الرجاء اضافة العبارة بالانجليزية',
            'type.required' => '⚠️ الرجاء تحديد نوع العبارة',
            'ranking_new_id.required' => '⚠️ الرجاء اختيار الرتبة',
        ]);

        $proverb_id = $request->id;

        $proverb = Proverb::findOrFail($proverb_id);

        if ($request->file('audio_ar')) {
            if (File::exists(public_path($proverb->audio_ar))) {
                File::delete(public_path($proverb->audio_ar));
            }
            $file = $request->file('audio_ar');
            $name_gen = hexdec(uniqid()) . '_ar.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/proverbs_audio'), $name_gen);
            $proverb->audio_ar = 'upload/proverbs_audio/' . $name_gen;
        }

        if ($request->file('audio_en')) {
            if (File::exists(public_path($proverb->audio_en))) {
                File::delete(public_path($proverb->audio_en));
            }
            $file = $request->file('audio_en');
            $name_gen = hexdec(uniqid()) . '_en.' . $file->getClientOriginalExtension();
            $file->move(public_path('upload/proverbs_audio'), $name_gen);
            $proverb->audio_en = 'upload/proverbs_audio/' . $name_gen;
        }

        $proverb->update([
            'title' => $request->title,
            'title_en' => $request->title_en,
            'type' => $request->type,
            'ranking_new_id' => $request->ranking_new_id,
            'audio_ar' => $proverb->audio_ar,
            'audio_en' => $proverb->audio_en,
        ]);

        $notification = [
            'message' => 'تم تعديل العبارة بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.proverb')->with($notification);
    }

    public function deleteProverb($id)
    {
        $proverb = Proverb::findOrFail($id);

        if (File::exists(public_path($proverb->audio_ar))) {
            File::delete(public_path($proverb->audio_ar));
        }
        if (File::exists(public_path($proverb->audio_en))) {
            File::delete(public_path($proverb->audio_en));
        }

        $proverb->delete();

        $notification = [
            'message' => 'تم حذف العبارة بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    // ============================================================
    //  API
    // ============================================================

    /**
     * POST /api/proverbs/by-rank
     * body: { "ranking_new_id": 2 }
     *
     * يُعيد جميع العبارات التابعة للرتبة المحددة.
     */
    public function getProverbsByRankApi(Request $request)
    {
        $request->validate([
            'ranking_new_id' => 'required|integer|exists:rankings_new,id',
        ]);

        $proverbs = Proverb::where('ranking_new_id', $request->ranking_new_id)->get();

        if ($proverbs->isEmpty()) {
            return response()->json([
                'success' => false,
                'message' => 'No proverbs found for this rank',
                'data'    => [],
            ], 200);
        }

        return response()->json([
            'success' => true,
            'data'    => $proverbs,
        ], 200);
    }
}
