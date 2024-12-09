<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer\Console\Commands;

use Illuminate\Console\Command;
use IntegrationHelper\IntegrationVersionLaravelServer\Jobs\RunReindexQueue;
use Symfony\Component\Console\Attribute\AsCommand;

#[AsCommand(name: 'integration-version:run-reindex')]
class RunReindex extends Command
{
    public const COMMAND_NAME = 'integration-version:run-reindex';

    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = self::COMMAND_NAME . ' {--source=* }';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run Index by Source';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $source = $this->option('source');
        $source = is_array($source) ? current($source) : false;
        if(!$source) {
            throw new \Exception('Param source is required');
        }

        dispatch(new RunReindexQueue($source));
    }
}
