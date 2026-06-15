<div class="sidebar-wrapper" data-simplebar="true">
    <div class="sidebar-header">
        <div>
            <img src="{{ asset('backend/assets/images/logo-icon.png') }}" class="logo-icon" alt="logo icon">

        </div>
        <div>
            <h4 class="logo-text">فيك تحدي</h4>
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




        				@if(Auth::user()->can('عرض أنواع الألعاب') || Auth::user()->can('إضافة أنواع الألعاب'))

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
               <i class="bx bx-joystick"></i>
                    {{-- <i class="bx bx-category"></i> --}}

                </div>
                <div class="menu-title">انواع الألعاب</div>
            </a>
            <ul>

            @if(Auth::user()->can('عرض أنواع الألعاب'))

                <li> <a href="{{route('all.game.type')}}"><i class='bx bx-radio-circle'></i>عرض الأنواع</a>
                </li>
            @endif


        @if(Auth::user()->can('إضافة أنواع الألعاب'))


                <li> <a href="{{route('add.game.type')}}"><i class='bx bx-radio-circle'></i>إضافة نوع جديد</a>
                </li>

            @endif




            </ul>
        </li>
				@endif




                @if(Auth::user()->can('إضافة الفئات الرئيسية') || Auth::user()->can('عرض الفئات الرئيسية'))


          <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">الفئات الرئيسية</div>
            </a>
            <ul>
                @if(Auth::user()->can('عرض الفئات الرئيسية'))

                <li> <a href="{{route('all.main.category')}}"><i class='bx bx-radio-circle'></i>عرض الفئات الرئيسية</a>
                </li>

                @endif

             @if(Auth::user()->can('إضافة الفئات الرئيسية') )

                <li> <a href="{{route('add.main.category')}}"><i class='bx bx-radio-circle'></i> إضافة الفئات الرئيسية</a>
                </li>
                @endif





            </ul>
        </li>
				@endif





         @if(Auth::user()->can('إضافة الفئات الفرعية') || Auth::user()->can('عرض الفئات الفرعية'))
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><i class="bx bx-category"></i>
                </div>
                <div class="menu-title">الفئات الفرعية</div>
            </a>
            <ul>




            @if(Auth::user()->can('عرض الفئات الفرعية'))

                <li> <a href="{{route('all.category')}}"><i class='bx bx-radio-circle'></i>عرض الفئات</a>
                </li>
				@endif


            @if(Auth::user()->can('إضافة الفئات الفرعية'))

                <li> <a href="{{route('add.category')}}"><i class='bx bx-radio-circle'></i>إضافة الفئات</a>
                </li>


				@endif


  <li> <a href="{{route('filter.category')}}"><i class='bx bx-radio-circle'></i>البحث المتقدم</a>
                </li>

            </ul>
        </li>
				@endif




         @if(Auth::user()->can('عرض المستخدمين') || Auth::user()->can('إضافة المستخدمين'))


        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <ion-icon name="person-outline"></ion-icon>

                </i>
                </div>

                <div class="menu-title"> إدارة المستخدمين</div>
            </a>
            <ul>

                @if(Auth::user()->can('عرض المستخدمين'))

                <li> <a href="{{route('all.users')}}"><i class='bx bx-radio-circle'></i>عرض المستخدمين</a>
                </li>

                @endif

              @if(Auth::user()->can('إضافة المستخدمين'))

                <li> <a href="{{route('add.user')}}"><i class='bx bx-radio-circle'></i>إضافة مستخدم جديد</a>
                </li>
                @endif





            </ul>
        </li>

        				@endif





                                 @if(Auth::user()->can('عرض الأسئلة') || Auth::user()->can('إضافة الأسئلة'))

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <ion-icon name="help-circle-outline"></ion-icon>
                </div>

                <div class="menu-title">الأسئلة</div>
            </a>
            <ul>
                @if(Auth::user()->can('عرض الأسئلة'))

                <li> <a href="{{route('all.question')}}"><i class='bx bx-radio-circle'></i>عرض الأسئلة</a>
                </li>
                @endif


                @if( Auth::user()->can('إضافة الأسئلة'))

                <li> <a href="{{route('add.question')}}"><i class='bx bx-radio-circle'></i>إضافة سؤال جديد</a>
                </li>


                @endif


                <li> <a href="{{route('excel.index')}}"><i class='bx bx-radio-circle'></i>اضافة الأسئلة من خلال
                    Excel</a>
                </li>


                <li> <a href="{{route('filter.question')}}"><i class='bx bx-radio-circle'></i>   البحث المتقدم
                    </a>
                </li>





            </ul>
        </li>

                				@endif





    @if(Auth::user()->can('إنشاء الأسئلة بإستخدام AI'))

        <li>
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
        </li>


 @endif


        @if(Auth::user()->can('عرض الألعاب المسجلة'))


        <li>
            <a href="{{route('all.games')}}">
                <div class="parent-icon">
                    <ion-icon name="game-controller-outline"></ion-icon>

                </div>


                <div class="menu-title">الألعاب المسجلة</div>
            </a>
        </li>
 @endif


{{--
        <li>
            <a href="{{route('add.ads')}}">
                <div class="parent-icon">
                    <ion-icon name="megaphone-outline"></ion-icon>

                </div>
                <div class="menu-title">ادارة الإعلانات</div>
            </a>
        </li> --}}


     @if(Auth::user()->can('عرض الإحصائيات'))


        <li>
            <a href="{{route('report.view')}}">
                <div class="parent-icon">
                    <ion-icon name="stats-chart-outline"></ion-icon>

                </div>
                <div class="menu-title">الاحصائيات</div>
            </a>
        </li>
 @endif




         @if(Auth::user()->can('إرسال الإشعارات') || Auth::user()->can('عرض الإشعارات'))

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <ion-icon name="notifications-outline"></ion-icon>
                </div>

                <div class="menu-title">ادارة الإشعارات</div>
            </a>
            <ul>
            @if(Auth::user()->can('عرض الإشعارات'))

                <li> <a href="{{route('all.notification')}}"><i class='bx bx-radio-circle'></i>عرض الاشعارات</a>
                </li>
                 @endif

            @if(Auth::user()->can('إرسال الإشعارات'))


                <li> <a href="{{route('send.notification')}}"><i class='bx bx-radio-circle'></i>ارسال اشعار جديد</a>
                </li>
             @endif





            </ul>
        </li>

         @endif






         {{-- <li>
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

        </li> --}}



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







     @if(Auth::user()->can('عرض الأسعار') || Auth::user()->can('إضافة الأسعار'))

                 <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><ion-icon name="cash-outline"></ion-icon>


                </i>
                </div>
                <div class="menu-title">ادارة الأسعار الخاصة بعملة اللعبة</div>
            </a>
            <ul>

                     @if(Auth::user()->can('عرض الأسعار'))

                <li> <a href="{{ route('all.price') }}"><i class="bx bx-right-arrow-alt"></i>جميع الأسعار</a>
                </li>
         @endif

                              @if(Auth::user()->can('إضافة الأسعار'))

                <li> <a href="{{ route('add.price') }}"><i class="bx bx-right-arrow-alt"></i>إضافة سعر جديد</a>
                </li>
         @endif


            </ul>
        </li>
         @endif





                 <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><ion-icon name="cash-outline"></ion-icon>


                </i>
                </div>
                <div class="menu-title">ادارة قيمة الألعاب بالنسبة للعملات وبالنسبة للقيمة النقدية</div>
            </a>
            <ul>


                <li> <a href="{{ route('all.game.offline.price') }}"><i class="bx bx-right-arrow-alt"></i>جميع الألعاب وقيمتها بالعملات</a>
                </li>



                <li> <a href="{{ route('add.game.offline.price') }}"><i class="bx bx-right-arrow-alt"></i>إضافة قيمة لعبة جديد</a>
                </li>

                <li> <a href="{{ route('all.game.purchase') }}"><i class="bx bx-right-arrow-alt"></i>عرض القيمة النقدية للألعاب</a>
                </li>

                <li> <a href="{{ route('add.game.purchase') }}"><i class="bx bx-right-arrow-alt"></i>إضافة قيمة نقدية جديدة</a>
                </li>

            </ul>
        </li>





              @if(Auth::user()->can('عرض الكوبونات') || Auth::user()->can('إضافة الكوبونات'))

            <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon"><ion-icon name="happy-outline"></ion-icon></i>
                </div>
                <div class="menu-title">ادارة الكوبونات</div>
            </a>
            <ul>

        @if(Auth::user()->can('عرض الكوبونات'))

                <li> <a href="{{ route('all.coupon') }}"><i class="bx bx-right-arrow-alt"></i>جميع الكوبونات</a>
                </li>
         @endif



          @if(Auth::user()->can('إضافة الكوبونات'))

                <li> <a href="{{ route('add.coupon') }}"><i class="bx bx-right-arrow-alt"></i>إضافة كوبون</a>
                </li>
          @endif

                <li> <a href="{{ route('all.coupon_companies') }}"><i class="bx bx-right-arrow-alt"></i>جميع كوبونات الشركات</a>
                </li>
                <li> <a href="{{ route('add.coupon_companies') }}"><i class="bx bx-right-arrow-alt"></i>إضافة كوبون شركة</a>
                </li>

                <li> <a href="{{ route('all.used_coupon_companies') }}"><i class="bx bx-right-arrow-alt"></i>سجل استخدام الكوبونات</a>
                </li>

                <li> <a href="{{ route('all.game.coupon') }}"><i class="bx bx-right-arrow-alt"></i>جميع كوبونات الألعاب</a>
                </li>
                <li> <a href="{{ route('add.game.coupon') }}"><i class="bx bx-right-arrow-alt"></i>إضافة كوبون ألعاب جديد</a>
                </li>

            </ul>
        </li>
         @endif






    @if(Auth::user()->can('عرض المستويات') || Auth::user()->can('إضافة المستويات'))

        <li>
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon"><ion-icon name="layers-outline"></ion-icon></div>
        <div class="menu-title">إدارة المستويات</div>
    </a>
    <ul>
    @if(Auth::user()->can('عرض المستويات') )

        <li>
            <a href="{{ route('all.level') }}">
                <i class="bx bx-right-arrow-alt"></i> جميع المستويات
            </a>
        </li>

                 @endif


                     @if(Auth::user()->can('إضافة المستويات'))

        <li>
            <a href="{{ route('add.level') }}">
                <i class="bx bx-right-arrow-alt"></i> إضافة مستوى
            </a>
        </li>

                         @endif

    </ul>
</li>
         @endif





             @if(Auth::user()->can('عرض الرتب') || Auth::user()->can('إضافة الرتب'))

<li>
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon"><ion-icon name="trophy-outline"></ion-icon></div>
        <div class="menu-title">ادارة الرتب</div>
    </a>
    <ul>
         @if(Auth::user()->can('عرض الرتب'))

        <li>
            <a href="{{ route('all.ranking') }}"><i class="bx bx-right-arrow-alt"></i>جميع الرتب</a>
        </li>

        @endif

                 @if( Auth::user()->can('إضافة الرتب'))

        <li>
            <a href="{{ route('add.ranking') }}"><i class="bx bx-right-arrow-alt"></i>إضافة رتبة جديدة</a>
        </li>
                @endif

    </ul>
</li>
         @endif



                      @if(Auth::user()->can('عرض أنواع عناصر اللعبة') || Auth::user()->can('إضافة أنواع عناصر اللعبة'))

<li>
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon">
            <ion-icon name="cube-outline"></ion-icon>
        </div>
        <div class="menu-title">إدارة نوع عنصر اللعبة</div>
    </a>
    <ul>

        @if(Auth::user()->can('عرض أنواع عناصر اللعبة'))

        <li>
            <a href="{{ route('all.item.type') }}">
                <i class="bx bx-right-arrow-alt"></i>جميع الأنواع
            </a>
        </li>

                 @endif

     @if(Auth::user()->can('إضافة أنواع عناصر اللعبة'))

        <li>
            <a href="{{ route('add.item.type') }}">
                <i class="bx bx-right-arrow-alt"></i>إضافة نوع
            </a>
        </li>

                         @endif

    </ul>
</li>
         @endif




     @if(Auth::user()->can('عرض عناصر اللعبة') || Auth::user()->can('إضافة عناصر اللعبة'))


<li>
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon">
            <ion-icon name="game-controller-outline"></ion-icon>
        </div>
        <div class="menu-title">إدارة عناصر اللعبة</div>
    </a>
    <ul>

     @if(Auth::user()->can('عرض عناصر اللعبة') )

        <li>
            <a href="{{ route('all.game.item') }}">
                <i class="bx bx-right-arrow-alt"></i>جميع العناصر
            </a>
        </li>
         @endif

     @if( Auth::user()->can('إضافة عناصر اللعبة'))

        <li>
            <a href="{{ route('add.game.item') }}">
                <i class="bx bx-right-arrow-alt"></i>إضافة عنصر
            </a>
        </li>
         @endif



    </ul>
</li>

         @endif




      @if(Auth::user()->can('عرض تصنيفات الافاتار') || Auth::user()->can('إضافة تصنيفات الافاتار') || Auth::user()->can('عرض عناصر الافاتار') || Auth::user()->can('إضافة عناصر الافاتار'))
<li>
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon">
            <ion-icon name="shirt-outline"></ion-icon>
        </div>
        <div class="menu-title">إدارة الأفاتار</div>
    </a>
    <ul>
        @if(Auth::user()->can('عرض تصنيفات الافاتار'))
        <li>
            <a href="{{ route('all.avatar.category') }}">
                <i class="bx bx-right-arrow-alt"></i>جميع التصنيفات
            </a>
        </li>
        @endif
        @if(Auth::user()->can('إضافة تصنيفات الافاتار'))
        <li>
            <a href="{{ route('add.avatar.category') }}">
                <i class="bx bx-right-arrow-alt"></i>إضافة تصنيف
            </a>
        </li>
        @endif
        @if(Auth::user()->can('عرض عناصر الافاتار'))
        <li>
            <a href="{{ route('all.avatar.item') }}">
                <i class="bx bx-right-arrow-alt"></i>جميع العناصر
            </a>
        </li>
        @endif
        @if(Auth::user()->can('إضافة عناصر الافاتار'))
        <li>
            <a href="{{ route('add.avatar.item') }}">
                <i class="bx bx-right-arrow-alt"></i>إضافة عنصر جديد
            </a>
        </li>
        @endif
    </ul>
</li>
      @endif




      @if(Auth::user()->can('عرض المساعدات'))

<li>
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon">
            <ion-icon name="help-buoy-outline"></ion-icon>
        </div>
        <div class="menu-title">إدارة مساعدات اللعبة</div>
    </a>
    <ul>
        <li>
            <a href="{{ route('all.game.helper') }}">
                <i class="bx bx-right-arrow-alt"></i>جميع المساعدات
            </a>
        </li>
        {{-- <li>
            <a href="{{ route('add.game.helper') }}">
                <i class="bx bx-right-arrow-alt"></i>إضافة مساعدة
            </a>
        </li> --}}
    </ul>
</li>

         @endif

{{--
<li>
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon">
            <ion-icon name="gift-outline"></ion-icon>
        </div>
        <div class="menu-title">إدارة حزم اللعبة</div>
    </a>
    <ul>
        <li>
            <a href="{{ route('all.game.bundle') }}">
                <i class="bx bx-right-arrow-alt"></i>جميع الحزم
            </a>
        </li>
        <li>
            <a href="{{ route('add.game.bundle') }}">
                <i class="bx bx-right-arrow-alt"></i>إضافة حزمة
            </a>
        </li>
    </ul>
</li>
 --}}





<!-- إدارة حزم اللعبة -->

     @if(Auth::user()->can('عرض جميع الحزم') || Auth::user()->can('إضافة الحزم'))

<li>
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon">
            <ion-icon name="gift-outline"></ion-icon>
        </div>
        <div class="menu-title">إدارة حزم اللعبة</div>
    </a>
    <ul>

     @if(Auth::user()->can('عرض جميع الحزم'))

        <li>
            <a href="{{ route('all.game.bundle') }}">
                <i class="bx bx-right-arrow-alt"></i>جميع الحزم
            </a>
        </li>
         @endif

     @if(Auth::user()->can('إضافة الحزم'))


        <li>
            <a href="{{ route('add.game.bundle') }}">
                <i class="bx bx-right-arrow-alt"></i>إضافة حزمة
            </a>
        </li>
         @endif



    </ul>
</li>

         @endif


<!-- إدارة صحلاحيات اللعبة -->

     @if(Auth::user()->can('عرض جميع الصلاحيات') || Auth::user()->can('إضافة الصلاحيات') || Auth::user()->can('عرض الأدوار') || Auth::user()->can('إضافة الأدوار'))

<li>
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon">
<ion-icon name="lock-closed-outline"></ion-icon>
        </div>
        <div class="menu-title">إدارة صلاحيات اللعبة</div>
    </a>
    <ul>

             @if(Auth::user()->can('عرض جميع الصلاحيات'))

        <li>
            <a href="{{ route('all.permission') }}">
                <i class="bx bx-right-arrow-alt"></i>جميع الصلاحيات
            </a>
        </li>

 @endif

             @if( Auth::user()->can('إضافة الصلاحيات'))

        <li>
            <a href="{{ route('add.permission') }}">
                <i class="bx bx-right-arrow-alt"></i>إضافة صلاحية جديدة
            </a>
        </li>

 @endif

             @if(Auth::user()->can('عرض الأدوار'))

         <li>
            <a href="{{ route('all.roles') }}">
                <i class="bx bx-right-arrow-alt"></i>جميع الأدوار
            </a>
        </li>
 @endif

     @if(Auth::user()->can('إضافة الأدوار'))


         <li>
            <a href="{{ route('add.roles') }}">
                <i class="bx bx-right-arrow-alt"></i>اضافة دور جديد
            </a>
        </li>

 @endif




         {{-- <li>
            <a href="{{ route('add.roles.permission') }}">
                <i class="bx bx-right-arrow-alt"></i> اضافة صلاحيات للأدوار
            </a>
        </li> --}}


    </ul>
</li>

         @endif

     @if(Auth::user()->can('عرض المديرين') || Auth::user()->can('إضافة المديرين'))

				<li>
					<a class="has-arrow" href="javascript:;">
						<div class="parent-icon">            <i class="bx bx-group"></i>


						</div>
						<div class="menu-title">ادارة المديرين</div>
					</a>
					<ul>

     @if(Auth::user()->can('عرض المديرين'))

						<li> <a href="{{ route('all.admin') }}"><i class="bx bx-right-arrow-alt"></i>كل المديرين</a>
						</li>
         @endif


            @if( Auth::user()->can('إضافة المديرين'))

						<li> <a href="{{ route('add.admin') }}"><i class="bx bx-right-arrow-alt"></i>اضافة مدير جديد</a>

						</li>
         @endif


					</ul>
				</li>
         @endif


<!-- إدارة ألعاب المستخدمين -->

     @if(Auth::user()->can('عرض جميع ألعاب المستخدمين') || Auth::user()->can('إضافة ألعاب المستخدمين'))

<li>
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon">
            <ion-icon name="game-controller-outline"></ion-icon>
        </div>
        <div class="menu-title">ألعاب المستخدمين</div>
    </a>
    <ul>

         @if(Auth::user()->can('عرض جميع ألعاب المستخدمين'))

        <li>
            <a href="{{ route('all.user.games') }}">
                <i class="bx bx-right-arrow-alt"></i>جميع الألعاب
            </a>
        </li>

                 @endif


         @if(Auth::user()->can('إضافة ألعاب المستخدمين'))

        <li>
            <a href="{{ route('add.user.game') }}">
                <i class="bx bx-right-arrow-alt"></i>إضافة لعبة جديدة
            </a>
        </li>

     @endif

    </ul>
</li>
         @endif


<!-- دليل اللعبة -->

     @if(Auth::user()->can('عرض أدلة اللعبة') || Auth::user()->can('إضافة أدلة اللعبة'))

<li>
    <a href="javascript:;" class="has-arrow">
        <div class="parent-icon">
            <ion-icon name="book-outline"></ion-icon>
        </div>
        <div class="menu-title">دليل اللعبة</div>
    </a>
    <ul>

     @if(Auth::user()->can('عرض أدلة اللعبة'))

        <li>
            <a href="{{ route('all.game.guide') }}">
                <i class="bx bx-right-arrow-alt"></i>جميع الأدلة
            </a>
        </li>
         @endif



             @if(Auth::user()->can('إضافة أدلة اللعبة'))

        <li>
            <a href="{{ route('add.game.guide') }}">
                <i class="bx bx-right-arrow-alt"></i>إضافة دليل جديد
            </a>
        </li>

                 @endif

    </ul>
</li>
         @endif




     @if(Auth::user()->can('عرض العملات') || Auth::user()->can('إضافة العملات'))

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">

<ion-icon name="cash-outline"></ion-icon>
                </div>

                <div class="menu-title">ادارة عملة اللعبة</div>
            </a>
            <ul>

                     @if( Auth::user()->can('إضافة العملات'))

       <li> <a href="{{route('add.game.coin')}}"><i class='bx bx-radio-circle'></i>اضافة عملة جديدة</a>
                </li>

         @endif


         @if(Auth::user()->can('عرض العملات'))

                  <li> <a href="{{route('all.game.coin')}}"><i class='bx bx-radio-circle'></i>عرض العملات</a>
                </li>

         @endif






            </ul>
        </li>
         @endif

     @if(Auth::user()->can('إعدادت اللعبة'))

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
<ion-icon name="construct-outline"></ion-icon>
                </div>

                <div class="menu-title">ادارة اللعبة</div>
            </a>
            <ul>


                <li> <a href="{{route('add.versions')}}"><i class='bx bx-radio-circle'></i>اعدادات اللعبة</a>
                </li>






            </ul>
        </li>
         @endif



            @if(Auth::user()->can('عرض الرعاة') || Auth::user()->can('إضافة راعي جديد'))

         <li>
            <a href="javascript:;" class="has-arrow">
<div class="parent-icon"><ion-icon name="business-outline"></ion-icon></div>


                <div class="menu-title">ادارة الرعاة</div>
            </a>
            <ul>

                @if(Auth::user()->can('عرض الرعاة'))

                   <li> <a href="{{ route('sponsor.all') }}"><i class="bx bx-right-arrow-alt"></i>كل الرعاة</a>
                </li>

                 @endif

                @if( Auth::user()->can('إضافة راعي جديد'))

                 <li> <a href="{{ route('sponsor.add.new') }}"><i class="bx bx-right-arrow-alt"></i>اضافة جديد</a>
                </li>
                @endif

                {{-- <li> <a href="{{ route('sponsor.add.cate') }}"><i class="bx bx-right-arrow-alt"></i> في شاشة الفئات</a>
                </li>

                 <li> <a href="{{ route('sponsor.add.question') }}"><i class="bx bx-right-arrow-alt"></i> في شاشة السؤال</a>
                </li> --}}





            </ul>
        </li>
         @endif




            @if(Auth::user()->can('عرض كل الرعاة مع المكافآت') || Auth::user()->can('اضافة المكافآت مع الرعاة') || Auth::user()->can('متابعة المستخدمين المكافآت'))


           <li>
            <a href="javascript:;" class="has-arrow">
<div class="parent-icon"><ion-icon name="business-outline"></ion-icon></div>


                <div class="menu-title">ادارة الرعاة مع المكافآت</div>
            </a>
            <ul>

            @if(Auth::user()->can('عرض كل الرعاة مع المكافآت') )

                   <li> <a href="{{ route('all.rewards.sponsors') }}"><i class="bx bx-right-arrow-alt"></i>كل الرعاة مع المكافآت</a>
                </li>
         @endif

                     @if( Auth::user()->can('اضافة المكافآت مع الرعاة'))


                 <li> <a href="{{ route('add.rewards.sponsors') }}"><i class="bx bx-right-arrow-alt"></i>اضافة جديد</a>
                </li>
         @endif

            @if( Auth::user()->can('متابعة المستخدمين المكافآت'))

                   <li> <a href="{{ route('get.all.rewards.users') }}"><i class="bx bx-right-arrow-alt"></i>متابعة المستخدمين للمكافات</a>
                </li>
         @endif

                {{-- <li> <a href="{{ route('sponsor.add.cate') }}"><i class="bx bx-right-arrow-alt"></i> في شاشة الفئات</a>
                </li>

                 <li> <a href="{{ route('sponsor.add.question') }}"><i class="bx bx-right-arrow-alt"></i> في شاشة السؤال</a>
                </li> --}}





            </ul>
        </li>
         @endif










        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">

<ion-icon name="cash-outline"></ion-icon>
                </div>

                <div class="menu-title">ادارة العملات مع النقاط</div>
            </a>
            <ul>


       <li> <a href="{{route('add.game.coin')}}"><i class='bx bx-radio-circle'></i>اضافة نظام نقاط جديدة</a>
                </li>




                  <li> <a href="{{route('all.point.coin')}}"><i class='bx bx-radio-circle'></i>عرض النقاط مع العملات</a>
                </li>








            </ul>
        </li>




        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="bx bx-text"></i>
                </div>
                <div class="menu-title">العبارات</div>
            </a>
            <ul>
                <li> <a href="{{route('all.proverb')}}"><i class='bx bx-radio-circle'></i>عرض العبارات</a>
                </li>
                <li> <a href="{{route('add.proverb')}}"><i class='bx bx-radio-circle'></i>إضافة عبارة جديدة</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="bx bx-star"></i>
                </div>
                <div class="menu-title">الرتب (الجديدة)</div>
            </a>
            <ul>
                <li> <a href="{{route('all.rankings.new')}}"><i class='bx bx-radio-circle'></i>عرض الرتب</a>
                </li>
                <li> <a href="{{route('add.ranking.new')}}"><i class='bx bx-radio-circle'></i>إضافة رتبة جديدة</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="bx bx-movie-play"></i>
                </div>
                <div class="menu-title">حركات الأنيميشن</div>
            </a>
            <ul>
                <li> <a href="{{route('all.animation')}}"><i class='bx bx-radio-circle'></i>عرض الحركات</a>
                </li>
                <li> <a href="{{route('add.animation')}}"><i class='bx bx-radio-circle'></i>إضافة حركة جديدة</a>
                </li>
            </ul>
        </li>

        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="bx bx-gift"></i>
                </div>
                <div class="menu-title">الخطط المجانية</div>
            </a>
            <ul>
                <li> <a href="{{route('all.free.plan')}}"><i class='bx bx-radio-circle'></i>عرض الخطط</a>
                </li>
                <li> <a href="{{route('add.free.plan')}}"><i class='bx bx-radio-circle'></i>إضافة خطة جديدة</a>
                </li>
            </ul>
        </li>

        @if(Auth::user()->role === 'admin' || Auth::user()->can('عرض البلاغات'))
        <li>
            <a href="javascript:;" class="has-arrow">
                <div class="parent-icon">
                    <i class="bx bx-error-alt"></i>
                </div>
                <div class="menu-title">إدارة البلاغات</div>
            </a>
            <ul>
                <li> <a href="{{ route('all.problem.reports') }}"><i class='bx bx-radio-circle'></i>عرض البلاغات</a>
                </li>
            </ul>
        </li>
        @endif

    </ul>
    <!--end navigation-->
</div>
