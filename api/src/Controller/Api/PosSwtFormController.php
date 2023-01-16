<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;

/**
 * PosSwtForm Controller
 *
 * @property \App\Model\Table\PosSwtFormTable $PosSwtForm
 *
 * @method \App\Model\Entity\PosSwtForm[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PosSwtFormController extends ApiController
{
    
    public $connect;

    public function initialize()
    {
        parent::initialize();
        
        $this->Auth->allow();

        $this->loadModel("PosSwtFormTitulo");
        $this->loadModel("PosSwtFormQuestao");
        $this->loadModel("PosObsObservacao");
        
    }

    /**
     * [getQuestionsForm metodo para retornar as questoes do formulario]
     * @param  int    $codigo_unidade [description]
     * @param  int    $form_tipo      [description]
     * @return [type]                 [description]
     */
    public function getQuestionsForm(int $codigo_unidade, int $form_tipo)
    {
        
        $data = array();

        //valida se tem os parametros corretamente
        if(empty($codigo_unidade)){
            $error = "Parametro vazio (unidade)";
            $this->set(compact('error'));
            return;
        }

        if(empty($form_tipo)){
            $error = "Parametro vazio (form_tipo)";
            $this->set(compact('error'));
            return;
        }

        $val_form_tipo = array('1','2');
        if(!in_array($form_tipo,$val_form_tipo)) {
            $error = "Valor não corresponde ao esperado (form_tipo)";
            $this->set(compact('error'));
            return;
        }

        //busca os dados do formulario montando o json
        $questoes = $this->PosSwtForm->getQuestoesForm($codigo_unidade,$form_tipo);
        
        //verifica se econtrou algum erro
        if(isset($questoes['error'])) {
            $error = $questoes['error'];
            $this->set(compact('error'));
            return;
        }
        $data = $questoes;

        $this->set(compact('data'));
    } //fim getQuestionsForm

    /**
     * [getDadosObservadorEhs metodo para trazer os dados da area do observador ]
     * @param  int    $codigo_unidade [description]
     * @return [type]                 [description]
     */
    public function getDadosObservadorEhs(int $codigo_unidade)
    {

        $data = array();

        //valida se tem os parametros corretamente
        if(empty($codigo_unidade)){
            $error = "Parametro vazio (unidade)";
            $this->set(compact('error'));
            return;
        }

        $dados_obs = $this->PosObsObservacao->getDadosSwtObs($codigo_unidade);

        //verifica se econtrou algum erro
        if(isset($dados_obs['error'])) {
            $error = $dados_obs['error'];
            $this->set(compact('error'));
            return;
        }

        //seta o modelo de retorno do endpoint
        $data = array(
            "classificacao" => [array(
                "codigo" => '',
                "descricao" => '',
                "cor" => '',
                "total" => ''
            )],
            "categoria" => [array(
                "codigo" => '',
                "classificacao" => '',
                "cor" => '',
                "categoria" => '',
                "total" => ''
            )]
        );
        if(!empty($dados_obs)) {
            $data = $dados_obs;
        }

        $this->set(compact('data'));


    }// getDadosObservadorEhs

    
}
