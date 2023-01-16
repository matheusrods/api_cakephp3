<?php
namespace App\Services\Gr\Status;

use App\Services\AbstractService;
use Cake\Log\LogTrait;

use Cake\ORM\TableRegistry;

use App\Utils\DatetimeUtil;

class StatusService extends AbstractService {
    
    use LogTrait;
    
    protected $_options = [];

    public function __construct(array $opcoes = null)
    {
        
    }
    // exemplo de validar opcoes 
    public function validaOpcoes(array $opcoes = null)
    {
        if (empty($opcoes['to'])) {
            return ['error'=>'Destinatário requerido'];
        }
                    
        if (isset($opcoes['cc'])){
            if (is_array($opcoes['cc'])) {
                $opcoes['cc'] = implode(';', $opcoes['cc']);
            }
            if (is_array($opcoes['to'])) {
                $opcoes['cc'] = implode(';', $opcoes['cc']);
            }
        }

        return $opcoes;        
    }

    public function now( array $opcoes = [] ){

        $dt = new DatetimeUtil();
        return $dt->now();
    }

}