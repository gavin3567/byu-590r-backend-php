<?php

namespace App\Console\Commands;

use App\Mail\PokemonCardsMasterList;
use App\Models\PokemonCard;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;

class PokemonCardReport extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'report:pokemon-cards {--email=}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Returns a list of all checked out Pokemon cards to the admin user';

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $sendToEmail = $this->option('email');

        if(!$sendToEmail) {
            $this->error('Email address is required!');
            return Command::FAILURE;
        }

        // Get all cards that have copies checked out
        $checkedOutCards = PokemonCard::where('checked_qty', '>', 0)->get();

        $this->info("Found " . $checkedOutCards->count() . " Pokemon cards with checked out copies.");

        if ($checkedOutCards->count() > 0) {
            // Send the list of checked out Pokemon cards to management
            Mail::to($sendToEmail)->send(new PokemonCardsMasterList($checkedOutCards));
            $this->info("Report email sent to $sendToEmail");
        } else {
            $this->info("No checked out Pokemon cards found. No email sent.");
        }

        return Command::SUCCESS;
    }
}