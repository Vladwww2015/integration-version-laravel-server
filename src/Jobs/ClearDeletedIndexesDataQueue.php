<?php
namespace IntegrationHelper\IntegrationVersionLaravelServer\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ClearDeletedIndexesDataQueue implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * @return void
     */
    public function handle()
    {
        app(\IntegrationHelper\IntegrationVersionLaravelServer\Service\ClearDeletedIndexes::class)->execute();
    }
}
