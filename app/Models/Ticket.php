<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ticket extends Model
{
    use HasFactory;
    protected $fillable = [
        'title',
        'category_id',
        'description',
        'solution_deadline',
        'status_id',
    ];

    public $timestamps = false;

    protected $dates = [
        'deleted_at',
        'solution_deadline',
        'solved_at',
    ];

    public static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $ticket->solution_deadline = now()->addDays(3);
            $ticket->status_id = 1;
        });
    }

    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function status()
    {
        return $this->belongsTo(Status::class);
    }

    public function updateStatus($newStatus)
    {
        if (in_array($newStatus, Status::allowedStatuses())) {
            $this->status_id = Status::where('name', $newStatus)->first()->id;
            $this->save();
        } else {
            throw new \InvalidArgumentException("Invalid status provided.");
        }
    }
}
