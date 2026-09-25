@extends('admin.master_admin')
@section('admin')

<!--breadcrumb-->
<div class="page-breadcrumb d-none d-sm-flex align-items-center mb-3">
    <div class="breadcrumb-title pe-3">إدارة الـ QR Code</div>
    <div class="ps-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb mb-0 p-0">
                <li class="breadcrumb-item"><a href="{{ route('dashboard') }}"><i class="bx bx-home-alt"></i></a></li>
                <li class="breadcrumb-item active" aria-current="page">عرض كل الـ QR Code المنشأة</li>
            </ol>
        </nav>
    </div>
    <div class="ms-auto">
        <a href="{{ route('add.qr.code') }}" class="btn btn-primary px-3 shadow-sm">
            <i class="fa-solid fa-plus me-1"></i> إنشاء QR Code جديد
        </a>
    </div>
</div>
<!--end breadcrumb-->

<div class="card shadow-sm border-0">
    <div class="card-header bg-transparent d-flex align-items-center justify-content-between py-3">
        <h5 class="mb-0 text-primary fw-bold">
            <i class="bx bx-qr-scan me-2"></i> قائمة الـ QR Code المنشأة ({{ count($qrCodes) }})
        </h5>
        <div class="badge bg-light-primary text-primary px-3 py-2 fw-semibold">
            تحديث تلقائي وفوري
        </div>
    </div>
    <div class="card-body">
        <div class="table-responsive">
            <table id="example" class="table table-striped table-bordered align-middle" style="width:100%">
                <thead class="table-light">
                    <tr class="text-center">
                        <th style="width: 40px;">#</th>
                        <th style="width: 80px;">رمز الـ QR</th>
                        <th>العنوان التعريفي</th>
                        <th style="min-width: 220px;">نص العرض</th>
                        <th>نوع المرفق</th>
                        <th style="min-width: 180px;">رابط الصفحة العامة</th>
                        <th>عدد الزيارات</th>
                        <th>الحالة</th>
                        <th>تاريخ الإنشاء</th>
                        <th style="min-width: 130px;">الإجراءات</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($qrCodes as $key => $item)
                    <tr>
                        <td class="text-center fw-bold">{{ $key + 1 }}</td>

                        <!-- QR Code Thumbnail -->
                        <td class="text-center">
                            <div class="qr-thumb-box position-relative d-inline-block p-1 bg-white rounded border shadow-sm cursor-pointer"
                                 onclick="openQrModal('{{ $item->id }}', '{{ addslashes($item->title) }}', '{{ $item->public_url }}', '{{ asset($item->qr_image) }}', '{{ $item->code }}')"
                                 title="انقر لتكبير أو تحميل أو طباعة رمز الـ QR">
                                @if(!empty($item->qr_image) && file_exists(public_path($item->qr_image)))
                                    <img src="{{ asset($item->qr_image) }}" alt="QR Code" style="width: 55px; height: 55px; object-fit: contain;">
                                @else
                                    <div class="d-flex align-items-center justify-content-center" style="width: 55px; height: 55px; background: #f8f9fa;">
                                        {!! $item->generateQrSvg(50) !!}
                                    </div>
                                @endif
                                <span class="badge bg-primary position-absolute bottom-0 end-0 p-1" style="font-size: 8px;"><i class="fa-solid fa-magnifying-glass-plus"></i></span>
                            </div>
                        </td>

                        <!-- Title -->
                        <td>
                            <div class="fw-bold text-dark">{{ $item->title }}</div>
                            <span class="badge bg-light text-secondary border mt-1" style="font-size: 11px;">
                                <i class="fa-solid fa-hashtag me-1"></i> {{ $item->code }}
                            </span>
                        </td>

                        <!-- Content Preview -->
                        <td>
                            <div class="text-wrap" style="max-height: 80px; overflow-y: auto; font-size: 13px; line-height: 1.5; color: #333;">
                                {{ Str::limit($item->text_content, 120) }}
                            </div>
                            @if(mb_strlen($item->text_content) > 120)
                                <button type="button" class="btn btn-link btn-sm p-0 text-primary text-decoration-none mt-1" style="font-size: 11px;" onclick="showFullTextModal('{{ addslashes($item->title) }}', `{!! addslashes(nl2br(e($item->text_content))) !!}`)">
                                    <i class="fa-solid fa-eye me-1"></i> قراءة النص كاملاً
                                </button>
                            @endif
                        </td>

                        <!-- Media Preview -->
                        <td class="text-center">
                            @if($item->media_type === 'image')
                                <div class="d-flex flex-column align-items-center gap-1">
                                    <span class="badge bg-success"><i class="fa-solid fa-image me-1"></i> صورة</span>
                                    @if($item->media_path && file_exists(public_path($item->media_path)))
                                        <img onclick="showImageModal(this.src)" src="{{ asset($item->media_path) }}" alt="مرفق" class="rounded border shadow-sm mt-1 cursor-pointer" style="width: 45px; height: 45px; object-fit: cover;" title="انقر لتكبير الصورة">
                                    @else
                                        <span class="text-muted small">ملف غير موجود</span>
                                    @endif
                                </div>
                            @elseif($item->media_type === 'video')
                                <div class="d-flex flex-column align-items-center gap-1">
                                    <span class="badge bg-danger"><i class="fa-solid fa-video me-1"></i> فيديو</span>
                                    @if($item->media_path && file_exists(public_path($item->media_path)))
                                        <button type="button" class="btn btn-sm btn-outline-danger mt-1 py-0 px-2" style="font-size: 11px;" onclick="showVideoModal('{{ addslashes($item->title) }}', '{{ asset($item->media_path) }}')">
                                            <i class="fa-solid fa-play me-1"></i> تشغيل
                                        </button>
                                    @else
                                        <span class="text-muted small">ملف غير موجود</span>
                                    @endif
                                </div>
                            @else
                                <span class="badge bg-secondary"><i class="fa-solid fa-font me-1"></i> نص فقط</span>
                            @endif
                        </td>

                        <!-- Public URL & Copy -->
                        <td>
                            <div class="d-flex align-items-center gap-1">
                                <input type="text" class="form-control form-control-sm text-start bg-light" value="{{ $item->public_url }}" id="url-input-{{ $item->id }}" readonly style="font-size: 11px; direction: ltr;">
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="copyToClipboard('url-input-{{ $item->id }}')" title="نسخ الرابط">
                                    <i class="fa-solid fa-copy"></i>
                                </button>
                                <a href="{{ $item->public_url }}" target="_blank" class="btn btn-sm btn-outline-success" title="فتح الصفحة">
                                    <i class="fa-solid fa-arrow-up-right-from-square"></i>
                                </a>
                            </div>
                        </td>

                        <!-- Views Count -->
                        <td class="text-center">
                            <span class="badge bg-info text-dark px-2 py-1 fw-bold fs-6">
                                <i class="fa-solid fa-eye me-1"></i> {{ number_format($item->views_count) }}
                            </span>
                        </td>

                        <!-- Status Toggle -->
                        <td class="text-center">
                            @if($item->status === 'active')
                                <a href="{{ route('status.qr.code', $item->id) }}" class="badge bg-success text-decoration-none p-2" title="انقر للتعطيل">
                                    <i class="fa-solid fa-check-circle me-1"></i> مفعل
                                </a>
                            @else
                                <a href="{{ route('status.qr.code', $item->id) }}" class="badge bg-danger text-decoration-none p-2" title="انقر للتفعيل">
                                    <i class="fa-solid fa-times-circle me-1"></i> معطل
                                </a>
                            @endif
                        </td>

                        <!-- Created At -->
                        <td class="text-center text-nowrap">
                            <div class="fw-bold" style="font-size: 13px;">{{ $item->created_at->format('Y-m-d') }}</div>
                            <div class="text-muted small" style="font-size: 11px;">{{ $item->created_at->format('h:i A') }}</div>
                            <div class="text-primary small" style="font-size: 11px;">({{ $item->created_at->diffForHumans() }})</div>
                        </td>

                        <!-- Actions -->
                        <td class="text-center text-nowrap">
                            <div class="d-flex align-items-center justify-content-center gap-1">
                                <!-- View Page in New Tab -->
                                <a href="{{ $item->public_url }}" target="_blank" class="btn btn-sm btn-outline-success" title="عرض الصفحة العامة">
                                    <i class="fa-solid fa-globe"></i>
                                </a>

                                <!-- Download QR Code as PNG -->
                                <button type="button" class="btn btn-sm btn-outline-primary" onclick="downloadDirectPng('{{ $item->code }}', '{{ asset($item->qr_image) }}')" title="تحميل رمز الـ QR (PNG)">
                                    <i class="fa-solid fa-download"></i>
                                </button>

                                <!-- Edit -->
                                <a href="{{ route('edit.qr.code', $item->id) }}" class="btn btn-sm btn-outline-info" title="تعديل البيانات">
                                    <i class="fa-solid fa-pencil"></i>
                                </a>

                                <!-- Delete -->
                                <a href="{{ route('delete.qr.code', $item->id) }}" class="btn btn-sm btn-outline-danger" id="delete" title="حذف الـ QR Code">
                                    <i class="fa-solid fa-trash"></i>
                                </a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr>
                        <td colspan="10" class="text-center py-5 text-muted">
                            <div class="mb-3">
                                <i class="bx bx-qr-scan font-50 text-secondary"></i>
                            </div>
                            <h5>لا توجد أي رموز QR منشأة حتى الآن</h5>
                            <p class="small text-muted mb-3">ابدأ بإنشاء أول رمز QR وصفحة عرض الآن بسهولة</p>
                            <a href="{{ route('add.qr.code') }}" class="btn btn-primary px-4">
                                <i class="fa-solid fa-plus me-1"></i> إنشاء QR Code جديد
                            </a>
                        </td>
                    </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>

<!-- ================= MODALS ================= -->

<!-- 1. Full QR Code Display & Download Modal -->
<div class="modal fade" id="qrDisplayModal" tabindex="-1" aria-labelledby="qrDisplayModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-primary text-white">
                <h5 class="modal-title fw-bold" id="qrDisplayModalLabel">
                    <i class="fa-solid fa-qrcode me-2"></i> رمز الـ QR Code
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body text-center p-4">
                <h5 class="fw-bold text-dark mb-1" id="modalQrTitle"></h5>
                <p class="text-muted small mb-3">امسح الرمز بواسطة كاميرا الهاتف لفتح صفحة العرض مباشرة</p>

                <!-- QR Container -->
                <div class="d-inline-block p-3 bg-white rounded-3 border shadow-sm mb-3 position-relative" id="modalQrCanvasContainer">
                    <img id="modalQrImage" src="" alt="QR Code" style="width: 250px; height: 250px; object-fit: contain;">
                </div>

                <!-- URL preview & copy -->
                <div class="input-group mb-3" dir="ltr">
                    <button class="btn btn-primary" type="button" onclick="copyToClipboard('modalQrUrlInput')">
                        <i class="fa-solid fa-copy me-1"></i> نسخ الرابط
                    </button>
                    <input type="text" class="form-control text-start bg-light" id="modalQrUrlInput" readonly style="font-size: 13px;">
                </div>

                <!-- Download & Print Actions -->
                <div class="d-flex justify-content-center gap-2 flex-wrap">
                    <button type="button" class="btn btn-primary px-4 fw-bold shadow-sm" onclick="downloadQrAsPng()">
                        <i class="fa-solid fa-download me-1"></i> تحميل QR Code (PNG)
                    </button>
                    <button type="button" class="btn btn-outline-dark px-3" onclick="printQrCode()">
                        <i class="fa-solid fa-print me-1"></i> طباعة الرمز
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- 2. Full Text Modal -->
<div class="modal fade" id="fullTextModal" tabindex="-1" aria-labelledby="fullTextModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content shadow-lg border-0">
            <div class="modal-header bg-dark text-white">
                <h5 class="modal-title fw-bold" id="fullTextModalTitle">
                    <i class="fa-solid fa-paragraph me-2 text-primary"></i> نص العرض
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body p-4">
                <div id="fullTextModalBody" class="p-3 bg-light rounded-3 border" style="white-space: pre-wrap; font-size: 14px; line-height: 1.8; color: #2d3748;"></div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إغلاق</button>
            </div>
        </div>
    </div>
</div>

<!-- 3. Video Modal -->
<div class="modal fade" id="videoModal" tabindex="-1" aria-labelledby="videoModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg modal-dialog-centered">
        <div class="modal-content bg-dark text-white shadow-lg border-0">
            <div class="modal-header border-secondary">
                <h5 class="modal-title fw-bold" id="videoModalTitle">
                    <i class="fa-solid fa-video me-2 text-danger"></i> معاينة الفيديو المرفق
                </h5>
                <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close" onclick="stopModalVideo()"></button>
            </div>
            <div class="modal-body text-center p-3">
                <video id="modalVideoPlayer" controls class="w-100 rounded-3 shadow-sm" style="max-height: 480px; background: #000;"></video>
            </div>
        </div>
    </div>
</div>

<!-- 4. Image Preview Modal -->
<div class="modal fade" id="imageModal" tabindex="-1" aria-labelledby="imageModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered modal-lg">
        <div class="modal-content bg-transparent border-0 shadow-none">
            <div class="modal-body text-center p-0 position-relative">
                <button type="button" class="btn btn-light position-absolute top-0 end-0 m-3 rounded-circle shadow" data-bs-dismiss="modal" style="z-index: 10;">
                    <i class="fa-solid fa-xmark"></i>
                </button>
                <img id="modalImagePreview" src="" alt="صورة كبيرة" class="img-fluid rounded-3 shadow-lg" style="max-height: 85vh; background: #fff;">
            </div>
        </div>
    </div>
</div>

<style>
    .cursor-pointer {
        cursor: pointer;
    }
    .qr-thumb-box {
        transition: transform 0.2s ease, box-shadow 0.2s ease;
    }
    .qr-thumb-box:hover {
        transform: scale(1.08);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15) !important;
    }
</style>

<script>
    // Copy URL to Clipboard
    function copyToClipboard(elementId) {
        const input = document.getElementById(elementId);
        if (input) {
            input.select();
            input.setSelectionRange(0, 99999);
            navigator.clipboard.writeText(input.value).then(() => {
                if (typeof toastr !== 'undefined') {
                    toastr.success('تم نسخ الرابط إلى الحافظة بنجاح!');
                } else {
                    alert('تم نسخ الرابط بنجاح!');
                }
            }).catch(() => {
                document.execCommand('copy');
                if (typeof toastr !== 'undefined') {
                    toastr.success('تم نسخ الرابط إلى الحافظة!');
                }
            });
        }
    }

    // Track current modal QR Code
    let currentModalQrCode = '';

    // Open QR Code details modal
    function openQrModal(id, title, url, qrSrc, code) {
        currentModalQrCode = code || id;
        $('#modalQrTitle').text(title || 'QR Code');
        $('#modalQrUrlInput').val(url);
        $('#modalQrImage').attr('src', qrSrc);

        const modal = new bootstrap.Modal(document.getElementById('qrDisplayModal'));
        modal.show();
    }

    // Direct download QR as PNG with English filename
    function downloadDirectPng(code, qrSrc) {
        const canvas = document.createElement('canvas');
        const ctx = canvas.getContext('2d');
        const cleanCode = (code || 'code').replace(/[^a-zA-Z0-9_-]/g, '');
        const filename = 'qrcode_fiktahadi_' + cleanCode + '.png';

        canvas.width = 1200;
        canvas.height = 1200;

        // Draw white background
        ctx.fillStyle = '#ffffff';
        ctx.fillRect(0, 0, canvas.width, canvas.height);

        const tempImg = new Image();
        tempImg.crossOrigin = 'anonymous';
        tempImg.onload = function() {
            ctx.drawImage(tempImg, 60, 60, 1080, 1080);
            const pngUrl = canvas.toDataURL('image/png');
            const downloadLink = document.createElement('a');
            downloadLink.href = pngUrl;
            downloadLink.download = filename;
            document.body.appendChild(downloadLink);
            downloadLink.click();
            document.body.removeChild(downloadLink);

            if (typeof toastr !== 'undefined') {
                toastr.success('تم تحميل صورة الـ QR Code (PNG) بنجاح');
            }
        };
        tempImg.src = qrSrc;
    }

    // Download QR Code from modal as PNG
    function downloadQrAsPng() {
        const img = document.getElementById('modalQrImage');
        downloadDirectPng(currentModalQrCode, img.src);
    }

    // Print QR Code
    function printQrCode() {
        const title = $('#modalQrTitle').text();
        const qrSrc = $('#modalQrImage').attr('src');
        const url = $('#modalQrUrlInput').val();

        const printWindow = window.open('', '', 'width=800,height=700');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html lang="ar" dir="rtl">
            <head>
                <title>طباعة QR Code - ${title}</title>
                <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@600;700;900&display=swap" rel="stylesheet">
                <style>
                    body { font-family: 'Cairo', sans-serif; text-align: center; padding: 40px; }
                    .card { border: 2px solid #222; border-radius: 20px; padding: 30px; display: inline-block; max-width: 500px; }
                    h2 { margin-bottom: 10px; color: #1e155c; }
                    img { width: 320px; height: 320px; margin: 20px auto; }
                    .url { font-size: 14px; color: #555; word-break: break-all; margin-top: 15px; }
                </style>
            </head>
            <body>
                <div class="card">
                    <h2>فيك تحدي</h2>
                    <h3>${title}</h3>
                    <img src="${qrSrc}" alt="QR Code">
                    <p>امسح الرمز لزيارة الصفحة</p>
                    <div class="url">${url}</div>
                </div>
                <script>
                    window.onload = function() { window.print(); window.close(); }
                <\/script>
            </body>
            </html>
        `);
        printWindow.document.close();
    }

    // Full text modal
    function showFullTextModal(title, text) {
        $('#fullTextModalTitle').text(title);
        $('#fullTextModalBody').html(text);
        const modal = new bootstrap.Modal(document.getElementById('fullTextModal'));
        modal.show();
    }

    // Video modal
    function showVideoModal(title, videoSrc) {
        $('#videoModalTitle').text(title);
        const video = document.getElementById('modalVideoPlayer');
        video.src = videoSrc;
        const modal = new bootstrap.Modal(document.getElementById('videoModal'));
        modal.show();
        video.play().catch(()=>{});
    }

    function stopModalVideo() {
        const video = document.getElementById('modalVideoPlayer');
        video.pause();
        video.src = '';
    }

    $('#videoModal').on('hidden.bs.modal', function () {
        stopModalVideo();
    });

    // Image Modal
    function showImageModal(src) {
        $('#modalImagePreview').attr('src', src);
        const modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    }
</script>
@endsection
