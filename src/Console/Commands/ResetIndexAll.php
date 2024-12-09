<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'integration-version:reset-index-all')]
class ResetIndexAll extends Command
{
    public const COMMAND_NAME = 'integration-version:reset-index-all';

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
    protected $description = 'Reset All Indexes';

    public function __construct(
        protected \IntegrationHelper\IntegrationVersionLaravelServer\Service\ResetIndexAll $resetIndexAll
    ){
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->resetIndexAll->execute();
    }
}
