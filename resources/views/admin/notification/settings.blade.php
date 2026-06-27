@extends('admin.master_admin')
@section('admin')

<div class="card">
    <div class="card-header">
        <h5>إعدادات الإشعارات التي تريد استقبالها</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('admin.notification.settings.update') }}" method="POST">
            @csrf
            
            <div class="mb-4">
                <div class="form-check form-switch fs-6 mb-3">
                    <input class="form-check-input" type="checkbox" id="notify_new_user" name="notify_new_user" value="1" {{ $user->notify_new_user ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold ms-2" for="notify_new_user">
                        هل تود تلقي إشعارات عند تسجيل عضو جديد؟
                    </label>
                    <div class="text-muted small ms-2">عند تفعيل هذا الخيار، ستصلك إشعارات فورية في أعلى لوحة التحكم بمجرد قيام أي مستخدم جديد بالتسجيل في التطبيق.</div>
                </div>
                
                <hr>
                
                <div class="form-check form-switch fs-6 mb-3 mt-3">
                    <input class="form-check-input" type="checkbox" id="notify_problem_report" name="notify_problem_report" value="1" {{ $user->notify_problem_report ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold ms-2" for="notify_problem_report">
                        هل تود استقبال إشعارات في حال إرسال بلاغ جديد من قبل مستخدم ما؟
                    </label>
                    <div class="text-muted small ms-2">عند تفعيل هذا الخيار، ستصلك تنبيهات فورية عند قيام أي مستخدم بالإبلاغ عن مشكلة في سؤال أو إجابة داخل التطبيق.</div>
                </div>
                
                <hr>
                
                <div class="form-check form-switch fs-6 mb-3 mt-3">
                    <input class="form-check-input" type="checkbox" id="notify_game_played" name="notify_game_played" value="1" {{ $user->notify_game_played ? 'checked' : '' }}>
                    <label class="form-check-label fw-bold ms-2" for="notify_game_played">
                        هل تود استقبال إشعار جديد في حال إنشاء أو لعب لعبة جديدة من قبل المستخدم؟
                    </label>
                    <div class="text-muted small ms-2">عند تفعيل هذا الخيار، ستصلك إشعارات فورية بمجرد قيام أي مستخدم بإنشاء أو لعب لعبة جديدة داخل التطبيق.</div>
                </div>
            </div>
            
            <button type="submit" class="btn btn-primary px-4">حفظ الإعدادات</button>
        </form>
    </div>
</div>

@endsection
