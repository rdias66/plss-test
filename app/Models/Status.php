<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Traits\Uuids;

class Status extends Model
{
    use HasFactory, Uuids;

    protected $guarded = [];

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }

    public static function defaultStatus()
    {
        return self::where('name', 'Novo')->first();
    }

    public static function allowedStatuses()
    {
        return ['Pendente', 'Resolvido'];
    }
}
