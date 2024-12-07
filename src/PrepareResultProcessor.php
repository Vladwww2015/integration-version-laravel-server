<?php
namespace IntegrationHelper\IntegrationVersionLaravelServer;
class PrepareResultProcessor
{
    private static $instance = null;

    private array $preparatorProcessors = [];

    private function __construct()
    {}

    public static function getInstance(): PrepareResultProcessor
    {
        if(static::$instance === null) static::$instance = new self();

        return static::$instance;
    }

    public function addPreparatorProcess(PreparatorProcessInterface $preparator): PrepareResultProcessor
    {
        $this->preparatorProcessors[$preparator->getSource()] = $preparator;

        return $this;
    }

    public function prepare(string $source, mixed $result): mixed
    {
        $preparatorProcess = $this->preparatorProcessors[$source] ?? false;

        return $preparatorProcess ? $preparatorProcess->prepare($result) : $result;
    }
}
