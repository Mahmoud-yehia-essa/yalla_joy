<?php

namespace App\Exports;

use App\Models\ProblemReport;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class ProblemReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
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
     * Get the collection of problem reports.
     */
    public function collection()
    {
        if ($this->items === null) {
            $this->items = ProblemReport::with(['user', 'question'])->latest()->get();
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
            'المُبلِّغ (المستخدم)',
            'السؤال المُرتبط',
            'نوع المشكلة',
            'ملاحظات إضافية',
            'تاريخ الإبلاغ',
            'الحالة'
        ];
    }

    /**
     * Map each row of the dataset.
     */
    public function map($item): array
    {
        $this->rowNumber++;

        $user = 'مستخدم غير معروف';
        if ($item->user) {
            $user = $item->user->name;
            if ($item->user->email) {
                $user .= "\n(" . $item->user->email . ")";
            }
        }

        $questionText = 'سؤال غير موجود (ID: ' . $item->question_id . ')';
        if ($item->question) {
            $questionText = $item->question->qu_title . "\n(ID: " . $item->question_id . ")";
        }

        $issueType = 'غير معروف';
        if ($item->issue_type == 'question_error') {
            $issueType = 'خطأ في السؤال';
        } elseif ($item->issue_type == 'answer_error') {
            $issueType = 'خطأ في الإجابة';
        } elseif ($item->issue_type == 'inappropriate_content') {
            $issueType = 'محتوى غير لائق';
        } else {
            $issueType = $item->issue_type;
        }

        $notes = $item->additional_notes ?: 'لا توجد ملاحظات';
        $reportDate = $item->created_at ? $item->created_at->format('Y-m-d H:i') : 'N/A';

        $status = 'غير معروف';
        if ($item->status == 'pending') {
            $status = 'قيد الانتظار';
        } elseif ($item->status == 'resolved') {
            $status = 'تم الحل';
        } elseif ($item->status == 'ignored') {
            $status = 'تم التجاهل';
        }

        return [
            $this->rowNumber,
            $user,
            $questionText,
            $issueType,
            $notes,
            $reportDate,
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
        $totalColumns = 'G';

        // 2. Set Row Heights
        $sheet->getRowDimension(1)->setRowHeight(35); // Header row height
        for ($i = 2; $i <= $totalRows; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(45); // Data row height

            // Alternating zebra striping
            if ($i % 2 == 0) {
                $sheet->getStyle("A{$i}:G{$i}")->applyFromArray([
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
            'B' => 28,  // المُبلِّغ
            'C' => 35,  // السؤال المرتبط
            'D' => 18,  // نوع المشكلة
            'E' => 30,  // ملاحظات إضافية
            'F' => 20,  // تاريخ الإبلاغ
            'G' => 15,  // الحالة
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

        // 5. Add Hyperlinks to Question column (Column C)
        $items = $this->collection();
        $row = 2;
        foreach ($items as $item) {
            if ($item->question) {
                $editUrl = route('edit.question', $item->question_id);
                $cell = $sheet->getCell('C' . $row);
                $cell->getHyperlink()->setUrl($editUrl);
                $cell->getHyperlink()->setTooltip('اضغط لتعديل السؤال في لوحة التحكم');
                
                // Style link text
                $sheet->getStyle('C' . $row)->applyFromArray([
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
