<?php

namespace App\Controller\Api;

use App\Controller\Api\ApiController;

use App\Utils\DatetimeUtil;

class StatusController extends ApiController
{

    public function initialize()
    {
        parent::initialize();

        $this->Auth->allow(['index']);
    }

    public function index( )
    {
        $dt = new DatetimeUtil();
        return $this->responseOK($dt->now());
    }
}