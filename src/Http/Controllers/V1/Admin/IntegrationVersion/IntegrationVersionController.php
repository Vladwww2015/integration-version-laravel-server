<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer\Http\Controllers\V1\Admin\IntegrationVersion;

use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\DB;
use IntegrationHelper\IntegrationVersion\IntegrationVersionItemManagerInterface;
use IntegrationHelper\IntegrationVersionLaravel\Repositories\IntegrationVersionRepository;
use IntegrationHelper\IntegrationVersionLaravelServer\PrepareResultProcessor;
use Webkul\RestApi\Http\Controllers\V1\Admin\AdminController;
/**
 * @inheritDoc
 */
class IntegrationVersionController extends AdminController
{
    /**
     * Contains route related configuration
     *
     * @var array
     */
    protected $_config;

    /**
     * @param IntegrationVersionRepository $integrationVersionRepository
     * @param IntegrationVersionItemManagerInterface $integrationVersionItemManager
     */
    public function __construct(
        protected IntegrationVersionRepository $integrationVersionRepository,
        protected IntegrationVersionItemManagerInterface $integrationVersionItemManager
    ) {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function getIdentities()
    {
        $identities = [];
        $hashDateTime = '';
        $hash = '';
        $isError = false;
        $message = 'Success';
        try {
            $this->validate(request(), [
                'source' => 'required',
                'old_hash' => 'required',
                'page' => 'required|int|gt:0',
                'limit' => 'required|int|gt:499',
                'hash_date_time' => 'required|date_format:Y-m-d H:i:s',
            ]);

            $source = request()->get('source');
            $oldHash = request()->get('old_hash');
            $hashDateTimeParam = request()->get('hash_date_time');
            $page = request()->get('page');
            $limit = request()->get('limit');

            $item = $this->integrationVersionRepository->getItemBySource($source);
            if($item && $item->getIdValue()) {
                $hash = $item->getHash();
                $hashDateTime = $item->getHashDateTime();
                $identities = $this->integrationVersionItemManager
                    ->getIdentitiesForNewestVersions($item->getIdValue(), $oldHash, $hashDateTimeParam, $page, $limit);

            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $isError = true;
            $hash = '';
            $updatedAt = '';
        }

        return new JsonResponse([
            'identities' => $identities,
            'latest_hash' => $hash,
            'hash_date_time' => $hashDateTime,
            'message' => $message,
            'is_error' => $isError
        ]);
    }

    public function getDataByIdentities()
    {
        try {
            $result = [];
            $message = 'Success';
            $isError = false;
            $this->validate(request(), [
                'source' => 'required',
                'identities' => 'required|array'
            ]);
            $source = request()->get('source');

            $integrationVersion = $this->integrationVersionRepository->getItemBySource($source);
            if(!$integrationVersion || !$integrationVersion->getIdValue()) {
                throw new \Exception(sprintf('Integration version for source %s not found.', $source));
            }
            $identities = request()->get('identities');

            $result = PrepareResultProcessor::getInstance()->prepare(
                $source,
                DB::table($integrationVersion->getTableName())
                    ->whereIn($integrationVersion->getIdentityColumn(), $identities)->get()
            ); //TODO TODO TODO upd with source connection

        } catch (\Exception $e) {
            $message = $e->getMessage();
            $isError = true;
        }

        return new JsonResponse([
            'is_error' => $isError,
            'message' => $message,
            'data' => $result
        ]);
    }

    public function getLatestHash()
    {
        try {
            $message = 'Success';
            $isError = false;
            $hash = '';
            $hashDateTime = '';
            $this->validate(request(), [
                'source' => 'required'
            ]);
            $source = request()->get('source');

            $item = $this->integrationVersionRepository->getItemBySource($source);
            if($item && $item->getHash()) {
                $hash = $item->getHash();
                $hashDateTime = $item->getHashDateTime();
            }
        } catch (\Exception $e) {
            $message = $e->getMessage();
            $isError = true;
        }

        return new JsonResponse([
            'is_error' => $isError,
            'message' => $message,
            'hash' => $hash,
            'hash_date_time' => $hashDateTime
        ]);
    }

}
