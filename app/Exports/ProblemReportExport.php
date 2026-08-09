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

    public function __construct(?Request $request = null)
    {
        $this->request = $request;
    }

    /**
     * Get the collection of problem reports.
     */
    public function collection()
    {
        if ($this->items === null) {
            $query = ProblemReport::with(['user', 'question', 'cheatingUser']);

            if ($this->request) {
                // Issue Type Filter
                if ($this->request->filled('issue_type') && $this->request->issue_type !== 'all') {
                    $query->where('issue_type', $this->request->issue_type);
                }

                // Report Type Filter
                if ($this->request->filled('report_type') && $this->request->report_type !== 'all') {
                    $query->where('report_type', $this->request->report_type);
                }

                // Status Filter
                if ($this->request->filled('status') && $this->request->status !== 'all') {
                    $query->where('status', $this->request->status);
                }

                // Sorting
                if ($this->request->sort_by === 'oldest') {
                    $query->oldest();
                } else {
                    $query->latest();
                }
            } else {
                $query->latest();
            }

            $this->items = $query->get();
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
            'مصدر المشكلة',
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

        $getUserName = function($u) {
            if (!$u) return 'مستخدم غير معروف';
            $name = trim(($u->fname ?? '') . ' ' . ($u->lname ?? ''));
            return $name !== '' ? $name : ($u->user_name ?? $u->email ?? 'مستخدم غير معروف');
        };

        $user = 'مستخدم غير معروف';
        if ($item->issue_type == 'cheating') {
            $reporter = $getUserName($item->user) . ($item->user && $item->user->email ? " (" . $item->user->email . ")" : "");
            $reported = ($item->cheatingUser ? $getUserName($item->cheatingUser) : 'غير معروف (ID: ' . $item->user_id_cheating . ')') . ($item->cheatingUser && $item->cheatingUser->email ? " (" . $item->cheatingUser->email . ")" : "");
            $user = "المبلِّغ: " . $reporter . "\nالمبلَّغ عنه: " . $reported;
            $questionText = 'غير مرتبط بسؤال (حالة غش)';
        } else {
            if ($item->user) {
                $user = $getUserName($item->user);
                if ($item->user->email) {
                    $user .= "\n(" . $item->user->email . ")";
                }
            }
            $questionText = 'سؤال غير موجود (ID: ' . $item->question_id . ')';
            if ($item->question) {
                $questionText = $item->question->qu_title . "\n(ID: " . $item->question_id . ")";
            }
        }

        $reportType = 'السؤال';
        if ($item->report_type == 'answer') {
            $reportType = 'الإجابة';
        } elseif ($item->report_type == 'question') {
            $reportType = 'السؤال';
        }

        $issueType = 'غير معروف';
        if ($item->issue_type == 'question_error') {
            $issueType = 'خطأ في السؤال';
        } elseif ($item->issue_type == 'answer_error') {
            $issueType = 'خطأ في الإجابة';
        } elseif ($item->issue_type == 'inappropriate_content') {
            $issueType = 'محتوى غير لائق';
        } elseif ($item->issue_type == 'cheating') {
            $issueType = 'حالة غش';
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
            $reportType,
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
        $totalColumns = 'H';

        // 2. Set Row Heights
        $sheet->getRowDimension(1)->setRowHeight(35); // Header row height
        for ($i = 2; $i <= $totalRows; $i++) {
            $sheet->getRowDimension($i)->setRowHeight(45); // Data row height

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
            'B' => 28,  // المُبلِّغ
            'C' => 35,  // السؤال المرتبط
            'D' => 15,  // مصدر المشكلة
            'E' => 18,  // نوع المشكلة
            'F' => 30,  // ملاحظات إضافية
            'G' => 20,  // تاريخ الإبلاغ
            'H' => 15,  // الحالة
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
