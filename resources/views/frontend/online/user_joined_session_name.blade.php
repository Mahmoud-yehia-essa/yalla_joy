<!DOCTYPE html>
<html lang="ar" dir="rtl">

<head>
    <meta charset="UTF-8">
    <title>{{ $gameInfo->online_game_name }}</title>

    <meta name="viewport" content="width=device-width, initial-scale=1">

    {{-- Bootstrap --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    {{-- Google Font --}}
    <link href="https://fonts.googleapis.com/css2?family=Cairo:wght@400;600;800&display=swap" rel="stylesheet">

    <style>

        body{
            font-family: 'Cairo', sans-serif;
            background: linear-gradient(135deg,#0f2027,#203a43,#2c5364);
            min-height:100vh;
            color:#fff;
        }

        /* ===== Card ===== */
        .game-card{
            background:#ffffff;
            color:#333;
            border-radius:20px;
            box-shadow:0 20px 60px rgba(0,0,0,.25);
            animation: cardEnter 0.8s ease;
        }

        @keyframes cardEnter{
            from{
                opacity:0;
                transform:translateY(40px) scale(.95);
            }
            to{
                opacity:1;
                transform:translateY(0) scale(1);
            }
        }

        /* ===== Logo ===== */
        .app-logo{
            width:90px;
            animation: floatLogo 3s ease-in-out infinite;
        }

        @keyframes floatLogo{
            0%{ transform: translateY(0); }
            50%{ transform: translateY(-10px); }
            100%{ transform: translateY(0); }
        }

        /* ===== Categories ===== */
        .badge-category{
            font-size:14px;
            padding:8px 14px;
            border-radius:30px;
        }

        /* ===== Session Code ===== */
        .session-code{
            letter-spacing:2px;
            font-weight:bold;
        }

        /* ===== Title Glow ===== */
        .header-title{
            font-weight:800;
            animation: glowText 2.5s infinite;
        }

        @keyframes glowText{
            0%{ text-shadow:0 0 0 rgba(13,110,253,0); }
            50%{ text-shadow:0 0 15px rgba(13,110,253,.5); }
            100%{ text-shadow:0 0 0 rgba(13,110,253,0); }
        }

        /* ===== Live Badge ===== */
        .live-badge{
            animation:pulseLive 1.5s infinite;
        }

        @keyframes pulseLive{
            0%{ transform:scale(1); }
            50%{ transform:scale(1.1); }
            100%{ transform:scale(1); }
        }

        /* ===== Join Button ===== */
        .join-btn{
            font-size:20px;
            padding:14px 40px;
            border-radius:50px;
            transition:.3s;
            position:relative;
            overflow:hidden;
        }

        .join-btn:hover{
            transform:scale(1.05);
            box-shadow:0 10px 25px rgba(0,0,0,.3);
        }

        /* light sweep effect */
        .join-btn::before{
            content:'';
            position:absolute;
            top:0;
            left:-100%;
            width:100%;
            height:100%;
            background:linear-gradient(
                120deg,
                transparent,
                rgba(255,255,255,.6),
                transparent
            );
            transition:.6s;
        }

        .join-btn:hover::before{
            left:100%;
        }

    </style>
</head>

<body>

<div class="container py-5">

    <div class="row justify-content-center">

        <div class="col-lg-9">

            <div class="game-card p-5">

                {{-- شعار التطبيق --}}
                <div class="text-center mb-3">
                    <img src="{{ asset('backend/assets/images/login-images/logo_tahadi.png') }}"
                         class="app-logo"
                         alt="فيك تحدي">
                </div>

                {{-- عنوان المسابقة --}}
                <div class="text-center mb-2">

                    <h2 class="text-primary header-title">
                        🎮 {{ $gameInfo->online_game_name }}
                    </h2>

                    <p class="text-muted">
                        المنافسة بدأت... هل أنت مستعد للتحدي؟
                    </p>

                </div>

                {{-- LIVE --}}
                <div class="text-center mb-4">
                    <span class="badge bg-danger px-3 py-2 live-badge">
                        🔴 العب اون لاين الان
                    </span>
                </div>

                <hr>

                {{-- معلومات المسابقة --}}
                <div class="row text-center gy-4">

                    <div class="col-md-3">
                        <small class="text-muted">أنشأ بواسطة</small>
                        <h6 class="fw-bold mt-2">
                            {{ $gameInfo->user->fname ?? 'غير معروف' }}
                            {{ $gameInfo->user->lname ?? '' }}
                        </h6>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted">كود المسابقة</small>
                        <h5 class="session-code badge bg-dark mt-2">
                            {{ $gameInfo->game_session_name }}
                        </h5>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted">تاريخ الإنشاء</small>
                        <h6 class="fw-bold mt-2">
                            {{ $gameInfo->created_at->format('Y-m-d') }}
                        </h6>
                    </div>

                    <div class="col-md-3">
                        <small class="text-muted">عدد المشاركين</small>
                        <h6 class="fw-bold mt-2">
                            👥
                            {{ $gameInfo->users_count }}
                        </h6>
                    </div>

                </div>

                <hr class="my-4">

                {{-- التصنيفات --}}
                <div>

                    <h5 class="fw-bold mb-3">
                        📚 تصنيفات اللعبة
                    </h5>

                    <div class="d-flex flex-wrap gap-2">

                        @foreach($gameInfo->categories as $item)
                            <span class="badge bg-primary badge-category">
                                {{ $item->category->category_name ?? 'تصنيف' }}
                            </span>
                        @endforeach

                    </div>

                </div>

                {{-- رسالة تشويقية --}}
                <div class="alert alert-info text-center mt-5">

                    🚀 حان وقت الإثارة!

                    <br><br>

                    انضم الآن إلى المنافسة الأونلاين داخل
                    <strong>تطبيق فيك تحدي</strong>
                    وواجه لاعبين حقيقيين في تحديات مباشرة،
                    اجمع النقاط واثبت أنك الأفضل!

                </div>

                {{-- زر الانضمام --}}
                <div class="text-center mt-4">

                    <a href="#" class="btn btn-success join-btn shadow">
                        🔥 انضم للمنافسة الآن
                    </a>

                </div>

            </div>

        </div>

    </div>

</div>

</body>
</html>
