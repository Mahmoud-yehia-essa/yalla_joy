@extends('admin.master_admin')
@section('admin')
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.6.0/jquery.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Cairo&family=Tajawal&family=Amiri&family=Roboto&display=swap" rel="stylesheet">

<div class="col-lg-16">
    <div class="card">
        <div class="card-body">

            {{-- Display Success Message --}}
            @if(session('success'))
                <div class="alert alert-success">
                    {{ session('success') }}
                </div>
            @endif

            {{-- Display Validation Errors --}}
            @if($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('update.versions.store') }}">
                @csrf



                 <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">اسم اللعبة</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="text" class="form-control @error('app_name') is-invalid @enderror"
                               name="app_name" value="{{ old('app_name', $appVersion->app_name) }}">
                        @error('ios')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">اصدار اللعبة الحالي</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="text" class="form-control @error('version') is-invalid @enderror"
                               name="version" value="{{ old('version', $appVersion->version) }}">
                        @error('version')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">App Store رابط اللعبة على</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="text" class="form-control @error('ios') is-invalid @enderror"
                               name="ios" value="{{ old('ios', $appVersion->ios) }}">
                        @error('ios')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">Google Play  رابط اللعبة على</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="text" class="form-control @error('android') is-invalid @enderror"
                               name="android" value="{{ old('android', $appVersion->android) }}">
                        @error('android')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">الوصف</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <textarea name="des" class="form-control @error('des') is-invalid @enderror"
                                  id="input11" placeholder="Description ..." rows="3">{{ old('des', $appVersion->des) }}</textarea>
                        @error('des')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">رقم الواتساب للتواصل (مع كود الدولة)</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="text" class="form-control @error('whatsapp_number') is-invalid @enderror"
                               name="whatsapp_number" value="{{ old('whatsapp_number', $appVersion->whatsapp_number) }}"
                               placeholder="مثال: +966500000000 أو 966500000000">
                        @error('whatsapp_number')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">البريد الإلكتروني للتواصل</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="email" class="form-control @error('contact_email') is-invalid @enderror"
                               name="contact_email" value="{{ old('contact_email', $appVersion->contact_email) }}"
                               placeholder="مثال: support@fik-tahadi.com">
                        @error('contact_email')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">نقاط الفائز في لعبة الميدان (أونلاين)</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="number" min="0" class="form-control @error('online_game_win_points') is-invalid @enderror"
                               name="online_game_win_points" value="{{ old('online_game_win_points', $appVersion->online_game_win_points ?? 6) }}"
                               placeholder="مثال: 6">
                        <small class="text-muted">عدد النقاط الثابتة التي يحصل عليها الفائز في لعبة الميدان كبديل عن مجموع نقاط الأسئلة التي جاوب عليها أثناء اللعبة.</small>
                        @error('online_game_win_points')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">نقاط الفريق المرشح الفائز في لعبة الجلسة (الاوف لاين)</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">
                        <input type="number" min="0" class="form-control @error('offline_game_win_points') is-invalid @enderror"
                               name="offline_game_win_points" value="{{ old('offline_game_win_points', $appVersion->offline_game_win_points ?? 6) }}"
                               placeholder="مثال: 6">
                        <small class="text-muted">عدد النقاط الثابتة التي يحصل عليها المستخدم في حالة فوز الفريق الذي قام بترشيحه في بداية لعبة الجلسة كبديل عن مجموع درجات الأسئلة الصحيحة.</small>
                        @error('offline_game_win_points')
                            <div class="text-danger">{{ $message }}</div>
                        @enderror
                    </div>
                </div>



                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">التحديث في اللعبة الزامي ؟</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">


                        <select  name="update_required" class="form-select" aria-label="Default select example">



                            <option value="yes" {{ old('update_required',$appVersion->update_required) == 'yes' ? 'selected' : '' }} >نعم</option>

                            <option value="no" {{ old('update_required',$appVersion->update_required) == 'no' ? 'selected' : '' }} >لا</option>




                        </select>

                        @error('special') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>


                <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">العناصر في اللعبة ؟</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">


                        <select  name="app_type" class="form-select" aria-label="Default select example">



                            <option value="free" {{ old('app_type',$appVersion->app_type) == 'free' ? 'selected' : '' }} >مجانية</option>

                            <option value="paid" {{ old('app_type',$appVersion->app_type) == 'paid' ? 'selected' : '' }} >مدفوعة</option>




                        </select>

                        @error('special') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>





                  <div class="row mb-3">
                    <div class="col-sm-3">
                        <h6 class="mb-0">نوع الخط</h6>
                    </div>
                    <div class="col-sm-9 text-secondary">


                        <select  id="fontSelect" name="font_family_id" class="form-select" aria-label="Default select example">


 <option value="">اختر نوع الخط</option>
        @foreach($fontFamilies as $font)
                                                <option value="{{ $font->id }}" {{ old('font_family_id',$appVersion->font_family_id) == $font->id ? 'selected' : '' }} style="font-family: '{{ $font->font_family_name }}';">


            {{-- <option value="{{ $font->id }}"   style="font-family: '{{ $font->font_family_name }}';"> --}}
                {{ $font->font_family_name }}
            </option>
        @endforeach


                        </select>

                        @error('font_family_id') <span class="text-danger">{{ $message }}</span> @enderror
                    </div>
                </div>




{{-- <!-- عرض المعاينة -->
<div class="mt-2">
    <label>معاينة الخط:</label>
    <p id="fontPreview" style="font-size: 20px;">هذا نص تجريبي للمعاينة</p>
</div> --}}


                  <div class="mb-3">
            <label class="form-label">لون الخلفية</label>
            <input type="color" name="primary_color" value="{{ $appVersion->primary_color ?? '#ED7032' }}" class="form-control form-control-color">
        </div>

                   <div class="mb-3">
            <label class="form-label">لون النصوص</label>
            <input type="color" name="font_color_normal" value="{{ $appVersion->font_color_normal ?? '#ED7032' }}" class="form-control form-control-color">
        </div>

        <hr class="my-4">

        {{-- Payment Gateway Environment Settings Section --}}
        <div class="card shadow-sm mb-4" style="background: #ffffff; border: 1px solid #cbd5e1; border-radius: 16px; overflow: hidden;">
            <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-3 border-bottom" style="background: #f8fafc;">
                <div class="d-flex align-items-center gap-2">
                    <i class="bx bx-credit-card text-primary" style="font-size: 24px;"></i>
                    <h5 class="mb-0 fw-bold" style="color: #0f172a; font-size: 17px;">إدارة والتحكم في بيئة الدفع الإلكتروني (Ottu Gateway)</h5>
                </div>
                <div>
                    @if(($appVersion->payment_mode ?? 'sandbox') == 'live')
                        <span class="badge bg-success px-3 py-2 fw-bold" style="font-size: 13px;">
                            <i class="bx bx-check-circle me-1"></i> وضع الدفع الحقيقي الحي (Live)
                        </span>
                    @else
                        <span class="badge bg-warning text-dark px-3 py-2 fw-bold" style="font-size: 13px;">
                            <i class="bx bx-test-tube me-1"></i> وضع الدفع التجريبي (Sandbox)
                        </span>
                    @endif
                </div>
            </div>
            <div class="card-body p-3 p-md-4" style="background: #ffffff;">
                <div class="row mb-3 align-items-center">
                    <div class="col-sm-3">
                        <h6 class="mb-0 fw-bold" style="color: #0f172a; font-size: 15px;">بيئة الدفع الحالية:</h6>
                    </div>
                    <div class="col-sm-9">
                        <div class="d-flex flex-column gap-2.5">
                            <div class="p-3 border rounded-3 d-flex align-items-start gap-3" style="background: {{ ($appVersion->payment_mode ?? 'sandbox') == 'sandbox' ? '#fffbeb' : '#ffffff' }}; border-color: {{ ($appVersion->payment_mode ?? 'sandbox') == 'sandbox' ? '#f59e0b' : '#e2e8f0' }} !important; cursor: pointer;">
                                <input class="form-check-input mt-1" type="radio" name="payment_mode" id="mode_sandbox" value="sandbox" {{ old('payment_mode', $appVersion->payment_mode ?? 'sandbox') == 'sandbox' ? 'checked' : '' }}>
                                <label class="form-check-label mb-0" for="mode_sandbox" style="cursor: pointer; flex: 1;">
                                    <div class="fw-bold" style="color: #92400e; font-size: 14.5px;">🟡 البيئة التجريبية (Sandbox / Test Mode)</div>
                                    <div style="color: #78350f; font-size: 13px; line-height: 1.5; margin-top: 4px;">تفعيل بيئة الاختبار لتجربة عمليات الدفع ببطاقات KNET التجريبية دون خصم أي مبالغ مالية حقيقية.</div>
                                </label>
                            </div>
                            <div class="p-3 border rounded-3 d-flex align-items-start gap-3" style="background: {{ ($appVersion->payment_mode ?? 'sandbox') == 'live' ? '#f0fdf4' : '#ffffff' }}; border-color: {{ ($appVersion->payment_mode ?? 'sandbox') == 'live' ? '#10b981' : '#e2e8f0' }} !important; cursor: pointer;">
                                <input class="form-check-input mt-1" type="radio" name="payment_mode" id="mode_live" value="live" {{ old('payment_mode', $appVersion->payment_mode ?? 'sandbox') == 'live' ? 'checked' : '' }}>
                                <label class="form-check-label mb-0" for="mode_live" style="cursor: pointer; flex: 1;">
                                    <div class="fw-bold" style="color: #065f46; font-size: 14.5px;">🟢 البيئة الحقيقية الحية (Live Production Mode)</div>
                                    <div style="color: #047857; font-size: 13px; line-height: 1.5; margin-top: 4px;">تفعيل بيئة الدفع الحقيقية لاستقبال مدفوعات المستخدمين الفعلية داخل التطبيق.</div>
                                </label>
                            </div>
                        </div>
                        @error('payment_mode')
                            <div class="text-danger mt-1 fw-bold">{{ $message }}</div>
                        @enderror
                    </div>
                </div>

                {{-- Interactive KNET Test Card & Guide Section (Ultra High-Contrast Premium Design) --}}
                <div class="card shadow-sm mt-4 mb-4" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 16px; overflow: hidden;">
                    
                    {{-- Guide Header --}}
                    <div class="d-flex justify-content-between align-items-center flex-wrap gap-2 p-3 border-bottom" style="background: #f8fafc;">
                        <div class="d-flex align-items-center gap-2">
                            <span class="badge bg-primary text-white font-weight-bold px-2.5 py-1.5" style="font-size: 12px; letter-spacing: 0.5px;">KNET SANDBOX</span>
                            <h6 class="mb-0 fw-bold" style="font-size: 16px; color: #0f172a;">
                                <i class="bx bx-credit-card-front text-primary align-middle me-1"></i> دليل بيانات بطاقة كي نت (KNET) التجريبية للاختبار
                            </h6>
                        </div>
                        <span class="badge border px-3 py-1.5 fw-bold" style="background: #e0f2fe; color: #0369a1; border-color: #bae6fd !important; font-size: 12px;">
                            <i class="bx bx-info-circle me-1 align-middle"></i> مخصص للاختبار في البيئة التجريبية (Sandbox)
                        </span>
                    </div>

                    <div class="card-body p-3 p-md-4" style="background: #ffffff;">
                        <div class="row align-items-center g-4">
                            
                            {{-- Virtual Visual KNET Card --}}
                            <div class="col-lg-5 col-md-6">
                                <div class="p-4 position-relative shadow" style="background: linear-gradient(135deg, #0f172a 0%, #1e3a8a 50%, #0284c7 100%); border-radius: 16px; min-height: 215px; color: #ffffff; border: 1px solid rgba(255,255,255,0.2); box-shadow: 0 10px 25px -5px rgba(15, 23, 42, 0.4) !important;">
                                    <!-- Card Top Header -->
                                    <div class="d-flex justify-content-between align-items-center mb-3">
                                        <div class="d-flex align-items-center gap-2">
                                            <!-- EMV Chip -->
                                            <div style="width: 42px; height: 32px; background: linear-gradient(135deg, #fde68a 0%, #d97706 100%); border-radius: 6px; border: 1px solid #fef3c7; display: flex; align-items: center; justify-content: center; box-shadow: 0 2px 4px rgba(0,0,0,0.3);">
                                                <div style="width: 26px; height: 18px; border: 1px solid rgba(0,0,0,0.3); border-radius: 3px;"></div>
                                            </div>
                                            <i class="bx bx-wifi text-white" style="font-size: 24px; transform: rotate(90deg); opacity: 0.95;"></i>
                                        </div>
                                        <div class="badge bg-white text-primary fw-bold px-2.5 py-1.5 shadow-sm" style="font-size: 12px; letter-spacing: 0.5px;">
                                            💳 KNET / كي نت
                                        </div>
                                    </div>

                                    <!-- Card Number -->
                                    <div class="my-3 text-center">
                                        <div class="d-flex justify-content-center align-items-center gap-2">
                                            <span class="font-monospace text-white fw-bold" style="font-size: 19px; letter-spacing: 2px; text-shadow: 0 2px 5px rgba(0,0,0,0.6);" id="knetCardNumberText">8888 8800 0000 0001</span>
                                            <button type="button" class="btn btn-sm btn-light py-1 px-2.5 text-primary fw-bold shadow-sm" onclick="copyToClipboard('8888880000000001', this)" title="نسخ رقم البطاقة" style="border-radius: 8px; font-size: 12px;">
                                                <i class="bx bx-copy"></i> نسخ
                                            </button>
                                        </div>
                                    </div>

                                    <!-- Card Footer: Expiry & PIN -->
                                    <div class="d-flex justify-content-between align-items-end pt-2" style="font-size: 12px;">
                                        <div>
                                            <div class="text-uppercase" style="font-size: 10px; font-weight: 700; color: #94a3b8;">CARD HOLDER</div>
                                            <div class="fw-bold text-white" style="font-size: 13px; letter-spacing: 0.5px;">TEST USER</div>
                                        </div>
                                        <div class="text-center">
                                            <div class="text-uppercase" style="font-size: 10px; font-weight: 700; color: #94a3b8;">EXPIRES</div>
                                            <div class="font-monospace fw-bold text-white" style="font-size: 14px;">09/2030</div>
                                        </div>
                                        <div class="text-end">
                                            <div class="text-uppercase" style="font-size: 10px; font-weight: 700; color: #94a3b8;">PIN / سري</div>
                                            <div class="badge bg-warning text-dark font-monospace fw-bold px-2 py-1" style="font-size: 13px;">1234</div>
                                        </div>
                                    </div>
                                </div>
                            </div>

                            {{-- Detailed Instructions Table (Clean, High-Contrast Light Style) --}}
                            <div class="col-lg-7 col-md-6">
                                <div class="table-responsive rounded-3 border" style="border-color: #cbd5e1 !important;">
                                    <table class="table table-sm mb-0 align-middle" style="font-size: 13px; background-color: #ffffff;">
                                        <thead>
                                            <tr style="background-color: #f1f5f9; color: #0f172a; border-bottom: 2px solid #cbd5e1;">
                                                <th class="py-2.5 px-3 fw-bold" style="width: 32%; color: #0f172a; font-size: 13px;">الحقل المطلوب</th>
                                                <th class="py-2.5 px-3 fw-bold" style="width: 38%; color: #0f172a; font-size: 13px;">القيمة المدخلة للاختبار</th>
                                                <th class="py-2.5 px-3 fw-bold" style="width: 30%; color: #0f172a; font-size: 13px;">ملاحظة وإيضاح</th>
                                            </tr>
                                        </thead>
                                        <tbody style="color: #1e293b;">
                                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                                <td class="px-3 fw-bold" style="color: #0f172a;">رقم البطاقة (Card Number)</td>
                                                <td class="px-3">
                                                    <code class="fw-bold font-monospace px-2 py-1 rounded" style="background: #e0f2fe; color: #0369a1; font-size: 13px;">8888 8800 0000 0001</code>
                                                    <button type="button" class="btn btn-outline-primary btn-sm py-0 px-2 ms-1" onclick="copyToClipboard('8888880000000001', this)" style="font-size: 11px;">
                                                        <i class="bx bx-copy"></i> نسخ
                                                    </button>
                                                </td>
                                                <td class="px-3" style="color: #475569; font-size: 12px;">بطاقة كي نت تجريبية معتمدة</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                                <td class="px-3 fw-bold" style="color: #0f172a;">تاريخ الانتهاء (Expiry Date)</td>
                                                <td class="px-3">
                                                    <span class="badge fw-bold font-monospace px-2.5 py-1" style="background: #1e293b; color: #ffffff; font-size: 12px;">09/2030</span>
                                                </td>
                                                <td class="px-3" style="color: #475569; font-size: 12px;">أي شهر / سنة مستقبلية</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                                <td class="px-3 fw-bold" style="color: #0f172a;">الرقم السري (PIN / CVV)</td>
                                                <td class="px-3">
                                                    <span class="badge bg-warning text-dark fw-bold font-monospace px-2.5 py-1" style="font-size: 12px;">1234</span>
                                                </td>
                                                <td class="px-3" style="color: #475569; font-size: 12px;">يقبل أي 4 أرقام</td>
                                            </tr>
                                            <tr style="border-bottom: 1px solid #f1f5f9;">
                                                <td class="px-3 fw-bold" style="color: #0f172a;">نوع البطاقة (Brand)</td>
                                                <td class="px-3">
                                                    <span class="badge bg-primary text-white fw-bold px-2.5 py-1" style="font-size: 12px;">KNET</span>
                                                </td>
                                                <td class="px-3" style="color: #475569; font-size: 12px;">بوابة كي نت الكويتية</td>
                                            </tr>
                                            <tr>
                                                <td class="px-3 fw-bold" style="color: #0f172a;">النتيجة المتوقعة (Status)</td>
                                                <td class="px-3">
                                                    <span class="badge fw-bold px-2.5 py-1" style="background: #059669; color: #ffffff; font-size: 12px;">
                                                        <i class="bx bx-check-circle me-1"></i> Success (دفع ناجح)
                                                    </span>
                                                </td>
                                                <td class="px-3 fw-bold" style="color: #059669; font-size: 12px;">إتمام العملية وتوليد الفاتورة</td>
                                            </tr>
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                        </div>

                        {{-- Testing Cases Note Box (ملاحظة توضيحية لحالات الاختبار) --}}
                        <div class="mt-3 p-3 rounded-3 border" style="background: #f0fdf4; border-color: #86efac !important; color: #14532d;">
                            <div class="d-flex align-items-start gap-2.5">
                                <i class="bx bx-bulb fs-5 mt-0.5" style="color: #16a34a;"></i>
                                <div style="font-size: 13.5px; line-height: 1.8;">
                                    <strong style="color: #15803d;">💡 ملاحظة لاختبار حالات الدفع المختلفة:</strong>
                                    <ul class="mb-0 ps-3 mt-1" style="list-style-type: disc;">
                                        <li>
                                            <strong>حالة الدفع الناجح (Captured / Success):</strong> أدخل أي تاريخ انتهاء مستقبلي مثل <code class="fw-bold px-1.5 py-0.5 rounded bg-white border" style="color: #059669;">09/2030</code> ⬅️ يتم خصم المبلغ الافتراضي بنجاح، إضافة العملات للمستخدم، وإرسال الفاتورة عبر البريد الإلكتروني.
                                        </li>
                                        <li>
                                            <strong>حالة الدفع غير المكتمل (Not Captured / Failed):</strong> أدخل تاريخ انتهاء <code class="fw-bold px-1.5 py-0.5 rounded bg-white border" style="color: #dc2626;">08/2026</code> ⬅️ لاختبار رفض العملية ومعالجة فشل الدفع في التطبيق.
                                        </li>
                                    </ul>
                                </div>
                            </div>
                        </div>

                        {{-- Critical Sandbox Warning Banner (تنبيه هام جداً لإتمام الدفع التجريبي بنجاح) --}}
                        <div class="mt-3 p-3.5 rounded-3 border-2" style="background: #fff8f8; border: 2px solid #ef4444; color: #1e293b;">
                            <div class="d-flex align-items-start gap-3">
                                <div class="rounded-circle p-2 d-flex align-items-center justify-content-center flex-shrink-0 mt-1" style="background: #dc2626; color: #ffffff; width: 40px; height: 40px;">
                                    <i class="bx bx-error fs-4"></i>
                                </div>
                                <div style="flex: 1;">
                                    <div class="d-flex align-items-center gap-2 mb-1.5">
                                        <h6 class="mb-0 fw-bold" style="color: #b91c1c; font-size: 15px;">
                                            ⚠️ تنبيه هام جداً لإتمام الدفع التجريبي بنجاح:
                                        </h6>
                                    </div>
                                    
                                    <p class="mb-2" style="font-size: 13.5px; line-height: 1.8; color: #334155;">
                                        في شاشة دفع KNET التجريبية (صفحة البنك)، <strong style="color: #b91c1c; text-decoration: underline;">يجب عدم تحديد أو تفعيل خيار التسجيل في KFast</strong> وتركه فارغاً كما هو موضح:
                                    </p>

                                    <!-- Visual Interactive Indicator of the checkbox -->
                                    <div class="p-2.5 px-3 my-2 rounded-3 border d-flex align-items-center justify-content-between flex-wrap gap-2" style="background: #ffffff; border-color: #fca5a5 !important;">
                                        <div class="d-flex align-items-center gap-2">
                                            <span style="display: inline-block; width: 20px; height: 20px; border: 2px solid #dc2626; border-radius: 4px; background: #ffffff; text-align: center; line-height: 16px; font-weight: bold; color: #dc2626;"></span>
                                            <span class="font-monospace fw-bold" style="color: #991b1b; font-size: 13.5px;">
                                                I have read & agree to the Terms to register for KFast
                                            </span>
                                        </div>
                                        <span class="badge px-2.5 py-1.5 fw-bold" style="background: #dc2626; color: #ffffff; font-size: 12px;">
                                            ❌ اتركه فارغاً (غير محدد)
                                        </span>
                                    </div>

                                    <div class="mt-2" style="font-size: 13.5px; line-height: 1.8; color: #334155;">
                                        👉 ثم الضغط مباشرة على زر <span class="badge bg-primary text-white px-2.5 py-1 font-monospace" style="font-size: 13px;">Submit</span> لإتمام عملية الدفع التجريبية بنجاح.
                                    </div>
                                </div>
                            </div>
                        </div>

                    </div>
                </div>

                {{-- Advanced Gateway Configuration --}}
                <div class="accordion mt-3" id="accordionOttuSettings">
                    <div class="accordion-item border rounded">
                        <h2 class="accordion-header" id="headingOttu">
                            <button class="accordion-button collapsed bg-light py-2 text-secondary" type="button" data-bs-toggle="collapse" data-bs-target="#collapseOttu" aria-expanded="false" aria-controls="collapseOttu" style="font-size: 13px;">
                                <i class="bx bx-cog me-2"></i> إعدادات متقدمة لمفاتيح وروابط بوابة الدفع (اختياري)
                            </button>
                        </h2>
                        <div id="collapseOttu" class="accordion-collapse collapse" aria-labelledby="headingOttu" data-bs-parent="#accordionOttuSettings">
                            <div class="accordion-body p-3 bg-white">
                                <h6 class="text-success font-weight-bold mb-3"><i class="bx bx-check-shield"></i> بيانات البيئة الحقيقية (Live)</h6>
                                <div class="row mb-2">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label small text-muted">Live API Key</label>
                                        <input type="text" name="ottu_live_api_key" class="form-control form-control-sm" value="{{ old('ottu_live_api_key', $appVersion->ottu_live_api_key ?? 'KSK2Iuqw.mowuSwOTIq6ZDT48FvQvW0GaaQPwFjIy') }}">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label small text-muted">Live API URL</label>
                                        <input type="text" name="ottu_live_api_url" class="form-control form-control-sm" value="{{ old('ottu_live_api_url', $appVersion->ottu_live_api_url ?? 'https://pay.pikw.com/b/checkout/v1/pymt-txn/') }}">
                                    </div>
                                </div>

                                <hr class="my-3">

                                <h6 class="text-warning font-weight-bold mb-3"><i class="bx bx-test-tube"></i> بيانات البيئة التجريبية (Sandbox)</h6>
                                <div class="row mb-2">
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label small text-muted">Sandbox API Key</label>
                                        <input type="text" name="ottu_sandbox_api_key" class="form-control form-control-sm" value="{{ old('ottu_sandbox_api_key', $appVersion->ottu_sandbox_api_key ?? 'GYj5Na8H.29g9hqNjm11nORQMa2WiZwIBQQ49MdAL') }}">
                                    </div>
                                    <div class="col-md-6 mb-2">
                                        <label class="form-label small text-muted">Sandbox API URL</label>
                                        <input type="text" name="ottu_sandbox_api_url" class="form-control form-control-sm" value="{{ old('ottu_sandbox_api_url', $appVersion->ottu_sandbox_api_url ?? 'https://sandbox.ottu.net/b/checkout/v1/pymt-txn/') }}">
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>

                <div class="row">
                    <div class="col-sm-3"></div>
                    <div class="col-sm-9 text-secondary">
                        <input type="submit" class="btn btn-primary px-4" value="تحديث">
                    </div>
                </div>



            </form>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const select = document.getElementById('fontSelect');
    const preview = document.getElementById('fontPreview');

    if (select && preview) {
        select.addEventListener('change', function() {
            const selectedOption = select.options[select.selectedIndex];
            const fontName = selectedOption.textContent.trim();
            preview.style.fontFamily = fontName;
        });
    }
});

function copyToClipboard(text, btnElement) {
    if (navigator.clipboard) {
        navigator.clipboard.writeText(text).then(function() {
            showCopySuccess(btnElement);
        }).catch(function(err) {
            fallbackCopy(text, btnElement);
        });
    } else {
        fallbackCopy(text, btnElement);
    }
}

function fallbackCopy(text, btnElement) {
    var textArea = document.createElement("textarea");
    textArea.value = text;
    document.body.appendChild(textArea);
    textArea.select();
    try {
        document.execCommand('copy');
        showCopySuccess(btnElement);
    } catch (err) {
        console.error('Fallback copy failed', err);
    }
    document.body.removeChild(textArea);
}

function showCopySuccess(btnElement) {
    if (!btnElement) return;
    var originalHtml = btnElement.innerHTML;
    btnElement.innerHTML = '<i class="bx bx-check text-success"></i> تم النسخ!';
    btnElement.classList.remove('btn-light', 'btn-outline-info');
    btnElement.classList.add('btn-success', 'text-white');
    setTimeout(function() {
        btnElement.innerHTML = originalHtml;
        btnElement.classList.remove('btn-success', 'text-white');
        btnElement.classList.add('btn-light');
    }, 1800);
}
</script>

@endsection
