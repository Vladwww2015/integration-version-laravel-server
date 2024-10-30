<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer\Service;

use IntegrationHelper\IntegrationVersion\IntegrationVersionManagerInterface;

class RunReindex
{
    public function __construct(
        protected IntegrationVersionManagerInterface $integrationVersionManager
    ) {}

    /**
     * Execute the console command.
     */
    public function execute(string $source)
    {
        $this->integrationVersionManager->executeFull($source);
    }
}
