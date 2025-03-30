<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class PokemonCard extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'name',
        'pokemon_name',
        'energy_type',
        'length',
        'weight',
        'card_number',
        'card_rarity',
        'description',
        'card_image'
    ];
}