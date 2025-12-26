<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeesCollection extends Model
{
    use SoftDeletes;

    protected $fillable = [
        'student_id',
        'voucher_no',
        'date',
        'amount'
    ];

    public function student()
    {
        return $this->belongsTo(Student::class);
    }
}
