<?php
namespace App\Controller\Api\Lyn;

use App\Controller\Api\ApiLynController;
use App\Utils\DatetimeUtil;

class StatusController extends ApiLynController
{
    public function initialize()
    {
        parent::initialize();
        $this->Auth->allow(['index']);
    }

    public function index( )
    {
        $dt = new DatetimeUtil();
        // TODO - definir retorno com André
        return $this->responseOK($dt->now());
    }

}
