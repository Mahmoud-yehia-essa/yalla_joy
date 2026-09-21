<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  
  <title>قريباً | لعبة فيك تحدي 🎮🔥</title>
  <meta name="description" content="قريباً.. لعبة فيك تحدي التنافسية الأكثر حماساً وتحدياً على App Store و Google Play!" />
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('assets/images/1024.png') }}" />
  <link rel="apple-touch-icon" href="{{ asset('assets/images/1024.png') }}" />

  <!-- Google Fonts: Readex Pro & Tajawal -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@500;700;800;900&family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet" />

  <!-- Font Awesome -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <style>
    :root {
      --bg-dark: #070a22;
      --bg-gradient: radial-gradient(circle at 50% 20%, #1e155c 0%, #0d1238 45%, #060919 100%);
      --accent-magenta: #af227b;
      --accent-yellow: #ffd200;
      --accent-cyan: #78e0ff;
      --accent-purple: #8b5cf6;
      --card-bg: rgba(18, 24, 76, 0.7);
      --card-border: rgba(139, 92, 246, 0.35);
      --text-white: #ffffff;
      --text-muted: #94a3b8;
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      min-height: 100vh;
      font-family: 'Tajawal', sans-serif;
      background: var(--bg-dark);
      background-image: var(--bg-gradient);
      color: var(--text-white);
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      overflow-x: hidden;
      position: relative;
      padding: 20px;
    }

    /* Ambient Background Glows */
    .ambient-glow-1 {
      position: absolute;
      top: -100px;
      right: -100px;
      width: 400px;
      height: 400px;
      background: radial-gradient(circle, rgba(175, 34, 123, 0.35) 0%, transparent 70%);
      filter: blur(60px);
      pointer-events: none;
      z-index: 0;
      animation: floatSlow 8s ease-in-out infinite alternate;
    }

    .ambient-glow-2 {
      position: absolute;
      bottom: -100px;
      left: -100px;
      width: 450px;
      height: 450px;
      background: radial-gradient(circle, rgba(120, 224, 255, 0.25) 0%, transparent 70%);
      filter: blur(70px);
      pointer-events: none;
      z-index: 0;
      animation: floatSlow 10s ease-in-out infinite alternate-reverse;
    }

    @keyframes floatSlow {
      0% { transform: translateY(0) scale(1); }
      100% { transform: translateY(40px) scale(1.1); }
    }

    /* Main Container */
    .soon-container {
      position: relative;
      z-index: 1;
      width: 100%;
      max-width: 640px;
      background: var(--card-bg);
      border: 1px solid var(--card-border);
      backdrop-filter: blur(20px);
      -webkit-backdrop-filter: blur(20px);
      border-radius: 28px;
      padding: 45px 35px;
      text-align: center;
      box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.6), 0 0 40px rgba(139, 92, 246, 0.2);
      animation: fadeInScale 0.8s cubic-bezier(0.16, 1, 0.3, 1);
    }

    @keyframes fadeInScale {
      0% {
        opacity: 0;
        transform: scale(0.92) translateY(20px);
      }
      100% {
        opacity: 1;
        transform: scale(1) translateY(0);
      }
    }

    /* Logo Styling */
    .logo-wrapper {
      position: relative;
      display: inline-block;
      margin-bottom: 25px;
    }

    .logo-img {
      width: 130px;
      height: 130px;
      object-fit: contain;
      border-radius: 26px;
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.45);
      border: 2px solid rgba(255, 210, 0, 0.4);
      animation: pulseLogo 3s ease-in-out infinite alternate;
    }

    @keyframes pulseLogo {
      0% { transform: translateY(0); box-shadow: 0 10px 25px rgba(175, 34, 123, 0.4); }
      100% { transform: translateY(-8px); box-shadow: 0 20px 35px rgba(255, 210, 0, 0.45); }
    }

    /* Soon Badge */
    .soon-badge {
      display: inline-flex;
      align-items: center;
      gap: 8px;
      background: linear-gradient(135deg, rgba(175, 34, 123, 0.4) 0%, rgba(139, 92, 246, 0.4) 100%);
      border: 1px solid rgba(255, 210, 0, 0.4);
      color: var(--accent-yellow);
      padding: 7px 20px;
      border-radius: 50px;
      font-family: 'Readex Pro', sans-serif;
      font-weight: 700;
      font-size: 0.95rem;
      margin-bottom: 20px;
      box-shadow: 0 4px 15px rgba(0, 0, 0, 0.3);
    }

    .pulse-dot {
      width: 10px;
      height: 10px;
      background-color: var(--accent-yellow);
      border-radius: 50%;
      box-shadow: 0 0 10px var(--accent-yellow);
      animation: blink 1.5s infinite;
    }

    @keyframes blink {
      0%, 100% { opacity: 1; transform: scale(1); }
      50% { opacity: 0.4; transform: scale(0.75); }
    }

    /* Titles */
    h1 {
      font-family: 'Readex Pro', sans-serif;
      font-weight: 900;
      font-size: 2.3rem;
      line-height: 1.3;
      margin-bottom: 15px;
      background: linear-gradient(135deg, #ffffff 30%, #ffd200 70%, #ff8c00 100%);
      -webkit-background-clip: text;
      -webkit-text-fill-color: transparent;
      text-shadow: 0 4px 20px rgba(255, 210, 0, 0.15);
    }

    p.subtitle {
      font-size: 1.15rem;
      line-height: 1.8;
      color: var(--text-muted);
      margin-bottom: 30px;
      font-weight: 500;
    }

    /* Feature tags */
    .features-row {
      display: flex;
      justify-content: center;
      gap: 12px;
      flex-wrap: wrap;
      margin-bottom: 30px;
    }

    .feature-pill {
      background: rgba(255, 255, 255, 0.06);
      border: 1px solid rgba(255, 255, 255, 0.12);
      padding: 8px 16px;
      border-radius: 12px;
      font-size: 0.9rem;
      font-weight: 600;
      color: #e2e8f0;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .feature-pill i {
      color: var(--accent-cyan);
    }

    /* Stores section */
    .stores-preview {
      display: flex;
      justify-content: center;
      gap: 15px;
      flex-wrap: wrap;
      margin-top: 10px;
    }

    .store-badge {
      display: flex;
      align-items: center;
      gap: 10px;
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.18);
      padding: 10px 22px;
      border-radius: 14px;
      color: var(--text-white);
      font-size: 0.95rem;
      font-weight: 700;
      box-shadow: 0 4px 12px rgba(0,0,0,0.25);
      transition: all 0.3s ease;
    }

    .store-badge i {
      font-size: 1.35rem;
      color: var(--accent-yellow);
    }

    /* Footer Copyright */
    .footer-text {
      margin-top: 25px;
      font-size: 0.85rem;
      color: #64748b;
      font-weight: 500;
    }

    @media (max-width: 480px) {
      .soon-container {
        padding: 35px 20px;
        border-radius: 22px;
      }
      h1 {
        font-size: 1.85rem;
      }
      p.subtitle {
        font-size: 1rem;
      }
      .logo-img {
        width: 105px;
        height: 105px;
      }
      .store-badge {
        padding: 8px 16px;
        font-size: 0.85rem;
      }
    }
  </style>
</head>
<body>

  <!-- Ambient Glows -->
  <div class="ambient-glow-1"></div>
  <div class="ambient-glow-2"></div>

  <!-- Main Box -->
  <div class="soon-container">
    
    <!-- Logo -->
    <div class="logo-wrapper">
      <img src="{{ asset('assets/images/1024.png') }}" onerror="this.onerror=null; this.src='{{ asset('frontend/landing/assets/images/1024.png') }}';" alt="فيك تحدي" class="logo-img" />
    </div>

    <!-- Badge -->
    <div>
      <div class="soon-badge">
        <span class="pulse-dot"></span>
        <span>انتظرونا قريباً</span>
      </div>
    </div>

    <!-- Main Title -->
    <h1>قريباً لعبة فيك تحدي 🔥🎮</h1>

    <!-- Description -->
    <p class="subtitle">
      نعمل حالياً على إعداد وتجهيز التجربة التنافسية الأقوى والأكثر حماساً للجلسات والجمعات والتحديات المباشرة!
    </p>

    <!-- Features -->
    <div class="features-row">
      <div class="feature-pill">
        <i class="fa-solid fa-gamepad"></i>
        <span>لعبة الجلسة</span>
      </div>
      <div class="feature-pill">
        <i class="fa-solid fa-trophy"></i>
        <span>لعبة الميدان أونلاين</span>
      </div>
      <div class="feature-pill">
        <i class="fa-solid fa-wand-magic-sparkles"></i>
        <span>وسائل مساعدة حصرية</span>
      </div>
    </div>

    <!-- Stores Preview -->
    <div class="stores-preview">
      <div class="store-badge">
        <i class="fa-brands fa-apple"></i>
        <span>App Store</span>
      </div>
      <div class="store-badge">
        <i class="fa-brands fa-google-play"></i>
        <span>Google Play</span>
      </div>
    </div>

    <div class="footer-text">
      جميع الحقوق محفوظة &copy; {{ date('Y') }} لعبة فيك تحدي
    </div>

  </div>

</body>
</html>
