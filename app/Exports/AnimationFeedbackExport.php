<?php

namespace App\Exports;

use App\Models\AnimationFeedback;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class AnimationFeedbackExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
     * Get the collection of animation items.
     */
    public function collection()
    {
        if ($this->items === null) {
            $this->items = AnimationFeedback::with(['rankingNew', 'coin'])->latest()->get();
        }

        return $this->items;
    }

    /**
     * Define the headings.
     */
    public function headings(): array
    {
        return [
            'الرقم',
            'النوع',
            'اسم الحركة',
            'الرتبة',
            'الحركة (أنيميشن)',
            'الصوت',
            'مجانية؟',
            'العملة / العدد'
        ];
    }

    /**
     * Map each row of the dataset.
     */
    public function map($item): array
    {
        $this->rowNumber++;

        $type = $item->type == 'positive' ? 'إيجابية (Positive)' : 'سلبية (Negative)';
        
        $name = ($item->name ?? '---') . "\n(" . ($item->name_en ?? '---') . ")";
        $rank = $item->rankingNew ? $item->rankingNew->rank_name : 'غير محدد';
        $isFree = $item->is_free == 1 ? 'نعم' : 'لا';
        
        $coinAmount = '-';
        if ($item->is_free == 0 && $item->coin) {
            $coinAmount = $item->coin->name . ' / ' . ($item->coin_amount ?? 0);
        }

        $animationText = $item->file_path ? 'اضغط لعرض الحركة 🎬' : 'لا يوجد';
        $audioText = $item->audio ? 'استماع للصوت 🔊' : 'لا يوجد';

        return [
            $this->rowNumber,
            $type,
            $name,
            $rank,
            $animationText,
            $audioText,
            $isFree,
            $coinAmount
        ];
    }

    /**
     * Style the spreadsheet.
     */
    public function styles(Worksheet $sheet)
    {
        // 1. Enable Right-to-Left layout
        $sheet->setRightToLeft(true);

        $totalRows = $sheet->getHighestRow();
        $totalColumns = 'H';

        // 2. Set Row Heights
        $sheet->getRowDimension(1)->setRowHeight(35); // Header row height
        for ($i = 2; $i <= $totalRows; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(45); // Height to fit double line names

            // Apply alternating zebra striping
            if ($i % 2 == 0) {
                $sheet->getStyle("A{$i}:H{$i}")->applyFromArray([
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
            'B' => 18,  // النوع
            'C' => 30,  // اسم الحركة
            'D' => 20,  // الرتبة
            'E' => 25,  // الحركة (أنيميشن)
            'F' => 22,  // الصوت
            'G' => 12,  // مجانية؟
            'H' => 22,  // العملة / العدد
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

        // 5. Add Hyperlinks and custom style to link cells
        $items = $this->collection();
        $row = 2;
        foreach ($items as $item) {
            // Animation Hyperlink
            if ($item->file_path) {
                $previewUrl = route('admin.animation.preview', $item->id);
                $cell = $sheet->getCell('E' . $row);
                $cell->getHyperlink()->setUrl($previewUrl);
                $cell->getHyperlink()->setTooltip('اضغط لمعاينة الحركة وتشغيل الصوت');
                
                // Style link text
                $sheet->getStyle('E' . $row)->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => '0066CC'],
                        'underline' => true,
                        'bold' => true
                    ]
                ]);
            }

            // Audio Hyperlink
            if ($item->audio) {
                $audioUrl = asset($item->audio);
                $cell = $sheet->getCell('F' . $row);
                $cell->getHyperlink()->setUrl($audioUrl);
                $cell->getHyperlink()->setTooltip('اضغط للاستماع للملف الصوتي');

                // Style link text
                $sheet->getStyle('F' . $row)->applyFromArray([
                    'font' => [
                        'color' => ['rgb' => '0066CC'],
                        'underline' => true,
                        'bold' => true
                    ]
                ]);
            }
            $row++;
        }

        // 6. Header Row styles (White bold text on Dark Purple background)
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
