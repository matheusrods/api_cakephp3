<?php

namespace App\Exception;

//use Exception;
use Cake\Core\Exception\Exception;

Class ModelException extends Exception{

    /**
     * {@inheritDoc}
     */
    protected $_messageTemplate = 'Erro %s::%s() não encontrado, ou não acessível.';

    /**
     * {@inheritDoc}
     */
    protected $_defaultCode = 400;
}