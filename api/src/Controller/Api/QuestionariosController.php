<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;

/**
 * Questionarios Controller
 *
 * @property \App\Model\Table\QuestionariosTable $Questionarios
 *
 * @method \App\Model\Entity\Questionario[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class QuestionariosController extends ApiController
{
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function view($codigo_usuario)
    {
        $data = $this->obterListaQuestionarios( $codigo_usuario );

        $this->set(compact('data'));
    }

    public function perguntasRespostas($codigo_usuario, $codigo_questionario)
    {

        $data = $this->obterListaQuestoes($codigo_usuario, $codigo_questionario);
        // debug($data);exit;
        $this->set(compact('data'));
    }

    /**
     * Exemplo Payload
     * {
     *	"questionario":1,
     *	"respostas":[
     *	{
     * 		"codigo_pergunta": "4",
     *		"resposta":
     *			{
     *				"codigo": "9"
     *			}
     *	},
     *	{
     *		"codigo_pergunta": "12",
     *		"resposta": [
     *			{
     *				"codigo": "14"
     *			}
     *		]
     *	}
     * ]
     * }
     *
     * @param int $codigo_usuario
     * @param int $codigo_questionario
     * @return void
     */
    public function salvarRespostaUsuario($codigo_usuario, $codigo_questionario)
    {
        $data = array();
        $payload = $this->request->getData();

        // validação dos dados recebidos
        $payload_validado = $this->validaPayloadDeRespostas($payload, $codigo_usuario, $codigo_questionario);

        if(!empty($payload_validado) && isset($payload_validado['error'])){
            $error = $payload_validado['error'];
            $this->set(compact('error'));
            return;
        }

        //PARA ESSES CODIGOS ESTA DEIXAR O PAYLOAD BAGUNCADO PRECISA AJEITAR WILL
        if($codigo_questionario == 13 || $codigo_questionario == 16) {}
        else{

            //remonta as questoes e respostas
            $payload = $this->Questionarios->remontaRespostas($codigo_usuario, $codigo_questionario, $payload);
        }

        // atibuir respostas/questoes a um usuario utilizando Payload recebido
        $data = $this->salvarPayloadDeRespostas($payload, $codigo_usuario);

        if(!isset($data['finalizado']) || !$data['finalizado']){
            if(!empty($this->salvarPayloadDeRespostas($payload, $codigo_usuario))){
                $error = 'Não foi possível salvar as respostas';
                $this->set(compact('error'));
                return;
            }
        }

        //lyn covid
        $questionario_lyn = array(13,16);
        if(in_array($codigo_questionario, $questionario_lyn)) {             
            $this->loadModel('Respostas');
            $this->Respostas->setUsuarioGrupoCovid($codigo_usuario);
        }//questionario do covid
        
        $this->set(compact('data'));

    }


    // METODOS PRIVADOS

    /**
     * Verifica se uma questao existe em um questionario
     *
     * @param array $questionarios
     * @param integer $codigo_questao
     * @return bool
     */
    private function validarSeQuestaoExiste(array $questionarios, int $codigo_questao){

        $iterator = new \RecursiveArrayIterator($questionarios);
        $recursive = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
        $return = false;
        foreach ($recursive as $key => $value) {

            // debug($return); 
            // debug($value['codigo_pergunta']);
            // debug($codigo_questao);

            if(empty($return) && isset($value['codigo_pergunta']) && $value['codigo_pergunta'] == $codigo_questao){
                $return = true;
            }
        }
        return $return;
    }

    /**
     * Valida dados recebidos
     *
     * @param object $payload
     * @param int $codigo_usuario
     * @param int $codigo_questionario
     * @return array
     */
    private function validaPayloadDeRespostas($payload, int $codigo_usuario, int $codigo_questionario){
        
        // se não tem payload, posso fazer nada meu fiu
        if(empty($payload)){
            $this->log(sprintf("%s :: Payload não encontrado", __METHOD__));
            return ['error' => 'Payload não encontrado'];
        }

        // obter dados se este questionário já foi preenchido dentro de alguma regra
        $questionario_historico = $this->validaQuestionarioHistoricoAtivo($codigo_usuario, $codigo_questionario);

        // se tem historico ativo então não permitir prosseguir
        if(!empty($questionario_historico)){
            $this->log(sprintf("%s :: Questionário já preenchido", __METHOD__));
            return ['error' => 'Questionário já preenchido'];
        }

        $payload_encoded = json_decode(json_encode( $payload, JSON_FORCE_OBJECT ));

        if(isset($payload_encoded->questionario) && $payload_encoded->questionario != $codigo_questionario ){
            $this->log(sprintf("%s :: Payload com codigo de questionário irregular", __METHOD__));
            return ['error' => 'Payload com codigo de questionário irregular'];
        }

        // obter dados sobre o questionario
        $questionario_dados = $this->obterListaQuestoes($codigo_usuario, $codigo_questionario);

        if(empty($questionario_dados)){
            $this->log(sprintf("%s :: Questionário não encontrado", __METHOD__));
            return ['error' => 'Questionário não encontrado'];
        }

        // se contem respostas, valide-as
        if(isset($payload_encoded->respostas)) {

            // quantidade de questoes no questionario [por $codigo_questionario]
            $questionario_quantidade_respostas = count($questionario_dados['perguntas']);

            // quantidade de respostas recebidas no payload
            $payload_quantidade_respostas = count((array)$payload_encoded->respostas);

            // COMENTADO ESTA VALIDAÇÃO PORQUE TEMOS RESPOSTAS QUE SÃO RESPONDIDAS AUTOMATICAMENTE RETIRANDO DAS PERGUNTAS 
            // ONDE A QUANTIDADE DE RESPOSTAS PODEM NÃO BATER
            // 
            // se não bater a mesma quantidade pode haver algo errado, falta questão payload incompleto
            // $retirar_questionario = array(13,16);
            // if(!in_array($codigo_questionario,$retirar_questionario)){//questionario covid
            //     if($questionario_quantidade_respostas != $payload_quantidade_respostas){
            //         $this->log(sprintf("%s :: Payload com numero/quantidade de questões irregular", __METHOD__));
            //         return ['error' => 'Payload com numero/quantidade de questões irregular'];
            //     }
            // }
            // 
            // FIM COMENTARIO DE QUANTIDADE DE RESPOSTAS
            
            // debug($payload_encoded->respostas);

            // se o código de perguntas não existir no questionario
            // foreach ($payload_encoded->respostas as $key => $resposta) {

            //     $codigo_questao = isset($resposta->codigo_pergunta) ? $resposta->codigo_pergunta : null;

            //     // validar se questao existe no questionário
            //     $questao_existe = $this->validarSeQuestaoExiste($questionario_dados, $codigo_questao);
            //     // debug($questao_existe);

            //     if(!$questao_existe){
            //         $error = "Questão {$codigo_questao} não encontrada no Questionário";
            //         $this->log(sprintf("%s :: %s", __METHOD__, $error));
            //         return ['error' => $error];
            //     }

            //     // TODO - validar duplicidade (evitar mandar quantidade certa com resposta duplicada)
            // }
        }

        return [ 'message' => 'Payload válido' ];
    }

    /**
     *
     * Salvar respostas de um questionario
     *
     * ex Payload
     * {
     * 	"questionario":1,
     * 	"respostas":[
     * 		{
     * 			"codigo_pergunta": "4",
     * 			"codigo_resposta": "9"
     * 		},
     * 		{
     * 			"codigo_pergunta": "12",
     * 			"codigo_resposta": "14"
     * 		},
     * 		{
     * 			"codigo_pergunta": "12",
     * 			"codigo_resposta": "14"
     * 		}
     * 	]
     * }
     * @param object $payload
     * @param int $codigo_usuario
     * @return void
     */
    private function salvarPayloadDeRespostas($payload, int $codigo_usuario){

        $codigo_questionario = $payload['questionario'];
        $questionario_respostas = $payload['respostas'];
        $latitude = isset($payload['latitude']) ? $payload['latitude'] : null;
        $longitude = isset($payload['longitude']) ? $payload['longitude'] : null;

        // debug($questionario_respostas);exit;

        $data = $this->Questionarios->salvarRespostas($codigo_usuario, $codigo_questionario, $questionario_respostas, $latitude, $longitude);
        //print_r($data);//Array ( [pontos] => 0 [descricao] => Risco não mensurado [finalizado] => 1 )      

        if(isset($data['finalizado']) && $data['finalizado'] == true && isset($data['descricao'])){
            
            $data['codigo_cor'] = $this->avaliarCores($data['descricao']);

            //calcula o percentual
            $data['percentual'] = $this->Questionarios->getPercentual($codigo_usuario,$codigo_questionario);
            if($codigo_questionario == 9){
                $data['percentual'] =$this->porcentagemCores($data['codigo_cor']);
            }

            $data['textos'] = $this->avaliarRetornoAoSalvarRespostas($data,$codigo_questionario, $questionario_respostas);

            if(isset($data['textos']['codigo_cor'])){
                $data['codigo_cor'] = $data['textos']['codigo_cor'];
                unset($data['textos']['codigo_cor']);
            }
            
        }

        return $data;
    }

    private function porcentagemCores(string $codigo_cor){

        switch ($codigo_cor) {
            case 1://verde
                $porcentagem = 15;
                break;
            case 2://laranja
                $porcentagem = 50;
                break;
            case 4: //vermelho
                $porcentagem = 90;
                break;
            default:
                $porcentagem = 1;
                break;
        }

        return $porcentagem;
    }

    private function avaliarCores(string $descricao){

        switch ($descricao) {
            case 'ALTO RISCO':
                $codigo_cor = 4; //vermelho
                break;
            case 'RISCO MODERADO':
                $codigo_cor = 2; //laranja
                break;
            case 'BAIXO RISCO':
                $codigo_cor = 1; //verde
                break;
            case 'Improvável':
                $codigo_cor = 1; //verde
                break;
            case 'Possível (Questionável)':
                $codigo_cor = 2; //verde
                break;
            case 'Provável':
                $codigo_cor = 4; //verde
                break;
            default:
                $codigo_cor = 1; //verde
                break;
        }

        return $codigo_cor;
    }

    private function avaliarRetornoAoSalvarRespostas(array $resultado, $codigo_questionario, $questionario_respostas){

        $this->loadModel('CaracteristicasQuestionarios');

        //pega as respostas, ou seja os textos
        $codigos_respostas = array();
        //varre as respostas
        foreach($questionario_respostas AS $resp) {
            $codigos_respostas[] = $resp['codigo_resposta'];
        }

        //chama o metodo para trazer os textos
        $textos_respostas = $this->CaracteristicasQuestionarios->getTextosQuestionarios($codigo_questionario, $codigos_respostas);
        //var_dump($textos_respostas);

        //varre os textos para organizar
        $riscos = array();
        $positivos = array();
        $count_risco = 0;
        $count_positivo = 0;
        foreach($textos_respostas AS $txt_resp) {
            //verifica se é positivo ou negativo
            if(strstr($txt_resp['titulo'],'NEGATIVO')) {
                $count_risco++;

                $riscos[] = [
                                'subtitulo'=>'Fator '.$count_risco,
                                'subconteudo'=> $txt_resp['descricao']
                            ];
            }
            else if(strstr($txt_resp['titulo'],'POSITIVO')) {
                $count_positivo++;

                $positivos[] = [
                                'subtitulo'=>'Fator '.$count_positivo,
                                'subconteudo'=> $txt_resp['descricao']
                            ];
            }
        }//fim foreach
        // debug($riscos);exit;

        // if($codigo_questionario == 16){

        //     if(empty($count_positivo)){
        //         $titulo = "Ótimo!";
        //         $subtitulo = "Faça sua parte para evitar a transmissão da COVID-19.";
        //         $conteudo = '';
        //         if(!empty($riscos)) {
        //             $conteudo = $riscos[array_key_first($riscos)]['subconteudo'];
        //         }
        //         $data['codigo_cor'] = 1;
        //     }else{
        //         $titulo = "Urgência!";
        //         $subtitulo = "Procure imediatamente uma unidade de saúde.";                
        //         $conteudo = '';
        //         if(!empty($positivos)) {
        //             $conteudo = $positivos[array_key_first($positivos)]['subconteudo'];
        //         }
        //         $data['codigo_cor'] = 4;
        //     }

        //     $data = [];
        //     $data['entenda'] = [
        //         'titulo' => $titulo,
        //         'conteudo' => [
        //             ['subtitulo'=>$subtitulo, 'subconteudo'=>$conteudo]
        //         ]
        //     ];

        // }else{

            $descricao = $resultado['descricao'];//não mensurado

            //pegar o texto de entenda o resultado.
            $texto_entenda = $this->Questionarios->find()->select(['observacoes' => 'RHHealth.dbo.ufn_decode_utf8_string(observacoes)'])->where(['codigo' => $codigo_questionario])->hydrate(false)->first();
            $entenda = '';
            if(!empty($texto_entenda)) {
                // $entenda = utf8_decode($texto_entenda['observacoes']);
                $entenda = $texto_entenda['observacoes'];
            }

            $data = [];
            $data['entenda'] = [
                'titulo' => 'Entenda o Resultado',
                'conteudo' => [
                    ['subtitulo'=>'Sobre a Doença', 'subconteudo'=>$entenda]
                ]
            ];

            switch ($descricao) {
                case 'ALTO RISCO':

                    $data['riscos'] = [
                        'titulo' => 'Seus fatores de Alto Risco',
                        'conteudo' => $riscos,
                    ];
                    $data['protecao'] = [
                        'titulo' => 'Seus fatores de Proteção',
                        'conteudo' => $positivos,
                    ];


                break;
                case 'RISCO MODERADO':

                    $data['riscos'] = [
                        'titulo' => 'Seus fatores de Risco Moderado',
                        'conteudo' => $riscos,
                    ];
                    $data['protecao'] = [
                        'titulo' => 'Seus fatores de Proteção',
                        'conteudo' => $positivos,
                    ];
                break;
                case 'BAIXO RISCO':

                    $data['riscos'] = [
                        'titulo' => 'Seus fatores de Baixo Risco',
                        'conteudo' => $riscos,
                    ];
                    $data['protecao'] = [
                        'titulo' => 'Seus fatores de Proteção',
                        'conteudo' => $positivos,
                    ];

                    break;

                default:

                    break;
            }
        // }

        return $data;
    }

    private function validaQuestionarioHistoricoAtivo(int $codigo_usuario, int $codigo_questionario){

        $this->loadModel('UsuariosQuestionarios');
        $data = $this->UsuariosQuestionarios->validaHistoricoAtivo($codigo_usuario, $codigo_questionario);

        return $data;
    }

    /**
     * Obter Lista de questões com respostas
     *
     * ex. resposta
     *
     * {
     *  "status": 200,
     *  "result": {
     *   "data": [
     *      {
     *        "codigo_pergunta": "4",
     *        "label_pergunta": "Qual seu sexo?",
     *        "respostas": [
     *          {
     *            "codigo": "9",
     *            "label": "Feminino"
     *            "codigo_proxima_pergunta": "12",
     *          },
     *          {
     *            "codigo": "11",
     *            "label": "Masculino"
     *            "codigo_proxima_pergunta": "12",
     *          }
     *        ]
     *      },
     *
     * @param int $codigo_usuario
     * @param int $codigo_questionario
     * @return array | null
     */
    private function obterListaQuestoes(int $codigo_usuario, int $codigo_questionario){

        return $this->Questionarios->listarPerguntasRespostasFormatadas($codigo_usuario, $codigo_questionario);
    }

    /**
     * Obter Questionarios
     *
     * @param integer $codigo_usuario
     * @return array | null
     */
    private function obterListaQuestionarios(int $codigo_usuario){

        //instancia a usuarios dados para pegar o sexo
        $this->loadModel('UsuariosDados');

        //pega os dados do usuario
        $data = $this->UsuariosDados->find()->where(['codigo_usuario' => $codigo_usuario])->first();

        if(!empty($data) && isset($data->sexo)){

            /**
             * logica para saber se vai ter empresas que nao devem apresetar os questioanrios
             */
            //pega os questionarios que podem aparecer na lista
            $this->Funcionarios = $this->loadModel('Funcionarios');
            $codigo_cliente_vinculado = $this->Funcionarios->obterCodigoClienteVinculado($data->cpf);

            $cliente_questionario_inativo = null;
            if(!is_null($codigo_cliente_vinculado)) {
                $this->ClienteQuestionario = $this->loadModel('ClienteQuestionarios');
                $dadoClienteQuestionario = $this->ClienteQuestionario->questionarioRetiraPermissao($codigo_cliente_vinculado, 1);

                if(!empty($dadoClienteQuestionario)) {

                    debug($dadoClienteQuestionario);exit;
                    
                    //varre os dados de questionarios inativados para o cliente
                    foreach($dadoClienteQuestionario AS $dadoCQ) {

                        $cliente_questionario_inativo[] = $dadoCQ['codigo_questionario'];
                    
                    }//fim dadosclientequestionario
                
                }//fim dadoclientequestionario

            }//fim codigo_cliente_vinculado            
            /**
             * fim da logica para saber se vamos apresentar o questionario
             */
            
            $data = $this->Questionarios->lista($codigo_usuario, $data->sexo,$cliente_questionario_inativo);

        }        

        return $data;
    }

}
