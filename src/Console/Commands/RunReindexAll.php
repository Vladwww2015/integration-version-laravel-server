<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'integration-version:run-reindex-all')]
class RunReindexAll extends Command
{
    public const COMMAND_NAME = 'integration-version:run-reindex-all';

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
    protected $description = 'Run All Indexes';

    public function __construct(
        protected \IntegrationHelper\IntegrationVersionLaravelServer\Service\RunReindexAll $runReindexAll
    )
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->runReindexAll->execute();
    }
}
