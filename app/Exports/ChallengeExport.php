<?php

namespace App\Exports;

use App\Models\Challenge;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ChallengeExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected $request;
    private $items = null;
    private $rowNumber = 0;

    public function __construct(?Request $request = null)
    {
        $this->request = $request;
    }

    public function collection()
    {
        if ($this->items === null) {
            $query = Challenge::with(['sender', 'receiver', 'winner']);

            if ($this->request) {
                if ($this->request->filled('invitation_statue') && $this->request->invitation_statue !== 'all') {
                    $query->where('invitation_statue', $this->request->invitation_statue);
                }

                if ($this->request->filled('search')) {
                    $search = $this->request->search;
                    $query->where(function ($q) use ($search) {
                        $q->where('game_code', 'like', "%{$search}%")
                          ->orWhereHas('sender', function ($sq) use ($search) {
                              $sq->where('fname', 'like', "%{$search}%")
                                ->orWhere('lname', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                          })
                          ->orWhereHas('receiver', function ($rq) use ($search) {
                              $rq->where('fname', 'like', "%{$search}%")
                                ->orWhere('lname', 'like', "%{$search}%")
                                ->orWhere('email', 'like', "%{$search}%");
                          });
                    });
                }

                if ($this->request->sort_by === 'oldest') {
                    $query->oldest();
                } else {
                    $query->latest();
                }
            } else {
                $query->latest();
            }

            $this->items = $query->get();
        }

        return $this->items;
    }

    public function headings(): array
    {
        return [
            '#',
            'معرف التحدي',
            'اسم مرسل الدعوة',
            'بريد المرسل',
            'اسم مستقبل الدعوة',
            'بريد المستقبل',
            'رمز اللعبة (Game Code)',
            'تاريخ التحدي',
            'حالة الدعوة',
            'الفائز بالتحدي',
            'النقاط المحققة',
            'تاريخ فتح الانضمام',
            'تاريخ غلق الانضمام',
            'تاريخ الإنشاء',
        ];
    }

    public function map($challenge): array
    {
        $this->rowNumber++;

        $senderName = $challenge->sender ? ($challenge->sender->fname . ' ' . $challenge->sender->lname) : 'مستخدم محذوف';
        $senderEmail = $challenge->sender ? $challenge->sender->email : '-';

        $receiverName = $challenge->receiver ? ($challenge->receiver->fname . ' ' . $challenge->receiver->lname) : 'مستخدم محذوف';
        $receiverEmail = $challenge->receiver ? $challenge->receiver->email : '-';

        $winnerName = $challenge->winner ? ($challenge->winner->fname . ' ' . $challenge->winner->lname) : 'غير محدد';

        $statusText = match ($challenge->invitation_statue) {
            'pending' => 'معلقة (Pending)',
            'accepted' => 'مقبولة (Accepted)',
            'rejected' => 'مرفوضة (Rejected)',
            'completed', 'finished' => 'مكتملة (Completed)',
            'canceled', 'cancelled' => 'ملغاة (Canceled)',
            default => $challenge->invitation_statue,
        };

        return [
            $this->rowNumber,
            $challenge->id,
            $senderName,
            $senderEmail,
            $receiverName,
            $receiverEmail,
            $challenge->game_code,
            $challenge->date ? $challenge->date->format('Y-m-d H:i:s') : '-',
            $statusText,
            $winnerName,
            $challenge->score_get !== null ? $challenge->score_get : '-',
            $challenge->join_start_at ? $challenge->join_start_at->format('Y-m-d H:i:s') : '-',
            $challenge->join_end_at ? $challenge->join_end_at->format('Y-m-d H:i:s') : '-',
            $challenge->created_at ? $challenge->created_at->format('Y-m-d H:i:s') : '-',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['rgb' => 'FFFFFF']],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '1E293B']
                ]
            ],
        ];
    }
}
