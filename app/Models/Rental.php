<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Carbon\Carbon;

class Rental extends Model
{
    protected $fillable = [
        'book_id',
        'student_id',
        'librarian_id',
        'rented_at',
        'returned_at',
    ];
    protected $appends = ['days_rented', 'is_overdue'];


    public function book()
    {
        return $this->belongsTo(Book::class);
    }

    public function student()
    {
        return $this->belongsTo(User::class, 'student_id');
    }

    public function librarian()
    {
        return $this->belongsTo(User::class, 'librarian_id');
    }
    public function getDaysRentedAttribute()
    {

        return Carbon::parse($this->rented_at)->diffInDays(now(), false);
    }

    public function getIsOverdueAttribute()
    {
        $limitDays = 10; //
        return $this->days_rented > $limitDays;
    }
}
