<?php
namespace App\Controller\Api;

use App\Controller\Api\ThermalCareApiController;
use App\Utils\DatetimeUtil;

class ThermalCareMenuController extends ThermalCareApiController
{

    /**
     * Obter menus para aplicação
     * 
     * GET api/thermal-care/menu
     * 
     * @return JSON Datetime
     */
    public function obterLista( $codigo_usuario = null )
    {        
        $data = [];

        return $this->responseJson($data);
    }

}