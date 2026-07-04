<?php

namespace App\Exports;

use App\Models\RankingNew;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class RankingNewExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected $request;
    private $items = null;
    private $rowNumber = 0;

    public function __construct(Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * Get the collection of rankings.
     */
    public function collection()
    {
        if ($this->items === null) {
            $this->items = RankingNew::with(['rankRewardCoin', 'levelRewardCoin'])->oldest()->get();
        }

        return $this->items;
    }

    /**
     * Define headings.
     */
    public function headings(): array
    {
        return [
            'الرقم',
            'اسم الرتبة',
            'ترتيب الرتبة',
            'عملة الوصول',
            'العدد (الوصول)',
            'الرتبة الأخيرة؟',
            'هل هي مجانية؟',
            'عدد المستويات',
            'الفوز (للمستوى)',
            'إجمالي الفوز للرتبة التالية',
            'عملة الانتقال',
            'العدد (الانتقال)'
        ];
    }

    /**
     * Map each row of the dataset.
     */
    public function map($item): array
    {
        $this->rowNumber++;

        $name = ($item->rank_name ?? '---') . "\n(" . ($item->rank_name_en ?? '---') . ")";
        $rankRewardCoin = $item->rankRewardCoin ? $item->rankRewardCoin->name : 'لا يوجد';
        $levelRewardCoin = $item->levelRewardCoin ? $item->levelRewardCoin->name : 'لا يوجد';
        $isLast = $item->is_last == 1 ? 'نعم' : 'لا';
        $isFree = $item->is_free == 1 ? 'نعم' : 'لا';

        return [
            $this->rowNumber,
            $name,
            $item->rank_order ?? 0,
            $rankRewardCoin,
            $item->rank_reward_amount ?? 0,
            $isLast,
            $isFree,
            $item->levels_count ?? 0,
            $item->wins_to_next_level ?? 0,
            $item->total_wins_to_next_rank ?? 0,
            $levelRewardCoin,
            $item->level_reward_amount ?? 0
        ];
    }

    /**
     * Style the worksheet.
     */
    public function styles(Worksheet $sheet)
    {
        // 1. Enable Right-to-Left layout
        $sheet->setRightToLeft(true);

        $totalRows = $sheet->getHighestRow();
        $totalColumns = 'L';

        // 2. Set Row Heights
        $sheet->getRowDimension(1)->setRowHeight(35); // Header row height
        for ($i = 2; $i <= $totalRows; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(40); // Data row height to fit Arabic/English names

            // Alternating zebra striping
            if ($i % 2 == 0) {
                $sheet->getStyle("A{$i}:L{$i}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8F9FA']
                    ]
                ]);
            }
        }

        // 3. Set Columns Widths
        $widths = [
            'A' => 8,   // الرقم
            'B' => 25,  // اسم الرتبة
            'C' => 15,  // ترتيب الرتبة
            'D' => 18,  // عملة الوصول
            'E' => 18,  // العدد الوصول
            'F' => 18,  // الرتبة الأخيرة
            'G' => 18,  // مجانية
            'H' => 15,  // عدد المستويات
            'I' => 18,  // الفوز للمستوى
            'J' => 25,  // إجمالي الفوز للرتبة التالية
            'K' => 18,  // عملة الانتقال
            'L' => 18,  // العدد الانتقال
        ];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // 4. Set Common styles (alignment, borders)
        $styleArray = [
            'borders' => [
                'allBorders' => [
                    'borderStyle' => \PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN,
                    'color' => ['rgb' => 'D0D5DD'],
                ],
            ],
            'alignment' => [
                'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                'vertical' => \PhpOffice\PhpSpreadsheet\Style\Alignment::VERTICAL_CENTER,
                'wrapText' => true
            ]
        ];
        $sheet->getStyle("A1:{$totalColumns}{$totalRows}")->applyFromArray($styleArray);

        // 5. Header Row styles (White bold text on Dark Purple background)
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 11,
                    'name' => 'Segoe UI'
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '32296A']
                ]
            ]
        ];
    }
}
