<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>معاينة الحركة: {{ $animation->name }}</title>
    
    <!-- Cairo Font for Arabic & Inter for English -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@300;400;600;700;800&family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    
    <!-- Boxicons for modern icons -->
    <link href="https://unpkg.com/boxicons@2.1.4/css/boxicons.min.css" rel="stylesheet">
    
    <!-- Lottie Player -->
    <script src="https://unpkg.com/@lottiefiles/lottie-player@latest/dist/lottie-player.js"></script>

    <style>
        :root {
            --primary-color: #32296A;
            --primary-light: #4A3E94;
            --accent-color: #8C7CFA;
            --success-color: #10B981;
            --danger-color: #EF4444;
            --text-dark: #1E1B4B;
            --text-light: #F3F4F6;
            --bg-gradient: linear-gradient(135deg, #0F0C1B 0%, #1A1635 50%, #2D255B 100%);
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: 'Cairo', 'Inter', sans-serif;
            background: var(--bg-gradient);
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 20px;
            color: var(--text-light);
            overflow-x: hidden;
        }

        /* Glassmorphism Container */
        .preview-container {
            background: rgba(255, 255, 255, 0.05);
            backdrop-filter: blur(20px);
            -webkit-backdrop-filter: blur(20px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            border-radius: 24px;
            width: 100%;
            max-width: 900px;
            box-shadow: 0 20px 50px rgba(0, 0, 0, 0.4);
            display: grid;
            grid-template-columns: 1fr 1.2fr;
            overflow: hidden;
            animation: fadeIn 0.8s cubic-bezier(0.16, 1, 0.3, 1);
        }

        @media (max-width: 768px) {
            .preview-container {
                grid-template-columns: 1fr;
            }
        }

        /* Animation Section */
        .visual-panel {
            background: rgba(15, 12, 27, 0.4);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            padding: 40px 20px;
            position: relative;
            border-left: 1px solid rgba(255, 255, 255, 0.05);
        }

        @media (max-width: 768px) {
            .visual-panel {
                border-left: none;
                border-bottom: 1px solid rgba(255, 255, 255, 0.05);
            }
        }

        .lottie-wrapper {
            width: 280px;
            height: 280px;
            background: rgba(255, 255, 255, 0.02);
            border: 1px solid rgba(255, 255, 255, 0.05);
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            box-shadow: inset 0 0 30px rgba(140, 124, 250, 0.1), 0 10px 30px rgba(0, 0, 0, 0.2);
            position: relative;
            transition: transform 0.3s ease;
        }

        .lottie-wrapper:hover {
            transform: scale(1.02);
        }

        lottie-player {
            width: 90%;
            height: 90%;
        }

        /* Info Section */
        .info-panel {
            padding: 40px;
            display: flex;
            flex-direction: column;
            justify-content: space-between;
        }

        .header-section {
            margin-bottom: 30px;
        }

        .badges-row {
            display: flex;
            flex-wrap: wrap;
            gap: 10px;
            margin-bottom: 20px;
        }

        .badge {
            padding: 6px 14px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 6px;
            letter-spacing: 0.5px;
        }

        .badge-positive {
            background: rgba(16, 185, 129, 0.15);
            color: #34D399;
            border: 1px solid rgba(16, 185, 129, 0.2);
        }

        .badge-negative {
            background: rgba(239, 68, 68, 0.15);
            color: #F87171;
            border: 1px solid rgba(239, 68, 68, 0.2);
        }

        .badge-rank {
            background: rgba(140, 124, 250, 0.15);
            color: #A78BFA;
            border: 1px solid rgba(140, 124, 250, 0.2);
        }

        .badge-price {
            background: rgba(245, 158, 11, 0.15);
            color: #FBBF24;
            border: 1px solid rgba(245, 158, 11, 0.2);
        }

        .badge-free {
            background: rgba(6, 182, 212, 0.15);
            color: #22D3EE;
            border: 1px solid rgba(6, 182, 212, 0.2);
        }

        h1.title-ar {
            font-size: 28px;
            font-weight: 800;
            color: #FFFFFF;
            margin-bottom: 5px;
            line-height: 1.4;
        }

        h2.title-en {
            font-family: 'Inter', sans-serif;
            font-size: 18px;
            font-weight: 400;
            color: #9CA3AF;
            margin-bottom: 20px;
        }

        .description-box {
            background: rgba(255, 255, 255, 0.02);
            border-radius: 16px;
            padding: 20px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            margin-bottom: 30px;
        }

        .description-title {
            font-size: 14px;
            font-weight: 700;
            color: var(--accent-color);
            margin-bottom: 8px;
            text-transform: uppercase;
        }

        .description-text {
            font-size: 15px;
            color: #D1D5DB;
            line-height: 1.6;
        }

        /* Custom Interactive Controller */
        .controls-section {
            background: rgba(255, 255, 255, 0.03);
            border-radius: 20px;
            padding: 25px;
            border: 1px solid rgba(255, 255, 255, 0.05);
            display: flex;
            flex-direction: column;
            gap: 15px;
        }

        .media-status {
            display: flex;
            align-items: center;
            justify-content: space-between;
            font-size: 14px;
            color: #9CA3AF;
        }

        .btn-controls-group {
            display: flex;
            gap: 12px;
        }

        .btn-control {
            flex: 1;
            padding: 12px 20px;
            border-radius: 12px;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            transition: all 0.3s ease;
            border: none;
        }

        .btn-play-all {
            background: #8C7CFA;
            color: #FFFFFF;
            box-shadow: 0 4px 15px rgba(140, 124, 250, 0.3);
        }

        .btn-play-all:hover {
            background: #A396FF;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(140, 124, 250, 0.4);
        }

        .btn-mute {
            background: rgba(255, 255, 255, 0.08);
            color: #D1D5DB;
            border: 1px solid rgba(255, 255, 255, 0.1);
        }

        .btn-mute:hover {
            background: rgba(255, 255, 255, 0.15);
            color: #FFFFFF;
        }

        /* Action Buttons */
        .footer-actions {
            display: flex;
            justify-content: space-between;
            margin-top: 30px;
        }

        .btn-action {
            padding: 10px 20px;
            border-radius: 10px;
            text-decoration: none;
            font-size: 14px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 8px;
            transition: all 0.3s ease;
        }

        .btn-back {
            background: transparent;
            color: #9CA3AF;
            border: 1px solid rgba(255, 255, 255, 0.15);
        }

        .btn-back:hover {
            color: #FFFFFF;
            background: rgba(255, 255, 255, 0.05);
            border-color: rgba(255, 255, 255, 0.3);
        }

        /* Hidden standard audio player */
        audio {
            display: none;
        }

        /* Animations */
        @keyframes fadeIn {
            from {
                opacity: 0;
                transform: scale(0.96) translateY(10px);
            }
            to {
                opacity: 1;
                transform: scale(1) translateY(0);
            }
        }
    </style>
</head>
<body>

    <div class="preview-container">
        
        <!-- Left Column: Visual Panel -->
        <div class="visual-panel">
            <div class="lottie-wrapper">
                @if($animation->file_path)
                    <lottie-player id="lottiePlayer" src="{{ asset($animation->file_path) }}" background="transparent" speed="1" loop autoplay></lottie-player>
                @else
                    <div style="text-align: center; color: var(--danger-color);">
                        <i class="bx bx-error-circle" style="font-size: 48px;"></i>
                        <p style="margin-top: 10px; font-weight: 700;">لا يوجد ملف حركة</p>
                    </div>
                @endif
            </div>
            
            @if($animation->audio)
                <audio id="audioPlayer" loop>
                    <source src="{{ asset($animation->audio) }}" type="audio/mpeg">
                    متصفحك لا يدعم تشغيل الصوت.
                </audio>
            @endif
        </div>
        
        <!-- Right Column: Info & Details -->
        <div class="info-panel">
            <div class="header-section">
                <!-- Badges Row -->
                <div class="badges-row">
                    @if($animation->type == 'positive')
                        <span class="badge badge-positive"><i class="bx bx-smile"></i> إيجابية</span>
                    @else
                        <span class="badge badge-negative"><i class="bx bx-sad"></i> سلبية</span>
                    @endif
                    
                    <span class="badge badge-rank"><i class="bx bx-award"></i> {{ $animation->rankingNew ? $animation->rankingNew->rank_name : 'غير محدد' }}</span>
                    
                    @if($animation->is_free == 1)
                        <span class="badge badge-free"><i class="bx bx-gift"></i> مجانية</span>
                    @else
                        <span class="badge badge-price"><i class="bx bx-coin-stack"></i> {{ $animation->coin ? $animation->coin->name : 'عملة' }} / {{ $animation->coin_amount ?? 0 }}</span>
                    @endif
                </div>

                <!-- Main Titles -->
                <h1 class="title-ar">{{ $animation->name }}</h1>
                <h2 class="title-en">{{ $animation->name_en }}</h2>
            </div>

            <!-- Description Box -->
            <div class="description-box">
                <div class="description-title">الوصف والتعليق</div>
                <div class="description-text">
                    {{ $animation->description ?: 'لا يوجد وصف متاح لهذه الحركة حالياً.' }}
                    @if($animation->description_en)
                        <div style="font-family: 'Inter', sans-serif; font-size: 14px; margin-top: 10px; color: #9CA3AF; direction: ltr; text-align: left;">
                            {{ $animation->description_en }}
                        </div>
                    @endif
                </div>
            </div>

            <!-- Interactive Controller -->
            <div class="controls-section">
                <div class="media-status">
                    <span id="playerStatus"><i class="bx bx-play-circle"></i> الحركة والصوت متزامنان</span>
                    @if($animation->audio)
                        <span><i class="bx bx-volume-full"></i> يحتوي على صوت</span>
                    @else
                        <span><i class="bx bx-volume-mute"></i> لا يحتوي على صوت</span>
                    @endif
                </div>
                
                <div class="btn-controls-group">
                    <button class="btn-control btn-play-all" onclick="togglePlayAll()">
                        <i class="bx bx-pause" id="playIcon"></i>
                        <span id="playText">إيقاف مؤقت</span>
                    </button>
                    
                    @if($animation->audio)
                        <button class="btn-control btn-mute" onclick="toggleMute()">
                            <i class="bx bx-volume-mute" id="muteIcon"></i>
                            <span id="muteText">كتم الصوت</span>
                        </button>
                    @endif
                </div>
            </div>

            <!-- Footer Actions -->
            <div class="footer-actions">
                <a href="{{ route('all.animation') }}" class="btn-action btn-back">
                    <i class="bx bx-arrow-right"></i> العودة للوحة التحكم
                </a>
                <button class="btn-action btn-back" onclick="window.close()">
                    إغلاق الصفحة <i class="bx bx-x"></i>
                </button>
            </div>
        </div>

    </div>

    <script>
        const player = document.getElementById('lottiePlayer');
        const audio = document.getElementById('audioPlayer');
        let isPlaying = true;
        let isMuted = false;

        // Auto-play audio when loaded
        window.addEventListener('DOMContentLoaded', () => {
            if (audio) {
                // Try auto-play
                audio.play().catch(error => {
                    console.log("Auto-play blocked by browser. User interaction required.");
                    // Update play button text to prompt user to play
                    const playText = document.getElementById('playText');
                    const playIcon = document.getElementById('playIcon');
                    playText.innerText = "تشغيل الحركة والصوت";
                    playIcon.className = "bx bx-play";
                    isPlaying = false;
                    if (player) player.pause();
                });
            }
        });

        function togglePlayAll() {
            const playIcon = document.getElementById('playIcon');
            const playText = document.getElementById('playText');
            
            if (isPlaying) {
                // Pause everything
                if (player) player.pause();
                if (audio) audio.pause();
                playIcon.className = "bx bx-play";
                playText.innerText = "تشغيل الكل";
                isPlaying = false;
            } else {
                // Play everything
                if (player) player.play();
                if (audio) audio.play();
                playIcon.className = "bx bx-pause";
                playText.innerText = "إيقاف مؤقت";
                isPlaying = true;
            }
        }

        function toggleMute() {
            const muteIcon = document.getElementById('muteIcon');
            const muteText = document.getElementById('muteText');
            
            if (audio) {
                if (isMuted) {
                    audio.muted = false;
                    muteIcon.className = "bx bx-volume-mute";
                    muteText.innerText = "كتم الصوت";
                    isMuted = false;
                } else {
                    audio.muted = true;
                    muteIcon.className = "bx bx-volume-full";
                    muteText.innerText = "تشغيل الصوت";
                    isMuted = true;
                }
            }
        }
    </script>
</body>
</html>
