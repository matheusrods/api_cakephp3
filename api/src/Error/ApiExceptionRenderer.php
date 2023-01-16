<?php
namespace App\Error;

use Cake\Error\ExceptionRenderer;
use App\Error\ValidationErrorException;

class ApiExceptionRenderer extends ExceptionRenderer
{
 // HttpExceptions automatically map to methods matching the inflected variable name
 public function validationError(ValidationErrorException $exception)
 {
     $code = $this->_code($exception);
     $method = $this->_method($exception);
     $template = $this->_template($exception, $method, $code);

     $message = $this->_message($exception, $code);
     $url = $this->controller->request->getRequestTarget();

     $response = $this->controller->getResponse();
     foreach ((array)$exception->responseHeader() as $key => $value) {
         $response = $response->withHeader($key, $value);
     }
     $this->controller->setResponse($response->withStatus($code));

     $viewVars = [
         'message' => $message,
         'url' => h($url),
         'error' => $exception,
         'code' => $code,
         // set the errors as a view variable
         'errors' => $exception->getValidationErrors(),
         '_serialize' => [
             'message',
             'url',
             'code',
             'errors' // mark the variable as to be serialized
         ]
     ];
     $this->controller->set($viewVars);

     return $this->_outputMessage($template);
 }
}