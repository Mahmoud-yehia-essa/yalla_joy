@extends('admin.master_admin')
@section('admin')

<div class="card">
    <div class="card-header">
        <h5>إضافة سؤال للعبة: {{ $game->name }}</h5>
    </div>
    <div class="card-body">
        <form action="{{ route('store.user.game.question') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="user_game_id" value="{{ $game->id }}">

            <!-- عنوان السؤال -->
            <div class="mb-3">
                <label>عنوان السؤال <span class="text-danger">*</span></label>
                <input type="text" name="qu_title" class="form-control" required>
            </div>

            <!-- نقاط السؤال -->
            <div class="mb-3">
                <label>نقاط السؤال</label>
                <input type="number" name="qu_points" class="form-control" min="1" required>
            </div>

            <!-- المدة -->
            <div class="mb-3">
                <label>مدة السؤال (بالثواني)</label>
                <input type="number" name="time_counter" class="form-control" min="1" required>
            </div>

            <!-- نوع السؤال -->
            <div class="mb-3">
                <label>نوع السؤال</label>
                <select name="questions_type" class="form-select" id="questionType" onchange="toggleQuestionMedia()">
                    <option value="text">نص فقط</option>
                    <option value="text_image">نص مع صورة</option>
                    <option value="text_video">نص مع فيديو</option>
                    <option value="text_audio">نص مع صوت</option>
                </select>
            </div>

            <!-- ملفات السؤال -->
            <div id="imageField" class="mb-3" style="display:none;">
                <label>صورة السؤال</label>
                <input type="file" name="qu_image" class="form-control" accept="image/*" onchange="previewFile(event, 'previewImage')">
                <img id="previewImage" style="display:none; max-width:120px; margin-top:5px; border:1px solid #ddd;">
            </div>

            <div id="videoField" class="mb-3" style="display:none;">
                <label>فيديو السؤال</label>
                <input type="file" name="qu_video" class="form-control" accept="video/*" onchange="previewFile(event, 'previewVideo')">
                <video id="previewVideo" style="display:none; max-width:200px; margin-top:5px;" controls></video>
            </div>

            <div id="audioField" class="mb-3" style="display:none;">
                <label>صوت السؤال</label>
                <input type="file" name="qu_sound" class="form-control" accept="audio/*" onchange="previewFile(event, 'previewAudio')">
                <audio id="previewAudio" style="display:none; margin-top:5px;" controls></audio>
            </div>

            <hr>
            <h6>إجابات السؤال</h6>

            <div id="answersWrapper">
                <div class="answer-item border rounded p-3 mb-3">
                    <div class="mb-2">
                        <label>نص الإجابة</label>
                        <input type="text" name="answers[0][answer_title]" class="form-control" required>
                    </div>

                    <div class="mb-2">
                        <label>نوع الإجابة</label>
                        <select name="answers[0][answer_type]" class="form-select" onchange="toggleAnswerMedia(this, 0)">
                            <option value="text">نص فقط</option>
                            <option value="text_image">نص مع صورة</option>
                            <option value="text_video">نص مع فيديو</option>
                            <option value="text_audio">نص مع صوت</option>
                        </select>
                    </div>

                    <div class="answer-media mt-2">
                        <input type="file" name="answers[0][answer_image]" class="form-control answer-image d-none" accept="image/*" onchange="previewFile(event, 'answerImage0')">
                        <img id="answerImage0" style="display:none; max-width:120px; margin-top:5px; border:1px solid #ddd;">

                        <input type="file" name="answers[0][answer_video]" class="form-control answer-video d-none" accept="video/*" onchange="previewFile(event, 'answerVideo0')">
                        <video id="answerVideo0" style="display:none; max-width:200px; margin-top:5px;" controls></video>

                        <input type="file" name="answers[0][answer_sound]" class="form-control answer-sound d-none" accept="audio/*" onchange="previewFile(event, 'answerAudio0')">
                        <audio id="answerAudio0" style="display:none; margin-top:5px;" controls></audio>
                    </div>
                </div>
            </div>

            {{-- <button type="button" class="btn btn-outline-primary mb-3" onclick="addAnswer()">+ إضافة إجابة أخرى</button> --}}
            <br>
            <button type="submit" class="btn btn-success">💾 حفظ السؤال</button>
        </form>
    </div>
</div>

<script>
function toggleQuestionMedia() {
    const type = document.getElementById('questionType').value;
    document.getElementById('imageField').style.display = (type === 'text_image') ? 'block' : 'none';
    document.getElementById('videoField').style.display = (type === 'text_video') ? 'block' : 'none';
    document.getElementById('audioField').style.display = (type === 'text_audio') ? 'block' : 'none';
}

function previewFile(event, previewId) {
    const file = event.target.files[0];
    const preview = document.getElementById(previewId);
    if (!file) return;

    const reader = new FileReader();
    reader.onload = e => {
        preview.src = e.target.result;
        preview.style.display = 'block';
        if (preview.tagName === 'VIDEO' || preview.tagName === 'AUDIO') {
            preview.load();
        }
    };
    reader.readAsDataURL(file);
}

let answerIndex = 1;
function addAnswer() {
    const wrapper = document.getElementById('answersWrapper');
    const html = `
    <div class="answer-item border rounded p-3 mb-3">
        <div class="mb-2">
            <label>نص الإجابة</label>
            <input type="text" name="answers[${answerIndex}][answer_title]" class="form-control" required>
        </div>

        <div class="mb-2">
            <label>نوع الإجابة</label>
            <select name="answers[${answerIndex}][answer_type]" class="form-select" onchange="toggleAnswerMedia(this, ${answerIndex})">
                <option value="text">نص فقط</option>
                <option value="text_image">نص مع صورة</option>
                <option value="text_video">نص مع فيديو</option>
                <option value="text_audio">نص مع صوت</option>
            </select>
        </div>

        <div class="answer-media mt-2">
            <input type="file" name="answers[${answerIndex}][answer_image]" class="form-control answer-image d-none" accept="image/*" onchange="previewFile(event, 'answerImage${answerIndex}')">
            <img id="answerImage${answerIndex}" style="display:none; max-width:120px; margin-top:5px; border:1px solid #ddd;">

            <input type="file" name="answers[${answerIndex}][answer_video]" class="form-control answer-video d-none" accept="video/*" onchange="previewFile(event, 'answerVideo${answerIndex}')">
            <video id="answerVideo${answerIndex}" style="display:none; max-width:200px; margin-top:5px;" controls></video>

            <input type="file" name="answers[${answerIndex}][answer_sound]" class="form-control answer-sound d-none" accept="audio/*" onchange="previewFile(event, 'answerAudio${answerIndex}')">
            <audio id="answerAudio${answerIndex}" style="display:none; margin-top:5px;" controls></audio>
        </div>
    </div>`;
    wrapper.insertAdjacentHTML('beforeend', html);
    answerIndex++;
}

function toggleAnswerMedia(select, index) {
    const parent = select.closest('.answer-item');
    parent.querySelector('.answer-image').classList.add('d-none');
    parent.querySelector('.answer-video').classList.add('d-none');
    parent.querySelector('.answer-sound').classList.add('d-none');

    const type = select.value;
    if (type === 'text_image') parent.querySelector('.answer-image').classList.remove('d-none');
    else if (type === 'text_video') parent.querySelector('.answer-video').classList.remove('d-none');
    else if (type === 'text_audio') parent.querySelector('.answer-sound').classList.remove('d-none');
}
</script>

@endsection
