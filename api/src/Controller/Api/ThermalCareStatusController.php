<?php
namespace App\Controller\Api;

use App\Controller\Api\ThermalCareApiController;
use App\Utils\DatetimeUtil;

class ThermalCareStatusController extends ThermalCareApiController
{

    public function initialize()
    {
        parent::initialize();

        $this->Auth->allow(['index']);
    }

    /**
     * Verifica estado da api
     * 
     * URL: api/thermal-care/status
     * 
     * @return JSON Datetime
     */
    public function index( )
    {
        $dt = new DatetimeUtil();
        return $this->responseOK($dt->now());
    }

}