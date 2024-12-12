<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer\Service;

use IntegrationHelper\IntegrationVersion\Context;
use IntegrationHelper\IntegrationVersion\IntegrationVersionManagerInterface;
use IntegrationHelper\IntegrationVersion\IntegrationVersionItemManagerInterface;
use IntegrationHelper\IntegrationVersion\Model\IntegrationVersionItemInterface;
use IntegrationHelper\IntegrationVersion\Repository\IntegrationVersionRepositoryInterface;

class ClearDeletedIndexes
{
    public function __construct(
        protected IntegrationVersionRepositoryInterface $integrationVersionRepository,
        protected IntegrationVersionManagerInterface $integrationVersionManager,
        protected IntegrationVersionItemManagerInterface $integrationVersionItemManager
    ) {}

    public function execute(): void
    {
        /**
         * @var $item IntegrationVersionItemInterface
         */
        $idsToDelete = [];
        $sources = [];

        foreach (
            $this->integrationVersionItemManager->getItemsWithDeletedStatus() as
            $items
        ) {
            foreach ($items as $item) {
                $idsToDelete[] = $item->getIdValue();
                $sources[$item->getParentId()] = $item->getParentId();
            }
        }
        $hashDateTime = Context::getInstance()->getDateTime()->getNow();

        if($idsToDelete) {
            foreach (array_chunk($idsToDelete, 1000) as $chunk) {
                $this->integrationVersionItemManager->delete($chunk);
            }
            if($sources) {
                foreach ($sources as $sourceId) {
                    $inventoryVersion = $this->integrationVersionRepository->getItemById($sourceId);
                    $hash = Context::getInstance()->getHashGenerator()->generate($inventoryVersion->getSource());
                    $this->integrationVersionManager->saveNewHash($inventoryVersion->getSource(), $hash, $hashDateTime);
                }
            }
        }
    }
}
