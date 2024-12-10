<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer\Service;

use IntegrationHelper\IntegrationVersion\Context;
use IntegrationHelper\IntegrationVersion\IntegrationVersionManagerInterface;
use IntegrationHelper\IntegrationVersion\IntegrationVersionItemManagerInterface;
use IntegrationHelper\IntegrationVersion\Model\IntegrationVersionItemInterface;

class ClearDeletedIndexes
{
    public function __construct(
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
            $item
        ) {
            $idsToDelete[] = $item->getIdValue();
            $sources[$item->getParentId()] = $item->getParentId();
        }
        $hashDateTime = Context::getInstance()->getDateTime()->getNow();

        if($idsToDelete) {
            foreach (array_chunk($idsToDelete, 1000) as $chunk) {
                $this->integrationVersionItemManager->delete($chunk);
            }
            if($sources) {
                foreach ($sources as $source) {
                    $hash = Context::getInstance()->getHashGenerator()->generate($source);
                    $this->integrationVersionManager->saveNewHash($source, $hash, $hashDateTime);
                }
            }
        }
    }
}
