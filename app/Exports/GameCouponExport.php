<?php

namespace App\Exports;

use App\Models\GameCoupon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class GameCouponExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
     * Get the collection of game coupons.
     */
    public function collection()
    {
        if ($this->items === null) {
            $this->items = GameCoupon::with('gamePurchase')->latest()->get();
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
            'كود الكوبون',
            'النوع',
            'التأثير / القيمة',
            'باقة الشراء المرتبطة',
            'الحد الأقصى / الاستخدام',
            'تاريخ الانتهاء',
            'الحالة'
        ];
    }

    /**
     * Map each row of the dataset.
     */
    public function map($item): array
    {
        $this->rowNumber++;

        $type = '---';
        if ($item->type == 'percentage') {
            $type = 'خصم نسبة مئوية';
        } elseif ($item->type == 'free_games') {
            $type = 'ألعاب مجانية';
        } elseif ($item->type == 'package_bonus') {
            $type = 'مكافأة باقة';
        }

        $value = '---';
        if ($item->type == 'percentage') {
            $value = ($item->discount_percentage ?? 0) . '%';
        } elseif ($item->type == 'free_games' || $item->type == 'package_bonus') {
            $value = ($item->free_games_count ?? 0) . ' ألعاب';
        }

        $purchase = 'غير مرتبط';
        if ($item->gamePurchase) {
            $purchase = ($item->gamePurchase->games_count ?? 0) . ' ألعاب بسعر ' . ($item->gamePurchase->price ?? 0);
        }

        $usage = $item->usage_limit ? ($item->used_count ?? 0) . ' / ' . $item->usage_limit : ($item->used_count ?? 0) . ' / ∞';
        $expires = $item->expires_at ? Carbon::parse($item->expires_at)->format('Y-m-d H:i') : 'غير محدد';
        $status = $item->is_active ? 'نشط' : 'معطل';

        return [
            $this->rowNumber,
            $item->code ?? '---',
            $type,
            $value,
            $purchase,
            $usage,
            $expires,
            $status
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
        $totalColumns = 'H';

        // 2. Set Row Heights
        $sheet->getRowDimension(1)->setRowHeight(35); // Header row height
        for ($i = 2; $i <= $totalRows; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(35); // Data row height

            // Alternating zebra striping
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
            'B' => 18,  // كود الكوبون
            'C' => 20,  // النوع
            'D' => 18,  // التأثير / القيمة
            'E' => 28,  // باقة الشراء المرتبطة
            'F' => 22,  // الحد الأقصى / الاستخدام
            'G' => 22,  // تاريخ الانتهاء
            'H' => 12,  // الحالة
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
