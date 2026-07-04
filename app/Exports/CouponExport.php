<?php

namespace App\Exports;

use App\Models\Coupon;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class CouponExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
     * Get the collection of coupons.
     */
    public function collection()
    {
        if ($this->items === null) {
            $this->items = Coupon::latest()->get();
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
            'اسم الكوبون',
            'الخصم',
            'تاريخ الانتهاء',
            'حالة الكوبون'
        ];
    }

    /**
     * Map each row of the dataset.
     */
    public function map($item): array
    {
        $this->rowNumber++;

        $discount = ($item->coupon_discount ?? 0) . '%';
        $validity = $item->coupon_validity ? Carbon::parse($item->coupon_validity)->format('Y-m-d') : '---';
        
        $status = 'غير صالح للاستخدام';
        if ($item->coupon_validity >= Carbon::now()->format('Y-m-d')) {
            $status = 'صالح للاستخدام';
        }

        return [
            $this->rowNumber,
            $item->coupon_name ?? '---',
            $discount,
            $validity,
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
        $totalColumns = 'E';

        // 2. Set Row Heights
        $sheet->getRowDimension(1)->setRowHeight(35); // Header row height
        for ($i = 2; $i <= $totalRows; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(35); // Data row height

            // Alternating zebra striping
            if ($i % 2 == 0) {
                $sheet->getStyle("A{$i}:E{$i}")->applyFromArray([
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
            'B' => 25,  // اسم الكوبون
            'C' => 15,  // الخصم
            'D' => 22,  // تاريخ الانتهاء
            'E' => 22,  // حالة الكوبون
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
