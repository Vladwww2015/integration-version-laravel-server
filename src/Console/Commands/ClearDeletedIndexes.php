<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer\Console\Commands;

use Illuminate\Console\Command;
use IntegrationHelper\IntegrationVersionLaravelServer\Jobs\ClearDeletedIndexesDataQueue;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'integration-version:clear-deleted')]
class ClearDeletedIndexes extends Command
{
    public const COMMAND_NAME = 'integration-version:clear-deleted';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = self::COMMAND_NAME;

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Clear Deleted Indexes';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        (new ClearDeletedIndexesDataQueue())->handle();
//        dispatch(new ClearDeletedIndexesDataQueue());
    }
}
