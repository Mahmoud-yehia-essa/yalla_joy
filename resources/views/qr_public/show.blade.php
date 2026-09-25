<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    
    <title>{{ $qrCode->title }} | لعبة فيك تحدي 🎮🔥</title>
    <meta name="description" content="{{ Str::limit(strip_tags($qrCode->text_content), 160) }}">
    
    <!-- Open Graph Meta Tags for Social Sharing -->
    <meta property="og:title" content="{{ $qrCode->title }} | فيك تحدي">
    <meta property="og:description" content="{{ Str::limit(strip_tags($qrCode->text_content), 160) }}">
    <meta property="og:type" content="website">
    <meta property="og:url" content="{{ $qrCode->public_url }}">
    @if($qrCode->isImage())
        <meta property="og:image" content="{{ asset($qrCode->media_path) }}">
    @else
        <meta property="og:image" content="{{ asset('assets/images/logo_tahadi.png') }}">
    @endif

    <!-- Favicon -->
    <link rel="icon" type="image/png" href="{{ asset('assets/images/1024.png') }}" onerror="this.href='{{ asset('backend/assets/images/favicon-32x32.png') }}'">

    <!-- Google Fonts: Readex Pro, Tajawal, Cairo -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;700;800;900&family=Readex+Pro:wght@500;700;800;900&family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet">

    <!-- Font Awesome 6 -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">

    <style>
        :root {
            --bg-dark: #070a22;
            --bg-gradient: radial-gradient(circle at 50% 15%, #24166b 0%, #0d1238 45%, #060919 100%);
            --accent-magenta: #af227b;
            --accent-yellow: #ffd200;
            --accent-cyan: #78e0ff;
            --accent-purple: #8b5cf6;
            --card-bg: rgba(18, 24, 76, 0.75);
            --card-border: rgba(139, 92, 246, 0.4);
            --text-white: #ffffff;
            --text-gold: #ffd200;
            --text-muted: #b4c0d8;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Cairo', 'Tajawal', sans-serif;
            background-color: var(--bg-dark);
            background-image: var(--bg-gradient);
            color: var(--text-white);
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: space-between;
            overflow-x: hidden;
            position: relative;
            padding: 20px 15px 30px;
        }

        /* Ambient Glow Effects in Background */
        .ambient-glow-1 {
            position: absolute;
            top: -100px;
            left: 50%;
            transform: translateX(-50%);
            width: 500px;
            height: 500px;
            background: radial-gradient(circle, rgba(175, 34, 123, 0.25) 0%, rgba(139, 92, 246, 0.15) 50%, transparent 70%);
            filter: blur(60px);
            z-index: 0;
            pointer-events: none;
        }

        .ambient-glow-2 {
            position: absolute;
            bottom: 50px;
            right: 5%;
            width: 350px;
            height: 350px;
            background: radial-gradient(circle, rgba(120, 224, 255, 0.15) 0%, rgba(30, 21, 92, 0.2) 60%, transparent 70%);
            filter: blur(70px);
            z-index: 0;
            pointer-events: none;
        }

        /* Container */
        .main-container {
            width: 100%;
            max-width: 680px;
            z-index: 2;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 24px;
        }

        /* Logo Area */
        .logo-wrapper {
            display: flex;
            flex-direction: column;
            align-items: center;
            animation: fadeInDown 0.8s cubic-bezier(0.16, 1, 0.3, 1);
            margin-top: 10px;
        }

        .game-logo {
            max-width: 130px;
            height: auto;
            object-fit: contain;
            filter: drop-shadow(0 6px 18px rgba(139, 92, 246, 0.45));
            transition: transform 0.3s ease;
            animation: float 4s ease-in-out infinite;
        }

        @keyframes float {
            0%, 100% { transform: translateY(0px); }
            50% { transform: translateY(-8px); }
        }

        /* Main Display Card */
        .display-card {
            width: 100%;
            background: var(--card-bg);
            backdrop-filter: blur(24px);
            -webkit-backdrop-filter: blur(24px);
            border: 1.5px solid var(--card-border);
            border-radius: 28px;
            padding: 30px 24px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.6), 0 0 30px rgba(139, 92, 246, 0.2);
            animation: fadeInUp 0.9s cubic-bezier(0.16, 1, 0.3, 1);
            position: relative;
            overflow: hidden;
        }

        .display-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 3px;
            background: linear-gradient(90deg, var(--accent-magenta), var(--accent-yellow), var(--accent-cyan));
        }

        /* Card Header / Badge */
        .card-header-badge {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            background: linear-gradient(135deg, rgba(175, 34, 123, 0.3), rgba(139, 92, 246, 0.3));
            border: 1px solid rgba(255, 210, 0, 0.4);
            color: var(--accent-yellow);
            padding: 8px 18px;
            border-radius: 50px;
            font-size: 14px;
            font-weight: 800;
            margin-bottom: 20px;
            box-shadow: 0 4px 15px rgba(175, 34, 123, 0.2);
        }

        /* Title */
        .page-title {
            font-family: 'Readex Pro', 'Cairo', sans-serif;
            font-size: 24px;
            font-weight: 800;
            color: #ffffff;
            line-height: 1.4;
            margin-bottom: 18px;
            text-shadow: 0 2px 10px rgba(0, 0, 0, 0.5);
        }

        /* Text Content Highlight Box */
        .text-content-box {
            background: rgba(10, 15, 48, 0.65);
            border: 1.5px solid rgba(120, 224, 255, 0.25);
            border-radius: 20px;
            padding: 24px 20px;
            margin-bottom: 24px;
            position: relative;
            box-shadow: inset 0 2px 10px rgba(0,0,0,0.3);
        }

        .text-content-box::before {
            content: '\f10e';
            font-family: 'Font Awesome 6 Free';
            font-weight: 900;
            position: absolute;
            top: -12px;
            right: 20px;
            background: var(--accent-magenta);
            color: #fff;
            width: 26px;
            height: 26px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            box-shadow: 0 2px 8px rgba(175, 34, 123, 0.6);
        }

        .main-text {
            font-size: 19px;
            font-weight: 700;
            color: #ffffff;
            line-height: 1.85;
            white-space: pre-wrap;
            word-break: break-word;
            text-align: right;
            direction: rtl;
        }

        /* Media Container */
        .media-container {
            width: 100%;
            border-radius: 20px;
            overflow: hidden;
            background: rgba(0, 0, 0, 0.4);
            border: 1.5px solid rgba(139, 92, 246, 0.3);
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.5);
            margin-top: 10px;
            position: relative;
        }

        /* Image Display */
        .attached-image {
            width: 100%;
            max-height: 520px;
            object-fit: contain;
            display: block;
            border-radius: 18px;
            cursor: pointer;
            transition: transform 0.3s ease;
        }

        .attached-image:hover {
            transform: scale(1.01);
        }

        .image-hint {
            position: absolute;
            bottom: 12px;
            left: 12px;
            background: rgba(0,0,0,0.7);
            color: #fff;
            font-size: 11px;
            padding: 4px 10px;
            border-radius: 20px;
            pointer-events: none;
            backdrop-filter: blur(4px);
        }

        /* Video Player */
        .attached-video {
            width: 100%;
            max-height: 520px;
            display: block;
            border-radius: 18px;
            background: #000;
        }

        /* Action Buttons */
        .actions-group {
            display: flex;
            gap: 12px;
            width: 100%;
            margin-top: 24px;
            flex-wrap: wrap;
        }

        .btn-action {
            flex: 1;
            min-width: 140px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 13px 20px;
            border-radius: 14px;
            font-size: 15px;
            font-weight: 800;
            text-decoration: none;
            cursor: pointer;
            border: none;
            transition: all 0.25s ease;
        }

        .btn-share {
            background: linear-gradient(135deg, var(--accent-magenta), #d946ef);
            color: #ffffff;
            box-shadow: 0 6px 20px rgba(175, 34, 123, 0.4);
        }

        .btn-share:hover {
            transform: translateY(-2px);
            box-shadow: 0 10px 25px rgba(175, 34, 123, 0.6);
            color: #fff;
        }

        .btn-copy {
            background: rgba(255, 255, 255, 0.1);
            color: #ffffff;
            border: 1px solid rgba(255, 255, 255, 0.2);
            backdrop-filter: blur(10px);
        }

        .btn-copy:hover {
            background: rgba(255, 255, 255, 0.2);
            transform: translateY(-2px);
            color: #fff;
        }

        /* App Download Banner */
        .app-download-card {
            width: 100%;
            background: rgba(18, 24, 76, 0.6);
            backdrop-filter: blur(16px);
            border: 1px solid rgba(139, 92, 246, 0.25);
            border-radius: 20px;
            padding: 20px;
            text-align: center;
            margin-top: 5px;
        }

        .app-download-title {
            font-size: 16px;
            font-weight: 800;
            color: var(--accent-yellow);
            margin-bottom: 6px;
        }

        .app-download-sub {
            font-size: 13px;
            color: var(--text-muted);
            margin-bottom: 14px;
        }

        .store-buttons {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 12px;
            flex-wrap: wrap;
        }

        .store-btn {
            display: inline-block;
            transition: transform 0.25s ease;
        }

        .store-btn img {
            height: 42px;
            width: auto;
            border-radius: 8px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.3);
        }

        .store-btn:hover {
            transform: scale(1.05);
        }

        /* Footer */
        .footer {
            margin-top: 30px;
            text-align: center;
            color: rgba(255, 255, 255, 0.5);
            font-size: 13px;
            font-weight: 600;
            z-index: 2;
        }

        .footer a {
            color: var(--accent-cyan);
            text-decoration: none;
        }

        /* Toast notification */
        .toast-notify {
            position: fixed;
            bottom: 30px;
            left: 50%;
            transform: translateX(-50%) translateY(100px);
            background: linear-gradient(135deg, #10b981, #059669);
            color: #ffffff;
            padding: 12px 28px;
            border-radius: 50px;
            font-size: 15px;
            font-weight: 800;
            box-shadow: 0 10px 30px rgba(0,0,0,0.5);
            z-index: 9999;
            opacity: 0;
            transition: all 0.35s cubic-bezier(0.16, 1, 0.3, 1);
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .toast-notify.show {
            transform: translateX(-50%) translateY(0);
            opacity: 1;
        }

        /* Lightbox Image Modal */
        .lightbox-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(4, 7, 26, 0.95);
            backdrop-filter: blur(10px);
            z-index: 99999;
            align-items: center;
            justify-content: center;
            padding: 20px;
        }

        .lightbox-modal.active {
            display: flex;
        }

        .lightbox-img {
            max-width: 95vw;
            max-height: 90vh;
            border-radius: 16px;
            box-shadow: 0 10px 40px rgba(0,0,0,0.8);
            object-fit: contain;
        }

        .lightbox-close {
            position: absolute;
            top: 20px;
            right: 20px;
            background: rgba(255,255,255,0.15);
            color: #fff;
            border: none;
            width: 44px;
            height: 44px;
            border-radius: 50%;
            font-size: 20px;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background 0.2s;
        }

        .lightbox-close:hover {
            background: rgba(255,255,255,0.3);
        }

        /* Animations */
        @keyframes fadeInDown {
            from { opacity: 0; transform: translateY(-25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        @keyframes fadeInUp {
            from { opacity: 0; transform: translateY(25px); }
            to { opacity: 1; transform: translateY(0); }
        }

        /* Responsive */
        @media (max-width: 480px) {
            .display-card {
                padding: 24px 16px;
                border-radius: 22px;
            }
            .game-logo {
                max-width: 105px;
            }
            .page-title {
                font-size: 20px;
            }
            .main-text {
                font-size: 16px;
                line-height: 1.75;
            }
            .text-content-box {
                padding: 18px 14px;
            }
            .btn-action {
                font-size: 14px;
                padding: 11px 16px;
            }
        }
    </style>
</head>
<body>

    <!-- Ambient Glows -->
    <div class="ambient-glow-1"></div>
    <div class="ambient-glow-2"></div>

    <div class="main-container">

        <!-- Game Logo Header -->
        <div class="logo-wrapper">
            <a href="{{ route('landing.page') }}" title="لعبة فيك تحدي">
                <img src="{{ asset('assets/images/logo_tahadi.png') }}" alt="شعار لعبة فيك تحدي" class="game-logo" onerror="this.src='{{ asset('frontend/landing/assets/images/logo_tahadi.png') }}'">
            </a>
        </div>

        @if($qrCode->status === 'inactive')
            <!-- Inactive Notice -->
            <div class="display-card text-center" style="border-color: rgba(239, 68, 68, 0.4);">
                <div class="card-header-badge" style="background: rgba(239, 68, 68, 0.2); border-color: #ef4444; color: #ef4444;">
                    <i class="fa-solid fa-circle-exclamation"></i> الرابط غير متاح حالياً
                </div>
                <h2 class="page-title">عذراً، هذه الصفحة غير نشطة مؤقتاً</h2>
                <p class="text-muted" style="color: var(--text-muted); font-size: 15px; margin-bottom: 20px;">
                    تم تعطيل هذا الرابط من قبل الإدارة أو انتهت صلاحية الفعالية. ترقبوا المزيد من التحديات قريباً!
                </p>
                <a href="{{ route('landing.page') }}" class="btn-action btn-share" style="display: inline-flex; width: auto; padding: 12px 30px;">
                    <i class="fa-solid fa-gamepad"></i> العودة للرئيسية
                </a>
            </div>
        @else
            <!-- Main Content Card -->
            <main class="display-card">


                <!-- Title -->
                @if(!empty($qrCode->title))
                    <h1 class="page-title text-center">{{ $qrCode->title }}</h1>
                @endif

                <!-- Text Content Box -->
                <div class="text-content-box">
                    <div class="main-text">{{ $qrCode->text_content }}</div>
                </div>

                <!-- Attached Media (Image or Video) -->
                @if($qrCode->isImage())
                    <div class="media-container" onclick="openLightbox('{{ asset($qrCode->media_path) }}')">
                        <img src="{{ asset($qrCode->media_path) }}" alt="{{ $qrCode->title }}" class="attached-image" loading="lazy">
                        <div class="image-hint">
                            <i class="fa-solid fa-expand me-1"></i> اضغط لتكبير الصورة
                        </div>
                    </div>
                @elseif($qrCode->isVideo())
                    <div class="media-container">
                        <video controls playsinline class="attached-video" preload="metadata" poster="{{ asset('assets/images/logo_tahadi.png') }}">
                            <source src="{{ asset($qrCode->media_path) }}">
                            متصفحك لا يدعم تشغيل هذا الفيديو.
                        </video>
                    </div>
                @endif

            </main>

            <!-- Download App Section -->
            <div class="app-download-card">
                <div class="app-download-title">
                    <i class="fa-solid fa-trophy me-1"></i> حمل لعبة "فيك تحدي" الآن مجاناً!
                </div>
                <div class="store-buttons">
                    <a href="https://apps.apple.com/us/app/feek-tahadi-%D9%81%D9%8A%D9%83-%D8%AA%D8%AD%D8%AF%D9%8A/id6780262870" target="_blank" class="store-btn" title="تحميل من App Store">
                        <img src="{{ asset('landing/images/app-store.png') }}" alt="App Store" onerror="this.style.display='none'">
                    </a>
                    <a href="https://play.google.com/store/apps/details?id=net.fiktahadi.fiktahadi_app" target="_blank" class="store-btn" title="تحميل من Google Play">
                        <img src="{{ asset('landing/images/google-play.png') }}" alt="Google Play" onerror="this.style.display='none'">
                    </a>
                </div>
            </div>
        @endif

        <!-- Footer -->
        <footer class="footer">
            <p>© 2026 جميع الحقوق محفوظة لـ شركة برفورمانس انك كويت للاستشارات د.م.م — Performance Inc Kuwait consulting w.L.L</p>
        </footer>

    </div>

    <!-- Toast Notification -->
    <div id="toastNotify" class="toast-notify">
        <i class="fa-solid fa-circle-check"></i>
        <span id="toastMsg">تم نسخ الرابط بنجاح!</span>
    </div>

    <!-- Lightbox Modal -->
    <div id="lightboxModal" class="lightbox-modal" onclick="closeLightbox()">
        <button type="button" class="lightbox-close" onclick="closeLightbox()">&times;</button>
        <img id="lightboxImg" src="" alt="صورة كبيرة" class="lightbox-img" onclick="event.stopPropagation()">
    </div>

    <script>
        // Show Toast Notification
        function showToast(message) {
            const toast = document.getElementById('toastNotify');
            const msgEl = document.getElementById('toastMsg');
            msgEl.innerText = message;
            toast.classList.add('show');
            setTimeout(() => {
                toast.classList.remove('show');
            }, 2600);
        }

        // Copy Page URL
        function copyPageLink() {
            const url = window.location.href;
            if (navigator.clipboard) {
                navigator.clipboard.writeText(url).then(() => {
                    showToast('تم نسخ الرابط إلى الحافظة بنجاح!');
                }).catch(() => {
                    fallbackCopy(url);
                });
            } else {
                fallbackCopy(url);
            }
        }

        function fallbackCopy(text) {
            const temp = document.createElement('input');
            temp.value = text;
            document.body.appendChild(temp);
            temp.select();
            document.execCommand('copy');
            document.body.removeChild(temp);
            showToast('تم نسخ الرابط بنجاح!');
        }

        // Native Web Share API or Fallback
        function sharePage() {
            const title = "{{ addslashes($qrCode->title) }} - لعبة فيك تحدي";
            const text = "{{ addslashes(Str::limit(strip_tags($qrCode->text_content), 100)) }}";
            const url = window.location.href;

            if (navigator.share) {
                navigator.share({
                    title: title,
                    text: text,
                    url: url
                }).catch((err) => {
                    // Ignore cancel
                });
            } else {
                // Fallback to WhatsApp share
                const whatsappUrl = 'https://api.whatsapp.com/send?text=' + encodeURIComponent(title + '\n' + url);
                window.open(whatsappUrl, '_blank');
            }
        }

        // Lightbox Functions
        function openLightbox(src) {
            const modal = document.getElementById('lightboxModal');
            const img = document.getElementById('lightboxImg');
            img.src = src;
            modal.classList.add('active');
        }

        function closeLightbox() {
            const modal = document.getElementById('lightboxModal');
            modal.classList.remove('active');
        }

        document.addEventListener('keydown', function(e) {
            if (e.key === 'Escape') {
                closeLightbox();
            }
        });
    </script>
</body>
</html>
