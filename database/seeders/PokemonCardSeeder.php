<?php

namespace Database\Seeders;

use App\Models\PokemonCard;
use Illuminate\Database\Seeder;

class PokemonCardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $cards = [
            [
                'name' => 'Charizard GX',
                'pokemon_name' => 'Charizard',
                'energy_type' => 'Fire',
                'length' => '1.7 m',
                'weight' => '90.5 kg',
                'card_number' => '9/68',
                'card_rarity' => 'Ultra Rare',
                'description' => 'A fire-type Pokemon known for its powerful flame attacks and iconic status.',
                'card_image' => 'images/charizard.jpg'
            ],
            [
                'name' => 'Pikachu V',
                'pokemon_name' => 'Pikachu',
                'energy_type' => 'Electric',
                'length' => '0.4 m',
                'weight' => '6.0 kg',
                'card_number' => '43/192',
                'card_rarity' => 'Rare',
                'description' => 'An electric-type Pokemon that serves as the mascot of the franchise.',
                'card_image' => 'images/pikachu.jpg'
            ],
            [
                'name' => 'Blastoise EX',
                'pokemon_name' => 'Blastoise',
                'energy_type' => 'Water',
                'length' => '1.6 m',
                'weight' => '85.5 kg',
                'card_number' => '17/83',
                'card_rarity' => 'Ultra Rare',
                'description' => 'A water-type Pokemon with powerful water cannons on its shell.',
                'card_image' => 'images/blastoise.jpg'
            ],
            [
                'name' => 'Venusaur VMAX',
                'pokemon_name' => 'Venusaur',
                'energy_type' => 'Grass',
                'length' => '2.0 m',
                'weight' => '100.0 kg',
                'card_number' => '2/72',
                'card_rarity' => 'Ultra Rare',
                'description' => 'A grass/poison-type Pokemon with a large flower on its back.',
                'card_image' => 'images/venusaur.jpg'
            ],
            [
                'name' => 'Mewtwo GX',
                'pokemon_name' => 'Mewtwo',
                'energy_type' => 'Psychic',
                'length' => '2.0 m',
                'weight' => '122.0 kg',
                'card_number' => '39/73',
                'card_rarity' => 'Ultra Rare',
                'description' => 'A psychic-type legendary Pokemon created by genetic manipulation.',
                'card_image' => 'images/mewtwo.jpg'
            ]
        ];

        foreach ($cards as $card) {
            PokemonCard::create($card);
        }
    }
}