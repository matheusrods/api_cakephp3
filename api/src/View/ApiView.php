<?php

namespace App\View;

use Cake\Core\Exception\Exception;
use Cake\View\View;
use App\Utils\Encriptacao;

/**
 * Json View
 *
 * Default view class for rendering API response
 */
class ApiView extends View
{

    /**
     * Renders api response
     *
     * @param string|null $view Name of view file to use
     * @param string|null $layout Layout to use.
     * @return string|null Rendered content or null if content already rendered and returned earlier.
     * @throws Exception If there is an error in the view.
     */
    public function render($view = null, $layout = null)
    {
        if ($this->hasRendered) {
            return null;
        }
        
        $this->response = $this->response->withType('json');

        $this->layout = "ajax";

        $content = [
            'status' => 200
        ];

        $code = $this->response->getStatusCode();

        if ($code != 200) {

            // quando conter erros no response
            $content['status'] = $code;
            $content['error'] = $this->response->getReasonPhrase();

            if(isset($this->viewVars) && !empty($this->viewVars) && $code == 400){
                $content['result'] = $this->viewVars;
            }

        } else {

            

            if(isset($this->viewVars) && !empty($this->viewVars)){

                if(isset($this->viewVars['error'])) {
                    $content['status'] = 404;
                }

                $content['result'] = $this->viewVars;

                // quando conter paging
                if ($this->request->getParam('paging') !== false &&
                    in_array($this->response->getType(), ['application/json', 'application/xml'])
                ) {
                    $content['result']['paging'] = current($this->request->getParam('paging'));
                }
               
            }
        }

        $json_content = json_encode($content);
        
        // debug($this->request->getHeader('custom'));exit;

        //verifica se precisa criptografar o retorno
        $var_payload = $this->request->input('json_decode');
        if(!empty($this->request->getHeader('Custom'))) {
            if($this->request->getHeader('Custom')[0] == 'cript') {
                $json_content = $this->setEncript($json_content);
            }
        }
        else if(isset($var_payload->payload)) {
            $json_content = $this->setEncript($json_content);
        }
        else if(isset($this->request->getData()['payload'])) {
            $json_content = $this->setEncript($json_content);
        }

        $this->Blocks->set('content', $this->renderLayout($json_content, $this->layout));

        $this->hasRendered = true;

        return $this->Blocks->get('content');
    }

    /**
     * [setEncript metodo para criptografar o retorno dos dados]
     * @param [type] $json_content [description]
     */
    public function setEncript($json_content)
    {
        $encriptacao = new Encriptacao();
        $payload_encript['payload'] = $encriptacao->encriptar($json_content);
        $json_content = json_encode($payload_encript);

        return $json_content;


    }//fim setEncript



}