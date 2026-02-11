<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EnergyReading extends Model
{
    protected $fillable = ['timestamp', 'location', 'price_eur_mwh', 'source'];
}
