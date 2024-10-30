<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer\Service;

use IntegrationHelper\IntegrationVersion\IntegrationVersionManagerInterface;
use IntegrationHelper\IntegrationVersion\Repository\IntegrationVersionRepositoryInterface;

class ResetIndexAll
{
    public function __construct(
        protected IntegrationVersionRepositoryInterface $integrationVersionRepository,
        protected IntegrationVersionManagerInterface $integrationVersionManager
    ) {}

    /**
     * Execute the console command.
     */
    public function execute()
    {
        foreach ($this->integrationVersionRepository->getItems() as $item) {
            $this->integrationVersionManager->setPendingStatus($item->getSource());
        }
    }
}
