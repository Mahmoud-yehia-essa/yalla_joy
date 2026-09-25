<?php
/**
 * فيك تحدي - Fik Tahadi | Smart App Store Redirector
 * يكتشف نوع جهاز المستخدم ويقوم بتحويله تلقائياً إلى المتجر المناسب:
 * - أجهزة Apple (iOS / iPadOS) -> App Store
 * - أجهزة Android -> Google Play Store
 * - أجهزة الكمبيوتر والمكتبي -> صفحة الهبوط الرئيسية index.html
 */

$iosUrl = "https://apps.apple.com/us/app/feek-tahadi-فيك-تحدي/id6780262870";
$androidUrl = "https://play.google.com/store/apps/details?id=net.fiktahadi.fiktahadi_app";
$fallbackUrl = "./index.html";

$userAgent = isset($_SERVER['HTTP_USER_AGENT']) ? strtolower($_SERVER['HTTP_USER_AGENT']) : '';

// فحص أجهزة أبل (iPhone, iPad, iPod)
$isIos = (
    strpos($userAgent, 'iphone') !== false ||
    strpos($userAgent, 'ipad') !== false ||
    strpos($userAgent, 'ipod') !== false
);

// فحص أجهزة أندرويد
$isAndroid = (
    strpos($userAgent, 'android') !== false
);

// تحديد الرابط المناسب
$targetUrl = $fallbackUrl;
$deviceType = 'desktop';

if ($isIos) {
    $targetUrl = $iosUrl;
    $deviceType = 'ios';
} elseif ($isAndroid) {
    $targetUrl = $androidUrl;
    $deviceType = 'android';
}

// محاولة التحويل الفوري عبر الهيدر
if (!headers_sent()) {
    header("Cache-Control: no-cache, no-store, must-revalidate");
    header("Pragma: no-cache");
    header("Expires: 0");
    header("Location: " . $targetUrl, true, 302);
}
?>
<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>جاري تحويلك إلى المتجر... | فيك تحدي</title>
  
  <!-- Automatic Meta Refresh -->
  <meta http-equiv="refresh" content="0; url=<?= htmlspecialchars($targetUrl) ?>">
  
  <!-- Open Graph Meta -->
  <meta property="og:title" content="تحميل لعبة فيك تحدي">
  <meta property="og:description" content="حمّل لعبة فيك تحدي الآن مباشرة على هاتفك">
  <meta property="og:image" content="./images/logo.png">

  <link rel="icon" type="image/png" href="./images/favicon.png">
  <link rel="stylesheet" href="./css/style.css">

  <script>
    // تحويل إضافي فوري عبر الجافاسكربت للتأكد 100% من التحويل
    (function() {
      var ua = navigator.userAgent || navigator.vendor || window.opera;
      var iosUrl = "<?= $iosUrl ?>";
      var androidUrl = "<?= $androidUrl ?>";
      var fallbackUrl = "<?= $fallbackUrl ?>";

      var isIos = /iPad|iPhone|iPod/.test(ua) || (navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1);
      var isAndroid = /android/i.test(ua);

      if (isIos) {
        window.location.replace(iosUrl);
      } else if (isAndroid) {
        window.location.replace(androidUrl);
      } else {
        window.location.replace(fallbackUrl);
      }
    })();
  </script>
  
  <style>
    body {
      display: flex;
      align-items: center;
      justify-content: center;
      min-height: 100vh;
      margin: 0;
      text-align: center;
      color: #ffffff;
      font-family: 'Cairo', 'Tajawal', sans-serif;
    }
    .redirect-box {
      background: rgba(15, 23, 42, 0.85);
      border: 1px solid rgba(56, 189, 248, 0.3);
      padding: 30px;
      border-radius: 24px;
      backdrop-filter: blur(20px);
      max-width: 90%;
      width: 400px;
      box-shadow: 0 20px 50px rgba(0,0,0,0.5);
    }
    .spinner {
      width: 48px;
      height: 48px;
      border: 4px solid rgba(56, 189, 248, 0.2);
      border-top-color: #38bdf8;
      border-radius: 50%;
      animation: spin 0.8s linear infinite;
      margin: 0 auto 20px;
    }
    @keyframes spin {
      to { transform: rotate(360deg); }
    }
    .redirect-link {
      display: inline-block;
      margin-top: 15px;
      color: #ffb703;
      font-weight: bold;
      text-decoration: none;
    }
  </style>
</head>
<body>

  <div class="redirect-box">
    <div class="spinner"></div>
    <h3>جاري توجيهك إلى المتجر...</h3>
    <p style="color: #94a3b8; font-size: 0.9rem; margin-top: 8px;">إذا لم يتم التحويل تلقائياً خلال ثوانٍ، اضغط على الرابط أدناه:</p>
    <a href="<?= htmlspecialchars($targetUrl) ?>" class="redirect-link" id="manualLink">اضغط هنا للتحميل المباشر</a>
  </div>

</body>
</html>
