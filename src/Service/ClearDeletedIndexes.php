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
        $idsToDelete = $sources = [];
        $locker = Cache::lock('integration-version-clear-deleted', 3600);

        if($locker->get()) {
            foreach (
                $this->integrationVersionItemManager->getItemsWithDeletedStatus() as
                $items
            ) {
                foreach ($items as $item) {
                    $idsToDelete[] = $item->getIdValue();
                    $sources[$item->getParentId()] = $item->getParentId();
                    if(count($idsToDelete) > 20000) {
                        $this->_delete($idsToDelete);
                        $idsToDelete = [];
                    }
                }
                if($idsToDelete) $this->_delete($idsToDelete);
                $idsToDelete = [];
            }

            $this->_updateHash($sources);

            $locker->release();
        }
    }

    protected function _updateHash(array $sources)
    {
        if($sources) {
            $hashDateTime = Context::getInstance()->getDateTime()->getNow();
            foreach ($sources as $sourceId) {
                $inventoryVersion = $this->integrationVersionRepository->getItemById($sourceId);
                $hash = Context::getInstance()->getHashGenerator()->generate($inventoryVersion->getSource());
                $this->integrationVersionManager->saveNewHash($inventoryVersion->getSource(), $hash, $hashDateTime);
            }
        }
    }
    protected function _delete(array $ids)
    {
        if($ids) {
            foreach (array_chunk($ids, 1000) as $chunkIds) {
                $this->integrationVersionItemManager->delete($chunkIds);
            }
        }
    }
}
