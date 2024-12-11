<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer\Console\Commands;

use Illuminate\Console\Command;
use IntegrationHelper\IntegrationVersion\Model\IntegrationVersionInterface;
use IntegrationHelper\IntegrationVersion\Repository\IntegrationVersionRepositoryInterface;
use IntegrationHelper\IntegrationVersionLaravelServer\Jobs\RunReindexQueue;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'integration-version:reset-and-run-index-all-queue')]
class ResetAndRunIndexAllQueue extends Command
{
    public const COMMAND_NAME = 'integration-version:reset-and-run-index-all';

    protected $description = 'Reset All And Run Indexes with queue';

    /**
     * @param IntegrationVersionRepositoryInterface $integrationVersionRepository
     */
    public function __construct(
        protected IntegrationVersionRepositoryInterface $integrationVersionRepository
    ){
        parent::__construct();
    }

    /**
     * Execute the console command.
     */
    public function handle()
    {
        /**
         * @var $item IntegrationVersionInterface
         */
        foreach ($this->integrationVersionRepository->getItems() as $item) {
            dispatch(new RunReindexQueue($item->getSource()));
        }
    }
}
