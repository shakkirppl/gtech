<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'students';

    protected $fillable = [
        'sl_no',
        'reg_no',
        'name',
        'address',
        'phone',
        'qualification',
        'admission_date',
        'course_id',
        'scheme_id',
        'total_fees',
        'status'
    ];

    protected $casts = [
        'admission_date' => 'date',
        'total_fees'     => 'decimal:2',
    ];

    /* =========================
     | Relationships
     ========================= */

    public function course()
    {
        return $this->belongsTo(Course::class)
            ->withDefault([
                'name' => '-'
            ]);
    }

    public function scheme()
    {
        return $this->belongsTo(Scheme::class)
            ->withDefault([
                'name' => '-'
            ]);
    }

    public function feesCollections()
    {
        return $this->hasMany(FeesCollection::class);
    }

    /* =========================
     | Query Scopes (Performance)
     ========================= */

    public function scopeActive($query)
    {
        return $query->where('status', 1);
    }

    public function scopeSearch($query, $search)
    {
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('reg_no', 'like', "%{$search}%")
                  ->orWhere('sl_no', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
    }
}
