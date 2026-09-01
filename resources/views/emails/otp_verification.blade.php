<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <style>
    body { font-family: 'Arial', sans-serif; background-color: #0d1b2a; margin: 0; padding: 0; }
    .container { max-width: 600px; margin: 20px auto; background-color: #ffffff; border-radius: 15px; overflow: hidden; box-shadow: 0 10px 30px rgba(0,0,0,0.5); }
    .header { background: linear-gradient(135deg, #14376F, #001f3f); padding: 40px 20px; text-align: center; }
    .logo { width: 180px; filter: drop-shadow(0 0 10px rgba(255,255,255,0.3)); }
    .content { padding: 40px 30px; text-align: center; color: #333333; line-height: 1.6; }
    .title { font-size: 26px; font-weight: bold; color: #14376F; margin-bottom: 20px; }
    .message { font-size: 16px; color: #555555; margin-bottom: 35px; }
    .otp-container { background-color: #f8f9fa; border: 2px dashed #14376F; border-radius: 12px; padding: 25px; display: inline-block; margin-bottom: 30px; min-width: 200px; }
    .otp-code { font-size: 42px; font-weight: 800; letter-spacing: 12px; color: #f39c12; margin: 0; text-shadow: 1px 1px 2px rgba(0,0,0,0.1); }
    .validity { font-size: 13px; color: #888888; margin-top: 15px; }
    .footer { background-color: #f1f1f1; padding: 25px; text-align: center; font-size: 12px; color: #777777; border-top: 1px solid #eeeeee; }
    .social-text { color: #14376F; font-weight: bold; margin-bottom: 10px; }
  </style>
</head>
<body>
  <div class="container">
    <div class="header">
      <img src="https://fiktahadi.com/backend/assets/images/login-images/logo_yalla.png" alt="فيك تحدي" class="logo">
    </div>
    <div class="content">
      <div class="title">مرحباً بك في عالم التحدي</div>
      <p class="message">
        @if($type === 'parent_verification')
          يرجى استخدام كود التحقق التالي لتأكيد موافقة ولي الأمر في <b>لعبة فيك تحدي</b>:
        @elseif($type === 'reset_password')
          يرجى استخدام كود التحقق التالي لإعادة تعيين كلمة المرور الخاصة بحسابك في <b>لعبة فيك تحدي</b>:
        @else
          سعداء بانضمامك إلينا في <b>لعبة فيك تحدي</b>. يرجى استخدام الكود التالي لتفعيل حسابك والبدء في المنافسة:
        @endif
      </p>
      <div class="otp-container">
        <p class="otp-code">{{ $otpCode }}</p>
      </div>
      <p class="validity">ملاحظة: هذا الكود سري وصالح للاستخدام لمرة واحدة فقط.</p>
    </div>
    <div class="footer">
      <p class="social-text">لعبة فيك تحدي - المعرفة متعة والتحدي عندنا</p>
      <p>&copy; 2026 جميع الحقوق محفوظة فيك تحدي</p>
    </div>
  </div>
</body>
</html>
