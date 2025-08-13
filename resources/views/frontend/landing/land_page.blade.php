<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>يلا جّوي - قريباً</title>
  <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
  <style>
    body {
      background: linear-gradient(-45deg, #ffffff, #f8f9fa, #e6ebf0, #f1f3f6);
      background-size: 300% 300%;
      animation: gradientBG 10s ease infinite;
    }

    @keyframes gradientBG {
      0% {
        background-position: 0% 50%;
      }
      50% {
        background-position: 100% 50%;
      }
      100% {
        background-position: 0% 50%;
      }
    }
  </style>
</head>
<body class="flex items-center justify-center h-screen text-gray-800 text-center px-4">

  <div>
    <img src="{{ asset('backend/assets/images/login-images/logo_yalla.png') }}" class="img-fluid zoom-animation" width="400" alt=""/>

    <h1 class="text-5xl font-extrabold mb-6">🎮 يلا جوي</h1>
    <p class="text-4xl font-semibold mb-6">قريباً</p>
  </div>

</body>
</html>
