<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;

class Konsumen extends Model
{
    protected $table = 'konsumen';
    protected $fillable = [
        'konsumen_card',
        'konsumen_nama',
        'konsumen_email',
        'konsumen_no_hp',
        'konsumen_alamat'
    ];

    public function konsumenNama():Attribute
    {
        return Attribute::make(
            set: fn ($value) => strtolower($value),
            get: fn ($value) => ucwords($value),
        );
    }
}
