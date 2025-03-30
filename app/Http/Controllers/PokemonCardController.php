<?php

namespace App\Http\Controllers;

use App\Models\PokemonCard;
use Illuminate\Http\Request;
use App\Http\Controllers\API\BaseController;

class PokemonCardController extends BaseController
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $pokemonCards = PokemonCard::all();

        return $this->sendResponse($pokemonCards, 'Pokemon cards retrieved successfully');
    }
}