<?php

namespace App\Http\Controllers;

use App\Models\QrCodeItem;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class QrCodeController extends Controller
{
    /**
     * Display a listing of all created QR codes.
     */
    public function allQrCodes()
    {
        $qrCodes = QrCodeItem::with('creator')->latest()->get();
        return view('admin.qr_code.all_qr_code', compact('qrCodes'));
    }

    /**
     * Show the form for creating a new QR code.
     */
    public function addQrCode()
    {
        return view('admin.qr_code.add_qr_code');
    }

    /**
     * Store a newly created QR code in storage.
     */
    public function storeQrCode(Request $request)
    {
        $rules = [
            'title' => 'nullable|string|max:255',
            'text_content' => 'required|string',
            'media_type' => 'required|in:none,image,video',
            'status' => 'nullable|in:active,inactive',
        ];

        if ($request->media_type === 'image') {
            $rules['image_file'] = 'required|image|mimes:jpeg,png,jpg,webp,gif,svg|max:20480';
        } elseif ($request->media_type === 'video') {
            $rules['video_file'] = 'required|file|mimes:mp4,mov,avi,webm,mkv,ogg|max:102400';
        }

        $messages = [
            'title.max' => '⚠️ العنوان يجب ألا يتجاوز 255 حرف',
            'text_content.required' => '⚠️ الرجاء كتابة النص المراد ظهوره في صفحة الرابط',
            'media_type.required' => '⚠️ الرجاء تحديد نوع المرفق',
            'image_file.required' => '⚠️ الرجاء إرفاق الصورة المطلوبة',
            'image_file.image' => '⚠️ الملف المرفق يجب أن يكون صورة صالحة',
            'image_file.mimes' => '⚠️ صيغ الصور المدعومة: JPEG, PNG, JPG, WEBP, GIF, SVG',
            'image_file.max' => '⚠️ حجم الصورة يجب ألا يتجاوز 20 ميجابايت',
            'video_file.required' => '⚠️ الرجاء إرفاق الفيديو المطلوب',
            'video_file.mimes' => '⚠️ صيغ الفيديو المدعومة: MP4, MOV, AVI, WEBM, MKV, OGG',
            'video_file.max' => '⚠️ حجم الفيديو يجب ألا يتجاوز 100 ميجابايت',
        ];

        $request->validate($rules, $messages);

        // Generate unique 10-character code
        do {
            $code = Str::lower(Str::random(10));
        } while (QrCodeItem::where('code', $code)->exists());

        $mediaPath = null;
        $mediaType = $request->media_type;

        // Handle Image Upload
        if ($mediaType === 'image' && $request->hasFile('image_file')) {
            $image = $request->file('image_file');
            $imageName = time() . '_' . Str::random(8) . '.' . $image->getClientOriginalExtension();
            $destinationPath = public_path('upload/qr_codes/images');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $image->move($destinationPath, $imageName);
            $mediaPath = 'upload/qr_codes/images/' . $imageName;
        }

        // Handle Video Upload
        if ($mediaType === 'video' && $request->hasFile('video_file')) {
            $video = $request->file('video_file');
            $videoName = time() . '_' . Str::random(8) . '.' . $video->getClientOriginalExtension();
            $destinationPath = public_path('upload/qr_codes/videos');
            if (!File::exists($destinationPath)) {
                File::makeDirectory($destinationPath, 0777, true, true);
            }
            $video->move($destinationPath, $videoName);
            $mediaPath = 'upload/qr_codes/videos/' . $videoName;
        }

        // Generate QR Code SVG file
        $publicUrl = route('qr.public.show', ['code' => $code]);
        $qrDir = public_path('upload/qr_codes/qrs');
        if (!File::exists($qrDir)) {
            File::makeDirectory($qrDir, 0777, true, true);
        }

        $qrFileName = 'qr_' . $code . '.svg';
        $qrFilePath = $qrDir . '/' . $qrFileName;

        $svgContent = QrCodeItem::generateSvgWithLogo($publicUrl, 400);

        File::put($qrFilePath, $svgContent);

        $qrItem = QrCodeItem::create([
            'code' => $code,
            'title' => $request->title,
            'text_content' => $request->text_content,
            'media_type' => $mediaType,
            'media_path' => $mediaPath,
            'qr_image' => 'upload/qr_codes/qrs/' . $qrFileName,
            'status' => $request->status ?? 'active',
            'created_by' => Auth::id(),
            'views_count' => 0,
        ]);

        $notification = [
            'message' => 'تم إنشاء الـ QR Code ورابط الصفحة بنجاح!',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.qr.code')->with($notification);
    }

    /**
     * Show the form for editing the specified QR code.
     */
    public function editQrCode($id)
    {
        $qrCode = QrCodeItem::findOrFail($id);
        return view('admin.qr_code.edit_qr_code', compact('qrCode'));
    }

    /**
     * Update the specified QR code in storage.
     */
    public function updateQrCode(Request $request, $id)
    {
        $qrCode = QrCodeItem::findOrFail($id);

        $rules = [
            'title' => 'nullable|string|max:255',
            'text_content' => 'required|string',
            'media_type' => 'required|in:none,image,video',
            'status' => 'nullable|in:active,inactive',
        ];

        if ($request->media_type === 'image' && $request->hasFile('image_file')) {
            $rules['image_file'] = 'image|mimes:jpeg,png,jpg,webp,gif,svg|max:20480';
        } elseif ($request->media_type === 'video' && $request->hasFile('video_file')) {
            $rules['video_file'] = 'file|mimes:mp4,mov,avi,webm,mkv,ogg|max:102400';
        }

        $messages = [
            'text_content.required' => '⚠️ الرجاء كتابة النص المراد ظهوره في صفحة الرابط',
            'media_type.required' => '⚠️ الرجاء تحديد نوع المرفق',
            'image_file.image' => '⚠️ الملف المرفق يجب أن يكون صورة صالحة',
            'image_file.mimes' => '⚠️ صيغ الصور المدعومة: JPEG, PNG, JPG, WEBP, GIF, SVG',
            'image_file.max' => '⚠️ حجم الصورة يجب ألا يتجاوز 20 ميجابايت',
            'video_file.mimes' => '⚠️ صيغ الفيديو المدعومة: MP4, MOV, AVI, WEBM, MKV, OGG',
            'video_file.max' => '⚠️ حجم الفيديو يجب ألا يتجاوز 100 ميجابايت',
        ];

        $request->validate($rules, $messages);

        $mediaType = $request->media_type;
        $mediaPath = $qrCode->media_path;

        // If media type changed to none, remove old file
        if ($mediaType === 'none') {
            if (!empty($qrCode->media_path) && File::exists(public_path($qrCode->media_path))) {
                File::delete(public_path($qrCode->media_path));
            }
            $mediaPath = null;
        } elseif ($mediaType === 'image') {
            // If new image uploaded
            if ($request->hasFile('image_file')) {
                if (!empty($qrCode->media_path) && File::exists(public_path($qrCode->media_path))) {
                    File::delete(public_path($qrCode->media_path));
                }
                $image = $request->file('image_file');
                $imageName = time() . '_' . Str::random(8) . '.' . $image->getClientOriginalExtension();
                $destinationPath = public_path('upload/qr_codes/images');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }
                $image->move($destinationPath, $imageName);
                $mediaPath = 'upload/qr_codes/images/' . $imageName;
            }
        } elseif ($mediaType === 'video') {
            // If new video uploaded
            if ($request->hasFile('video_file')) {
                if (!empty($qrCode->media_path) && File::exists(public_path($qrCode->media_path))) {
                    File::delete(public_path($qrCode->media_path));
                }
                $video = $request->file('video_file');
                $videoName = time() . '_' . Str::random(8) . '.' . $video->getClientOriginalExtension();
                $destinationPath = public_path('upload/qr_codes/videos');
                if (!File::exists($destinationPath)) {
                    File::makeDirectory($destinationPath, 0777, true, true);
                }
                $video->move($destinationPath, $videoName);
                $mediaPath = 'upload/qr_codes/videos/' . $videoName;
            }
        }

        // Check if QR image file exists, if not recreate it
        if (empty($qrCode->qr_image) || !File::exists(public_path($qrCode->qr_image))) {
            $publicUrl = route('qr.public.show', ['code' => $qrCode->code]);
            $qrDir = public_path('upload/qr_codes/qrs');
            if (!File::exists($qrDir)) {
                File::makeDirectory($qrDir, 0777, true, true);
            }
            $qrFileName = 'qr_' . $qrCode->code . '.svg';
            $qrFilePath = $qrDir . '/' . $qrFileName;

            $svgContent = QrCodeItem::generateSvgWithLogo($publicUrl, 400);

            File::put($qrFilePath, $svgContent);
            $qrCode->qr_image = 'upload/qr_codes/qrs/' . $qrFileName;
        }

        $qrCode->update([
            'title' => $request->title,
            'text_content' => $request->text_content,
            'media_type' => $mediaType,
            'media_path' => $mediaPath,
            'status' => $request->status ?? 'active',
        ]);

        $notification = [
            'message' => 'تم تحديث الـ QR Code بنجاح!',
            'alert-type' => 'success'
        ];

        return redirect()->route('all.qr.code')->with($notification);
    }

    /**
     * Remove the specified QR code from storage.
     */
    public function deleteQrCode($id)
    {
        $qrCode = QrCodeItem::findOrFail($id);

        // Delete media file
        if (!empty($qrCode->media_path) && File::exists(public_path($qrCode->media_path))) {
            File::delete(public_path($qrCode->media_path));
        }

        // Delete QR SVG file
        if (!empty($qrCode->qr_image) && File::exists(public_path($qrCode->qr_image))) {
            File::delete(public_path($qrCode->qr_image));
        }

        $qrCode->delete();

        $notification = [
            'message' => 'تم حذف الـ QR Code بنجاح!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    /**
     * Toggle active/inactive status.
     */
    public function toggleStatus($id)
    {
        $qrCode = QrCodeItem::findOrFail($id);
        $qrCode->status = ($qrCode->status === 'active') ? 'inactive' : 'active';
        $qrCode->save();

        $notification = [
            'message' => 'تم تغيير حالة الـ QR Code بنجاح!',
            'alert-type' => 'success'
        ];

        return redirect()->back()->with($notification);
    }

    /**
     * Download the QR Code as SVG or PNG.
     */
    public function downloadQr($id)
    {
        $qrCode = QrCodeItem::findOrFail($id);
        $publicUrl = route('qr.public.show', ['code' => $qrCode->code]);

        $svgContent = QrCodeItem::generateSvgWithLogo($publicUrl, 600);

        $filename = 'qrcode_fiktahadi_' . $qrCode->code . '.svg';

        return response($svgContent)
            ->header('Content-Type', 'image/svg+xml')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }

    /**
     * Show the public QR Code landing page.
     */
    public function showPublicQr($code)
    {
        $qrCode = QrCodeItem::where('code', $code)->firstOrFail();

        // Increment view count
        $qrCode->increment('views_count');

        return view('qr_public.show', compact('qrCode'));
    }
}
