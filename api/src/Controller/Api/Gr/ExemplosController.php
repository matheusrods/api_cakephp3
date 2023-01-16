<?php
namespace App\Controller\Api\Gr;

use App\Controller\Api\ApiGrController;
use App\Utils\DatetimeUtil;

use App\Services\Gr\Status\StatusService;

class ExemplosController extends ApiGrController
{
    
    public function index( )
    {
        $status = new StatusService();
        return $this->responseOK($status->now());
    }

}
