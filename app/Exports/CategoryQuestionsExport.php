<?php

namespace App\Exports;

use App\Models\Question;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromQuery;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Events\AfterSheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class CategoryQuestionsExport implements FromQuery, WithHeadings, WithMapping, WithStyles, ShouldAutoSize, WithEvents
{
    use Exportable;

    protected $request;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function query()
    {
        @ini_set('memory_limit', '512M');

        $query = Question::query()->with(['answers', 'answerQuestionOnlines']);

        if ($this->request->filled('category_id') && $this->request->category_id !== 'non') {
            $query->where('category_id', $this->request->category_id);
        }

        if ($this->request->filled('main_category_id') && $this->request->main_category_id !== 'non') {
            $query->where('main_category_id', $this->request->main_category_id);
        }

        if ($this->request->filled('game_type_id') && $this->request->game_type_id !== 'non') {
            $query->where('game_type_id', $this->request->game_type_id);
        }

        return $query->orderBy('id', 'asc');
    }

    public function headings(): array
    {
        return [
            'qu_id',
            'ans_id',
            'online_ans_id_1',
            'online_ans_id_2',
            'online_ans_id_3',
            'online_ans_id_4',
            'game_type_id',
            'main_category_id',
            'category_id',
            'qu_hint',
            'qu_hint_en',
            'qu_title',
            'qu_title_en',
            'qu_image',
            'qu_sound',
            'qu_video',
            'qu_points',
            'qu_points_online',
            'time_counter',
            'time_counter_online',
            'answer_title',
            'answer_title_en',
            'answer_image',
            'answer_sound',
            'answer_video',
            'answer_title_two',
            'answer_title_en_two',
            'answer_image_two',
            'answer_sound_two',
            'answer_video_two',
            'answer_title_three',
            'answer_title_en_three',
            'answer_image_three',
            'answer_sound_three',
            'answer_video_three',
            'answer_title_four',
            'answer_title_en_four',
            'answer_image_four',
            'answer_sound_four',
            'answer_video_four',
            'term'
        ];
    }

    public function map($q): array
    {
        $localAns = $q->answers->first();
        $onlines = $q->answerQuestionOnlines->values();

        return [
            $q->id,
            $localAns ? $localAns->id : '',
            isset($onlines[0]) ? $onlines[0]->id : '',
            isset($onlines[1]) ? $onlines[1]->id : '',
            isset($onlines[2]) ? $onlines[2]->id : '',
            isset($onlines[3]) ? $onlines[3]->id : '',
            $q->game_type_id,
            $q->main_category_id,
            $q->category_id,
            $q->qu_hint,
            $q->qu_hint_en,
            $q->qu_title,
            $q->qu_title_en,
            $q->qu_image,
            $q->qu_sound,
            $q->qu_video,
            $q->qu_points,
            $q->qu_points_online,
            $q->time_counter,
            $q->time_counter_online,
            $localAns ? $localAns->answer_title : (isset($onlines[0]) ? $onlines[0]->answer_title : ''),
            $localAns ? $localAns->answer_title_en : (isset($onlines[0]) ? $onlines[0]->answer_title_en : ''),
            $localAns ? $localAns->answer_image : (isset($onlines[0]) ? $onlines[0]->answer_image : ''),
            $localAns ? $localAns->answer_sound : (isset($onlines[0]) ? $onlines[0]->answer_sound : ''),
            $localAns ? $localAns->answer_video : (isset($onlines[0]) ? $onlines[0]->answer_video : ''),
            isset($onlines[1]) ? $onlines[1]->answer_title : '',
            isset($onlines[1]) ? $onlines[1]->answer_title_en : '',
            isset($onlines[1]) ? $onlines[1]->answer_image : '',
            isset($onlines[1]) ? $onlines[1]->answer_sound : '',
            isset($onlines[1]) ? $onlines[1]->answer_video : '',
            isset($onlines[2]) ? $onlines[2]->answer_title : '',
            isset($onlines[2]) ? $onlines[2]->answer_title_en : '',
            isset($onlines[2]) ? $onlines[2]->answer_image : '',
            isset($onlines[2]) ? $onlines[2]->answer_sound : '',
            isset($onlines[2]) ? $onlines[2]->answer_video : '',
            isset($onlines[3]) ? $onlines[3]->answer_title : '',
            isset($onlines[3]) ? $onlines[3]->answer_title_en : '',
            isset($onlines[3]) ? $onlines[3]->answer_image : '',
            isset($onlines[3]) ? $onlines[3]->answer_sound : '',
            isset($onlines[3]) ? $onlines[3]->answer_video : '',
            $q->term
        ];
    }

    public function styles(Worksheet $sheet)
    {
        // تعيين اتجاه ورقة العمل من اليمين إلى الشمال (Right-to-Left RTL)
        $sheet->setRightToLeft(true);

        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['argb' => 'FFFFFFFF'],
                    'size' => 11
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                    'vertical'   => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF10B981']
                ]
            ],
        ];
    }

    public function registerEvents(): array
    {
        return [
            AfterSheet::class => function(AfterSheet $event) {
                // ضمان تعيين اتجاه ورقة العمل من اليمين إلى الشمال (Right-to-Left RTL)
                $event->sheet->getDelegate()->setRightToLeft(true);
            },
        ];
    }
}
