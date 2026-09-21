<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=5.0" />
  <meta http-equiv="X-UA-Compatible" content="ie=edge" />
  
  <title>فيك تحدي؟ | قريباً على App Store و Google Play 🎮🔥</title>
  <meta name="description" content="تطبيق فيك تحدي - اللعبة التنافسية الأكثر مرحاً وحماساً للجلسات والجمعات العائلية والتحديات الأونلاين المباشرة مع وسائل مساعدة خارقة ومكافآت حقيقية." />
  <meta name="keywords" content="فيك تحدي, لعبة الجلسة, لعبة الميدان, العاب جماعية, تحدي, اسئلة, ذكاء, كويت, ترفيه, العاب اونلاين" />
  
  <!-- Open Graph / Social Media -->
  <meta property="og:type" content="website" />
  <meta property="og:title" content="فيك تحدي؟ | قريباً على App Store و Google Play 🎮🔥" />
  <meta property="og:description" content="اللعبة التنافسية الأولى للجمعات والتحديات.. العب لعبة الجلسة أو الميدان أونلاين، واستخدم وسائل المساعدة وتحدّ أصحابك!" />
  <meta property="og:image" content="{{ asset('assets/images/1024.png') }}" />
  
  <!-- Favicon -->
  <link rel="icon" type="image/png" href="{{ asset('assets/images/1024.png') }}" />
  <link rel="apple-touch-icon" href="{{ asset('assets/images/1024.png') }}" />

  <!-- Google Fonts: Readex Pro & Tajawal -->
  <link rel="preconnect" href="https://fonts.googleapis.com" />
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin />
  <link href="https://fonts.googleapis.com/css2?family=Readex+Pro:wght@400;500;600;700;800;900&family=Tajawal:wght@400;500;700;900&display=swap" rel="stylesheet" />

  <!-- Font Awesome Icons -->
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" />

  <!-- SweetAlert2 -->
  <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

  <style>
    /* ==========================================================================
       1. APP EXACT BRAND IDENTITY & VARIABLES (من التطبيق وفلاتر مباشرة)
       ========================================================================== */
    :root {
      /* ألوان التطبيق الأصلية */
      --app-bg-dark: #0d1238;
      --app-navy-deep: #141a7c;
      --app-navy-card: rgba(20, 26, 124, 0.7);
      --app-navy-card-hover: rgba(26, 36, 150, 0.85);
      
      --app-magenta: #af227b;
      --app-yellow: #ffd200;
      --app-cyan: #78e0ff;
      --app-purple-border: #b294fb;
      --app-text-blue: #14376f;
      --app-orange: #ff6a00;
      
      --text-white: #ffffff;
      --text-muted: #cbd5e1;
      
      --font-heading: 'Readex Pro', sans-serif;
      --font-body: 'Tajawal', sans-serif;
      
      --transition-smooth: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    /* ==========================================================================
       2. RESET & BASE STYLES
       ========================================================================== */
    *, *::before, *::after {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    html {
      scroll-behavior: smooth;
      font-size: 16px;
      overflow-x: hidden;
    }

    body {
      font-family: var(--font-body);
      background-color: var(--app-bg-dark);
      color: var(--text-white);
      line-height: 1.6;
      overflow-x: hidden;
      position: relative;
      background-image: 
        radial-gradient(circle at 15% 15%, rgba(175, 34, 123, 0.18) 0%, transparent 40%),
        radial-gradient(circle at 85% 35%, rgba(120, 224, 255, 0.12) 0%, transparent 45%),
        radial-gradient(circle at 50% 80%, rgba(20, 26, 124, 0.35) 0%, transparent 55%);
      background-attachment: fixed;
    }

    h1, h2, h3, h4, h5, h6 {
      font-family: var(--font-heading);
      font-weight: 700;
      line-height: 1.3;
    }

    a {
      text-decoration: none;
      color: inherit;
      transition: var(--transition-smooth);
    }

    img {
      max-width: 100%;
      height: auto;
      display: block;
    }

    .container {
      width: 100%;
      max-width: 1200px;
      margin: 0 auto;
      padding: 0 1.25rem;
    }

    /* ==========================================================================
       3. BACKGROUND FLOATING PARTICLES
       ========================================================================== */
    .bg-particles {
      position: fixed;
      top: 0;
      left: 0;
      width: 100%;
      height: 100%;
      pointer-events: none;
      z-index: 0;
      overflow: hidden;
    }

    .particle {
      position: absolute;
      border-radius: 50%;
      opacity: 0.25;
      animation: floatUp 16s infinite linear;
    }

    @keyframes floatUp {
      0% {
        transform: translateY(105vh) scale(0.8);
        opacity: 0;
      }
      20% { opacity: 0.35; }
      80% { opacity: 0.35; }
      100% {
        transform: translateY(-10vh) scale(1.1);
        opacity: 0;
      }
    }

    /* ==========================================================================
       4. NAVBAR
       ========================================================================== */
    .navbar {
      position: fixed;
      top: 0;
      left: 0;
      right: 0;
      z-index: 1000;
      padding: 0.85rem 0;
      background: rgba(13, 18, 56, 0.88);
      backdrop-filter: blur(16px);
      -webkit-backdrop-filter: blur(16px);
      border-bottom: 1px solid rgba(178, 148, 251, 0.2);
      transition: var(--transition-smooth);
    }

    .navbar.scrolled {
      padding: 0.6rem 0;
      background: rgba(13, 18, 56, 0.98);
      box-shadow: 0 8px 25px rgba(0, 0, 0, 0.4);
    }

    .nav-container {
      display: flex;
      align-items: center;
      justify-content: space-between;
    }

    .brand-logo {
      display: flex;
      align-items: center;
      gap: 0.75rem;
      font-family: var(--font-heading);
      font-weight: 800;
      font-size: 1.35rem;
      color: #fff;
    }

    .brand-logo img {
      width: 44px;
      height: 44px;
      border-radius: 12px;
      border: 2px solid var(--app-yellow);
    }

    .brand-logo span {
      color: var(--app-yellow);
    }

    .nav-links {
      display: flex;
      align-items: center;
      gap: 1.8rem;
      list-style: none;
    }

    .nav-link {
      font-family: var(--font-heading);
      font-size: 0.95rem;
      font-weight: 500;
      color: var(--text-muted);
      position: relative;
      padding: 0.35rem 0;
    }

    .nav-link:hover, .nav-link.active {
      color: var(--app-yellow);
    }

    .nav-actions {
      display: flex;
      align-items: center;
      gap: 1rem;
    }

    .btn-nav-soon {
      background: var(--app-yellow);
      color: var(--app-text-blue);
      font-family: var(--font-heading);
      font-weight: 800;
      font-size: 0.9rem;
      padding: 0.55rem 1.3rem;
      border-radius: 50px;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      border: 2px solid #fff;
      cursor: pointer;
      box-shadow: 0 4px 15px rgba(255, 210, 0, 0.3);
      transition: var(--transition-smooth);
    }

    .btn-nav-soon:hover {
      background: #fff;
      transform: translateY(-2px);
    }

    .menu-toggle {
      display: none;
      background: none;
      border: none;
      color: #fff;
      font-size: 1.5rem;
      cursor: pointer;
      padding: 0.25rem;
    }

    /* Mobile Drawer */
    .mobile-drawer {
      position: fixed;
      top: 0;
      right: -100%;
      width: 80%;
      max-width: 320px;
      height: 100vh;
      background: var(--app-bg-dark);
      z-index: 1001;
      padding: 2rem 1.5rem;
      display: flex;
      flex-direction: column;
      gap: 1.5rem;
      border-left: 1px solid var(--app-purple-border);
      transition: right 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .mobile-drawer.open {
      right: 0;
    }

    .drawer-overlay {
      position: fixed;
      inset: 0;
      background: rgba(0, 0, 0, 0.65);
      z-index: 1000;
      opacity: 0;
      visibility: hidden;
      transition: var(--transition-smooth);
    }

    .drawer-overlay.active {
      opacity: 1;
      visibility: visible;
    }

    .drawer-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      padding-bottom: 1rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .drawer-close {
      background: none;
      border: none;
      color: var(--text-muted);
      font-size: 1.5rem;
      cursor: pointer;
    }

    .drawer-links {
      display: flex;
      flex-direction: column;
      gap: 0.9rem;
      list-style: none;
    }

    .drawer-link {
      font-family: var(--font-heading);
      font-size: 1.05rem;
      font-weight: 600;
      color: #fff;
      display: flex;
      align-items: center;
      gap: 0.75rem;
      padding: 0.6rem 0.5rem;
      border-radius: 8px;
    }

    .drawer-link:hover {
      background: rgba(255, 255, 255, 0.06);
      color: var(--app-yellow);
    }

    /* ==========================================================================
       5. HERO SECTION (بدون تكرار الشعار - الشعار الرسمي الواحد واسفله قريباً)
       ========================================================================== */
    .hero-section {
      position: relative;
      min-height: 100vh;
      padding-top: 7.5rem;
      padding-bottom: 4rem;
      display: flex;
      align-items: center;
      z-index: 1;
    }

    .hero-grid {
      display: flex;
      flex-direction: column;
      align-items: center;
      justify-content: center;
      text-align: center;
      max-width: 860px;
      margin: 0 auto;
    }

    .hero-content {
      text-align: center;
      display: flex;
      flex-direction: column;
      align-items: center;
    }

    /* Hero Brand Banner: الشعار الواحد البارز وأسفله قريباً */
    .hero-brand-card {
      display: inline-flex;
      flex-direction: column;
      align-items: center;
      gap: 1.25rem;
      margin-bottom: 1.5rem;
    }

    .hero-main-logo-img {
      max-width: 380px;
      width: 100%;
      height: auto;
      filter: drop-shadow(0 10px 25px rgba(0, 0, 0, 0.5));
    }

    .hero-soon-pill {
      background: var(--app-yellow);
      color: var(--app-text-blue);
      font-family: var(--font-heading);
      font-size: 1.35rem;
      font-weight: 900;
      padding: 0.55rem 1.8rem;
      border-radius: 50px;
      border: 3px solid #fff;
      box-shadow: 0 8px 25px rgba(255, 210, 0, 0.4);
      display: inline-flex;
      align-items: center;
      gap: 0.6rem;
    }

    .hero-subtitle {
      font-size: 1.2rem;
      font-weight: 500;
      color: var(--text-muted);
      margin-bottom: 1.8rem;
      line-height: 1.8;
      max-width: 580px;
    }

    .hero-badges-row {
      display: flex;
      flex-wrap: wrap;
      gap: 0.75rem;
      margin-bottom: 2rem;
    }

    .feature-tag {
      background: rgba(20, 26, 124, 0.5);
      border: 1.5px solid var(--app-purple-border);
      padding: 0.45rem 0.95rem;
      border-radius: 14px;
      font-size: 0.9rem;
      font-weight: 600;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      color: #fff;
    }

    .feature-tag i {
      color: var(--app-cyan);
    }

    .hero-cta-group {
      display: flex;
      flex-wrap: wrap;
      align-items: center;
      gap: 1rem;
      margin-bottom: 2.25rem;
    }

    .btn-hero-primary {
      background: var(--app-yellow);
      color: var(--app-text-blue);
      font-family: var(--font-heading);
      font-weight: 900;
      font-size: 1.05rem;
      padding: 0.85rem 2rem;
      border-radius: 50px;
      display: inline-flex;
      align-items: center;
      gap: 0.65rem;
      border: 2px solid #fff;
      cursor: pointer;
      box-shadow: 0 8px 25px rgba(255, 210, 0, 0.35);
      transition: var(--transition-smooth);
    }

    .btn-hero-primary:hover {
      background: #fff;
      transform: translateY(-3px);
    }

    .btn-hero-secondary {
      background: rgba(255, 255, 255, 0.08);
      color: #fff;
      font-family: var(--font-heading);
      font-weight: 700;
      font-size: 1rem;
      padding: 0.85rem 1.75rem;
      border-radius: 50px;
      display: inline-flex;
      align-items: center;
      gap: 0.5rem;
      border: 1.5px solid var(--app-cyan);
      cursor: pointer;
      transition: var(--transition-smooth);
    }

    .btn-hero-secondary:hover {
      background: rgba(120, 224, 255, 0.15);
      transform: translateY(-2px);
    }

    /* Store Badges - Authentic Official Design */
    .store-badges-container {
      display: flex;
      align-items: center;
      flex-wrap: wrap;
      gap: 1rem;
    }

    .store-badge-card {
      background: linear-gradient(145deg, rgba(13, 18, 56, 0.92) 0%, rgba(20, 26, 124, 0.78) 100%);
      border: 1.5px solid rgba(178, 148, 251, 0.35);
      border-radius: 16px;
      padding: 0.65rem 1.15rem;
      display: inline-flex;
      align-items: center;
      gap: 0.9rem;
      transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
      cursor: pointer;
      box-shadow: 0 8px 24px rgba(0, 0, 0, 0.35);
      position: relative;
      overflow: hidden;
      backdrop-filter: blur(10px);
      text-decoration: none;
    }

    .store-badge-card::before {
      content: '';
      position: absolute;
      top: 0;
      left: -100%;
      width: 100%;
      height: 100%;
      background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.12), transparent);
      transition: 0.5s;
      pointer-events: none;
    }

    .store-badge-card:hover::before {
      left: 100%;
    }

    .store-badge-card.store-apple:hover {
      border-color: rgba(255, 255, 255, 0.75);
      background: linear-gradient(145deg, rgba(20, 26, 124, 0.95) 0%, rgba(13, 18, 56, 0.9) 100%);
      transform: translateY(-3px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4), 0 0 20px rgba(255, 255, 255, 0.18);
    }

    .store-badge-card.store-google:hover {
      border-color: rgba(0, 214, 255, 0.75);
      background: linear-gradient(145deg, rgba(20, 26, 124, 0.95) 0%, rgba(13, 18, 56, 0.9) 100%);
      transform: translateY(-3px);
      box-shadow: 0 12px 30px rgba(0, 0, 0, 0.4), 0 0 20px rgba(0, 214, 255, 0.25);
    }

    .store-icon-wrap {
      display: flex;
      align-items: center;
      justify-content: center;
      width: 32px;
      height: 32px;
      flex-shrink: 0;
    }

    .store-svg-icon {
      width: 28px;
      height: 28px;
      display: block;
      filter: drop-shadow(0 2px 5px rgba(0,0,0,0.35));
    }

    .store-text {
      text-align: right;
      display: flex;
      flex-direction: column;
      line-height: 1.15;
    }

    .store-text .sub {
      font-size: 0.68rem;
      color: rgba(255, 255, 255, 0.75);
      font-family: var(--font-heading);
      font-weight: 600;
    }

    .store-text .main {
      font-size: 1rem;
      font-weight: 800;
      color: #ffffff;
      font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif, var(--font-heading);
      letter-spacing: -0.2px;
    }

    .store-soon-tag {
      background: linear-gradient(135deg, var(--app-magenta) 0%, #d93d9b 100%);
      color: #fff;
      font-size: 0.68rem;
      font-weight: 800;
      padding: 0.25rem 0.65rem;
      border-radius: 20px;
      font-family: var(--font-heading);
      border: 1px solid rgba(255, 255, 255, 0.35);
      box-shadow: 0 2px 8px rgba(175, 34, 123, 0.4);
      display: inline-flex;
      align-items: center;
      gap: 0.35rem;
      white-space: nowrap;
    }

    .pulse-dot {
      width: 6px;
      height: 6px;
      background: #ffd200;
      border-radius: 50%;
      display: inline-block;
      box-shadow: 0 0 6px #ffd200;
      animation: pulseGlow 1.5s infinite ease-in-out;
    }

    @keyframes pulseGlow {
      0%, 100% { transform: scale(1); opacity: 0.8; }
      50% { transform: scale(1.4); opacity: 1; box-shadow: 0 0 10px #ffd200; }
    }

    /* Hero Interactive Game Board Preview (بدل تكرار الشعار) */
    .hero-game-preview-card {
      background: var(--app-navy-card);
      border: 2px solid var(--app-purple-border);
      border-radius: 28px;
      padding: 2rem;
      box-shadow: 0 20px 50px rgba(0, 0, 0, 0.5);
      position: relative;
    }

    .preview-header {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.25rem;
      padding-bottom: 0.85rem;
      border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    }

    .preview-header-title {
      font-size: 1.15rem;
      font-weight: 800;
      color: var(--app-yellow);
    }

    .preview-teams-row {
      display: grid;
      grid-template-columns: 1fr auto 1fr;
      align-items: center;
      gap: 1rem;
      margin-bottom: 1.5rem;
      text-align: center;
    }

    .team-badge {
      background: rgba(13, 18, 56, 0.8);
      border: 1.5px solid var(--app-cyan);
      border-radius: 14px;
      padding: 0.75rem 0.5rem;
    }

    .team-badge.team-active {
      border-color: var(--app-yellow);
      background: rgba(175, 34, 123, 0.35);
    }

    .team-name {
      font-size: 0.95rem;
      font-weight: 800;
      color: #fff;
    }

    .team-score {
      font-family: var(--font-heading);
      font-size: 1.2rem;
      font-weight: 900;
      color: var(--app-yellow);
    }

    .vs-text {
      font-family: var(--font-heading);
      font-weight: 900;
      font-size: 1.2rem;
      color: var(--app-magenta);
    }

    .preview-categories-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 0.6rem;
    }

    .cate-point-box {
      background: rgba(13, 18, 56, 0.8);
      border: 1px solid rgba(255, 255, 255, 0.15);
      border-radius: 12px;
      padding: 0.65rem 0.4rem;
      text-align: center;
    }

    .cate-point-box.highlight {
      border-color: var(--app-yellow);
      background: rgba(255, 210, 0, 0.15);
    }

    .point-val {
      font-family: var(--font-heading);
      font-weight: 800;
      font-size: 1rem;
      color: var(--app-yellow);
    }

    .point-time {
      font-size: 0.72rem;
      color: var(--text-muted);
    }

    /* ==========================================================================
       6. COMMON SECTION HEADINGS
       ========================================================================== */
    .section-padding {
      padding: 5.5rem 0;
      position: relative;
      z-index: 1;
    }

    .section-header {
      text-align: center;
      max-width: 750px;
      margin: 0 auto 3.5rem;
    }

    .section-badge {
      display: inline-flex;
      align-items: center;
      gap: 0.4rem;
      padding: 0.35rem 0.9rem;
      border-radius: 50px;
      background: rgba(175, 34, 123, 0.25);
      border: 1.5px solid var(--app-magenta);
      color: #fff;
      font-family: var(--font-heading);
      font-size: 0.85rem;
      font-weight: 700;
      margin-bottom: 0.85rem;
    }

    .section-title {
      font-size: 2.35rem;
      font-weight: 800;
      color: #fff;
      margin-bottom: 0.85rem;
    }

    .section-title span {
      color: var(--app-yellow);
    }

    .section-desc {
      font-size: 1.1rem;
      color: var(--text-muted);
      line-height: 1.7;
    }

    /* ==========================================================================
       7. GAME MODES SECTION (لعبة الجلسة & لعبة الميدان)
       ========================================================================== */
    .game-modes-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 2rem;
    }

    .mode-card {
      background: var(--app-navy-card);
      border: 2px solid var(--app-purple-border);
      border-radius: 26px;
      padding: 2.25rem 2rem;
      position: relative;
      box-shadow: 0 15px 40px rgba(0, 0, 0, 0.4);
      transition: var(--transition-smooth);
      display: flex;
      flex-direction: column;
      justify-content: space-between;
    }

    .mode-card:hover {
      transform: translateY(-6px);
      border-color: var(--app-yellow);
      background: var(--app-navy-card-hover);
    }

    .mode-card.mode-session {
      border-color: var(--app-magenta);
    }

    .mode-card.mode-field {
      border-color: var(--app-cyan);
    }

    .mode-card-header {
      display: flex;
      align-items: flex-start;
      justify-content: space-between;
      margin-bottom: 1.25rem;
    }

    .mode-icon-box {
      width: 64px;
      height: 64px;
      border-radius: 18px;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.9rem;
      border: 2px solid #fff;
    }

    .mode-session .mode-icon-box {
      background: var(--app-magenta);
      color: #fff;
    }

    .mode-field .mode-icon-box {
      background: var(--app-navy-deep);
      border-color: var(--app-cyan);
      color: var(--app-cyan);
    }

    .mode-badge-pill {
      font-family: var(--font-heading);
      font-size: 0.82rem;
      font-weight: 700;
      padding: 0.35rem 0.9rem;
      border-radius: 30px;
    }

    .mode-session .mode-badge-pill {
      background: rgba(175, 34, 123, 0.3);
      color: #ff85d0;
      border: 1px solid var(--app-magenta);
    }

    .mode-field .mode-badge-pill {
      background: rgba(120, 224, 255, 0.2);
      color: var(--app-cyan);
      border: 1px solid var(--app-cyan);
    }

    .mode-card-title {
      font-size: 1.8rem;
      font-weight: 800;
      color: #fff;
      margin-bottom: 0.6rem;
    }

    .mode-card-intro {
      font-size: 1.05rem;
      color: var(--text-muted);
      margin-bottom: 1.5rem;
      line-height: 1.65;
    }

    .mode-features-list {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 0.9rem;
      margin-bottom: 2rem;
    }

    .mode-feature-item {
      display: flex;
      align-items: flex-start;
      gap: 0.75rem;
      font-size: 0.95rem;
      color: #e2e8f0;
      line-height: 1.5;
    }

    .mode-feature-item i {
      margin-top: 0.25rem;
      font-size: 1rem;
      flex-shrink: 0;
    }

    .mode-session .mode-feature-item i {
      color: var(--app-yellow);
    }

    .mode-field .mode-feature-item i {
      color: var(--app-cyan);
    }

    .mode-stats-row {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 0.75rem;
      padding-top: 1.25rem;
      border-top: 1px solid rgba(255, 255, 255, 0.1);
    }

    .mode-stat-box {
      background: rgba(13, 18, 56, 0.8);
      border-radius: 14px;
      padding: 0.75rem 0.5rem;
      text-align: center;
      border: 1px solid rgba(255, 255, 255, 0.08);
    }

    .mode-stat-box .stat-num {
      font-family: var(--font-heading);
      font-size: 1.2rem;
      font-weight: 800;
      color: #fff;
    }

    .mode-session .stat-num { color: var(--app-yellow); }
    .mode-field .stat-num { color: var(--app-cyan); }

    .mode-stat-box .stat-label {
      font-size: 0.75rem;
      color: var(--text-muted);
      font-family: var(--font-heading);
    }

    /* ==========================================================================
       8. 7 OFFICIAL GAME HELPERS SECTION
       ========================================================================== */
    .helpers-section {
      background: rgba(13, 18, 56, 0.5);
    }

    .helpers-grid {
      display: grid;
      grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
      gap: 1.75rem;
    }

    .helper-card {
      background: var(--app-navy-card);
      border: 2px solid var(--app-purple-border);
      border-radius: 22px;
      padding: 1.75rem 1.4rem;
      display: flex;
      flex-direction: column;
      position: relative;
      box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
      transition: var(--transition-smooth);
    }

    .helper-card:hover {
      transform: translateY(-6px);
      border-color: var(--app-yellow);
      background: var(--app-navy-card-hover);
    }

    .helper-card-top {
      display: flex;
      align-items: center;
      justify-content: space-between;
      margin-bottom: 1.25rem;
    }

    .helper-img-wrap {
      width: 72px;
      height: 72px;
      border-radius: 18px;
      background: rgba(13, 18, 56, 0.9);
      border: 2px solid var(--app-cyan);
      display: flex;
      align-items: center;
      justify-content: center;
      padding: 6px;
    }

    .helper-card:hover .helper-img-wrap {
      border-color: var(--app-yellow);
      transform: scale(1.06);
    }

    .helper-img-wrap img {
      width: 100%;
      height: 100%;
      object-fit: contain;
    }

    .helper-number-badge {
      font-family: var(--font-heading);
      font-size: 0.85rem;
      font-weight: 800;
      color: var(--text-muted);
      background: rgba(255, 255, 255, 0.08);
      padding: 0.3rem 0.75rem;
      border-radius: 20px;
    }

    .helper-name {
      font-size: 1.45rem;
      font-weight: 800;
      color: #fff;
      margin-bottom: 0.2rem;
    }

    .helper-en-name {
      font-size: 0.82rem;
      color: var(--app-cyan);
      font-family: var(--font-heading);
      font-weight: 600;
      margin-bottom: 0.75rem;
    }

    .helper-action-title {
      font-size: 0.95rem;
      font-weight: 700;
      color: var(--app-yellow);
      margin-bottom: 0.6rem;
      font-family: var(--font-heading);
    }

    .helper-desc {
      font-size: 0.92rem;
      color: var(--text-muted);
      line-height: 1.6;
      margin-bottom: 1.25rem;
      flex-grow: 1;
    }

    .helper-tactic-box {
      background: rgba(13, 18, 56, 0.8);
      border-radius: 12px;
      padding: 0.65rem 0.85rem;
      font-size: 0.82rem;
      color: #e2e8f0;
      border-right: 3px solid var(--app-magenta);
      display: flex;
      align-items: center;
      gap: 0.5rem;
    }

    .helper-tactic-box i {
      color: var(--app-magenta);
    }



    /* ==========================================================================
       10. REWARDS & AVATAR SECTION
       ========================================================================== */
    .rewards-grid {
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 2rem;
    }

    .reward-box {
      background: var(--app-navy-card);
      border: 2px solid var(--app-purple-border);
      border-radius: 22px;
      padding: 2rem 1.5rem;
      text-align: center;
      transition: var(--transition-smooth);
    }

    .reward-box:hover {
      transform: translateY(-6px);
      border-color: var(--app-cyan);
    }

    .reward-icon-circle {
      width: 70px;
      height: 70px;
      border-radius: 50%;
      margin: 0 auto 1.25rem;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.85rem;
      background: rgba(13, 18, 56, 0.9);
      border: 2px solid var(--app-cyan);
      color: var(--app-cyan);
    }

    .reward-title {
      font-size: 1.3rem;
      font-weight: 800;
      color: #fff;
      margin-bottom: 0.65rem;
    }

    .reward-desc {
      font-size: 0.92rem;
      color: var(--text-muted);
      line-height: 1.6;
    }

    /* ==========================================================================
       11. DOWNLOAD CTA SECTION
       ========================================================================== */
    .download-cta-section {
      background: linear-gradient(135deg, var(--app-navy-deep) 0%, var(--app-magenta) 100%);
      border-radius: 32px;
      padding: 3.5rem 2rem;
      text-align: center;
      position: relative;
      border: 2px solid #fff;
      box-shadow: 0 25px 60px rgba(0, 0, 0, 0.6);
      margin-bottom: 4rem;
    }

    .cta-title {
      font-size: 2.5rem;
      font-weight: 900;
      color: #fff;
      margin-bottom: 0.85rem;
    }

    .cta-title span {
      color: var(--app-yellow);
    }

    .cta-desc {
      font-size: 1.15rem;
      color: #f1f5f9;
      max-width: 620px;
      margin: 0 auto 2.25rem;
      line-height: 1.7;
    }

    .pre-register-form {
      display: flex;
      align-items: center;
      justify-content: center;
      max-width: 500px;
      margin: 0 auto 2.25rem;
      gap: 0.5rem;
    }

    .pre-register-input {
      flex: 1;
      background: #fff;
      border: none;
      border-radius: 50px;
      padding: 0.85rem 1.4rem;
      font-family: var(--font-body);
      font-size: 1rem;
      color: var(--app-text-blue);
      outline: none;
    }

    .btn-submit-notify {
      background: var(--app-yellow);
      color: var(--app-text-blue);
      border: 2px solid #fff;
      border-radius: 50px;
      padding: 0.85rem 1.6rem;
      font-family: var(--font-heading);
      font-weight: 800;
      font-size: 0.95rem;
      cursor: pointer;
      white-space: nowrap;
      transition: var(--transition-smooth);
    }

    .btn-submit-notify:hover {
      background: #fff;
      transform: scale(1.04);
    }

    .cta-stores-row {
      display: flex;
      justify-content: center;
      gap: 1.25rem;
      flex-wrap: wrap;
    }

    /* ==========================================================================
       12. FOOTER
       ========================================================================== */
    .footer {
      background: #060919;
      padding: 3.5rem 0 2rem;
      border-top: 1px solid rgba(255, 255, 255, 0.08);
      position: relative;
      z-index: 1;
    }

    .footer-grid {
      display: grid;
      grid-template-columns: 1.5fr 1fr 1fr;
      gap: 3rem;
      margin-bottom: 2.5rem;
    }

    .footer-brand p {
      color: var(--text-muted);
      font-size: 0.95rem;
      margin-top: 0.85rem;
      line-height: 1.7;
      max-width: 360px;
    }

    .footer-col-title {
      font-family: var(--font-heading);
      font-size: 1.15rem;
      font-weight: 700;
      color: #fff;
      margin-bottom: 1.25rem;
    }

    .footer-links {
      list-style: none;
      display: flex;
      flex-direction: column;
      gap: 0.75rem;
    }

    .footer-link {
      color: var(--text-muted);
      font-size: 0.92rem;
      transition: var(--transition-smooth);
    }

    .footer-link:hover {
      color: var(--app-yellow);
      padding-right: 4px;
    }

    .social-links {
      display: flex;
      gap: 0.85rem;
      margin-top: 1rem;
    }

    .social-icon-btn {
      width: 40px;
      height: 40px;
      border-radius: 50%;
      background: rgba(255, 255, 255, 0.08);
      border: 1px solid rgba(255, 255, 255, 0.15);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 1.1rem;
      transition: var(--transition-smooth);
    }

    .social-icon-btn:hover {
      background: var(--app-magenta);
      border-color: #fff;
      transform: translateY(-2px);
    }

    .footer-bottom {
      text-align: center;
      padding-top: 2rem;
      border-top: 1px solid rgba(255, 255, 255, 0.05);
      color: #64748b;
      font-size: 0.85rem;
      font-family: var(--font-heading);
    }

    /* ==========================================================================
       13. RESPONSIVE DESIGN (MEDIA QUERIES)
       ========================================================================== */
    @media (max-width: 1024px) {
      .game-modes-grid { grid-template-columns: 1fr; }
      .rewards-grid { grid-template-columns: 1fr; }
      .footer-grid { grid-template-columns: 1fr 1fr; }
    }

    @media (max-width: 868px) {
      .nav-links, .nav-actions .btn-nav-soon { display: none; }
      .menu-toggle { display: block; }
      .hero-grid { grid-template-columns: 1fr; text-align: center; gap: 2.5rem; }
      .hero-content { text-align: center; }
      .hero-brand-card { align-items: center; }
      .hero-subtitle { margin-left: auto; margin-right: auto; }
      .hero-badges-row, .hero-cta-group, .store-badges-container { justify-content: center; }
      .footer-grid { grid-template-columns: 1fr; gap: 2rem; }
      .cta-title { font-size: 2rem; }
      .pre-register-form { flex-direction: column; width: 100%; }
      .pre-register-input, .btn-submit-notify { width: 100%; }
    }

    @media (max-width: 480px) {
      .hero-main-logo-img { max-width: 290px; }
      .hero-soon-pill { font-size: 1.1rem; padding: 0.45rem 1.4rem; }
      .section-title { font-size: 1.75rem; }
      .mode-card { padding: 1.75rem 1.25rem; }
      .helpers-grid { grid-template-columns: 1fr; }
      .phone-mockup-frame { width: 280px; height: 560px; }
    }
  </style>
</head>
<body>

  <!-- Background Particles -->
  <div class="bg-particles" id="particlesContainer"></div>

  <!-- Navbar -->
  <nav class="navbar" id="navbar">
    <div class="container nav-container">
      <a href="#" class="brand-logo">
        <img src="{{ asset('assets/images/1024.png') }}" alt="فيك تحدي" />
        <div class="brand-text">فيك <span>تحدي؟</span></div>
      </a>

      <ul class="nav-links">
        <li><a href="#home" class="nav-link active">الرئيسية</a></li>
        <li><a href="#modes" class="nav-link">أنماط الألعاب</a></li>
        <li><a href="#helpers" class="nav-link">وسائل المساعدة</a></li>
        <li><a href="#rewards" class="nav-link">الجوائز والأفاتار</a></li>
      </ul>

      <div class="nav-actions">
        <button class="btn-nav-soon" onclick="triggerNotifyModal()">
          <i class="fa-solid fa-bell"></i>
          قريباً على المتاجر
        </button>
        <button class="menu-toggle" id="menuToggle" aria-label="Open Menu">
          <i class="fa-solid fa-bars-staggered"></i>
        </button>
      </div>
    </div>
  </nav>

  <!-- Mobile Drawer -->
  <div class="drawer-overlay" id="drawerOverlay"></div>
  <aside class="mobile-drawer" id="mobileDrawer">
    <div class="drawer-header">
      <div class="brand-logo">
        <img src="{{ asset('assets/images/1024.png') }}" alt="فيك تحدي" />
        <div class="brand-text">فيك <span>تحدي؟</span></div>
      </div>
      <button class="drawer-close" id="drawerClose"><i class="fa-solid fa-xmark"></i></button>
    </div>
    <ul class="drawer-links">
      <li><a href="#home" class="drawer-link" onclick="closeDrawer()"><i class="fa-solid fa-house"></i> الرئيسية</a></li>
      <li><a href="#modes" class="drawer-link" onclick="closeDrawer()"><i class="fa-solid fa-gamepad"></i> أنماط الألعاب</a></li>
      <li><a href="#helpers" class="drawer-link" onclick="closeDrawer()"><i class="fa-solid fa-bolt"></i> وسائل المساعدة</a></li>
      <li><a href="#rewards" class="drawer-link" onclick="closeDrawer()"><i class="fa-solid fa-trophy"></i> الجوائز والأفاتار</a></li>
    </ul>
    <div style="margin-top: auto;">
      <button class="btn-nav-soon" style="width: 100%; justify-content: center;" onclick="triggerNotifyModal(); closeDrawer();">
        <i class="fa-solid fa-rocket"></i> احجز نسختك قريباً
      </button>
    </div>
  </aside>

  <!-- Hero Section -->
  <section class="hero-section" id="home">
    <div class="container">
      <div class="hero-grid">
        <div class="hero-content">
          <div class="hero-brand-card">
            <!-- الشعار الرسمي الواحد بدون تكرار -->
            <img src="{{ asset('assets/images/logo_tahadi.png') }}" alt="فيك تحدي" class="hero-main-logo-img" />
            <!-- أسفله قريباً مباشرة -->
            <div class="hero-soon-pill">
              <i class="fa-solid fa-sparkles"></i> قريباً على App Store و Google Play
            </div>
          </div>

          <p class="hero-subtitle">
            اللعبة الجماعية الأكثر متعة وتفاعلاً.. اختبر معلوماتك وسرعة بديهتك، ورّط أصحابك بوسائل المساعدة السبع الخارقة، وعيش حماس التنافس في الجلسة أو الميدان أونلاين!
          </p>

          <div class="hero-badges-row">
            <span class="feature-tag"><i class="fa-solid fa-layer-group"></i> 6 فئات متنوعة</span>
            <span class="feature-tag"><i class="fa-solid fa-circle-question"></i> 36 سؤالاً في المباراة</span>
            <span class="feature-tag"><i class="fa-solid fa-wand-magic-sparkles"></i> 7 وسائل مساعدة</span>
            <span class="feature-tag"><i class="fa-solid fa-gift"></i> مكافآت وكوبونات حقيقية</span>
          </div>

          <div class="hero-cta-group">
            <button class="btn-hero-primary" onclick="triggerNotifyModal()">
              <i class="fa-solid fa-rocket"></i>
              تجهّز للإطلاق قريباً
            </button>
            <a href="#modes" class="btn-hero-secondary">
              <i class="fa-solid fa-circle-play"></i>
              استكشف الألعاب
            </a>
          </div>

          <!-- Official Store Badges -->
          <div class="store-badges-container">
            <div class="store-badge-card store-apple" onclick="triggerNotifyModal('App Store')">
              <div class="store-icon-wrap">
                <svg class="store-svg-icon apple-svg" viewBox="0 0 170 170" width="28" height="28" fill="#FFFFFF" xmlns="http://www.w3.org/2000/svg">
                  <path d="M150.37 130.25c-2.45 5.66-5.35 10.87-8.71 15.66-4.58 6.53-8.33 11.05-11.22 13.56-4.48 4.12-9.28 6.23-14.42 6.35-3.69 0-8.14-1.05-13.32-3.18-5.19-2.12-9.97-3.17-14.34-3.17-4.58 0-9.49 1.05-14.75 3.17-5.26 2.13-9.5 3.24-12.74 3.35-4.35.13-9.16-1.9-14.42-6.08-3.69-3.08-7.7-7.94-12.04-14.58-6.19-9.5-11.05-20.45-14.58-32.86-3.53-12.4-5.3-24.16-5.3-35.27 0-16.78 4.58-30.82 13.73-42.12 9.15-11.3 20.35-17.06 33.6-17.29 4.35 0 9.29 1.1 14.83 3.3 5.54 2.2 9.4 3.34 11.58 3.42 1.95 0 5.9-1.22 11.83-3.65 5.94-2.44 11.05-3.53 15.34-3.3 14.13.68 25.13 6.09 33 16.23-12.18 7.37-18.17 17.5-17.97 30.38.2 10.01 4.09 18.42 11.66 25.24 7.57 6.82 16.48 10.74 26.74 11.75-2.22 6.53-4.99 13.23-8.31 20.1m-29.27-111.46c0 7.83-2.93 15.08-8.79 21.75-5.86 6.67-12.87 10.6-21.03 11.79-.11-1.09-.17-2.07-.17-2.94 0-7.61 3.09-14.89 9.27-21.84 6.18-6.96 13.43-10.87 21.75-11.73.11.98.17 1.97.17 2.97z"/>
                </svg>
              </div>
              <div class="store-text">
                <span class="sub">قريباً على</span>
                <span class="main">App Store</span>
              </div>
              <span class="store-soon-tag"><span class="pulse-dot"></span> Coming Soon</span>
            </div>

            <div class="store-badge-card store-google" onclick="triggerNotifyModal('Google Play')">
              <div class="store-icon-wrap">
                <svg class="store-svg-icon google-play-svg" viewBox="0 0 512 512" width="28" height="28" xmlns="http://www.w3.org/2000/svg">
                  <path fill="#00D6FF" d="M78.6,41.4C74.5,45.6,72,51.8,72,60.2v391.6c0,8.4,2.5,14.6,6.6,18.8l2.2,2.2L297.4,256.2v-4.4L80.8,39.2L78.6,41.4z"/>
                  <path fill="#FF3A44" d="M370.8,329.6l-73.4-73.4v-4.4l73.4-73.4l2.5,1.4l87,49.4c24.8,14.1,24.8,37.3,0,51.4l-87,49.4L370.8,329.6z"/>
                  <path fill="#00F076" d="M297.4,251.8L80.8,39.2c7.8-4.4,20.6-2.5,33.5,4.9l256.5,145.7L297.4,251.8z"/>
                  <path fill="#FFD400" d="M297.4,260.2l73.4,73.4-256.5,145.7c-12.9,7.4-25.7,9.3-33.5,4.9L297.4,260.2z"/>
                </svg>
              </div>
              <div class="store-text">
                <span class="sub">قريباً على</span>
                <span class="main">Google Play</span>
              </div>
              <span class="store-soon-tag"><span class="pulse-dot"></span> Coming Soon</span>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- Game Modes Section (لعبة الجلسة & لعبة الميدان) -->
  <section class="section-padding" id="modes">
    <div class="container">
      <div class="section-header">
        <span class="section-badge"><i class="fa-solid fa-gamepad"></i> أنماط اللعب الرسمية</span>
        <h2 class="section-title">اختر جوّك.. <span>جلسة رايقة أو ميدان مشتعل!</span></h2>
        <p class="section-desc">وضعيات لعب صُممت لتناسب كل اللمّات.. من جمعات البيت الدافئة إلى التحديات الحية والتنافس المباشر أونلاين!</p>
      </div>

      <div class="game-modes-grid">
        <!-- 1. لعبة الجلسة -->
        <div class="mode-card mode-session">
          <div>
            <div class="mode-card-header">
              <div class="mode-icon-box">
                <i class="fa-solid fa-couch"></i>
              </div>
              <span class="mode-badge-pill">لعبة الجلسة • الجمعات والعائلات</span>
            </div>

            <h3 class="mode-card-title">لعبة الجلسة 🛋️</h3>
            <p class="mode-card-intro">
              يتيح هذا الوضع للاعبين الاستمتاع باللعبة سواء في أجواء عائلية أو مع الأصدقاء، مع تجربة تفاعلية تعتمد على التحدي والمعرفة وسرعة البديهة بين فريقين في مكان واحد.
            </p>

            <ul class="mode-features-list">
              <li class="mode-feature-item">
                <i class="fa-solid fa-users"></i>
                <span><strong>تشكيل فريقين وتوقع الفائز:</strong> يقوم صاحب الحساب بإنشاء اللعبة، وتشكيل الفريقين، وتوقع الفائز لحصد النقاط والعملات الافتراضية.</span>
              </li>
              <li class="mode-feature-item">
                <i class="fa-solid fa-shuffle"></i>
                <span><strong>نظام الأدوار والتناوب (Round-based):</strong> 6 فئات يختار كل فريق 3 منها، بإجمالي 36 سؤالاً لا يتكرر أي سؤال خلال المباراة.</span>
              </li>
              <li class="mode-feature-item">
                <i class="fa-solid fa-stopwatch"></i>
                <span><strong>تدرج المستويات والوقت:</strong> سهل (200 نقطة / 30ث)، متوسط (400 نقطة / 45ث)، وصعب (600 نقطة / 60ث) مع 15 ثانية إضافية للفريق الخصم في حال التعثر.</span>
              </li>
              <li class="mode-feature-item">
                <i class="fa-solid fa-gift"></i>
                <span><strong>جوائز حتى للخاسر:</strong> جوائز ترضية وعملات افتراضية تتيح تطوير الأفاتار وشراء الكوبونات المتاحة داخل الكويت.</span>
              </li>
            </ul>
          </div>

          <div class="mode-stats-row">
            <div class="mode-stat-box">
              <div class="stat-num">36</div>
              <div class="stat-label">سؤالاً منوعاً</div>
            </div>
            <div class="mode-stat-box">
              <div class="stat-num">6</div>
              <div class="stat-label">فئات تنافسية</div>
            </div>
            <div class="mode-stat-box">
              <div class="stat-num">15s</div>
              <div class="stat-label">وقت إضافي للخصم</div>
            </div>
          </div>
        </div>

        <!-- 2. لعبة الميدان -->
        <div class="mode-card mode-field">
          <div>
            <div class="mode-card-header">
              <div class="mode-icon-box">
                <i class="fa-solid fa-earth-americas"></i>
              </div>
              <span class="mode-badge-pill">لعبة الميدان • أونلاين وتحدي مباشر</span>
            </div>

            <h3 class="mode-card-title">لعبة الميدان 🏃‍♂️</h3>
            <p class="mode-card-intro">
              يتيح هذا الوضع للاعبين الاستمتاع باللعبة أونلاين محلياً وإقليمياً بوضع تنافسي مباشر وسريع (1v1) يختبر سرعة البديهة والتحدي في نفس اللحظة!
            </p>

            <ul class="mode-features-list">
              <li class="mode-feature-item">
                <i class="fa-solid fa-dice"></i>
                <span><strong>البحث العشوائي (Random Matchmaking):</strong> بحث تلقائي وفوري عن منافس عشوائي لخوض مواجهة نارية مباشرة.</span>
              </li>
              <li class="mode-feature-item">
                <i class="fa-solid fa-door-open"></i>
                <span><strong>إنشاء الغرف الخاصة (Private Room):</strong> أنشئ غرفتك الخاصة وشارك كود الدخول مع صديقك للتحدي من أي مكان.</span>
              </li>
              <li class="mode-feature-item">
                <i class="fa-solid fa-bolt-lightning"></i>
                <span><strong>أسئلة متزامنة وتنافس فوري:</strong> عرض نفس السؤال لكلا الطرفين في نفس الثانية تماماً، والسرعة تحسم أسبقية النقاط.</span>
              </li>
              <li class="mode-feature-item">
                <i class="fa-solid fa-hand-fist"></i>
                <span><strong>جولات الحسم الفاصلة:</strong> في حال التعادل تُحسم المباراة بسؤال السرعة (Flash Round) أو أحجية (حجرة ورقة مقص) لتتويج البطل.</span>
              </li>
            </ul>
          </div>

          <div class="mode-stats-row">
            <div class="mode-stat-box">
              <div class="stat-num">1v1</div>
              <div class="stat-label">تحدي مباشر</div>
            </div>
            <div class="mode-stat-box">
              <div class="stat-num">Live</div>
              <div class="stat-label">تزامن فوري</div>
            </div>
            <div class="mode-stat-box">
              <div class="stat-num">Flash</div>
              <div class="stat-label">جولة حسم</div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </section>

  <!-- 7 Official Game Helpers Section -->
  <section class="section-padding helpers-section" id="helpers">
    <div class="container">
      <div class="section-header">
        <span class="section-badge"><i class="fa-solid fa-wand-magic-sparkles"></i> الأسلحة التكتيكية السبعة</span>
        <h2 class="section-title">وسائل المساعدة.. <span>اقلب الطاولة وورّط خصمك!</span> ⚡</h2>
        <p class="section-desc">7 وسائل مساعدة استراتيجية مستوحاة من روح التحدي والضحك، تمنحك القوة للتحكم بمسار المباراة وخطف الفوز في اللحظات الأخيرة!</p>
      </div>

      <div class="helpers-grid">
        <!-- 1. وهقة -->
        <div class="helper-card">
          <div class="helper-card-top">
            <div class="helper-img-wrap">
              <img src="{{ asset('upload/game_helper/help2.png') }}" onerror="this.src='{{ asset('frontend/landing/assets/images/help2.png') }}'" alt="وهقة" />
            </div>
            <span class="helper-number-badge">#1</span>
          </div>
          <h3 class="helper-name">وَهْقَة 🎯</h3>
          <div class="helper-en-name">Wahqa</div>
          <div class="helper-action-title">اختيار شخص معين للإجابة عن السؤال!</div>
          <p class="helper-desc">
            بعد أن يقوم الفريق صاحب الدور باختيار سؤاله، يحق لك تفعيل "وهقة" لتحديد لاعب بعينه من فريقهم ليكون هو الوحيد المسؤول عن الإجابة وتوريطه أمام الجميع!
          </p>
          <div class="helper-tactic-box">
            <i class="fa-solid fa-lightbulb"></i>
            <span><strong>التكتيك:</strong> ورّط أضعف واحد بالمعلومات بسؤال 600 نقطة!</span>
          </div>
        </div>

        <!-- 2. إكلها -->
        <div class="helper-card">
          <div class="helper-card-top">
            <div class="helper-img-wrap">
              <img src="{{ asset('upload/game_helper/help1.png') }}" onerror="this.src='{{ asset('frontend/landing/assets/images/help1.png') }}'" alt="إكلها" />
            </div>
            <span class="helper-number-badge">#2</span>
          </div>
          <h3 class="helper-name">إِكِلْهَا 🍽️</h3>
          <div class="helper-en-name">Akilha</div>
          <div class="helper-action-title">سؤال إجباري من اختيارك للخصم!</div>
          <p class="helper-desc">
            عندما يصل الدور للخصم، فعّل "إكلها" وافرِض عليهم الفئة والمستوى بنفسك بدل أن يختاروا براحتهم، ولبّسهم أصعب سؤال بالمباراة!
          </p>
          <div class="helper-tactic-box">
            <i class="fa-solid fa-lightbulb"></i>
            <span><strong>التكتيك:</strong> اختر لهم أصعب فئة ما يفهمون فيها شي!</span>
          </div>
        </div>

        <!-- 3. اقلبها -->
        <div class="helper-card">
          <div class="helper-card-top">
            <div class="helper-img-wrap">
              <img src="{{ asset('upload/game_helper/help3.png') }}" onerror="this.src='{{ asset('frontend/landing/assets/images/help3.png') }}'" alt="اقلبها" />
            </div>
            <span class="helper-number-badge">#3</span>
          </div>
          <h3 class="helper-name">اقْلِبْهَا 🔄</h3>
          <div class="helper-en-name">Eqlebha</div>
          <div class="helper-action-title">تغيير السؤال وتجنب الخسارة!</div>
          <p class="helper-desc">
            إذا طلع لك سؤال معقد وما عرفت إجابته، اقلبها فوراً واستبدله بسؤال عشوائي من فئة ومستوى عشوائي لتنقذ نقاط فريقك!
          </p>
          <div class="helper-tactic-box">
            <i class="fa-solid fa-lightbulb"></i>
            <span><strong>التكتيك:</strong> طوق النجاة لما تنحشر بسؤال تعجيزي.</span>
          </div>
        </div>

        <!-- 4. دوبلها -->
        <div class="helper-card">
          <div class="helper-card-top">
            <div class="helper-img-wrap">
              <img src="{{ asset('upload/game_helper/help5.png') }}" onerror="this.src='{{ asset('frontend/landing/assets/images/help5.png') }}'" alt="دوبلها" />
            </div>
            <span class="helper-number-badge">#4</span>
          </div>
          <h3 class="helper-name">دُوبِلْهَا ✖️2️⃣</h3>
          <div class="helper-en-name">Dablha</div>
          <div class="helper-action-title">مضاعفة نقاط السؤال المختار!</div>
          <p class="helper-desc">
            تُفعل قبل اختيار السؤال، وإذا اخترت سؤالاً وأجبت عليه بشكل صحيح تحصل على ضعف النقاط وتطير بصدارة الترتيب!
          </p>
          <div class="helper-tactic-box">
            <i class="fa-solid fa-triangle-exclamation"></i>
            <span><strong>ملاحظة:</strong> تُستخدم قبل اختيار السؤال وتكسب الدبل مع الإجابة الصحيحة فقط.</span>
          </div>
        </div>

        <!-- 5. ضيقها -->
        <div class="helper-card">
          <div class="helper-card-top">
            <div class="helper-img-wrap">
              <img src="{{ asset('upload/game_helper/help4.png') }}" onerror="this.src='{{ asset('frontend/landing/assets/images/help4.png') }}'" alt="ضيقها" />
            </div>
            <span class="helper-number-badge">#5</span>
          </div>
          <h3 class="helper-name">ضَيِّقْهَا ⏱️</h3>
          <div class="helper-en-name">Dayeqha</div>
          <div class="helper-action-title">تقليص وقت إجابة الخصم للنصف!</div>
          <p class="helper-desc">
            قبل دخول الخصم للسؤال، فعّل "ضيقها" ليتم إخبارهم بأن وقتهم تقلص إلى النصف لوضعهم تحت ضغط الثواني والربكة!
          </p>
          <div class="helper-tactic-box">
            <i class="fa-solid fa-lightbulb"></i>
            <span><strong>التكتيك:</strong> دمر تركيزهم بأسئلة الـ 600 نقطة الطويلة!</span>
          </div>
        </div>

        <!-- 6. أولها -->
        <div class="helper-card">
          <div class="helper-card-top">
            <div class="helper-img-wrap">
              <img src="{{ asset('upload/game_helper/help7.png') }}" onerror="this.src='{{ asset('frontend/landing/assets/images/help7.png') }}'" alt="أولها" />
            </div>
            <span class="helper-number-badge">#6</span>
          </div>
          <h3 class="helper-name">أَوَّلْهَا 🔤</h3>
          <div class="helper-en-name">Awelha</div>
          <div class="helper-action-title">كشف أول حرف من الإجابة!</div>
          <p class="helper-desc">
            وسيلة ذكية تعطي فريقك الحرف الأول من الإجابة لتسهيل الحل وتنشيط الذاكرة لما تكون الإجابة على طرف لسانك!
          </p>
          <div class="helper-tactic-box">
            <i class="fa-solid fa-lightbulb"></i>
            <span><strong>التكتيك:</strong> أفضل مفتاح للإجابات التاريخية وأسماء الأعلام.</span>
          </div>
        </div>

        <!-- 7. سگّرها -->
        <div class="helper-card">
          <div class="helper-card-top">
            <div class="helper-img-wrap">
              <img src="{{ asset('upload/game_helper/help9.png') }}" onerror="this.src='{{ asset('frontend/landing/assets/images/help9.png') }}'" alt="سگّرها" />
            </div>
            <span class="helper-number-badge">#7</span>
          </div>
          <h3 class="helper-name">سَگِّرْهَا 🔒</h3>
          <div class="helper-en-name">Sakrha</div>
          <div class="helper-action-title">منع الخصم نهائياً من الإجابة!</div>
          <p class="helper-desc">
            إذا شعرت أن الفريق الخصم يعرف إجابة السؤال ومستعد لخطف النقاط في الوقت الإضافي، سگّرها بوجههم وامنعهم نهائياً من المحاولة!
          </p>
          <div class="helper-tactic-box">
            <i class="fa-solid fa-lightbulb"></i>
            <span><strong>التكتيك:</strong> اقفل الباب بوجههم واحمِ صدارتك للنقاط!</span>
          </div>
        </div>
      </div>
    </div>
  </section>


  <!-- Rewards & Avatar Section -->
  <section class="section-padding" id="rewards">
    <div class="container">
      <div class="section-header">
        <span class="section-badge"><i class="fa-solid fa-coins"></i> اقتصاد اللعبة والمكافآت</span>
        <h2 class="section-title">العب، اكسب، <span>وطوّر شخصيتك!</span> 🏆</h2>
        <p class="section-desc">حتى لو خسرت.. في "فيك تحدي" الكل كسبان بجوائز ترضية وعملات افتراضية قابلة للاستبدال!</p>
      </div>

      <div class="rewards-grid">
        <!-- 1. الأفاتار والشخصيات -->
        <div class="reward-box">
          <div class="reward-icon-circle">
            <i class="fa-solid fa-user-astronaut"></i>
          </div>
          <h3 class="reward-title">تطوير الأفاتار الخاص بك</h3>
          <p class="reward-desc">
            استخدم العملات الفضية في تخصيص شخصيتك، شراء إكسسوارات حصرية، تعليقات ساخرة مضحكة، وإيموجيات مميزة تعبر عن روحك في التحدي.
          </p>
        </div>

        <!-- 2. العملات والرتب -->
        <div class="reward-box">
          <div class="reward-icon-circle">
            <i class="fa-solid fa-medal"></i>
          </div>
          <h3 class="reward-title">نظام الرتب والتدرج</h3>
          <p class="reward-desc">
            كلما زادت انتصاراتك ارتقيت في المستويات وتغير لقبك، لتحصل على عملات ذهبية وفضية إضافية تفتح لك خيارات غير محدودة في المتجر.
          </p>
        </div>

        <!-- 3. متجر الكوبونات -->
        <div class="reward-box">
          <div class="reward-icon-circle">
            <i class="fa-solid fa-ticket"></i>
          </div>
          <h3 class="reward-title">كوبونات وجوائز حقيقية</h3>
          <p class="reward-desc">
            استبدل العملات الذهبية بألعاب مجانية أو بكوبونات خصم ومكافآت حقيقية مقدمة من الشركات الراعية المعتمدة في الكويت.
          </p>
        </div>
      </div>
    </div>
  </section>

  <!-- Download & Coming Soon CTA Section -->
  <section class="container">
    <div class="download-cta-section">
      <h2 class="cta-title">تجهّز للمتعة.. <span>الإطلاق قريب جداً!</span> 🚀</h2>
      <p class="cta-desc">
        سجل بريدك الإلكتروني أو رقم هاتفك لتكون أول من يحمل اللعبة ويحصل على باقة عملات وأفاتار مجاني بمناسبة الإطلاق الرسمي!
      </p>

      <form class="pre-register-form" onsubmit="handlePreRegister(event)">
        <input type="text" class="pre-register-input" id="subscriberInput" placeholder="أدخل بريدك الإلكتروني أو رقم الهاتف..." required />
        <button type="submit" class="btn-submit-notify">
          <i class="fa-solid fa-paper-plane"></i> بلّغني أول ما تنزل!
        </button>
      </form>

      <div class="cta-stores-row">
        <div class="store-badge-card store-apple" onclick="triggerNotifyModal('App Store')">
          <div class="store-icon-wrap">
            <svg class="store-svg-icon apple-svg" viewBox="0 0 170 170" width="28" height="28" fill="#FFFFFF" xmlns="http://www.w3.org/2000/svg">
              <path d="M150.37 130.25c-2.45 5.66-5.35 10.87-8.71 15.66-4.58 6.53-8.33 11.05-11.22 13.56-4.48 4.12-9.28 6.23-14.42 6.35-3.69 0-8.14-1.05-13.32-3.18-5.19-2.12-9.97-3.17-14.34-3.17-4.58 0-9.49 1.05-14.75 3.17-5.26 2.13-9.5 3.24-12.74 3.35-4.35.13-9.16-1.9-14.42-6.08-3.69-3.08-7.7-7.94-12.04-14.58-6.19-9.5-11.05-20.45-14.58-32.86-3.53-12.4-5.3-24.16-5.3-35.27 0-16.78 4.58-30.82 13.73-42.12 9.15-11.3 20.35-17.06 33.6-17.29 4.35 0 9.29 1.1 14.83 3.3 5.54 2.2 9.4 3.34 11.58 3.42 1.95 0 5.9-1.22 11.83-3.65 5.94-2.44 11.05-3.53 15.34-3.3 14.13.68 25.13 6.09 33 16.23-12.18 7.37-18.17 17.5-17.97 30.38.2 10.01 4.09 18.42 11.66 25.24 7.57 6.82 16.48 10.74 26.74 11.75-2.22 6.53-4.99 13.23-8.31 20.1m-29.27-111.46c0 7.83-2.93 15.08-8.79 21.75-5.86 6.67-12.87 10.6-21.03 11.79-.11-1.09-.17-2.07-.17-2.94 0-7.61 3.09-14.89 9.27-21.84 6.18-6.96 13.43-10.87 21.75-11.73.11.98.17 1.97.17 2.97z"/>
            </svg>
          </div>
          <div class="store-text">
            <span class="sub">قريباً على</span>
            <span class="main">App Store</span>
          </div>
          <span class="store-soon-tag"><span class="pulse-dot"></span> Coming Soon</span>
        </div>

        <div class="store-badge-card store-google" onclick="triggerNotifyModal('Google Play')">
          <div class="store-icon-wrap">
            <svg class="store-svg-icon google-play-svg" viewBox="0 0 512 512" width="28" height="28" xmlns="http://www.w3.org/2000/svg">
              <path fill="#00D6FF" d="M78.6,41.4C74.5,45.6,72,51.8,72,60.2v391.6c0,8.4,2.5,14.6,6.6,18.8l2.2,2.2L297.4,256.2v-4.4L80.8,39.2L78.6,41.4z"/>
              <path fill="#FF3A44" d="M370.8,329.6l-73.4-73.4v-4.4l73.4-73.4l2.5,1.4l87,49.4c24.8,14.1,24.8,37.3,0,51.4l-87,49.4L370.8,329.6z"/>
              <path fill="#00F076" d="M297.4,251.8L80.8,39.2c7.8-4.4,20.6-2.5,33.5,4.9l256.5,145.7L297.4,251.8z"/>
              <path fill="#FFD400" d="M297.4,260.2l73.4,73.4-256.5,145.7c-12.9,7.4-25.7,9.3-33.5,4.9L297.4,260.2z"/>
            </svg>
          </div>
          <div class="store-text">
            <span class="sub">قريباً على</span>
            <span class="main">Google Play</span>
          </div>
          <span class="store-soon-tag"><span class="pulse-dot"></span> Coming Soon</span>
        </div>
      </div>
    </div>
  </section>

  <!-- Footer -->
  <footer class="footer">
    <div class="container">
      <div class="footer-grid">
        <div class="footer-brand">
          <a href="#" class="brand-logo">
            <img src="{{ asset('assets/images/1024.png') }}" alt="فيك تحدي" />
            <div class="brand-text">فيك <span>تحدي؟</span></div>
          </a>
          <p>
            تطبيق فيك تحدي هو المنصة الترفيهية التفاعلية الأولى للجمعات والتحديات الثقافية والحركية في الوطن العربي.
          </p>
          <div class="social-links">
            <a href="#" class="social-icon-btn" aria-label="Instagram"><i class="fa-brands fa-instagram"></i></a>
            <a href="#" class="social-icon-btn" aria-label="TikTok"><i class="fa-brands fa-tiktok"></i></a>
            <a href="#" class="social-icon-btn" aria-label="X (Twitter)"><i class="fa-brands fa-x-twitter"></i></a>
            <a href="#" class="social-icon-btn" aria-label="YouTube"><i class="fa-brands fa-youtube"></i></a>
          </div>
        </div>

        <div>
          <h4 class="footer-col-title">روابط سريعة</h4>
          <ul class="footer-links">
            <li><a href="#home" class="footer-link">الرئيسية</a></li>
            <li><a href="#modes" class="footer-link">أنماط الألعاب</a></li>
            <li><a href="#helpers" class="footer-link">وسائل المساعدة</a></li>
            <li><a href="#rewards" class="footer-link">الجوائز والأفاتار</a></li>
          </ul>
        </div>

        <div>
          <h4 class="footer-col-title">المعلومات القانونية</h4>
          <ul class="footer-links">
            <li><a href="{{ route('privacy.policy') }}" class="footer-link">سياسة الخصوصية</a></li>
            <li><a href="{{ route('privacy.policy') }}" class="footer-link">الشروط والأحكام</a></li>
            <li><a href="mailto:support@fiktahadi.com" class="footer-link">تواصل معنا والدعم الفني</a></li>
          </ul>
        </div>
      </div>

      <div class="footer-bottom">
        جميع الحقوق محفوظة &copy; {{ date('Y') }} لتطبيق <strong>فيك تحدي؟</strong> (Fik Tahadi).
      </div>
    </div>
  </footer>

  <!-- Scripts -->
  <script>
    // 1. Generate Floating Background Particles
    document.addEventListener('DOMContentLoaded', () => {
      const container = document.getElementById('particlesContainer');
      const colors = ['#ffd200', '#78e0ff', '#af227b', '#b294fb', '#ffffff'];

      for (let i = 0; i < 20; i++) {
        const p = document.createElement('div');
        p.classList.add('particle');
        const size = Math.floor(Math.random() * 8 + 4) + 'px';
        p.style.width = size;
        p.style.height = size;
        p.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
        p.style.left = Math.random() * 100 + 'vw';
        p.style.animationDuration = (Math.random() * 10 + 10) + 's';
        p.style.animationDelay = (Math.random() * 8) + 's';
        container.appendChild(p);
      }
    });

    // 2. Navbar Scroll Effect
    window.addEventListener('scroll', () => {
      const navbar = document.getElementById('navbar');
      if (window.scrollY > 40) {
        navbar.classList.add('scrolled');
      } else {
        navbar.classList.remove('scrolled');
      }
    });

    // 3. Mobile Drawer Controls
    const menuToggle = document.getElementById('menuToggle');
    const mobileDrawer = document.getElementById('mobileDrawer');
    const drawerOverlay = document.getElementById('drawerOverlay');
    const drawerClose = document.getElementById('drawerClose');

    menuToggle.addEventListener('click', () => {
      mobileDrawer.classList.add('open');
      drawerOverlay.classList.add('active');
    });

    function closeDrawer() {
      mobileDrawer.classList.remove('open');
      drawerOverlay.classList.remove('active');
    }

    drawerClose.addEventListener('click', closeDrawer);
    drawerOverlay.addEventListener('click', closeDrawer);


    // 5. SweetAlert Modal for Coming Soon
    function triggerNotifyModal(storeName = '') {
      Swal.fire({
        title: storeName ? `قريباً على ${storeName} 🚀` : 'ترقبوا الإطلاق قريباً! 🔥',
        html: `
          <div style="font-family: 'Tajawal', sans-serif; text-align: right; direction: rtl; color: #14376f; line-height: 1.8;">
            <p style="font-size: 1.05rem; margin-bottom: 1rem;">
              فريق <strong>فيك تحدي؟</strong> يضع اللمسات الأخيرة لأقوى تجربة ألعاب جماعية وتحديات تفاعلية في العالم العربي! 🎮
            </p>
            <div style="background: #f8fafc; border-radius: 12px; padding: 1rem; border: 1.5px solid #e2e8f0; margin-bottom: 1rem;">
              <p style="font-weight: 700; color: #af227b; margin-bottom: 0.5rem;">🎁 ميزة التسجيل المبكر:</p>
              <ul style="list-style: none; padding: 0; font-size: 0.95rem; color: #334155;">
                <li>🪙 <strong>100 عملة ذهبية</strong> رصيد ترحيبي عند الإطلاق.</li>
                <li>🦸‍♂️ <strong>أفاتار حصري</strong> للمسجلين الأوائل.</li>
                <li>⚡ إشعار فوري لحظة توفر اللعبة في متجرك.</li>
              </ul>
            </div>
            <input type="text" id="swalSubscriberInput" class="swal2-input" placeholder="أدخل بريدك الإلكتروني أو رقم الواتساب" style="font-family: 'Tajawal', sans-serif; direction: rtl; text-align: right; width: 100%; box-sizing: border-box;" />
          </div>
        `,
        icon: 'info',
        iconColor: '#ffd200',
        showCancelButton: true,
        confirmButtonText: 'احجز نسختك وبلّغني 🔔',
        cancelButtonText: 'إلغاء',
        confirmButtonColor: '#af227b',
        cancelButtonColor: '#64748b',
        preConfirm: () => {
          const val = document.getElementById('swalSubscriberInput').value;
          if (!val) {
            Swal.showValidationMessage('الرجاء إدخال البريد الإلكتروني أو رقم الهاتف');
          }
          return val;
        }
      }).then((result) => {
        if (result.isConfirmed) {
          Swal.fire({
            title: 'تم تسجيلك بنجاح! 🎉',
            text: 'شكراً لحماسك! سنكون أول من يبلغك فور توفر اللعبة رسمياً على المتاجر.',
            icon: 'success',
            confirmButtonText: 'رائع!',
            confirmButtonColor: '#af227b'
          });
        }
      });
    }

    function handlePreRegister(event) {
      event.preventDefault();
      const input = document.getElementById('subscriberInput');
      if (input.value) {
        Swal.fire({
          title: 'تم تسجيلك بنجاح! 🎉',
          html: `<p style="font-family: 'Tajawal', sans-serif; direction: rtl;">شكراً لانضمامك لقائمة الأوائل في <strong>فيك تحدي؟</strong>! سنتواصل معك عبر <b>${input.value}</b> فور نزول التطبيق.</p>`,
          icon: 'success',
          confirmButtonText: 'احجز نسختك! 🚀',
          confirmButtonColor: '#af227b'
        });
        input.value = '';
      }
    }
  </script>
</body>
</html>
