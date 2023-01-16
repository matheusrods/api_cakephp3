<?php
namespace App\Controller\Api;

use App\Controller\Api\ThermalCareApiController;
use App\Utils\DatetimeUtil;

class ThermalCareHomeController extends ThermalCareApiController
{

    /**
     * Verifica estado da api
     * 
     * URL: api/thermal-care/home/:codigo_usuario
     * 
     * @return JSON Datetime
     */
    public function index( )
    {
        $data = [];
        
        return $this->responseJson($data);
    }

}