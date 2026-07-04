<?php

namespace App\Http\Controllers;

use App\Models\ProblemReport;
use Illuminate\Http\Request;
use App\Exports\ProblemReportExport;
use Maatwebsite\Excel\Facades\Excel;

class ProblemReportController extends Controller
{
    public function allProblemReports()
    {
        $reports = ProblemReport::with(['user', 'question'])->latest()->get();
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
            'question_id' => 'required|exists:questions,id',
            'issue_type' => 'required|in:question_error,answer_error,inappropriate_content',
            'additional_notes' => 'nullable|string',
        ], [
            'user_id.required' => 'حقل معرف المستخدم مطلوب.',
            'user_id.exists' => 'المستخدم المحدد غير موجود.',
            'question_id.required' => 'حقل معرف السؤال مطلوب.',
            'question_id.exists' => 'السؤال المحدد غير موجود.',
            'issue_type.required' => 'نوع المشكلة مطلوب.',
            'issue_type.in' => 'نوع المشكلة غير صالح.',
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
            'question_id' => $request->question_id,
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

    public function exportProblemReports()
    {
        return Excel::download(new ProblemReportExport, 'problem_reports_' . date('Y_m_d_His') . '.xlsx');
    }
}
