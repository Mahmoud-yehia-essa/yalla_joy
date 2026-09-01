<?php

namespace App\Exports;

use App\Models\User;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithDrawings;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use PhpOffice\PhpSpreadsheet\Worksheet\Drawing;
use Carbon\Carbon;

class UsersExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithDrawings
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
     * Get the collection of users.
     */
    public function collection()
    {
        if ($this->items === null) {
            $this->items = User::where('role', '!=', 'admin')->latest()->get();
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
            'اسم المستخدم',
            'الاسم الأول',
            'اسم العائلة',
            'البريد الإلكتروني',
            'تاريخ الميلاد',
            'تاريخ التسجيل',
            'طريقة التسجيل',
            'نقاط الاونلاين الكلية',
            'نقاط الاونلاين المتاحة',
            'الصورة',
            'حالة الصورة'
        ];
    }

    /**
     * Map each row of the dataset.
     */
    public function map($item): array
    {
        $this->rowNumber++;

        $dob = 'لم يتم التحديد';
        if ($item->date_of_birth) {
            $dob = $item->date_of_birth . ' (' . Carbon::parse($item->date_of_birth)->age . ' سنة)';
        }

        $registrationDate = $item->created_at ? $item->created_at->format('Y-m-d H:i') : 'لم يتم التحديد';

        $registerType = 'غير معروف';
        if ($item->register_type == 'normal') {
            $registerType = 'البريد الإلكتروني';
        } elseif ($item->register_type == 'google') {
            $registerType = 'جوجل (Google)';
        } elseif ($item->register_type == 'facebook') {
            $registerType = 'فيسبوك (Facebook)';
        } elseif ($item->register_type == 'apple') {
            $registerType = 'آبل (Apple)';
        } else {
            $registerType = $item->register_type ?? 'غير معروف';
        }

        $photoApproval = 'لا توجد صورة';
        if (!empty($item->photo) && $item->photo != 'non') {
            if ($item->photo_approval_status == 'approved') {
                $photoApproval = 'مقبولة';
            } elseif ($item->photo_approval_status == 'rejected') {
                $photoApproval = 'مرفوضة';
            } else {
                $photoApproval = 'قيد المراجعة';
            }
        }

        return [
            $this->rowNumber,
            $item->user_name ?? '---',
            $item->fname ?? '---',
            $item->lname ?? '---',
            $item->email ?? '---',
            $dob,
            $registrationDate,
            $registerType,
            $item->online_points_fixed ?? 0,
            $item->online_points ?? 0,
            '', // Blank for the image overlay in Column K
            $photoApproval
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
            $photoPath = null;
            if (!empty($item->photo) && $item->photo != 'non' && file_exists(public_path('upload/user_images/' . $item->photo))) {
                $photoPath = public_path('upload/user_images/' . $item->photo);
            } elseif (file_exists(public_path('upload/no_image.jpg'))) {
                $photoPath = public_path('upload/no_image.jpg');
            }

            if ($photoPath) {
                $drawing = new Drawing();
                $drawing->setName($item->fname ?? 'User');
                $drawing->setDescription($item->fname ?? 'User Avatar');
                $drawing->setPath($photoPath);
                $drawing->setHeight(35); // 35px height fits perfectly
                $drawing->setCoordinates('K' . $row); // Column K is the image column

                // Centering offset mathematically:
                // Column K width is locked to 16 units (approx. 112px). Image is 35px.
                // Horizontal offset: (112 - 35) / 2 = 38px.
                // Row height is locked to 50pt (approx. 66px). Image is 35px.
                // Vertical offset: (66 - 35) / 2 = 15px.
                $drawing->setOffsetX(38);
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
        $totalColumns = 'L';

        // 2. Set Row Heights
        $sheet->getRowDimension(1)->setRowHeight(35); // Header row height
        for ($i = 2; $i <= $totalRows; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(50); // Data row height to fit avatars

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
            'B' => 20,  // اسم المستخدم
            'C' => 18,  // الاسم الأول
            'D' => 18,  // اسم العائلة
            'E' => 28,  // البريد الإلكتروني
            'F' => 22,  // تاريخ الميلاد
            'G' => 22,  // تاريخ التسجيل
            'H' => 20,  // طريقة التسجيل
            'I' => 22,  // نقاط الاونلاين الكلية
            'J' => 22,  // نقاط الاونلاين المتاحة
            'K' => 16,  // الصورة (locked width)
            'L' => 18,  // حالة الصورة
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
