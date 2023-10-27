<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CountryLog extends Model
{
    use HasFactory;

    protected $fillable = [
        'country_id',
        'old_values',
        'new_values'
    ];

    // relations
    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
