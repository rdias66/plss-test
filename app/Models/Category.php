<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Traits\Uuids;

class Category extends Model
{
    use HasFactory, Uuids, SoftDeletes;

    protected $fillable = ['name'];

    public $incrementing = false;

    protected $keyType = 'string';

    public function tickets()
    {
        return $this->hasMany(Ticket::class);
    }
}
