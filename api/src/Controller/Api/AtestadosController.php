<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use App\Utils\DatetimeUtil;
use App\Utils\Comum;
use App\Validator\AtestadoValidator;
use Cake\Core\Exception\Exception;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;

/**
 * Atestados Controller
 *
 * @property \App\Model\Table\AtestadosTable $Atestados
 *
 * @method \App\Model\Entity\Atestado[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class AtestadosController extends ApiController
{
    public function initialize()
    {
        parent::initialize();
        $this->connection = ConnectionManager::get('default');
        $this->Auth->allow(['salvarAtestadoPaciente', 'editarAtestadoPaciente','getAtestadoPorId']);
    }
    /**
     * metodo para pegar os atestados ativos
     *
     * @param  int $codigo_usuario   codigo do usuario
     * @return json
     */
    public function getAtestadosAtivos($codigo_usuario)
    {

        //pega os dados do usuario
        $this->loadModel('Usuario');

        //pega os dados do usuario
        $usuario = $this->Usuario->getUsuariosDadosFuncionario($codigo_usuario);

        //variavel auxiliar para o retorno do metodo home
        $data = array();
        if(!empty($usuario)) {


            //verifica se tem o codigo de funcionarios
            if(!empty($usuario->codigo_funcionario)) {

                //atestados
                $this->loadModel('Atestados');

                //atestados ativos
                $data = $this->Atestados->getAtestados($usuario->codigo_funcionario,'A');
                $data = $this->paginate($data);
            }

        }//fim empty dados usuario

        return $this->responseJson($data);

    }//fim getAtestadosAtivos

    /**
     * metodo para pegar os atestados ativos
     *
     * @param  int $codigo_usuario   codigo do usuario
     * @return json
     */
    public function getAtestadosHistorico($codigo_usuario)
    {

        $data = array();


        //pega os dados do usuario
        $this->loadModel('Usuario');

        //pega os dados do usuario
        $usuario = $this->Usuario->getUsuariosDadosFuncionario($codigo_usuario);
        
        //vinculos em empresas
        $this->FuncionariosModel = $this->loadModel('Funcionarios');
        $codigo_cliente_vinculado = $this->FuncionariosModel->obterCodigoClienteVinculado($usuario->cpf);//array

        //variavel auxiliar para o retorno do metodo home
        if(!empty($codigo_cliente_vinculado)) {

            //verifica se tem o codigo de funcionarios
            if(!empty($usuario->codigo_funcionario)) {

                //atestados
                $this->loadModel('Atestados');

                //atestados historico
                $data = $this->Atestados->getAtestados($usuario->codigo_funcionario,'H');
                $data = $this->paginate($data);
            }

        }//fim empty dados usuario

        return $this->responseJson($data);

    }//fim getAtestadosHistorico

    /**
     * Salvar um atestado
     *
     * ex.
     *
     * {
     *  "codigo_atestado": 123456,  <--- somente se PUT/estiver atualizando
     *  "profissional":{
     *		"codigo_medico": 17969,
     *		"numero_conselho": "58150",
     *		"uf_conselho": "MG",
     *		"nome": "Adriana de brito"
     *	},
     *	"periodo":{
     *		"afastamento_em_horas": false,
     *		"range": {
     *			"de": "06/09/2019",
     *			"ate": "12/09/2019",
     *			"em_dias": 6
     *		}
     *	},
     *	"periodo":{
     *		"afastamento_em_horas": true,
     *		"data":"12/09/2019",
     *		"range": {
     *			"de": "13:00",
     *			"ate": "17:00",
     *			"em_horas": 4
     *		}
     *	},
     *	"motivo":{
     *		"medico": 1,
     *      "codigo_motivo_licenca": 17,
     *		"codigo_motivo_esocial": 1015,
     *		"descricao": "01 - Acidente/Doença do trabalho"
     *	},
     *	"cid10":{
     *		"codigo": 1899,
     *		"descricao": "* D77 Outr transt sangue e org hematop doenc COP",
     *		"sigla":"D77"
     *  },
     *	"foto_atestado":{
     *		"imagem_base64": ""
     *  }
     * }
     *
     * @param integer $codigo_usuario
     * @return array
     */
    public function salvarAtestado(int $codigo_usuario){

        $data = [];

        $payload = $this->request->getData();

        $atualizando = boolval($this->request->is('put')); // se for PUT

        // validação dos dados recebidos
        $payload_validado = (new AtestadoValidator())->validaPayloadSalvar( $payload, $codigo_usuario, $atualizando);

        if(isset($payload_validado['error'])){
            $data['error'] = $payload_validado['error']; // traz os erros da validação
            return $this->responseJson($data);
        }

        // payload pode conter varios atestados
        foreach ($payload_validado as $key => $atestado) {
            $data[] = $this->salvarPayloadDeAtestado($atestado, $codigo_usuario, $atualizando);
        }

        return $this->responseJson($data);
    }

    /**
     * POST /atestado/paciente
     *
     * Salvar um novo atestado
     *
     * @return array
     */
    public function salvarAtestadoPaciente()
    {
        $this->request->allowMethod(['post', 'put']); // aceita apenas POST e PUT

        //Declara transação
        $conn = $this->connection;

        try {
            //recebe os dados que veio do post
            $dados = $this->request->getData();

            $atestadoTable = TableRegistry::getTableLocator()->get('Atestados');
            $atestado = $atestadoTable->newEntity();

            //Inseri os dados nos campos da tabela para inserir
            $atestado->codigo_cliente_funcionario = $dados['codigo_cliente_funcionario'];
            $atestado->codigo_medico              = $dados['codigo_medico'];
            $atestado->codigo_motivo_licenca      = $dados['codigo_motivo_licenca'];
            $atestado->data_afastamento_periodo   = $dados['data_afastamento_periodo'];
            $atestado->data_retorno_periodo       = $dados['data_retorno_periodo'];
            $atestado->afastamento_em_horas       = $dados['afastamento_em_horas'];
            $atestado->hora_afastamento           = $dados['hora_afastamento'];
            $atestado->hora_retorno               = $dados['hora_retorno'];
            $atestado->codigo_usuario_inclusao    = $dados['codigo_usuario_inclusao'];
            $atestado->afastamento_em_dias        = $dados['afastamento_em_dias'];
            $atestado->codigo_empresa             = $dados['codigo_empresa'];
            $atestado->restricao                  = $dados['restricao'];
            $atestado->ativo                      = 1;
            $atestado->codigo_func_setor_cargo    = $dados['codigo_func_setor_cargo'];

            //inicia transacao
            $conn->begin();

            if ($result = $atestadoTable->save($atestado)) {
                $data = $result;
            } else {
                $conn->rollback(); //Se houver erro após salvar, remove a ultima inserção
                $data = array(
                    "save" => false,
                    "message" => "Error ao adicionar novo paciente!"
                );
            }

            //finaliza a transacao
            $conn->commit();

            $this->set(compact('data'));
        } catch (Exception $e) {
            $conn->rollback(); //Se houver erro após salvar, remove a ultima inserção
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }//fim

    /**
     * PUT /atestado/paciente/:codigo_atestado
     *
     * Salvar um novo atestado
     *
     * @return array
     */
    public function editarAtestadoPaciente()
    {
        $this->request->allowMethod(['post']); // aceita apenas POST

        //Declara transação
        $conn = $this->connection;

        try {
            //recebe os dados que veio do post
            $dados = $this->request->getData();

            $atestadoTable = TableRegistry::getTableLocator()->get('Atestados');
            $atestado = $atestadoTable->get($dados['codigo_atestado']);

            //Inseri os dados nos campos da tabela para inserir
            $atestado->codigo_cliente_funcionario = $dados['codigo_cliente_funcionario'];
            $atestado->codigo_paciente            = $dados['codigo_paciente'];
            $atestado->codigo_medico              = $dados['codigo_medico'];
            $atestado->codigo_motivo_licenca      = $dados['codigo_motivo_licenca'];
            $atestado->data_afastamento_periodo   = $dados['data_afastamento_periodo'];
            $atestado->data_retorno_periodo       = $dados['data_retorno_periodo'];
            $atestado->afastamento_em_horas       = $dados['afastamento_em_horas'];
            $atestado->hora_afastamento           = $dados['hora_afastamento'];
            $atestado->hora_retorno               = $dados['hora_retorno'];
            $atestado->codigo_usuario_inclusao    = $dados['codigo_usuario_inclusao'];
            $atestado->afastamento_em_dias        = $dados['afastamento_em_dias'];
            $atestado->codigo_empresa             = $dados['codigo_empresa'];
            $atestado->restricao                  = $dados['restricao'];
            $atestado->ativo                      = $dados['ativo'];
            $atestado->codigo_func_setor_cargo    = $dados['codigo_func_setor_cargo'];

            //inicia transacao
            $conn->begin();

            if ($result = $atestadoTable->save($atestado)) {
                $data = $result;
            } else {
                $conn->rollback(); //Se houver erro após salvar, remove a ultima inserção
                $data = array(
                    "save" => false,
                    "message" => "Error ao adicionar novo paciente!"
                );
            }

            //finaliza a transacao
            $conn->commit();

            $this->set(compact('data'));
        } catch (Exception $e) {
            $conn->rollback(); //Se houver erro após salvar, remove a ultima inserção
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }//fim
    /**
     * GET /atestado/paciente/:codigo
     *
     * Retorna os dados referente ao codigo do atestado
     *
     * @param integer $codigo
     * @return array
     */
    public function getAtestadoPorId($codigo_paciente, $codigo_medico)
    {
        $this->request->allowMethod(['get']); // aceita apenas GET

        try {
            $atestadoTable = TableRegistry::getTableLocator()->get('Atestados');
            $dados = $atestadoTable->getAtestadosPaciente($codigo_paciente, $codigo_medico);

            $this->set('dados', $dados);

        } catch (Exception $e) {
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }//fim

    /**
     * GET /estabelecimento
     *
     * Obter nomes de estabelecimentos
     *
     * @return Json
     */
    public function obterNomeEstabelecimento()
    {
        // obrigatório passar um nome
        if(empty($this->request->query('nome'))){
            $data['error'] = 'Parâmetro nome não encontrado';
            return $this->responseJson($data);
        }

        if(strlen($this->request->query('nome')) <= 2){
            $data['error'] = 'Parâmetro nome deve conter mais que 2 caracteres';
            return $this->responseJson($data);
        }

        $query_params['descricao'] = urldecode($this->request->query('nome'));

        $this->loadModel('Fornecedores');
        $data = $this->Fornecedores->obterEstabelecimentoAutoComplete($query_params);

        return $this->responseJson($data);
    }

    /**
     * salva os dados de atestados recebidos
     *
     * @param object $payload_validado
     * @param int $codigo_usuario
     * @param int $codigo_cliente
     * @param boolean $atualizando
     * @return array
     */
    private function salvarPayloadDeAtestado($payload_validado, int $codigo_usuario, bool $atualizando = false){

        // verifica se payload passa imagem
        // se existe deve ser retirado pois não é esperado pela entity
        $imagem_atestado = null;

        if(isset($payload_validado['imagem_atestado'])){
            $imagem_atestado = $payload_validado['imagem_atestado'];
            unset($payload_validado['imagem_atestado']);
        }

        if($atualizando == false){
            $atestado = $this->Atestados->newEntity($payload_validado);
        } else {

            if(!isset($payload_validado['codigo_atestado'])){
                return ['error'=>['codigo_atestado inválido']];
            }

            $codigo_atestado = $payload_validado['codigo_atestado'];
            $atestado_atualizar = $this->Atestados->get(['codigo' => $codigo_atestado]);
            $atestado = $this->Atestados->patchEntity($atestado_atualizar, $payload_validado);
        }

        //insere o codigo_empresa
        $atestado->codigo_empresa = 1;
        $atestado->ativo = 1;

        if (!$this->Atestados->save($atestado)) {
            return ['error' => $atestado->getValidationErrors()];
        }

        $codigo_atestado = $atestado->codigo;

        if(!empty($codigo_atestado)){

            //valida se existe o cid pois não é obrigatorio
            $codigo_cid = null;
            if(isset($payload_validado['codigo_cid'])) {

                $codigo_cid = $payload_validado['codigo_cid'];

                $salvarCid = $this->salvarCidPorCodigoAtestado($codigo_usuario, $codigo_atestado, $codigo_cid);

                if(isset($salvarCid['error'])){
                    return ['error'=>$salvarCid['error'], 'codigo_atestado'=> $codigo_atestado];
                }
            }

            if($imagem_atestado){
                $salvarImagem = $this->salvarAnexosAtestado($codigo_usuario, $codigo_atestado, $imagem_atestado);

                if(isset($salvarImagem['error'])){
                    return ['error'=>$salvarImagem['error'], 'codigo_atestado'=> $codigo_atestado];
                }
            }
            return ['codigo_atestado'=> $codigo_atestado];
        }

        $atestado = null;

        return ['error'=>'Atestado foi gravado mas não foi possível obter o codigo_atestado'];
    }

    private function salvarImagemNoFileServer(int $codigo_usuario, $arquivo_base64){

        if(empty($arquivo_base64)){
            return ['error'=>'Imagem não encontrada, ou não definida corretamente em base64'];
        }
        //monta o array para enviar
        $dados = array(
            'file'   => $arquivo_base64,
            'prefix' => 'nina',
            'type'   => 'base64'
        );

        //url de imagem
        $url_imagem = Comum::sendFileToServer($dados);

        if(!empty($url_imagem)) {
            //pega o caminho da imagem
            $caminho_image = array("path" => $url_imagem->{'response'}->{'path'});

            //verifica se subiu corretamente a imagem
            if(!empty($caminho_image)) {

                //url criada
                return FILE_SERVER.$caminho_image['path'];
            }
        }


        return ['error'=>'Não foi possível gravar imagem'];
    }

    private function salvarAnexosAtestado(int $codigo_usuario, int $codigo_atestado, $imagem_atestado){

        $this->loadModel('AnexosAtestados');

        if(empty($imagem_atestado)){
            return ['error'=>'Imagem não encontrada'];
        }

        // retorna url da api do fileserver com a imagem gravada
        $url_imagem = $this->salvarImagemNoFileServer($codigo_usuario, $imagem_atestado);

        // debug($url_imagem);exit;

        if(isset($url_imagem['error'])){
            return ['error'=>$url_imagem['error']];
        }

        $params = [
            'codigo_atestado'=> $codigo_atestado,
            'caminho_arquivo'=> $url_imagem,
            'codigo_usuario_inclusao'=> $codigo_usuario,
            'codigo_empresa' => 1,
        ];

        $anexos = $this->AnexosAtestados->newEntity($params);

        if (!$this->AnexosAtestados->save($anexos)) {
            $error = $anexos->getValidationErrors();
            return ['error'=>$error];
        }

        return $anexos->codigo;
    }

    private function salvarCidPorCodigoAtestado(int $codigo_usuario, int $codigo_atestado, int $codigo_cid){

        if(empty($codigo_cid)) {
            return false;
        }

        $params = [
            'codigo_atestado'=>$codigo_atestado,
            'codigo_cid'=>$codigo_cid,
            'codigo_usuario_inclusao' => $codigo_usuario,
        ];

        $this->loadModel('AtestadosCid');

        $atestado_cid = $this->AtestadosCid->newEntity($params);

        if (!$this->AtestadosCid->save($atestado_cid)) {
            $error = $atestado_cid->getValidationErrors();
            return ['error'=>$error];
        }

        return $atestado_cid->codigo;
    }

}
