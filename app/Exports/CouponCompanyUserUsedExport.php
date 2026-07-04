<?php

namespace App\Exports;

use App\Models\CouponCompanyUserUsed;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class CouponCompanyUserUsedExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
     * Get the collection of used coupons.
     */
    public function collection()
    {
        if ($this->items === null) {
            $this->items = CouponCompanyUserUsed::with(['user', 'couponCompany.sponsor'])->latest()->get();
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
            'المستخدم',
            'رقم الهاتف',
            'الكوبون',
            'كود الكوبون',
            'الشركة',
            'حالة الشراء',
            'تاريخ الشراء',
            'حالة الاستخدام',
            'تاريخ الاستخدام'
        ];
    }

    /**
     * Map each row of the dataset.
     */
    public function map($item): array
    {
        $this->rowNumber++;

        $user = 'N/A';
        if ($item->user) {
            $user = $item->user->fname . ' ' . $item->user->lname;
            if ($item->user->username) {
                $user .= ' (' . $item->user->username . ')';
            }
        }
        
        $phone = $item->user->phone ?? 'N/A';
        $coupon = $item->couponCompany->coupon_name ?? 'N/A';
        $code = $item->couponCompany->coupon_code ?? 'N/A';
        $sponsor = $item->couponCompany->sponsor->title ?? 'N/A';
        
        $isBuy = $item->is_buy ? 'تم الشراء' : 'لم يتم الشراء';
        $buyDate = $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '---';
        
        $isUsed = $item->is_used ? 'تم الاستخدام' : 'غير مستخدم';
        $usedDate = $item->used_at ? Carbon::parse($item->used_at)->format('Y-m-d H:i:s') : '-';

        return [
            $this->rowNumber,
            $user,
            $phone,
            $coupon,
            $code,
            $sponsor,
            $isBuy,
            $buyDate,
            $isUsed,
            $usedDate
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
        $totalColumns = 'J';

        // 2. Set Row Heights
        $sheet->getRowDimension(1)->setRowHeight(35); // Header row height
        for ($i = 2; $i <= $totalRows; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(35); // Data row height

            // Alternating zebra striping
            if ($i % 2 == 0) {
                $sheet->getStyle("A{$i}:J{$i}")->applyFromArray([
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
            'B' => 25,  // المستخدم
            'C' => 18,  // رقم الهاتف
            'D' => 22,  // الكوبون
            'E' => 15,  // كود الكوبون
            'F' => 20,  // الشركة
            'G' => 15,  // حالة الشراء
            'H' => 22,  // تاريخ الشراء
            'I' => 15,  // حالة الاستخدام
            'J' => 22,  // تاريخ الاستخدام
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
