<?php

namespace App\Exports;

use App\Models\FeesCollection;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class FeesReportExport implements FromCollection, WithHeadings
{
    protected $from_date, $to_date, $fees_type;

    public function __construct($from_date, $to_date, $fees_type)
    {
        $this->from_date = $from_date;
        $this->to_date = $to_date;
        $this->fees_type = $fees_type;
    }

    public function collection()
    {
        $query = FeesCollection::with(['student.course','student.scheme']);

        if ($this->from_date && $this->to_date) {
            $query->whereBetween('date', [$this->from_date, $this->to_date]);
        }

        if ($this->fees_type != 'All') {
            $query->where('fees_type', $this->fees_type);
        }

        return $query->get()->map(function ($r, $key) {
            return [
                'Sl No' => $key + 1,
                'Name' => $r->student->name ?? '',
                'Mobile' => $r->student->phone ?? '',
                'Fees Date' => $r->date,
                'DOJ' => $r->student->admission_date ?? '',
                'Course' => $r->student->course->name ?? '',
                'Scheme' => $r->student->scheme->name ?? '',
                'Fees Type' => ucfirst(str_replace('_',' ',$r->fees_type)),
                'Voucher No' => $r->voucher_no,
                'Amount' => $r->amount,
            ];
        });
    }

    public function headings(): array
    {
        return [
            'Sl No',
            'Name',
            'Mobile',
            'Fees Date',
            'DOJ',
            'Course',
            'Scheme',
            'Fees Type',
            'Voucher No',
            'Amount',
        ];
    }
}
