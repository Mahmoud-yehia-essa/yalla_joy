<?php

namespace App\Exports;

use App\Models\CouponCompany;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Carbon\Carbon;

class CouponCompanyExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
     * Get the collection of coupon companies.
     */
    public function collection()
    {
        if ($this->items === null) {
            $this->items = CouponCompany::with(['sponsor', 'gameCoin'])->latest()->get();
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
            'الكود',
            'الشركة',
            'تاريخ الانتهاء',
            'تكلفة الشراء',
            'نوع الكوبون',
            'كوبون خاص؟'
        ];
    }

    /**
     * Map each row of the dataset.
     */
    public function map($item): array
    {
        $this->rowNumber++;

        $name = ($item->coupon_name ?? '---') . "\n(" . ($item->coupon_name_en ?? '---') . ")";
        $sponsor = $item->sponsor ? $item->sponsor->title : 'N/A';
        $validity = $item->valid_until ? Carbon::parse($item->valid_until)->format('Y-m-d') : 'دائم';
        
        $price = '-';
        if ($item->gameCoin) {
            $price = ($item->game_coins_count ?? 0) . ' (' . $item->gameCoin->name . ')';
        }

        $type = $item->is_scratch_coupon ? 'كوبون قشط' : 'كوبون عادي';
        $isSpecial = $item->is_special_coupon ? 'نعم' : 'لا';

        return [
            $this->rowNumber,
            $name,
            $item->coupon_code ?? '---',
            $sponsor,
            $validity,
            $price,
            $type,
            $isSpecial
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
            $sheet->getRowDimension($i)->setRowHeight(40); // Data row height to fit dual language names

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
            'B' => 30,  // اسم الكوبون
            'C' => 15,  // الكود
            'D' => 20,  // الشركة
            'E' => 18,  // تاريخ الانتهاء
            'F' => 22,  // تكلفة الشراء
            'G' => 18,  // نوع الكوبون
            'H' => 15,  // كوبون خاص؟
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
