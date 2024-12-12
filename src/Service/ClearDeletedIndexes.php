<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer\Service;

use Illuminate\Support\Facades\Cache;
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
        $locker = Cache::lock('integration-version-clear-deleted', 3600);

        if($locker->get()) {
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
            $locker->forceRelease();
        }
    }
}
