<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Str;


/**
 * Class GenerateAppKeyCommand
 *
 * @category Console_Command
 * @package  App\Console\Commands
 */
class GenerateAppKeyCommand extends Command
{
    /**
     * The console command name.
     *
     * @var string
     */
    protected $signature = 'key:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate new API key';


    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        echo Str::random(32).PHP_EOL;
        return true;
    }
}