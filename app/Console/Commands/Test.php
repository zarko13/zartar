<?php

namespace App\Console\Commands;

use App\Repositories\Command\CommandService;
use Illuminate\Console\Command;

class Test extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:test';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {

        $input = "!please mak:e me a new model.?";


        $result = CommandService::removePunctuation($input)->returnOrFail();


        dd($result);
    }
}
