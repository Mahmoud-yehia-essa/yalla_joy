<?php

namespace App\Exports;

use App\Models\PaymentTransaction;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\Exportable;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;

class FinancialReportExport implements FromCollection, WithHeadings, WithMapping, WithStyles
{
    use Exportable;

    protected $request;
    private $items = null;
    private $rowNumber = 0;

    public function __construct(?Request $request = null)
    {
        $this->request = $request;
    }

    public function collection()
    {
        if ($this->items === null) {
            $query = PaymentTransaction::with(['user', 'price', 'gameCoin'])->latest();

            if ($this->request) {
                if ($this->request->filled('status') && $this->request->status !== 'all') {
                    if ($this->request->status === 'paid' || $this->request->status === 'success') {
                        $query->paid();
                    } elseif ($this->request->status === 'failed') {
                        $query->failed();
                    } else {
                        $query->where('status', $this->request->status);
                    }
                }

                if ($this->request->filled('package_type') && $this->request->package_type !== 'all') {
                    $query->where('package_type', $this->request->package_type);
                }

                if ($this->request->filled('date_from')) {
                    $query->whereDate('created_at', '>=', $this->request->date_from);
                }

                if ($this->request->filled('date_to')) {
                    $query->whereDate('created_at', '<=', $this->request->date_to);
                }

                if ($this->request->filled('search')) {
                    $search = $this->request->search;
                    $query->where(function ($q) use ($search) {
                        $q->where('order_no', 'like', "%{$search}%")
                          ->orWhere('session_id', 'like', "%{$search}%")
                          ->orWhere('customer_name', 'like', "%{$search}%")
                          ->orWhere('customer_email', 'like', "%{$search}%")
                          ->orWhere('customer_phone', 'like', "%{$search}%")
                          ->orWhereHas('user', function ($uq) use ($search) {
                              $uq->where('fname', 'like', "%{$search}%")
                                 ->orWhere('lname', 'like', "%{$search}%")
                                 ->orWhere('email', 'like', "%{$search}%")
                                 ->orWhere('user_name', 'like', "%{$search}%");
                          });
                    });
                }
            }

            $this->items = $query->get();
        }

        return $this->items;
    }

    public function headings(): array
    {
        return [
            '#',
            'رقم الطلب',
            'معرف جلسة Ottu (Session ID)',
            'اسم العميل',
            'البريد الإلكتروني',
            'رقم الهاتف',
            'تفاصيل الباقة المشتراة',
            'عدد الألعاب',
            'عدد العملات',
            'المبلغ (د.ك)',
            'طريقة الدفع',
            'رقم عملية الدفع البنكية (Payment/Ref ID)',
            'حالة العملية',
            'تاريخ ووقت العملية',
            'تاريخ الدفع'
        ];
    }

    public function map($item): array
    {
        $this->rowNumber++;

        $userName = $item->user 
            ? trim(($item->user->fname ?? '') . ' ' . ($item->user->lname ?? ''))
            : ($item->customer_name ?? 'زائر');
        if (empty($userName)) {
            $userName = $item->user->user_name ?? 'مستخدم';
        }

        $email = $item->user->email ?? ($item->customer_email ?? '---');
        $phone = $item->user->phone ?? ($item->customer_phone ?? '---');
        $paymentRef = $item->gateway_payment_id ?: ($item->gateway_ref_number ?: '---');

        return [
            $this->rowNumber,
            $item->order_no ?? '---',
            $item->session_id ?? '---',
            $userName,
            $email,
            $phone,
            $item->package_title ?? '---',
            $item->games_count ?? 0,
            $item->coins_count ?? 0,
            number_format((float)$item->amount, 3),
            $item->gateway_method_name,
            $paymentRef,
            $item->status_arabic,
            $item->created_at ? $item->created_at->format('Y-m-d H:i:s') : '---',
            $item->paid_at ? $item->paid_at->format('Y-m-d H:i:s') : '---',
        ];
    }

    public function styles(Worksheet $sheet)
    {
        $sheet->setRightToLeft(true);

        return [
            1 => [
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFF']],
                'fill' => [
                    'fillType'   => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => '2B3A4A'],
                ],
                'alignment' => [
                    'horizontal' => \PhpOffice\PhpSpreadsheet\Style\Alignment::HORIZONTAL_CENTER,
                ],
            ],
        ];
    }
}
