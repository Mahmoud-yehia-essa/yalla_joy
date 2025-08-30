<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('backend/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">

        </div>
        <div>
            <h4 class="logo-text"> يلا ! جَوّي</h4>
        </div>
        <div class="toggle-icon ms-auto"><i class='bx bx-arrow-back'></i>
        </div>
     </div>
    <!--navigation-->
    <ul class="metismenu" id="menu">
        <li>
            <a href="{{route('dashboard')}}">
                <div class="parent-icon"><i class='bx bx-home-alt'></i>
                </div>
                <div class="menu-title">الرئيسية</div>
            </a>
        </li>



        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
               <i class="bx bx-joystick"></i>
                    {{-- <i class="bx bx-category"></i> --}}

                </div>
                <div class="menu-title">انواع الألعاب</div>
            </a>
            <ul>
                <li> <a href="{{route('all.game.type')}}"><i class='bx bx-radio-circle'></i>عرض الأنواع</a>
                </li>
                <li> <a href="{{route('add.game.type')}}"><i class='bx bx-radio-circle'></i>إضافة نوع جديد</a>
                </li>





            </ul>
        </li>




          <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">الفئات الرئيسية</div>
            </a>
            <ul>
                <li> <a href="{{route('all.main.category')}}"><i class='bx bx-radio-circle'></i>عرض الفئات الرئيسية</a>
                </li>
                <li> <a href="{{route('add.main.category')}}"><i class='bx bx-radio-circle'></i> إضافة الفئات الرئيسية</a>
                </li>





            </ul>
        </li>





        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">الفئات الفرعية</div>
            </a>
            <ul>
                <li> <a href="{{route('all.category')}}"><i class='bx bx-radio-circle'></i>عرض الفئات</a>
                </li>
                <li> <a href="{{route('add.category')}}"><i class='bx bx-radio-circle'></i>إضافة الفئات</a>
                </li>





            </ul>
        </li>


        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <ion-icon name="person-outline"></ion-icon>

                </i>
                </div>

                <div class="menu-title"> إدارة المستخدمين</div>
            </a>
            <ul>
                <li> <a href="{{route('all.users')}}"><i class='bx bx-radio-circle'></i>عرض المستخدمين</a>
                </li>
                <li> <a href="{{route('add.user')}}"><i class='bx bx-radio-circle'></i>إضافة مستخدم جديد</a>
                </li>





            </ul>
        </li>



        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <ion-icon name="help-circle-outline"></ion-icon>
                </div>

                <div class="menu-title">الأسئلة</div>
            </a>
            <ul>
                <li> <a href="{{route('all.question')}}"><i class='bx bx-radio-circle'></i>عرض الأسئلة</a>
                </li>
                <li> <a href="{{route('add.question')}}"><i class='bx bx-radio-circle'></i>إضافة سؤال جديد</a>
                </li>





            </ul>
        </li>



        {{-- <li>
            <a href="javascript:;" class="has-arrow">
             <div class="parent-icon">
            <i class='bx bx-bot'></i> <!-- Robot icon from Boxicons -->

        </div>

                <div class="menu-title"> الأسئلة بإستخدام AI</div>
            </a>
            <ul>
                <li> <a href="{{route('all.question.ai')}}"><i class='bx bx-radio-circle'></i>إنشاء الأسئلة</a>
                </li>






            </ul>
        </li> --}}


        <li>
            <a href="{{route('all.games')}}">
                <div class="parent-icon">
                    <ion-icon name="game-controller-outline"></ion-icon>

                </div>


                <div class="menu-title">الألعاب المسجلة</div>
            </a>
        </li>


{{--
        <li>
            <a href="{{route('add.ads')}}">
                <div class="parent-icon">
                    <ion-icon name="megaphone-outline"></ion-icon>

                </div>
                <div class="menu-title">ادارة الإعلانات</div>
            </a>
        </li> --}}


        <li>
            <a href="{{route('report.view')}}">
                <div class="parent-icon">
                    <ion-icon name="stats-chart-outline"></ion-icon>

                </div>
                <div class="menu-title">الاحصائيات</div>
            </a>
        </li>


        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <ion-icon name="notifications-outline"></ion-icon>
                </div>

                <div class="menu-title">ادارة الإشعارات</div>
            </a>
            <ul>
                <li> <a href="{{route('all.notification')}}"><i class='bx bx-radio-circle'></i>عرض الاشعارات</a>
                </li>
                <li> <a href="{{route('send.notification')}}"><i class='bx bx-radio-circle'></i>ارسال اشعار جديد</a>
                </li>





            </ul>
        </li>



         <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
<ion-icon name="ribbon-outline"></ion-icon>
                </div>

                <div class="menu-title"> التحكم في تكلفة عناصر اللعبة</div>
            </a>
            <ul>
                <li> <a href="{{route('all.title.position')}}"><i class='bx bx-radio-circle'></i> كل العناصر المضافة</a>
                </li>
                <li> <a href="{{route('add.title.position')}}"><i class='bx bx-radio-circle'></i>اضافة عنصر جديد</a>
                </li>
            </ul>

        </li>



           {{-- <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
<ion-icon name="shirt-outline"></ion-icon>
                </div>

                <div class="menu-title"> التحكم في الملابس والاكسسورات</div>
            </a>
            <ul>
                <li> <a href="{{route('all.notification')}}"><i class='bx bx-radio-circle'></i> كل المكافأت</a>
                </li>
                <li> <a href="{{route('send.notification')}}"><i class='bx bx-radio-circle'></i>اضافة مكافأة جديدة</a>
                </li>
            </ul>

        </li> --}}







                 <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><ion-icon name="cash-outline"></ion-icon>


                </i>
                </div>
                <div class="menu-title">ادارة الأسعار الخاصة بعملة اللعبة</div>
            </a>
            <ul>

                <li> <a href="{{ route('all.price') }}"><i class="bx bx-right-arrow-alt"></i>جميع الأسعار</a>
                </li>


                <li> <a href="{{ route('add.price') }}"><i class="bx bx-right-arrow-alt"></i>إضافة سعر جديد</a>
                </li>


            </ul>
        </li>

            <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><ion-icon name="happy-outline"></ion-icon></i>
                </div>
                <div class="menu-title">ادارة الكوبونات</div>
            </a>
            <ul>

                <li> <a href="{{ route('all.coupon') }}"><i class="bx bx-right-arrow-alt"></i>جميع الكوبونات</a>
                </li>


                <li> <a href="{{ route('add.coupon') }}"><i class="bx bx-right-arrow-alt"></i>إضافة كوبون</a>
                </li>


            </ul>
        </li>


        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
<ion-icon name="phone-portrait-outline"></ion-icon>
                </div>

                <div class="menu-title">ادارة اللعبة</div>
            </a>
            <ul>

                 <li> <a href="{{route('add.game.coin')}}"><i class='bx bx-radio-circle'></i>اداة عملة اللعبة</a>
                </li>
                <li> <a href="{{route('add.versions')}}"><i class='bx bx-radio-circle'></i>اعدادات اللعبة</a>
                </li>






            </ul>
        </li>


           <li>
            <a href="javascript:;" class="has-arrow">
<div class="parent-icon"><ion-icon name="business-outline"></ion-icon></div>


                <div class="menu-title">ادارة الرعاة</div>
            </a>
            <ul>


                   <li> <a href="{{ route('sponsor.all') }}"><i class="bx bx-right-arrow-alt"></i>كل الرعاة</a>
                </li>

                 <li> <a href="{{ route('sponsor.add.new') }}"><i class="bx bx-right-arrow-alt"></i>اضافة جديد</a>
                </li>

                {{-- <li> <a href="{{ route('sponsor.add.cate') }}"><i class="bx bx-right-arrow-alt"></i> في شاشة الفئات</a>
                </li>

                 <li> <a href="{{ route('sponsor.add.question') }}"><i class="bx bx-right-arrow-alt"></i> في شاشة السؤال</a>
                </li> --}}





            </ul>
        </li>






           <li>
            <a href="javascript:;" class="has-arrow">
<div class="parent-icon"><ion-icon name="business-outline"></ion-icon></div>


                <div class="menu-title">ادارة الرعاة مع المكافآت</div>
            </a>
            <ul>


                   <li> <a href="{{ route('all.rewards.sponsors') }}"><i class="bx bx-right-arrow-alt"></i>كل الرعاة مع المكافآت</a>
                </li>

                 <li> <a href="{{ route('add.rewards.sponsors') }}"><i class="bx bx-right-arrow-alt"></i>اضافة جديد</a>
                </li>


                   <li> <a href="{{ route('get.all.rewards.users') }}"><i class="bx bx-right-arrow-alt"></i>متابعة المستخدمين للمكافات</a>
                </li>

                {{-- <li> <a href="{{ route('sponsor.add.cate') }}"><i class="bx bx-right-arrow-alt"></i> في شاشة الفئات</a>
                </li>

                 <li> <a href="{{ route('sponsor.add.question') }}"><i class="bx bx-right-arrow-alt"></i> في شاشة السؤال</a>
                </li> --}}





            </ul>
        </li>




    </ul>
    <!--end navigation-->
</div>
