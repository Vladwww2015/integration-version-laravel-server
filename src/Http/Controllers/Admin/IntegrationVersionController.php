<?php

namespace IntegrationHelper\IntegrationVersionLaravelServer\Http\Controllers\Admin;

use Illuminate\Http\JsonResponse;
use Illuminate\Routing\Controller;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use IntegrationHelper\IntegrationVersion\IntegrationVersionItemManagerInterface;


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
     * @param IntegrationVersionItemManagerInterface $integrationVersionItemManager
     */
    public function __construct(
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
        $this->validate(request(), [
            'name' => 'required',
            'entity_type' => 'required',
            'data' => 'required'
        ]);

        $identities = [];


        return new JsonResponse($identities);
    }

}
