<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;

/**
 * PosMetas Controller
 *
 *
 * @method \App\Model\Entity\PosMeta[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class PosMetasController extends ApiController
{
    public function initialize()
    {
        parent::initialize();

        $this->Auth->allow();
    }

    /**
     * [grafico metodo para pegar as metas dos ultimos 6 meses e desenhar um grafico]
     * @param  int    $codigo_unidade [description]
     * @return [type]                 [description]
     */
    public function grafico(int $codigo_unidade)
    {
        $data = array();

        //valida se tem os parametros corretamente
        if (empty($codigo_unidade)) {
            $error = "Parametro vazio (unidade)";
            $this->set(compact('error'));
            return;
        }

        //pega o codigo do usuario pelo token bearer
        $codigo_usuario = $this->getDadosToken()->codigo_usuario;

        if (empty($codigo_usuario)) {
            $error = "Necessário se logar novamente para gerar uma nova chave!";
            $this->set(compact('error'));
            return;
        }

        $ultimos_meses = 6;
        $data = $this->PosMetas->getGrafico($codigo_usuario, $codigo_unidade, $ultimos_meses);

        //verifica se econtrou algum erro
        if (isset($data['error'])) {
            $error = $data['error'];
            $this->set(compact('error'));
            return;
        }

        $this->set(compact('data'));
    }
}
