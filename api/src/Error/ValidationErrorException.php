<?php
namespace App\Error;

use Cake\Datasource\EntityInterface;
use Cake\Http\Exception\HttpException;

class ValidationErrorException extends HttpException
{
    protected $_validationErrors;

    public function __construct(EntityInterface $entity, $message = null, $code = 422)
    {
        
        $this->_validationErrors = $entity->getErrors();
        
        if ($message === null) {
            $message = 'A validation error occurred.';
        }

        parent::__construct($message, $code);
    }

    public function getValidationErrors()
    {
        return $this->_validationErrors;
    }
}