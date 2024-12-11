<?php
namespace IntegrationHelper\IntegrationVersionLaravelServer\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Contracts\Queue\ShouldBeUniqueUntilProcessing;

class RunReindexQueue implements ShouldQueue, ShouldBeUniqueUntilProcessing
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @param string $source
     */
    public function __construct(
        protected string $source
    ) {}

    /**
     * @return void
     */
    public function handle()
    {
        app(\IntegrationHelper\IntegrationVersionLaravelServer\Service\ResetIndex::class)->execute($this->source);
        app(\IntegrationHelper\IntegrationVersionLaravelServer\Service\RunReindex::class)->execute($this->source);
    }
}
