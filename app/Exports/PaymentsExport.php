<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class PaymentsExport implements FromCollection, WithHeadings, WithMapping
{
    protected $records;

    public function __construct($records)
    {
        $this->records = $records;
    }

    public function collection()
{
    return collect($this->records); 
}

    public function headings(): array
    {
        return [
            'S.No', 'ItemID', 'Purchased Item Name', 'User ID', 'Plan Startdate', 'Plan Enddate',
            'Subscription Type', 'Payment Gateway', 'TransactionID', 'Paid by parent', 'Paid UserID',
            'Cost', 'Coupon Applied', 'CouponID', 'Actual Cost', 'Discount Amount',
            'After Discount', 'Paid Amount', 'Payment status', 'Created datetime', 'Updated datetime'
        ];
    }

    public function map($item): array
    {
        static $count = 1;

        $item_type = ucfirst($item->plan_type);
        if ($item->plan_type == 'combo') {
            $item_type = 'Exam Series';
        }

        return [
            $count++, $item->item_id, $item->item_name, $item->user_id, $item->start_date,
            $item->end_date, $item_type, $item->payment_gateway, $item->transaction_id,
            $item->paid_by_parent, $item->paid_by, $item->cost, $item->coupon_applied,
            $item->coupon_id, $item->actual_cost, $item->discount_amount,
            $item->after_discount, $item->paid_amount, $item->payment_status,
            $item->created_at, $item->updated_at
        ];
    }
}

