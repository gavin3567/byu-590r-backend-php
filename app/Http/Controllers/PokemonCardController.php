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

        // Process S3 URLs for each card image
        foreach ($pokemonCards as $card) {
            if ($card->card_image) {
                $card->card_image = $this->getS3Url($card->card_image);
            }
        }

        return $this->sendResponse($pokemonCards, 'Pokemon cards retrieved successfully');
    }
}