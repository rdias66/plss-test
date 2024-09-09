<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Status extends Model
{
    use HasFactory;

    protected $guarded = []; // No fillable fields as the system controls the status

    // Relationships
    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    // Static method to retrieve default status
    public static function defaultStatus()
    {
        return self::where('name', 'Novo')->first();
    }

    // Static method to retrieve allowed statuses
    public static function allowedStatuses()
    {
        return ['Pendente', 'Resolvido'];
    }
}
