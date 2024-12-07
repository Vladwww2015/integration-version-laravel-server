<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer;

interface PreparatorProcessInterface
{
    public function getSource(): string;

    public function prepare(mixed $data): mixed;
}
