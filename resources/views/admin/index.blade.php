{{-- ['Task', 'Hours per Day'],
['المستخدمين المسجلين',     {{$users->count()}}],
['الفئات',      {{$category->count()}}],
['الألعاب',  {{$games->count()}}],
['الأسئلة', {{$questions->count()}}], --}}
@extends('admin.master_admin')
@section('admin')
<script type="text/javascript">
    google.charts.load('current', {'packages':['corechart']});
    google.charts.setOnLoadCallback(drawChart);

    function drawChart() {
    var data = google.visualization.arrayToDataTable([
        ['Task', 'Hours per Day'],
                [' أنواع اللعب', {{$gameType->count()}}],
                [' الفئات الرئيسية', {{$mainCategory->count()}}],

        ['الفئات الفرعية', {{$category->count()}}],

                ['عناصر اللعبة', {{$titlePosition->count()}}],

        ['المستخدمين المسجلين', {{$users->count()}}],
        ['الأسئلة', {{$questions->count()}}],
        ['الألعاب', {{$games->count()}}],
                ['الرعاة', {{$sponsor->count()}}],

    ]);

    var options = {
        title: '',
        //#endregion


        colors: ['#1C3E14','#D22FBF','#67B586','#4B0A05', '#5636D3', '#3357FF', '#15232A','#894818'] // Add your desired colors here

    };

    var chart = new google.visualization.PieChart(document.getElementById('piechart'));

    chart.draw(data, options);
}
  </script>

  <style>

    .bg-gradient-magenta {
    background: linear-gradient(135deg, #FF00CC, #333399);
    color: white;
}

.bg-gradient-cyan {
 background: linear-gradient(135deg, #400000, #8B0000);
    color: white;
}

.bg-gradient-darkteal {
    background: linear-gradient(135deg, #0B3D0B, #06470C);
    color: white;
}

.bg-gradient-darkorange {
    background: linear-gradient(135deg, #8B4000, #FF7300);
    color: white;
}

.bg-gradient-game-new {
    background: linear-gradient(135deg, #0a0a0a, #866c15);
    color: white;
}

/* ========================================================= */
/* 🏆 Top 10 Online Leaderboard Styles & Micro-Animations     */
/* ========================================================= */
.leaderboard-wrapper {
    background: linear-gradient(135deg, #0f172a 0%, #1e1b4b 50%, #0f172a 100%);
    border: 1px solid rgba(255, 255, 255, 0.1);
    border-radius: 20px;
    position: relative;
    overflow: hidden;
    box-shadow: 0 10px 30px rgba(0, 0, 0, 0.35);
}

.leaderboard-wrapper::before {
    content: '';
    position: absolute;
    top: -50%;
    left: -50%;
    width: 200%;
    height: 200%;
    background: radial-gradient(circle at 30% 20%, rgba(250, 204, 21, 0.08) 0%, transparent 40%),
                radial-gradient(circle at 80% 80%, rgba(99, 102, 241, 0.08) 0%, transparent 40%);
    pointer-events: none;
}

.leaderboard-track {
    display: flex;
    gap: 16px;
    overflow-x: auto;
    scroll-behavior: smooth;
    padding: 12px 4px 16px 4px;
    -webkit-overflow-scrolling: touch;
    scrollbar-width: thin;
    scrollbar-color: rgba(250, 204, 21, 0.4) rgba(255, 255, 255, 0.05);
}

.leaderboard-track::-webkit-scrollbar {
    height: 6px;
}
.leaderboard-track::-webkit-scrollbar-track {
    background: rgba(255, 255, 255, 0.05);
    border-radius: 10px;
}
.leaderboard-track::-webkit-scrollbar-thumb {
    background: linear-gradient(90deg, #f59e0b, #6366f1);
    border-radius: 10px;
}

.player-card {
    flex: 0 0 210px;
    max-width: 210px;
    background: rgba(30, 41, 59, 0.75);
    backdrop-filter: blur(10px);
    border-radius: 16px;
    padding: 18px 14px;
    text-align: center;
    position: relative;
    border: 1px solid rgba(255, 255, 255, 0.08);
    transition: all 0.35s cubic-bezier(0.4, 0, 0.2, 1);
    cursor: pointer;
}

.player-card:hover {
    transform: translateY(-8px) scale(1.02);
    box-shadow: 0 15px 30px rgba(0, 0, 0, 0.45);
}

/* Rank 1 - Golden Champion */
.player-card.rank-1 {
    background: linear-gradient(180deg, rgba(245, 158, 11, 0.18) 0%, rgba(30, 41, 59, 0.85) 100%);
    border: 1.5px solid #fbbf24;
    box-shadow: 0 0 20px rgba(251, 191, 36, 0.25);
    animation: goldPulse 3s infinite ease-in-out;
}
.player-card.rank-1 .avatar-ring {
    border: 3px solid #fbbf24;
    box-shadow: 0 0 15px rgba(251, 191, 36, 0.6);
}

/* Rank 2 - Silver */
.player-card.rank-2 {
    background: linear-gradient(180deg, rgba(203, 213, 225, 0.15) 0%, rgba(30, 41, 59, 0.85) 100%);
    border: 1.5px solid #cbd5e1;
    box-shadow: 0 0 15px rgba(203, 213, 225, 0.2);
}
.player-card.rank-2 .avatar-ring {
    border: 3px solid #cbd5e1;
    box-shadow: 0 0 12px rgba(203, 213, 225, 0.5);
}

/* Rank 3 - Bronze */
.player-card.rank-3 {
    background: linear-gradient(180deg, rgba(217, 119, 6, 0.15) 0%, rgba(30, 41, 59, 0.85) 100%);
    border: 1.5px solid #f97316;
    box-shadow: 0 0 15px rgba(249, 115, 22, 0.2);
}
.player-card.rank-3 .avatar-ring {
    border: 3px solid #f97316;
    box-shadow: 0 0 12px rgba(249, 115, 22, 0.5);
}

.rank-crown {
    position: absolute;
    top: -12px;
    left: 50%;
    transform: translateX(-50%);
    font-size: 20px;
    animation: floatCrown 2.5s infinite ease-in-out;
    z-index: 5;
}

.rank-pill {
    position: absolute;
    top: 10px;
    right: 10px;
    font-size: 11px;
    font-weight: 700;
    padding: 2px 8px;
    border-radius: 12px;
    background: rgba(0, 0, 0, 0.4);
    color: #fff;
    border: 1px solid rgba(255, 255, 255, 0.15);
}

.avatar-wrapper {
    position: relative;
    width: 68px;
    height: 68px;
    margin: 8px auto 10px auto;
}

.avatar-ring {
    width: 68px;
    height: 68px;
    border-radius: 50%;
    object-fit: cover;
    border: 2px solid rgba(255, 255, 255, 0.2);
    background: #1e293b;
    transition: all 0.3s ease;
}

.player-name {
    color: #ffffff;
    font-size: 14px;
    font-weight: 700;
    margin-bottom: 2px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.player-sub {
    color: #94a3b8;
    font-size: 11px;
    margin-bottom: 8px;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
}

.points-pill {
    background: linear-gradient(135deg, rgba(251, 191, 36, 0.2), rgba(245, 158, 11, 0.1));
    border: 1px solid rgba(251, 191, 36, 0.4);
    color: #fbbf24;
    font-weight: 700;
    font-size: 12px;
    padding: 5px 10px;
    border-radius: 20px;
    display: inline-flex;
    align-items: center;
    gap: 5px;
    box-shadow: 0 2px 8px rgba(251, 191, 36, 0.15);
    transition: all 0.3s ease;
}

.player-card:hover .points-pill {
    background: linear-gradient(135deg, #f59e0b, #d97706);
    color: #ffffff;
    box-shadow: 0 4px 12px rgba(245, 158, 11, 0.4);
}

.scroll-btn {
    width: 36px;
    height: 36px;
    border-radius: 50%;
    background: rgba(255, 255, 255, 0.1);
    border: 1px solid rgba(255, 255, 255, 0.15);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    transition: all 0.2s ease;
    cursor: pointer;
}
.scroll-btn:hover {
    background: #f59e0b;
    color: #000;
    transform: scale(1.1);
}

@keyframes floatCrown {
    0%, 100% { transform: translateX(-50%) translateY(0); }
    50% { transform: translateX(-50%) translateY(-5px); }
}

.notify-hint-badge {
    position: absolute;
    bottom: -10px;
    left: 50%;
    transform: translateX(-50%) translateY(4px);
    background: linear-gradient(135deg, #6366f1, #4f46e5);
    color: #fff;
    font-size: 10px;
    font-weight: bold;
    padding: 3px 10px;
    border-radius: 20px;
    white-space: nowrap;
    opacity: 0;
    transition: all 0.3s ease;
    box-shadow: 0 4px 12px rgba(99, 102, 241, 0.5);
    z-index: 6;
    pointer-events: none;
}

.player-card:hover .notify-hint-badge {
    opacity: 1;
    transform: translateX(-50%) translateY(0);
}

.template-chip {
    background: #f1f5f9;
    border: 1px solid #e2e8f0;
    border-radius: 20px;
    padding: 5px 12px;
    font-size: 12px;
    font-weight: 600;
    color: #334155;
    cursor: pointer;
    transition: all 0.2s ease;
    display: inline-flex;
    align-items: center;
    gap: 4px;
}

.template-chip:hover {
    background: #6366f1;
    color: #fff;
    border-color: #6366f1;
    transform: translateY(-2px);
}
  </style>

  <!-- ========================================================= -->
  <!-- 🏆 Top 10 Field Game Points Champions Leaderboard Banner  -->
  <!-- ========================================================= -->
  <div class="card leaderboard-wrapper p-3 mb-4 shadow-lg border-0">
      <div class="d-flex align-items-center justify-content-between mb-2 px-2 flex-wrap gap-2">
          <div class="d-flex align-items-center gap-2">
              <div class="p-2 rounded-circle" style="background: rgba(245, 158, 11, 0.2); border: 1px solid rgba(245, 158, 11, 0.4);">
                  <i class="fa-solid fa-trophy text-warning fs-4"></i>
              </div>
              <div>
                  <h5 class="mb-0 text-white fw-bold d-flex align-items-center gap-2">
                      أفضل 10 متصدرين في نقاط لعبة الميدان
                      <span class="badge bg-warning text-dark font-monospace" style="font-size: 11px;">TOP 10 LIVE ⚡</span>
                  </h5>
                  <small class="text-white-50">اضغط على أي لاعب لإرسال إشعار فوري له 🔔</small>
              </div>
          </div>

          <div class="d-flex align-items-center gap-2">
              <button type="button" class="scroll-btn" id="scrollRightBtn" title="السابق">
                  <i class="fa-solid fa-chevron-right"></i>
              </button>
              <button type="button" class="scroll-btn" id="scrollLeftBtn" title="التالي">
                  <i class="fa-solid fa-chevron-left"></i>
              </button>
          </div>
      </div>

      <div class="leaderboard-track" id="leaderboardTrack">
          @forelse($topOnlineUsers as $index => $topUser)
              @php
                  $rank = $index + 1;
                  $cardClass = $rank == 1 ? 'rank-1' : ($rank == 2 ? 'rank-2' : ($rank == 3 ? 'rank-3' : ''));
                  $displayName = trim(($topUser->fname ?? '') . ' ' . ($topUser->lname ?? ''));
                  if (empty($displayName)) {
                      $displayName = $topUser->user_name ?: ($topUser->name ?: ($topUser->email ?: 'مستخدم'));
                  }
                  $subText = $topUser->phone ?: ($topUser->email ?: ($topUser->user_name ? '@' . $topUser->user_name : 'لاعب'));

                  $userPhoto = url('upload/no_image.jpg');
                  if (!empty($topUser->photo)) {
                      if (filter_var($topUser->photo, FILTER_VALIDATE_URL)) {
                          $userPhoto = $topUser->photo;
                      } elseif (file_exists(public_path('upload/user_images/' . $topUser->photo))) {
                          $userPhoto = asset('upload/user_images/' . $topUser->photo);
                      } elseif (file_exists(public_path($topUser->photo))) {
                          $userPhoto = asset($topUser->photo);
                      }
                  }
                  $hasFcmToken = !empty($topUser->fcm_token) || !empty($topUser->firebase_token);
              @endphp

              <div class="player-card {{ $cardClass }}"
                   data-user-id="{{ $topUser->id }}"
                   data-user-name="{{ $displayName }}"
                   data-user-photo="{{ $userPhoto }}"
                   data-user-sub="{{ $subText }}"
                   data-user-points="{{ number_format($topUser->online_points ?? 0) }}"
                   data-user-rank="#{{ $rank }}"
                   data-has-token="{{ $hasFcmToken ? '1' : '0' }}"
                   title="اضغط لإرسال إشعار إلى {{ $displayName }}">
                  
                  @if($rank == 1)
                      <div class="rank-crown">👑</div>
                      <span class="rank-pill text-warning border-warning">#1 الأول</span>
                  @elseif($rank == 2)
                      <div class="rank-crown">🥈</div>
                      <span class="rank-pill text-light border-light">#2 الثاني</span>
                  @elseif($rank == 3)
                      <div class="rank-crown">🥉</div>
                      <span class="rank-pill text-warning border-warning">#3 الثالث</span>
                  @else
                      <span class="rank-pill">#{{ $rank }}</span>
                  @endif

                  <div class="avatar-wrapper">
                      <img src="{{ $userPhoto }}" alt="{{ $displayName }}" class="avatar-ring" onerror="this.src='{{ url('upload/no_image.jpg') }}'">
                  </div>

                  <div class="player-name" title="{{ $displayName }}">{{ $displayName }}</div>
                  <div class="player-sub" title="{{ $subText }}">{{ $subText }}</div>

                  <div class="points-pill">
                      <i class="fa-solid fa-fire text-warning"></i>
                      <span>{{ number_format($topUser->online_points ?? 0) }}</span>
                      <small style="font-size: 10px;">نقطة</small>
                  </div>

                  <div class="notify-hint-badge">
                      <i class="fa-solid fa-paper-plane me-1"></i> إرسال إشعار
                  </div>
              </div>
          @empty
              <div class="text-center py-4 w-100 text-white-50">
                  <i class="fa-solid fa-gamepad fs-2 mb-2 d-block"></i>
                  لا يوجد مستخدمين بنقاط لعبة الميدان مسجلة حتى الآن.
              </div>
          @endforelse
      </div>
  </div>

  <!-- ========================================================= -->
  <!-- 🔔 Modal إرسال إشعار للمستخدم                               -->
  <!-- ========================================================= -->
  <div class="modal fade" id="sendNotificationModal" tabindex="-1" aria-labelledby="sendNotificationModalLabel" aria-hidden="true">
      <div class="modal-dialog modal-dialog-centered">
          <div class="modal-content border-0 shadow-lg" style="border-radius: 20px; overflow: hidden;">
              <!-- Header -->
              <div class="modal-header text-white p-4" style="background: linear-gradient(135deg, #1e1b4b 0%, #312e81 100%);">
                  <div class="d-flex align-items-center gap-3">
                      <div class="position-relative">
                          <img id="modalUserPhoto" src="{{ url('upload/no_image.jpg') }}" class="rounded-circle border border-2 border-warning shadow-sm" style="width: 60px; height: 60px; object-fit: cover;">
                          <span id="modalUserRank" class="position-absolute bottom-0 end-0 badge bg-warning text-dark font-monospace" style="font-size: 10px;">#1</span>
                      </div>
                      <div>
                          <h5 class="modal-title fw-bold mb-1" id="modalUserName">اسم اللاعب</h5>
                          <div class="d-flex align-items-center gap-2 flex-wrap">
                              <span class="badge bg-white bg-opacity-25 text-white" id="modalUserSub">لاعب</span>
                              <span class="badge bg-warning text-dark" id="modalUserPoints"><i class="fa-solid fa-fire me-1"></i> 0 نقطة</span>
                          </div>
                      </div>
                  </div>
                  <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
              </div>

              <!-- Form Body -->
              <form id="dashboardNotificationForm" method="POST" action="{{ route('store.notification') }}">
                  @csrf
                  <input type="hidden" name="user_id" id="modalUserId" value="">

                  <div class="modal-body p-4">
                      <!-- Token Status Notice -->
                      <div id="tokenStatusAlert" class="alert p-2 px-3 mb-3 d-flex align-items-center gap-2" style="font-size: 12px; border-radius: 10px;">
                          <i class="fa-solid fa-circle-check fs-6 text-success" id="tokenStatusIcon"></i>
                          <span id="tokenStatusText">المستخدم جاهز لاستقبال الإشعارات</span>
                      </div>

                      <!-- Quick Templates -->
                      <div class="mb-3">
                          <label class="form-label text-muted small fw-bold mb-2">قوالب إشعارات سريعة:</label>
                          <div class="d-flex flex-wrap gap-2">
                              <span class="template-chip" data-title="🏆 تهنئة بالصدارة!" data-desc="ألف مبروك! أنت الآن ضمن أفضل اللاعبين المتصدرين في لعبة فيك تحدي. استمر في التألق!">
                                  🏆 تهنئة بالصدارة
                              </span>
                              <span class="template-chip" data-title="📢 رسالة من إدارة اللعبة" data-desc="نشكرك على تفاعلك الدائم في لعبة فيك تحدي نتمنى لك وقتاً ممتعاً!">
                                  📢 رسالة عامة
                              </span>
                          </div>
                      </div>

                      <!-- Title -->
                      <div class="mb-3">
                          <label for="notifTitleInput" class="form-label fw-bold text-dark">عنوان الإشعار <span class="text-danger">*</span></label>
                          <input type="text" class="form-control form-control-lg fs-6" id="notifTitleInput" name="title" placeholder="أدخل عنوان الإشعار..." required style="border-radius: 10px;">
                      </div>

                      <!-- Description -->
                      <div class="mb-2">
                          <label for="notifDescInput" class="form-label fw-bold text-dark">نص الإشعار <span class="text-danger">*</span></label>
                          <textarea class="form-control fs-6" id="notifDescInput" name="description" rows="3" placeholder="اكتب محتوى الإشعار بالتفصيل..." required style="border-radius: 10px;"></textarea>
                      </div>
                  </div>

                  <!-- Footer -->
                  <div class="modal-footer bg-light p-3 px-4 border-0">
                      <button type="button" class="btn btn-secondary px-3" data-bs-dismiss="modal" style="border-radius: 10px;">إلغاء</button>
                      <button type="submit" id="sendNotifBtn" class="btn btn-primary px-4 d-flex align-items-center gap-2" style="border-radius: 10px; background: linear-gradient(135deg, #4f46e5, #6366f1); border: none;">
                          <i class="fa-solid fa-paper-plane"></i>
                          <span>إرسال الإشعار الآن</span>
                      </button>
                  </div>
              </form>
          </div>
      </div>
  </div>

  <script>
      document.addEventListener('DOMContentLoaded', function() {
          // Horizontal scrolling buttons
          const track = document.getElementById('leaderboardTrack');
          const leftBtn = document.getElementById('scrollLeftBtn');
          const rightBtn = document.getElementById('scrollRightBtn');

          if (track && leftBtn && rightBtn) {
              leftBtn.addEventListener('click', function() {
                  track.scrollBy({ left: -240, behavior: 'smooth' });
              });
              rightBtn.addEventListener('click', function() {
                  track.scrollBy({ left: 240, behavior: 'smooth' });
              });
          }

          // Open Notification Modal on Player Card Click
          const playerNotificationModal = new bootstrap.Modal(document.getElementById('sendNotificationModal'));
          const playerCards = document.querySelectorAll('.player-card');

          playerCards.forEach(function(card) {
              card.addEventListener('click', function() {
                  const userId = this.getAttribute('data-user-id');
                  const userName = this.getAttribute('data-user-name');
                  const userPhoto = this.getAttribute('data-user-photo');
                  const userSub = this.getAttribute('data-user-sub');
                  const userPoints = this.getAttribute('data-user-points');
                  const userRank = this.getAttribute('data-user-rank');
                  const hasToken = this.getAttribute('data-has-token') === '1';

                  document.getElementById('modalUserId').value = userId;
                  document.getElementById('modalUserName').innerText = userName;
                  document.getElementById('modalUserPhoto').src = userPhoto;
                  document.getElementById('modalUserSub').innerText = userSub;
                  document.getElementById('modalUserPoints').innerHTML = `<i class="fa-solid fa-fire me-1"></i> ${userPoints} نقطة`;
                  document.getElementById('modalUserRank').innerText = userRank;

                  // Token alert
                  const alertBox = document.getElementById('tokenStatusAlert');
                  const alertIcon = document.getElementById('tokenStatusIcon');
                  const alertText = document.getElementById('tokenStatusText');

                  if (hasToken) {
                      alertBox.className = 'alert alert-success p-2 px-3 mb-3 d-flex align-items-center gap-2';
                      alertIcon.className = 'fa-solid fa-circle-check fs-6 text-success';
                      alertText.innerText = 'هذا المستخدم يملك تطبيقاً مفعلاً لاستقبال الإشعارات المباشرة (Push Notification).';
                  } else {
                      alertBox.className = 'alert alert-warning p-2 px-3 mb-3 d-flex align-items-center gap-2';
                      alertIcon.className = 'fa-solid fa-triangle-exclamation fs-6 text-warning';
                      alertText.innerText = 'تنبيه: المستخدم لم يسجل رمز التوكن بعد، سيتم حفظ الإشعار في سجله داخل التطبيق.';
                  }

                  // Default preset
                  document.getElementById('notifTitleInput').value = `🏆 مبروك يا ${userName}!`;
                  document.getElementById('notifDescInput').value = `أنت متصدر المركز ${userRank} في نقاط لعبة الميدان بإجمالي (${userPoints}) نقطة. واصل التحدي والانتصارات!`;

                  playerNotificationModal.show();
              });
          });

          // Quick Template Chips handler
          const chips = document.querySelectorAll('.template-chip');
          chips.forEach(function(chip) {
              chip.addEventListener('click', function() {
                  const title = this.getAttribute('data-title');
                  const desc = this.getAttribute('data-desc');
                  document.getElementById('notifTitleInput').value = title;
                  document.getElementById('notifDescInput').value = desc;
              });
          });

          // AJAX Submission for notification
          $('#dashboardNotificationForm').on('submit', function(e) {
              e.preventDefault();
              const form = $(this);
              const btn = $('#sendNotifBtn');
              const originalBtnHtml = btn.html();

              btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-1" role="status" aria-hidden="true"></span> جاري الإرسال...');

              $.ajax({
                  url: form.attr('action'),
                  method: 'POST',
                  data: form.serialize(),
                  headers: {
                      'X-Requested-With': 'XMLHttpRequest'
                  },
                  success: function(response) {
                      playerNotificationModal.hide();
                      btn.prop('disabled', false).html(originalBtnHtml);

                      if (typeof Swal !== 'undefined') {
                          Swal.fire({
                              icon: response.alert_type === 'warning' ? 'warning' : 'success',
                              title: response.alert_type === 'warning' ? 'تنبيه' : 'تم الإرسال بنجاح! 🚀',
                              text: response.message || 'تم إرسال الإشعار للمستخدم بنجاح.',
                              confirmButtonText: 'حسناً',
                              confirmButtonColor: '#4f46e5'
                          });
                      } else if (typeof toastr !== 'undefined') {
                          toastr.success(response.message || 'تم إرسال الإشعار بنجاح');
                      }
                  },
                  error: function(xhr) {
                      btn.prop('disabled', false).html(originalBtnHtml);
                      let errText = 'حدث خطأ أثناء محاولة إرسال الإشعار.';
                      if (xhr.responseJSON && xhr.responseJSON.message) {
                          errText = xhr.responseJSON.message;
                      }
                      if (typeof Swal !== 'undefined') {
                          Swal.fire({
                              icon: 'error',
                              title: 'خطأ',
                              text: errText,
                              confirmButtonText: 'حسناً'
                          });
                      } else {
                          alert(errText);
                      }
                  }
              });
          });
      });
  </script>

  <div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
    <div class="col">
        <a href="{{route('all.game.type')}}">
        <div class="card radius-10 bg-gradient-darkteal">
         <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-white">{{$gameType->count()}}</h5>
                <div class="ms-auto">
                    <i class='bx bx-user fs-3 text-white'></i>

                </div>
            </div>
            <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center text-white">
                <p class="mb-0">عدد انواع اللعب</p>

            </div>
        </div>
    </a>
      </div>
    </div>
    <div class="col">
        <a href="{{route('all.main.category')}}">

        <div class="card radius-10  bg-gradient-magenta">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-white"> {{$mainCategory->count()}}</h5>
                <div class="ms-auto">
                    <i class='bx bx-category fs-3 text-white'></i>
                </div>
            </div>
            <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center text-white">
                <p class="mb-0">عدد الفئات الرئيسية</p>
            </div>
        </div>
    </a>

      </div>
    </div>
   <div class="col">
        <a href="{{route('all.category')}}">

        <div class="card radius-10 bg-gradient-ohhappiness">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-white"> {{$category->count()}}</h5>
                <div class="ms-auto">
                    <i class='bx bx-category fs-3 text-white'></i>
                </div>
            </div>
            <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center text-white">
                <p class="mb-0">عدد الفئات الفرعية</p>
            </div>
        </div>
    </a>

    </div>
    </div>
    <div class="col">
        <a href="{{route('all.title.position')}}">

        <div class="card radius-10 bg-gradient-cyan bg-warning">
         <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-white">{{$titlePosition->count()}}</h5>
                <div class="ms-auto">
                    <i class='bx bx-joystick fs-3 text-white'></i>
                </div>
            </div>
            <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center text-white">
                <p class="mb-0">عدد عناصر اللعبة</p>
            </div>
        </div>
    </a>

     </div>
    </div>
</div><!--end row-->

<div class="row row-cols-1 row-cols-md-2 row-cols-xl-4">
    <div class="col">
        <a href="{{route('all.users')}}">
        <div class="card radius-10 bg-gradient-deepblue">
         <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-white">{{$users->count()}}</h5>
                <div class="ms-auto">
                    <i class='bx bx-user fs-3 text-white'></i>

                </div>
            </div>
            <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center text-white">
                <p class="mb-0">عدد المستخدمين</p>

            </div>
        </div>
    </a>
      </div>
    </div>


       <div class="col">
        <a href="{{route('sponsor.all')}}">

        <div class="card radius-10 bg-gradient-darkorange">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-white">{{$sponsor->count()}}</h5>
                <div class="ms-auto">
                    <i class='bx bx-help-circle fs-3 text-white'></i>

                </div>
            </div>
            <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center text-white">
                <p class="mb-0">عدد الرعاة</p>
            </div>
        </div>
    </a>





      </div>



    </div>
    <div class="col">
        <a href="{{route('all.question')}}">

        <div class="card radius-10 bg-gradient-ibiza">
        <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-white">{{$questions->count()}}</h5>
                <div class="ms-auto">
                    <i class='bx bx-help-circle fs-3 text-white'></i>

                </div>
            </div>
            <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center text-white">
                <p class="mb-0">عدد الأسئلة</p>
            </div>
        </div>
    </a>

    </div>
    </div>
    <div class="col">
        <a href="{{route('all.games')}}">

        <div class="card radius-10 bg-gradient-moonlit bg-warning">
         <div class="card-body">
            <div class="d-flex align-items-center">
                <h5 class="mb-0 text-white">{{$games->count()}}</h5>
                <div class="ms-auto">
                    <i class='bx bx-joystick fs-3 text-white'></i>
                </div>
            </div>
            <div class="progress my-2 bg-opacity-25 bg-white" style="height:4px;">
                <div class="progress-bar bg-white" role="progressbar" style="width: 100%" aria-valuenow="25" aria-valuemin="0" aria-valuemax="100"></div>
            </div>
            <div class="d-flex align-items-center text-white">
                <p class="mb-0">عدد الألعاب</p>
            </div>
        </div>
    </a>

     </div>
    </div>
</div><!--end row-->





   <div class="row row-cols-1 row-cols-lg-1">
    <div class="col">
        <div id="piechart" style="width: 100%; height: 500px;"></div>

     </div>


    </div><!--End Row-->



    <hr>
    <h4 class="mb-4">المستخدمين</h4>


    <div class="card">

        <div class="card-body">

            <div class="table-responsive">
                <table id="example" class="table table-striped table-bordered" style="width:100%">
                    <thead>
    <tr>
    <th>الرقم</th>
    <th>إسم الأول</th>
    <th>إسم العائلة</th>
    <th>البريد الإلكتروني</th>
    <th>تاريخ التسجيل</th>

    <th> الصورة</th>
    </tr>
    </thead>
    <tbody>
    @foreach($users as $key => $item)
    <tr>
    <td> {{ $key+1 }} </td>
    <td>{{ $item->fname }}</td>
    <td>{{ $item->lname }}</td>
    <td>{{ $item->email }}</td>
    <td>{{ $item->created_at ? $item->created_at->diffForHumans() : 'لم يتم التحديد' }}</td>


    <td> <img class="rounded-circle"  src="{{ (!empty($item->photo)) ? url('upload/user_images/'.$item->photo):url('upload/no_image.jpg') }}" style="width: 50px; height:50px; border: 2px solid #0aa2dd;" >  </td>


    </tr>
    @endforeach


    </tbody>
    <tfoot>
    <tr>
        <th>الرقم</th>
        <th>إسم الأول</th>
        <th>إسم العائلة</th>
        <th>البريد الإلكتروني</th>
        <th>تاريخ التسجيل</th>

        <th> الصورة</th>
    </tr>
    </tfoot>
    </table>
            </div>
        </div>
    </div>



@endsection
