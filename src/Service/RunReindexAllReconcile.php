<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer\Service;

use IntegrationHelper\IntegrationVersion\IntegrationVersionManagerInterface;
use IntegrationHelper\IntegrationVersion\Repository\IntegrationVersionRepositoryInterface;

class RunReindexAllReconcile
{
    public function __construct(
        protected IntegrationVersionRepositoryInterface $integrationVersionRepository,
        protected IntegrationVersionManagerInterface $integrationVersionManager
    ) {}

    public function execute(): void
    {
        foreach ($this->integrationVersionRepository->getItems() as $item) {
            try {
                $this->integrationVersionManager->executeFullReconcile($item->getSource());
            } catch (\Exception $e) {
            }
        }
    }
}
