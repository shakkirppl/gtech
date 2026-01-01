<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Student extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'students';

    /* =========================
     | STATUS CONSTANTS
     ========================= */
    public const STATUS_PRESENT   = 'Present';
    public const STATUS_LEAVE     = 'Leave';
    public const STATUS_COMPLETED = 'Completed';

    /* =========================
     | MASS ASSIGNABLE
     ========================= */
    protected $fillable = [
        'reg_no',
        'name',
        'address',
        'phone',
        'qualification',
        'admission_date',
        'course_id',
        'scheme_id',
        'course_fee',
        'exam_fee',
        'material_fee',
        'voucher_fee',
        'others_fee',
        'total_fees',
        'narration',
        'status'
    ];

    /* =========================
     | CASTS
     ========================= */
    protected $casts = [
        'admission_date' => 'date',
        'total_fees'     => 'decimal:2',
    ];

    /* =========================
     | RELATIONSHIPS
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
public function fees_collections()
{
    return $this->hasMany(FeesCollection::class, 'student_id');
}
    /* =========================
     | QUERY SCOPES
     ========================= */

    // Students currently attending
    public function scopePresent($query)
    {
        return $query->where('status', self::STATUS_PRESENT);
    }

    // Students on leave
    public function scopeOnLeave($query)
    {
        return $query->where('status', self::STATUS_LEAVE);
    }

    // Students completed course
    public function scopeCompleted($query)
    {
        return $query->where('status', self::STATUS_COMPLETED);
    }

    // Search scope (optimized for Select2 / large data)
    public function scopeSearch($query, $search)
    {
        if (!empty($search)) {
            $query->where(function ($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('reg_no', 'like', "%{$search}%")
                  ->orWhere('sl_no', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }
    }

    /* =========================
     | ACCESSORS (OPTIONAL)
     ========================= */

    // Human readable status badge class
    public function getStatusBadgeAttribute()
    {
        return match ($this->status) {
            self::STATUS_PRESENT   => 'success',
            self::STATUS_LEAVE     => 'warning',
            self::STATUS_COMPLETED => 'primary',
             self::STATUS_CANCELLED => 'danger',   // 👈 added
            default                => 'secondary',
        };
    }
}
