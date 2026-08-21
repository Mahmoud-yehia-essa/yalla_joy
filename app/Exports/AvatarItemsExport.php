<?php

namespace App\Exports;

use App\Models\AvatarItems;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;

class AvatarItemsExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithDrawings
{
    use Exportable;

    protected $request;
    private $items = null;
    private $rowNumber = 0;

    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Get the exact collection of items to export.
     */
    public function collection()
    {
        if ($this->items === null) {
            $query = AvatarItems::with(['category', 'coin']);

            if ($this->request->filled('category_id')) {
                $query->where('category_id', $this->request->category_id);
            }

            if ($this->request->filled('gender')) {
                $query->where('gender', $this->request->gender);
            }

            if ($this->request->filled('price_type')) {
                if ($this->request->price_type == 'free') {
                    $query->where('is_free', 1);
                } elseif ($this->request->price_type == 'paid') {
                    $query->where('is_free', 0);
                }
            }

            $this->items = $query->orderByRaw('order_by IS NULL ASC')
                ->orderBy('order_by', 'asc')
                ->orderBy('id', 'desc')
                ->get();
        }

        return $this->items;
    }

    /**
     * Define the headers for the Excel file.
     */
    public function headings(): array
    {
        return [
            'الرقم',
            'اسم العنصر',
            'الصورة',
            'التصنيف',
            'النوع',
            'نوع العملة',
            'السعر (العملات)',
            'تاريخ الإضافة'
        ];
    }

    /**
     * Map each row of the dataset.
     */
    public function map($avatarItem): array
    {
        $this->rowNumber++;

        $gender = '';
        if ($avatarItem->gender == 'boy') {
            $gender = 'ولد (Boy)';
        } elseif ($avatarItem->gender == 'girl') {
            $gender = 'بنت (Girl)';
        } else {
            $gender = $avatarItem->gender ?? '---';
        }

        $coinType = $avatarItem->is_free ? 'مجاني' : ($avatarItem->coin->name ?? '---');
        $price = $avatarItem->is_free ? 'مجاني' : ($avatarItem->coins_number ?? 0);

        return [
            $this->rowNumber,
            $avatarItem->name ?? '---',
            '', // Blank because the drawing will overlay on this cell
            $avatarItem->category->name ?? '---',
            $gender,
            $coinType,
            $price,
            $avatarItem->created_at ? $avatarItem->created_at->format('Y-m-d H:i') : '---'
        ];
    }

    /**
     * Define the drawings (images) to be rendered.
     */
    public function drawings()
    {
        $drawings = [];
        $items = $this->collection();
        
        $row = 2; // Headings are row 1, data starts at row 2
        foreach ($items as $item) {
            if ($item->image && file_exists(public_path($item->image))) {
                $drawing = new Drawing();
                $drawing->setName($item->name ?? 'Avatar');
                $drawing->setDescription($item->name ?? 'Avatar Image');
                $drawing->setPath(public_path($item->image));
                $drawing->setHeight(48); // height 48px
                $drawing->setCoordinates('C' . $row); // Column C is the image column
                
                // Centering offset mathematically:
                // Column C width is locked to 20 units (approx. 140px). Image is 48px.
                // Horizontal offset: (140 - 48) / 2 = 46px.
                // Row height is locked to 65pt (approx. 86px). Image is 48px.
                // Vertical offset: (86 - 48) / 2 = 19px.
                $drawing->setOffsetX(46);
                $drawing->setOffsetY(19);
                
                $drawings[] = $drawing;
            }
            $row++;
        }

        return $drawings;
    }

    /**
     * Apply styling to the worksheet.
     */
    public function styles(Worksheet $sheet)
    {
        // Set layout to Right-to-Left (RTL)
        $sheet->setRightToLeft(true);

        $totalRows = $sheet->getHighestRow();
        $totalColumns = 'H'; // Columns A to H

        // 1. Set Row Heights
        $sheet->getRowDimension(1)->setRowHeight(40); // Header row height
        for ($i = 2; $i <= $totalRows; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(65); // Large row height to fit images beautifully
            
            // Apply alternate zebra-striping background color
            if ($i % 2 == 0) {
                $sheet->getStyle("A{$i}:H{$i}")->applyFromArray([
                    'fill' => [
                        'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                        'startColor' => ['rgb' => 'F8F9FA']
                    ]
                ]);
            }
        }

        // 2. Set precise column widths (Prevents ShouldAutoSize from overwriting Column C width)
        $widths = [
            'A' => 8,   // الرقم
            'B' => 25,  // اسم العنصر
            'C' => 20,  // الصورة (locked width)
            'D' => 20,  // التصنيف
            'E' => 15,  // النوع
            'F' => 15,  // نوع العملة
            'G' => 15,  // السعر (العملات)
            'H' => 22,  // تاريخ الإضافة
        ];
        foreach ($widths as $col => $width) {
            $sheet->getColumnDimension($col)->setWidth($width);
        }

        // 3. Define and apply cell borders & alignments
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

        // 4. Header Row styling (White bold text on Dark Purple background)
        return [
            1 => [
                'font' => [
                    'bold' => true,
                    'color' => ['rgb' => 'FFFFFF'],
                    'size' => 12,
                    'name' => 'Segoe UI'
                ],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['rgb' => '32296A'] // Dark purple theme color
                ]
            ]
        ];
    }
}
