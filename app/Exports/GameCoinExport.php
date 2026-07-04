<?php

namespace App\Exports;

use App\Models\GameCoin;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class GameCoinExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithDrawings
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
     * Get the collection of game coins.
     */
    public function collection()
    {
        if ($this->items === null) {
            $this->items = GameCoin::latest()->get();
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
            'اسم العملة (عربي)',
            'اسم العملة (إنجليزي)',
            'الصورة',
            'الحالة',
            'تاريخ الإضافة'
        ];
    }

    /**
     * Map each row of the dataset.
     */
    public function map($item): array
    {
        $this->rowNumber++;

        $status = $item->status == 'active' ? 'نشطة (Active)' : 'غير نشطة (Inactive)';
        $createdAt = $item->created_at ? $item->created_at->format('Y-m-d H:i') : '---';

        return [
            $this->rowNumber,
            $item->name ?? '---',
            $item->name_en ?? '---',
            '', // Blank for the image overlay
            $status,
            $createdAt
        ];
    }

    /**
     * Define the drawings (images) to be rendered.
     */
    public function drawings()
    {
        $drawings = [];
        $items = $this->collection();

        $row = 2; // Headings at row 1, data starts at row 2
        foreach ($items as $item) {
            if ($item->photo && file_exists(public_path($item->photo))) {
                $drawing = new Drawing();
                $drawing->setName($item->name ?? 'Coin');
                $drawing->setDescription($item->name ?? 'Coin Photo');
                $drawing->setPath(public_path($item->photo));
                $drawing->setHeight(30); // 30px height fits small icons perfectly
                $drawing->setCoordinates('D' . $row); // Column D is the image column

                // Centering offset mathematically:
                // Column D width is locked to 15 units (approx. 105px). Image is 30px.
                // Horizontal offset: (105 - 30) / 2 = 37px.
                // Row height is locked to 45pt (approx. 60px). Image is 30px.
                // Vertical offset: (60 - 30) / 2 = 15px.
                $drawing->setOffsetX(37);
                $drawing->setOffsetY(15);

                $drawings[] = $drawing;
            }
            $row++;
        }

        return $drawings;
    }

    /**
     * Style the worksheet.
     */
    public function styles(Worksheet $sheet)
    {
        // 1. Enable Right-to-Left layout
        $sheet->setRightToLeft(true);

        $totalRows = $sheet->getHighestRow();
        $totalColumns = 'F';

        // 2. Set Row Heights
        $sheet->getRowDimension(1)->setRowHeight(35); // Header row height
        for ($i = 2; $i <= $totalRows; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(45); // Data row height to fit images

            // Alternating zebra striping
            if ($i % 2 == 0) {
                $sheet->getStyle("A{$i}:F{$i}")->applyFromArray([
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
            'B' => 25,  // اسم العملة عربي
            'C' => 25,  // اسم العملة إنجليزي
            'D' => 15,  // الصورة (locked width)
            'E' => 18,  // الحالة
            'F' => 22,  // تاريخ الإضافة
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
