<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Model;

use App\Traits\Uuids;

class Ticket extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = [
        'title',
        'category_id',
        'description',
        'solution_deadline',
        'status_id',
    ];

    protected $dates = [
        'deleted_at',
        'solution_deadline',
        'solved_at',
    ];

    protected $casts = [
        'solution_deadline' => 'date',
    ];

    public $incrementing = false;

    protected $keyType = 'string';

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($ticket) {
            $ticket->solution_deadline = now()->addDays(3);
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

    public function updateStatus($newStatusId)
    {
        $status = Status::find($newStatusId);

        if ($status && in_array($status->name, ['Pendente', 'Resolvido'])) {
            $this->status_id = $status->id;

            if ($status->name === 'Resolvido') {
                $this->solved_at = now();
            }

            $this->save();
        } else {
            throw new \InvalidArgumentException("Invalid status ID provided.");
        }
    }
}
