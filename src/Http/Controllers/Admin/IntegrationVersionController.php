<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use IntegrationHelper\IntegrationVersion\IntegrationVersionItemManagerInterface;
use IntegrationHelper\IntegrationVersion\IntegrationVersionManagerInterface;
use IntegrationHelper\IntegrationVersionLaravel\Repositories\IntegrationVersionRepository;


/**
 * @inheritDoc
 */
class IntegrationVersionController extends Controller
{
    use AuthorizesRequests,
        DispatchesJobs,
        ValidatesRequests;

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
        $this->middleware('admin');

        $this->_config = request('_config');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function getIdentities()
    {
        try {
            $updatedAt = '';
            $hash = '';
            $isError = false;
            $message = 'Success';
            $this->validate(request(), [
                'source' => 'required',
                'old_hash' => 'required',
                'page' => 'required|int|gt:1',
                'limit' => 'required|int|gt:500',
                'updated_at' => 'required|date_format:Y-m-d H:i:s',
            ]);

            $source = request()->get('source');
            $oldHash = request()->get('old_hash');
            $updatedAtParam = request()->get('updated_at');
            $page = request()->get('page');
            $limit = request()->get('limit');

            $identities = [];
            $item = $this->integrationVersionRepository->getItemBySource($source);
            if($item && $item->getIdValue()) {
                $hash = $item->getHash();
                $updatedAt = $item->getUpdatedAtValue();
                $identities = $this->integrationVersionItemManager
                    ->getIdentitiesForNewestVersions($item->getIdValue(), $oldHash, $updatedAtParam, $page, $limit);

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
            'updated_at' => $updatedAt,
            'message' => $message,
            'is_error' => $isError
        ]);
    }

    public function getLatestHash()
    {
        $this->validate(request(), [
            'source' => 'required'
        ]);
        $source = request()->get('source');

        $hash = '';
        $updatedAt = '';
        $item = $this->integrationVersionRepository->getItemBySource($source);
        if($item && $item->getHash()) {
            $hash = $item->getHash();
            $updatedAt = $item->getUpdatedAtValue();
        }

        return new JsonResponse([
            'hash' => $hash,
            'updated_at' => $updatedAt
        ]);
    }

}
