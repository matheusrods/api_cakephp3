<?php

namespace App\Controller\Api;

use App\Controller\Api\ApiController;

class UploadController extends ApiController
{

    public function initialize()
    {
        parent::initialize();
        $this->loadComponent('RequestHandler');
    }


    public function imagensIndicadores() {

        $this->request->allowMethod(['put']);
        dd($this->RequestHandler);
    }


}
