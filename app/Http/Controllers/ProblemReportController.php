<?php

namespace App\Http\Controllers;

use App\Models\ProblemReport;
use Illuminate\Http\Request;
use App\Exports\ProblemReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ProblemReportController extends Controller
{
    public function allProblemReports(Request $request)
    {
        $query = ProblemReport::with(['user', 'question', 'cheatingUser']);

        // Issue Type Filter
        if ($request->filled('issue_type') && $request->issue_type !== 'all') {
            $query->where('issue_type', $request->issue_type);
        }

        // Status Filter
        if ($request->filled('status') && $request->status !== 'all') {
            $query->where('status', $request->status);
        }

        // Sorting
        if ($request->sort_by === 'oldest') {
            $query->oldest();
        } else {
            $query->latest();
        }

        $reports = $query->get();
        return view('admin.problem_report.all_problem_reports', compact('reports'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,resolved,ignored'
        ], [
            'status.required' => '⚠️ الحقل مطلوب',
            'status.in' => '⚠️ الحالة غير صالحة',
        ]);

        $report = ProblemReport::findOrFail($id);
        $report->update([
            'status' => $request->status
        ]);

        $notification = array(
            'message' => 'تم تحديث حالة البلاغ بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function deleteProblemReport($id)
    {
        $report = ProblemReport::findOrFail($id);
        $report->delete();

        $notification = array(
            'message' => 'تم حذف البلاغ بنجاح',
            'alert-type' => 'success'
        );

        return redirect()->back()->with($notification);
    }

    public function storeProblemReportApi(Request $request)
    {
        $validator = \Illuminate\Support\Facades\Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'question_id' => 'required_unless:issue_type,cheating|nullable|exists:questions,id',
            'issue_type' => 'required|in:question_error,answer_error,inappropriate_content,cheating',
            'user_id_cheating' => 'required_if:issue_type,cheating|nullable|exists:users,id',
            'additional_notes' => 'nullable|string',
        ], [
            'user_id.required' => 'حقل معرف المستخدم مطلوب.',
            'user_id.exists' => 'المستخدم المحدد غير موجود.',
            'question_id.required_unless' => 'حقل معرف السؤال مطلوب.',
            'question_id.exists' => 'السؤال المحدد غير موجود.',
            'issue_type.required' => 'نوع المشكلة مطلوب.',
            'issue_type.in' => 'نوع المشكلة غير صالح.',
            'user_id_cheating.required_if' => 'حقل معرف المستخدم الذي غش مطلوب لحالة الغش.',
            'user_id_cheating.exists' => 'المستخدم المحدد للغش غير موجود.',
            'additional_notes.string' => 'الملاحظات الإضافية يجب أن تكون نصاً.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'خطأ في التحقق من البيانات',
                'errors' => $validator->errors()
            ], 422);
        }

        $report = ProblemReport::create([
            'user_id' => $request->user_id,
            'question_id' => $request->issue_type === 'cheating' ? null : $request->question_id,
            'user_id_cheating' => $request->issue_type === 'cheating' ? $request->user_id_cheating : null,
            'issue_type' => $request->issue_type,
            'additional_notes' => $request->additional_notes,
            'status' => 'pending',
        ]);

        return response()->json([
            'success' => true,
            'message' => 'تم تسجيل البلاغ بنجاح',
            'data' => $report
        ], 201);
    }

    public function exportProblemReports(Request $request)
    {
        return Excel::download(new ProblemReportExport($request), 'problem_reports_' . date('Y_m_d_His') . '.xlsx');
    }
}
