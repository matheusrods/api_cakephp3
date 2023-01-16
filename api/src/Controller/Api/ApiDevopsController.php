<?php
namespace App\Controller\Api;

use App\Controller\AppController;
use Cake\Event\Event;
use Cake\Log\Log;

use Cake\Utility\Security as Security;
use Firebase\JWT\JWT;

class ApiDevopsController extends AppController
{

    /**
     * beforeRender callback
     *
     * @param Event $event An Event instance
     * @return null
     */
    public function beforeRender(Event $event)
    {
        parent::beforeRender($event);
        $this->viewBuilder()->setClassName('Api');
        return null;
    }
    
}