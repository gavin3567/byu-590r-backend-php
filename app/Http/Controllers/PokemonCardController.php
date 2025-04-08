<?php
namespace App\Http\Controllers;

use App\Models\PokemonCard;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'pokemon_name' => 'required|string|max:255',
            'energy_type' => 'required|string|max:50',
            'card_image' => 'required|image|max:2048', // Required image
            'inventory_total_qty' => 'required|integer|min:1',
            // Add other validation rules as needed
        ]);

        // Handle image upload to S3
        $path = $request->file('card_image')->store('pokemon-cards', 's3');

        // Create Pokemon Card
        $pokemonCard = PokemonCard::create([
            'name' => $request->name,
            'pokemon_name' => $request->pokemon_name,
            'energy_type' => $request->energy_type,
            'length' => $request->length,
            'weight' => $request->weight,
            'card_number' => $request->card_number,
            'card_rarity' => $request->card_rarity,
            'description' => $request->description,
            'card_image' => $path,
            'inventory_total_qty' => $request->inventory_total_qty ?? 1,
            'checked_qty' => 0,
        ]);

        // Process image URL for response
        $pokemonCard->card_image = $this->getS3Url($pokemonCard->card_image);

        return $this->sendResponse($pokemonCard, 'Pokemon card created successfully');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id)
    {
        $pokemonCard = PokemonCard::findOrFail($id);

        $request->validate([
            'name' => 'required|string|max:255',
            'pokemon_name' => 'required|string|max:255',
            'energy_type' => 'required|string|max:50',
            'card_image' => 'nullable|image|max:2048', // Optional image
            'inventory_total_qty' => 'required|integer|min:1',
            // Add other validation rules
        ]);

        $data = $request->except('card_image');

        // Handle image update if provided
        if ($request->hasFile('card_image')) {
            // Delete old image from S3 if exists
            if ($pokemonCard->card_image) {
                Storage::disk('s3')->delete($pokemonCard->card_image);
            }

            // Upload new image
            $data['card_image'] = $request->file('card_image')->store('pokemon-cards', 's3');
        }

        $pokemonCard->update($data);

        // Process image URL for response
        if ($pokemonCard->card_image) {
            $pokemonCard->card_image = $this->getS3Url($pokemonCard->card_image);
        }

        return $this->sendResponse($pokemonCard, 'Pokemon card updated successfully');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $pokemonCard = PokemonCard::findOrFail($id);

        // Check if the card has checked out copies
        if ($pokemonCard->checked_qty > 0) {
            return $this->sendError('Cannot delete card with checked out copies');
        }

        // Delete image from S3
        if ($pokemonCard->card_image) {
            Storage::disk('s3')->delete($pokemonCard->card_image);
        }

        $pokemonCard->delete();

        return $this->sendResponse([], 'Pokemon card deleted successfully');
    }

    /**
     * Checkout a Pokemon card.
     */
    public function checkoutCard($id)
    {
        $pokemonCard = PokemonCard::findOrFail($id);

        if ($pokemonCard->checked_qty >= $pokemonCard->inventory_total_qty) {
            return $this->sendError('No copies available to checkout');
        }

        $pokemonCard->checked_qty += 1;
        $pokemonCard->save();

        // Process image URL for response
        if ($pokemonCard->card_image) {
            $pokemonCard->card_image = $this->getS3Url($pokemonCard->card_image);
        }

        return $this->sendResponse($pokemonCard, 'Pokemon card checked out successfully');
    }

    /**
     * Return a Pokemon card.
     */
    public function returnCard($id)
    {
        $pokemonCard = PokemonCard::findOrFail($id);

        if ($pokemonCard->checked_qty <= 0) {
            return $this->sendError('No copies checked out to return');
        }

        $pokemonCard->checked_qty -= 1;
        $pokemonCard->save();

        // Process image URL for response
        if ($pokemonCard->card_image) {
            $pokemonCard->card_image = $this->getS3Url($pokemonCard->card_image);
        }

        return $this->sendResponse($pokemonCard, 'Pokemon card returned successfully');
    }
}