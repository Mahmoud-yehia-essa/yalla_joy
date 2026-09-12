<?php

namespace App\Http\Controllers;

use App\Models\TermsAndCondition;
use Illuminate\Http\Request;

class TermsAndConditionsController extends Controller
{
    /**
     * عرض جميع بنود الشروط والأحكام في لوحة التحكم
     */
    public function allTerms(Request $request)
    {
        $terms = TermsAndCondition::orderByRaw('order_by IS NULL ASC')
            ->orderBy('order_by', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return view('admin.terms_and_conditions.all_terms', compact('terms'));
    }

    /**
     * صفحة إضافة بند جديد
     */
    public function addTerm()
    {
        return view('admin.terms_and_conditions.add_term');
    }

    /**
     * حفظ بند جديد في قاعدة البيانات
     */
    public function storeTerm(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'content' => 'required|string',
            'content_en' => 'nullable|string',
            'order_by' => 'nullable|integer|min:1',
        ], [
            'title.required' => '⚠️ الرجاء كتابة عنوان القسم بالعربية',
            'title.string' => '⚠️ الرجاء التأكد من كتابة العنوان بشكل صحيح',
            'content.required' => '⚠️ الرجاء كتابة محتوى وبنود الشروط والأحكام بالعربية',
            'order_by.integer' => '⚠️ رقم الترتيب يجب أن يكون رقماً صحيحاً',
        ]);

        TermsAndCondition::create([
            'title' => $request->title,
            'title_en' => $request->title_en,
            'content' => $request->content,
            'content_en' => $request->content_en,
            'order_by' => $request->order_by ?? 1,
            'status' => 'active',
        ]);

        $notification = [
            'message' => 'تمت إضافة بند الشروط والأحكام بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.terms.and.conditions')->with($notification);
    }

    /**
     * صفحة تعديل بند الشروط والأحكام
     */
    public function editTerm($id)
    {
        $term = TermsAndCondition::findOrFail($id);
        return view('admin.terms_and_conditions.edit_term', compact('term'));
    }

    /**
     * تحديث بيانات البند
     */
    public function updateTerm(Request $request)
    {
        $id = $request->id;
        $term = TermsAndCondition::findOrFail($id);

        $request->validate([
            'title' => 'required|string|max:255',
            'title_en' => 'nullable|string|max:255',
            'content' => 'required|string',
            'content_en' => 'nullable|string',
            'order_by' => 'nullable|integer|min:1',
        ], [
            'title.required' => '⚠️ الرجاء كتابة عنوان القسم بالعربية',
            'content.required' => '⚠️ الرجاء كتابة محتوى وبنود الشروط والأحكام بالعربية',
        ]);

        $term->update([
            'title' => $request->title,
            'title_en' => $request->title_en,
            'content' => $request->content,
            'content_en' => $request->content_en,
            'order_by' => $request->order_by ?? $term->order_by,
        ]);

        $notification = [
            'message' => 'تم تعديل بند الشروط والأحكام بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.terms.and.conditions')->with($notification);
    }

    /**
     * حذف بند
     */
    public function deleteTerm($id)
    {
        TermsAndCondition::findOrFail($id)->delete();

        $notification = [
            'message' => 'تم حذف البند بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.terms.and.conditions')->with($notification);
    }

    /**
     * إلغاء تفعيل البند
     */
    public function termInactive($id)
    {
        TermsAndCondition::findOrFail($id)->update(['status' => 'inactive']);

        $notification = [
            'message' => 'تم إلغاء تفعيل البند',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    /**
     * تفعيل البند
     */
    public function termActive($id)
    {
        TermsAndCondition::findOrFail($id)->update(['status' => 'active']);

        $notification = [
            'message' => 'تم تفعيل البند بنجاح',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    /**
     * تحديث الترتيب عبر AJAX
     */
    public function updateOrder(Request $request)
    {
        $request->validate([
            'id' => 'required|exists:terms_and_conditions,id',
            'order_by' => 'required|integer|min:1',
        ]);

        $term = TermsAndCondition::findOrFail($request->id);
        $term->order_by = $request->order_by;
        $term->save();

        return response()->json([
            'status' => true,
            'message' => 'تم تحديث ترتيب البند بنجاح',
        ]);
    }

    // ==========================================
    // API Endpoints
    // ==========================================

    /**
     * نقطة نهاية API لتطبيق فلاتر لجلب الشروط والأحكام المفعلة
     */
    public function getTermsApi(Request $request)
    {
        $terms = TermsAndCondition::active()
            ->orderByRaw('order_by IS NULL ASC')
            ->orderBy('order_by', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        return response()->json([
            'success' => true,
            'message' => 'Terms and conditions retrieval successful',
            'data' => $terms,
        ], 200);
    }
}
