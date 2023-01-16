<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\TableRegistry;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

use Cake\Event\Event;
use Cake\Log\Log;

use Cake\Datasource\ConnectionManager;
use App\Utils\EncodingUtil;

/**
 * Questionarios Model
 *
 * @property \App\Model\Table\CaracteristicasTable&\Cake\ORM\Association\BelongsToMany $Caracteristicas
 *
 * @method \App\Model\Entity\Questionario get($primaryKey, $options = [])
 * @method \App\Model\Entity\Questionario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Questionario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Questionario|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Questionario saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Questionario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Questionario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Questionario findOrCreate($search, callable $callback = null, $options = [])
 */
class QuestionariosTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('questionarios');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Caracteristicas', [
            'foreignKey' => 'questionario_id',
            'targetForeignKey' => 'caracteristica_id',
            'joinTable' => 'caracteristicas_questionarios'
        ]);

        $this->hasMany('Questoes')
        ->setConditions(['status' => 1]);

    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('codigo')
            ->allowEmptyString('codigo', null, 'create');

        $validator
            ->integer('ordem')
            ->allowEmptyString('ordem');

        $validator
            ->notEmptyString('status');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->requirePresence('codigo_usuario_alteracao', 'create')
            ->notEmptyString('codigo_usuario_alteracao');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->requirePresence('descricao', 'create')
            ->notEmptyString('descricao');

        $validator
            ->scalar('observacoes')
            ->maxLength('observacoes', 500)
            ->allowEmptyString('observacoes');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->scalar('background')
            ->maxLength('background', 255)
            ->allowEmptyString('background');

        $validator
            ->scalar('icone')
            ->maxLength('icone', 255)
            ->allowEmptyString('icone');

        $validator
            ->integer('quantidade_dias_notificacao')
            ->allowEmptyString('quantidade_dias_notificacao');

        $validator
            ->scalar('aplicacao_sexo')
            ->maxLength('aplicacao_sexo', 1)
            ->allowEmptyString('aplicacao_sexo');

        $validator
            ->scalar('protocolo')
            ->maxLength('protocolo', 255)
            ->allowEmptyString('protocolo');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }

    /**
     * metodo para pegar o status de respostas dos questionarios
     *
     * @param  integer $codigo_usuario
     * @return
     */
    public function getStatusQuestionarios($dados_usuario)
    {

        //campos para exibicao
        $fields = array(
            'codigo_questionario' => 'Questionarios.codigo',
            'codigo_usuario_quest' => 'UsuariosQuest.codigo',
            'finalizado' => 'UsuariosQuest.finalizado',
            'concluido' => 'UsuariosQuest.concluido',
        );

        //juncoes
        $joins = array(
            array(
                'table' => 'usuarios_questionarios',
                'alias' => 'UsuariosQuest',
                'type' => 'LEFT',
                'conditions' => 'UsuariosQuest.codigo IN ( SELECT TOP 1 codigo FROM usuarios_questionarios UQ WHERE codigo_usuario = '.$dados_usuario['codigo_usuario'].' AND UQ.codigo_questionario = Questionarios.codigo ORDER BY data_inclusao DESC)'
            ),
        );

        //monta as condicoes para o questionario
        $conditions['Questionarios.status'] = 1;
        $conditions[] = "Questionarios.codigo NOT IN (13,16)";

        if(!empty($dados_usuario['sexo']) && !is_null($dados_usuario['sexo'])) {
            $conditions['OR'][]['Questionarios.aplicacao_sexo'] = 'A';
            $conditions['OR'][]['Questionarios.aplicacao_sexo'] = $dados_usuario['sexo'];
        }//verificacao do sexo

        //ordenacao
        $order = array('Questionarios.codigo');

        //pega os dados dos questionarios que foram respondidos
        $dados_status = $this->find()->select($fields)->join($joins)->where($conditions)->order($order)->all();

        //para realizar a conta corretamente
        $questionarios_respondidos = 0;

        //verifica se existe valor
        if(!empty($dados_status)) {
            //varre os dados dos status
            foreach($dados_status as $ds) {

                //verifica se tem valor do usuario questionario
                if(isset($ds['codigo_usuario_quest']) && !empty($ds['codigo_usuario_quest'])){
                    $questionarios_respondidos++;
                }

            }//fim foreach

        }//fim dados_status

        //calcula a porcentagem dos questionarios respondidos
        $porcentagem_questionario = ROUND(($questionarios_respondidos * 100) / count($dados_status));

        //retorna o resultado dos dados
        return $porcentagem_questionario."%";

    }//fim getStatusQuestionario


    /**
     * metodo para pegar a listagem dos questionarios
     *
     *     * codigo de cores:
     *        1 - verde,
     *        2 - amarelo,
     *        3 - laranja,
     *        4 - vermelho
     *
     * @param integer $codigo_usuario
     * @param string $sexo
     * @return array
     */
    public function lista(int $codigo_usuario, string $sexo = null, $questionario_inativos_cliente = null)
    {

        $iconv = new EncodingUtil();

        //pega os resultados
        $resultados = $this->montaDeParaResultados();

        $fields = array(
            'codigo',
            'ordem',
            'descricao',
            'aplicacao_sexo',
            'observacoes',
            'background' => "img_app",
            'icone' => "img_app",
            'numero_de_perguntas' => "(SELECT COUNT(LabelQuestoes.codigo) FROM questoes Questoes LEFT JOIN label_questoes LabelQuestoes ON (Questoes.codigo_label_questao = LabelQuestoes.codigo) WHERE Questoes.codigo_questionario = Questionarios.codigo AND LabelQuestoes.type = 'Q')",
            'numero_respostas' => '(SELECT COUNT(*) FROM respostas WHERE codigo_historico_resposta = (SELECT TOP 1 codigo FROM usuarios_questionarios WHERE codigo_usuario = '.$codigo_usuario.' AND codigo_questionario = Questionarios.codigo ORDER BY data_inclusao desc))',
            'pontuacao_questionario' => '(SELECT SUM(pontos) FROM respostas WHERE codigo_historico_resposta = (SELECT TOP 1 codigo FROM usuarios_questionarios WHERE codigo_usuario = '.$codigo_usuario.' AND codigo_questionario = Questionarios.codigo ORDER BY data_inclusao desc))',
            'percentual' => "(((SELECT SUM(pontos) FROM respostas WHERE codigo_historico_resposta = (SELECT TOP 1 codigo FROM usuarios_questionarios WHERE codigo_usuario = ".$codigo_usuario . " AND codigo_questionario = Questionarios.codigo ORDER BY data_inclusao DESC)) * 100) / (SELECT SUM(pontos) FROM questoes WHERE codigo_questionario = Questionarios.codigo ))"
        );

        $codigos_questionarios_nao_devem_aparecer = array(13,16);

        if(!empty($questionario_inativos_cliente)) {
            $codigos_questionarios_nao_devem_aparecer = array_merge($codigos_questionarios_nao_devem_aparecer,$questionario_inativos_cliente);
        }


        $results = $this->find()
                        ->select($fields)
                        ->where(['status'=> 1, 
                            'aplicacao_sexo IN ' => ['A',$sexo],
                            'codigo NOT IN ' => $codigos_questionarios_nao_devem_aparecer
                        ])
                        ->order(['ordem'=>'asc'])
                        ->all()
                        ->toArray();

        $info_questionarios = $results;
        $iconv = new EncodingUtil();
        
        //monta a resposta do questionario
        foreach ($results as $indice_questionario => $dados_questionario) {

            // define mensagem padrão :Duda, por Slack
            //$info_questionarios[$indice_questionario]['risco'] = 'Você ainda não respondeu o questionário';
            $info_questionarios[$indice_questionario]['risco'] = 'Indefinido';
            $info_questionarios[$indice_questionario]['background'] = FILE_SERVER.$info_questionarios[$indice_questionario]['background'];
            $info_questionarios[$indice_questionario]['icone'] = FILE_SERVER.$info_questionarios[$indice_questionario]['icone'];

            // if(is_null($info_questionarios[$indice_questionario]['pontuacao_questionario'])) {
            //     $info_questionarios[$indice_questionario]['pontuacao_questionario'] = 0;
            // }

            // if(is_null($info_questionarios[$indice_questionario]['percentual'])) {
            //     $info_questionarios[$indice_questionario]['percentual'] = 0;
            // }

            if(isset($resultados[$dados_questionario['codigo']]) && !empty($resultados[$dados_questionario['codigo']])){
                //if($dados_questionario['codigo']==9){
                    //print_r($resultados[$dados_questionario['codigo']]);
                    //exit;
                //}
                
                //varre o de/para
                foreach ($resultados[$dados_questionario['codigo']] as $key => $depara_resultado) {

                    if( $dados_questionario['pontuacao_questionario'] <= $depara_resultado['Valor'] ) {
                        if(isset($resultados[$dados_questionario['codigo']][$key])){
                            $descricao = $iconv->convert($resultados[$dados_questionario['codigo']][$key]['descricao']);

                            if($info_questionarios[$indice_questionario]['numero_respostas'] <> 0){
                                $info_questionarios[$indice_questionario]['risco'] = $descricao;
                            }
                            switch ($key) {
                                case 0:
                                    if($info_questionarios[$indice_questionario]['numero_respostas'] == 0) {
                                        $info_questionarios[$indice_questionario]['percentual'] = 0;
                                    } else if($info_questionarios[$indice_questionario]['numero_respostas'] > 0) {
                                        $info_questionarios[$indice_questionario]['percentual'] = 1;
                                    } else {
                                        $info_questionarios[$indice_questionario]['percentual'] = 15;
                                    }

                                    $info_questionarios[$indice_questionario]['codigo_cor'] = 1; //verde
                                    // debug('verde');
                                    break;
                                case 1:
                                    $info_questionarios[$indice_questionario]['percentual'] = 50;
                                    $info_questionarios[$indice_questionario]['codigo_cor'] = 2; //amarelo
                                    // debug('amarelo');
                                    break;
                                case 2:
                                    $info_questionarios[$indice_questionario]['percentual'] = 90;
                                    $info_questionarios[$indice_questionario]['codigo_cor'] = 4; //vermelho
                                    // debug('vermelho');
                                    break;
                                default:
                                    $info_questionarios[$indice_questionario]['percentual'] = 90;
                                    $info_questionarios[$indice_questionario]['codigo_cor'] = 4; //vermelho
                                    // debug('vermelho tmb');
                                    break;
                            }
                        }
                        //fechando loop de dentro já que achou a cor
                        break;
                    } else {
                        $info_questionarios[$indice_questionario]['risco'] = 'ALTO RISCO';
                        $info_questionarios[$indice_questionario]['codigo_cor'] = 4; //vermelho


                    }
                } // FIM FOREACH resultado
                $info_questionarios[$indice_questionario]['entenda'] = [
                    'titulo' => 'Entenda o Resultado',
                    'conteudo' => [
                        ['subtitulo'=>'Sobre a Doença', 'subconteudo'=> $iconv->convert($dados_questionario['observacoes'])]
                    ]
                ];

            }
        }
       // debug($info_questionarios);exit;


        return $info_questionarios;
    }

    public function montaDeParaResultados()
    {

        //instancia a tabela de resultados
        $this->Resultados = TableRegistry::get('Resultados');

        $dados = array();
        $array_resultados = $this->Resultados->find()->all();

        foreach ($array_resultados as $value) {

            $dados[$value->codigo_questionario][] = array(
                'Valor' => $value->valor,
                'descricao' => $value->descricao
            );
        }

        return $dados;

    }

    /**
     * Questionário com as questões
     *
     * Lista de Perguntas e Respostas baseadas no $codigo_questionario e
     * validadas de acordo com regras de apresentação, pois algumas questões não
     * precisa se respondida se o $codigo_usuario tiver associado uma idade, genero, etc
     *
     * @param integer $codigo_usuario
     * @param integer $codigo_questionario
     * @return array|null
     */
    public function listarPerguntasRespostas(int $codigo_usuario, int $codigo_questionario, bool $avaliarRegras = true )
    {

        $params = ['codigo_questionario'=> $codigo_questionario];

        $strSql = 'WITH cteQuestoes as (
            select
                qp.codigo,
                RHHealth.dbo.ufn_decode_utf8_string(qp.label) AS label,
                qp.pontos
            from questoes qp
            where codigo_questionario = :codigo_questionario),
            
            cteRespQuest as (
                select
                    cte.codigo as cte_codigo,
                    cte.label as cte_label,
                    q.codigo as codigo,
                    RHHealth.dbo.ufn_decode_utf8_string(q.label) as label,
                    q.pontos as pontos,
                    q.codigo_proxima_questao as codigo_proxima_questao,
                    q.codigo_label_questao
                from questoes q
                    inner join cteQuestoes cte on q.codigo_questao = cte.codigo
            )
        ​
            select *
            from cteRespQuest
            order by cte_codigo, codigo
        ';
        $connection = ConnectionManager::get('default');
        $questoes = $connection->execute($strSql, $params )->fetchAll('assoc');

        // avaliar perguntas e respostas que serão respondidas pelo sistema
        if($avaliarRegras){
            $questoes = $this->avaliarRegrasPerguntasRespostas($codigo_usuario, $codigo_questionario, $questoes);
        }
        return $questoes;

    }
    
    /**
     * Avalia questoes que serão respondidas automaticamente pelo sistema
     *
     * @param integer $codigo_usuario
     * @param integer $codigo_questionario
     * @param array $questoes
     * @return array
     */
    public function avaliarRegrasPerguntasRespostas(int $codigo_usuario, int $codigo_questionario, $questoes)
    {

        // lista das questoes que o sistema podera responder automaticamente de
        // acordo com os dados do usuario
        $questoes_avaliadas_pelo_sistema = [4, 12, 15, 89, 119, 146, 192, 219, 94, 243];

        $questoes_avaliadas = []; // questoes a serem avaliadas pelo sistema no momento de salvar
        $questoes_respondidas = []; // questoes respondidas pelo sistema

        // avaliar questoes
        foreach ($questoes as $key => $questao) {
            $codigo_questao = $questao['cte_codigo'];

            if(in_array($codigo_questao, $questoes_avaliadas_pelo_sistema)){
                $questoes_avaliadas[] = $questao;

                // valida o que sera respondido pelo sistema
                $respondido = $this->validaRespostaPeloSistema($codigo_usuario, $codigo_questao);

                // se questao nao foi respondida pelo sistema ainda
                if(!$this->validaQuestaoJaRespondida($codigo_questao, $questoes_respondidas)){
                    $questoes_respondidas[] = $respondido;
                }

                if(!empty($respondido)){
                    unset($questoes[$key]);
                }

                //continue;
            }
        }

        // PARA DEBUGAR QUESTIONARIO
        // $questoes_avaliadas -- encontradas pelo sistema por codigo_questionario
        // $questoes_respondidas -- respondidas pelo sistema por codigo_usuario
        // $questoes --
        //dd($questoes_avaliadas, $questoes_respondidas, $questoes);

        return $questoes;
    }

    private function validaQuestaoJaRespondida(int $codigo_questao, array $questoes = []){

        $iterator = new \RecursiveArrayIterator($questoes);
        $recursive = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);

        foreach ($recursive as $key => $value) {

            if(empty($return) && isset($value['codigo_pergunta']) && $value['codigo_pergunta'] == $codigo_questao){
                return true;
            }
        }

        return false;
    }

    private function obterDadosDoUsuario(int $codigo_usuario){

        $Usuarios = TableRegistry::get('Usuario');
        return $Usuarios->obterDadosDoUsuario($codigo_usuario);
    }

    /**
     * Obter Genero do usuário
     *
     * @param integer $codigo_usuario
     * @return string M | F
     */
    private function obterGeneroDoUsuario(int $codigo_usuario){

        $dados = $this->obterDadosDoUsuario($codigo_usuario);
        $genero = isset($dados->sexo) ? $dados->sexo : '';
        return (string) $genero;
    }

    /**
     * Obter Idade do usuario
     *
     * @param integer $codigo_usuario
     * @return int
     */
    private function obterIdadeDoUsuario(int $codigo_usuario){

        $dados = $this->obterDadosDoUsuario($codigo_usuario);
        $data_nascimento = $dados->data_nascimento;

        list($ano, $mes, $dia) = explode('-', $data_nascimento);

        $hoje = mktime(0, 0, 0, date('m'), date('d'), date('Y'));
    	$nascimento = mktime( 0, 0, 0, $mes, $dia, $ano);

   		$idade = floor((((($hoje - $nascimento) / 60) / 60) / 24) / 365.25);

        return (int)$idade;
    }

    /**
     * Obter o IMC do usuario
     *
     * @param integer $codigo_usuario
     * @return int
     */
    private function obterImcDoUsuario(int $codigo_usuario){

        $UsuarioImc = TableRegistry::get('UsuariosImc');
        $conditions = ['codigo_usuario' => $codigo_usuario];
        $dados_imc = $UsuarioImc->find()->select('resultado')->where($conditions)->order('data_inclusao DESC')->first();
        $imc = ( !empty($dados_imc) ? $dados_imc : NULL );
        return $imc;
    }

    public function validaRespostaPeloSistema(int $codigo_usuario, int $codigo_questao){

        $codigo_resposta = null;

		switch ($codigo_questao) {
			//GENERO
            case 4:
                $genero = $this->obterGeneroDoUsuario($codigo_usuario);

                if($genero == 'F'){
                    $codigo_resposta = 9;
                }

                if($genero == 'M'){
                    $codigo_resposta = 11;
                }

				break;
			//IDADE
            case 12:
				if ($this->obterIdadeDoUsuario($codigo_usuario) > 65) {
					$codigo_resposta = 13;
				} else {
					$codigo_resposta = 14;
				}
				break;
			case 15:
				if ($this->obterIdadeDoUsuario($codigo_usuario) > 55) {
					$codigo_resposta = 16;
				} else {
					$codigo_resposta = 50;
				}
				break;
            case 89:

                $idade = $this->obterIdadeDoUsuario($codigo_usuario);

				if ($idade < 45) {
					$codigo_resposta = 90;
				} else if ( $idade >= 45 && $idade <= 54 ) {
					$codigo_resposta = 91;
				} else if ( $idade >= 55 && $idade <= 64 ) {
					$codigo_resposta = 92;
				} else if ( $idade > 64 ) {
					$codigo_resposta = 93;
				}
				break;
			case 119:
				if ($this->obterIdadeDoUsuario($codigo_usuario) > 40) {
					$codigo_resposta = 120;
				} else {
					$codigo_resposta = 121;
				}
				break;
			case 146:
				if ($this->obterIdadeDoUsuario($codigo_usuario) > 45) {
					$codigo_resposta = 147;
				} else {
					$codigo_resposta = 148;
				}
				break;
			case 192:
				if ($this->obterIdadeDoUsuario($codigo_usuario) > 45) {
					$codigo_resposta = 193;
				} else {
					$codigo_resposta = 194;
				}
				break;
            case 219:
                $idade = $this->obterIdadeDoUsuario($codigo_usuario);
				if ($idade < 30) {
					$codigo_resposta = 220;
				} else if ( $idade >= 30 && $idade <= 39 ) {
					$codigo_resposta = 221;
				} else if ( $idade >= 40 && $idade <= 65 ) {
					$codigo_resposta = 222;
				} else if ( $idade > 65 ) {
					$codigo_resposta = 223;
				}
				break;

			//IMC
            case 94:

                $imc = $this->obterImcDoUsuario($codigo_usuario);
                if(!empty($imc)) {
                    $imc = $imc->resultado;
    				if ($imc < 25) {
    					$codigo_resposta = 95;
    				} else if ($imc >= 25 && $imc <= 30) {
    					$codigo_resposta = 96;
    				} else if ($imc > 30) {
    					$codigo_resposta = 97;
    				}
                }
				break;
            case 243:
                $imc = $this->obterImcDoUsuario($codigo_usuario);

                if(!empty($imc)) {
                    $imc = $imc->resultado;
    				if ($imc < 25) {
    					$codigo_resposta = 244;
    				} else if ($imc >= 25 && $imc <= 30) {
    					$codigo_resposta = 245;
    				} else if ($imc > 30) {
    					$codigo_resposta = 246;
    				}
                }

				break;
		}

		if ($codigo_resposta){
			return [
                'codigo_pergunta' => $codigo_questao,
				'codigo_resposta' => $codigo_resposta,
            ];
        }

		return [];
    }

    /**
     * Perguntas e respostas formatadas para seres apresentadas para API
     *
     * ex saida
     *  {
     *        "codigo_pergunta": "54",
     *        "label_pergunta": "Você fuma?",
     *        "respostas": [
     *          {
     *            "codigo": "58",
     *            "label": "De 6 a 10 cigarros por dia",
     *            "codigo_proxima_pergunta": "62"
     *          },
     *          {
     *            "codigo": "59",
     *            "label": "De 11 a 20 cigarros por dia",
     *            "codigo_proxima_pergunta": "62"
     *          },
     *          {
     * @param integer $codigo_usuario
     * @param integer $codigo_questionario
     * @return void
     */
    public function listarPerguntasRespostasFormatadas(int $codigo_usuario, int $codigo_questionario)
    {

        $data = [];
        $perguntas = [];
        $iconv = new EncodingUtil();
        $codigos_questoes = array();

        $lista = $this->listarPerguntasRespostas($codigo_usuario, $codigo_questionario);

        // debug($lista);exit;

        if(is_array($lista) && count($lista)>0) {

            $retira_pergunta = array();

            foreach ($lista as $key => $value) {

                $codigo_questao = $value['cte_codigo'];

                if($codigo_questionario == 13 || $codigo_questionario == 16) {}
                else{
                    //quando for a primeira pergunta
                    if($key == 0) {

                        $pergunta_respondida = $this->perguntaRespondida($codigo_questao,$codigo_usuario);

                        //verifica se a proxima pergunta deve ser retirada para ser respondida
                        if(!empty($pergunta_respondida)) {
                            $retira_pergunta[$codigo_questao] = $codigo_questao;
                        }

                    }

                    //procura se precisa ser respondida a proxima pergunta
                    $codigo_proxima_pergunta = $value['codigo_proxima_questao'];
                    if(!empty($codigo_proxima_pergunta)) {
                        $pergunta_respondida = $this->perguntaRespondida($codigo_proxima_pergunta,$codigo_usuario);

                        //verifica se a proxima pergunta deve ser retirada para ser respondida
                        if(!empty($pergunta_respondida)) {
                            $retira_pergunta[$codigo_proxima_pergunta] = $codigo_proxima_pergunta;
                        }
                    }
                }

                // monta perguntas
                //se for a primeira interação
                if(isset($perguntas[$codigo_questao])){
                    $perguntas[$codigo_questao]['respostas'][] = [
                        'codigo'=>$value['codigo'],
                        'label'=>$value['label'],
                        'codigo_proxima_pergunta'=>$value['codigo_proxima_questao']
                    ];
                    continue;
                }

                $perguntas[$codigo_questao] = [
                    'codigo_pergunta'=> $value['cte_codigo'],
                    'label_pergunta'=> $value['cte_label']
                ];
                // monta respostas sugeridas
                $perguntas[$codigo_questao]['respostas'][] = [
                    'codigo'=>$value['codigo'],
                    'label'=>$value['label'],
                    'codigo_proxima_pergunta'=>$value['codigo_proxima_questao']
                ];

            }//fim foreach
            // debug($retira_pergunta);
            // debug($perguntas);
            // debug(array(count($retira_pergunta),count($perguntas)));

            //verifica se tem questoes para retirar da lista de respostas
            if(!empty($retira_pergunta)) {

                if(count($retira_pergunta) != count($perguntas)) {

                    // varre os ids que deve ser retirado para responder
                    foreach($retira_pergunta AS $codigo_questao => $cod) {
                        //verifica se tem o id
                        if(isset($perguntas[$cod])) {

                            //retira as perguntas que vai ser enviada
                            unset($perguntas[$cod]);

                        }//fim verificacao perguntas
                    }//fim foreach
                    
                    //pega o ultimo indice do array de perguntas para setar null a proxima pergunta
                    $ultima_questao = end($perguntas);            
                    $codigo_questao = $ultima_questao['codigo_pergunta'];

                    if(isset($perguntas[$codigo_questao]['respostas'])) {
                        //varre as ultimas respostas
                        foreach($perguntas[$codigo_questao]['respostas'] AS $keys => $arrRespostas) {
                            //seta as perguntas
                            $perguntas[$codigo_questao]['respostas'][$keys]['codigo_proxima_pergunta'] = NULL;
                        }//fim foreach

                        //reorganiza as questoes para serem respondidas
                        $perguntas = $this->reorganizaPerguntas($perguntas);
                    }

                }
                
            }//fim if retira pergunta
        
        }//fim if lista

        if(!empty($perguntas)){
            $data = array_merge($perguntas, $data);
        }
        
        // debug($data);exit;
        
        if($codigo_questionario == 13 || $codigo_questionario == 16){
            $arr['latitude_longitude'] = true;
        }
        $arr['perguntas'] = $data;

        return $arr;
    }

    /**
     * [perguntaRespondida description]
     * 
     * metodo para saber se a proxima questao ja foi respondida em algum momento no sistema
     * 
     * @param  [type] $proxima_questao [description]
     * @param  [type] $usuario         [description]
     * @return [type]                  [description]
     */
    public function perguntaRespondida($proxima_questao = null, $usuario)
    {
        // VALIDA SE JÁ EXISTE UMA QUESTAO RESPONDIDA COM MESMA RESPOSTA E DEVOLVE A INFORMAÇÃO
        $query = 'WITH CodigoLabelResposta AS (
                SELECT TOP 1
                    Resposta.codigo_label_questao AS codigo_label_resposta
                FROM respostas Resposta
                INNER JOIN questoes Questao ON (Questao.codigo = Resposta.codigo_questao)
                INNER JOIN questoes Respostas ON (Respostas.codigo_questao = Questao.codigo AND Respostas.codigo_label_questao IN (
                            SELECT Resposta.codigo_label_questao
                            FROM questoes Questao
                            INNER JOIN questoes Resposta ON (Resposta.codigo_questao = Questao.codigo)
                            WHERE Questao.codigo = '.$proxima_questao.' 
                        )
                    )
                WHERE Resposta.codigo_label_questao IN (
                            SELECT Resposta2.codigo_label_questao
                            FROM questoes Questao2
                            INNER JOIN questoes Resposta2 ON (Resposta2.codigo_questao = Questao2.codigo)
                            WHERE Questao2.codigo = '.$proxima_questao.'
                                AND (Questao2.codigo_questionario != Resposta.codigo_questionario 
                                    OR Questao2.codigo_questionario = Resposta.codigo_questionario )
                        )
                        AND Questao.codigo_label_questao IN (
                            SELECT
                            Questao2.codigo_label_questao
                            FROM questoes Questao2
                            INNER JOIN questoes Resposta2 ON (Resposta2.codigo_questao = Questao2.codigo)
                            WHERE Questao2.codigo = '.$proxima_questao.'
                                AND (Questao2.codigo_questionario != Resposta.codigo_questionario
                                    OR Questao2.codigo_questionario = Resposta.codigo_questionario )
                        )
                        AND Resposta.data_inclusao >= DATEADD(day, -30, getdate())
                        AND [Resposta].[codigo_usuario] = '.$usuario.'
                GROUP BY Resposta.codigo_label_questao,
                    Questao.codigo_label_questao,
                    Questao.label,
                    Respostas.codigo_label_questao,
                    Respostas.label,
                    Resposta.data_inclusao
                    ORDER BY Resposta.data_inclusao DESC
            )
            SELECT Resposta.codigo,
                Resposta.codigo_questao
            FROM questoes Resposta
            WHERE Resposta.codigo_questao = '.$proxima_questao.'
                AND Resposta.codigo_label_questao = (select codigo_label_resposta FROM CodigoLabelResposta);';

        // $log= new Log();
        // $log::debug($query);

        //conn para executar a query do sql
        $connection = ConnectionManager::get('default');
        $return = $connection->execute($query)->fetchAll('assoc');

        return $return;
    
    }//fim perguntaRespondida

    /**
     * [reorganizaPerguntas description]
     * 
     * metodo para reorganizar as perguntas para serem respondidas pelo app
     * 
     * remontar as perguntas corretamente com as proximas perguntas
     * 
     * @param  [type] $perguntas [description]
     * @return [type]            [description]
     */
    public function reorganizaPerguntas($perguntas)
    {
        
        $codigo_proxima_pergunta_anterior = null;

        //varre as questoes
        foreach($perguntas AS $codigo_questao => $dados_perguntas) {

            //verifica se tem um codigo de proxima pergunta
            if(!is_null($codigo_proxima_pergunta_anterior)) {

                //verifica se o codigo_questao é a mesma da proxima_pergunta_anterior
                if($codigo_proxima_pergunta_anterior != $codigo_questao) {
                    
                    //varre as respostas da pergunta anterior
                    foreach($perguntas[$codigo_questao_anterior]['respostas'] AS $key => $resp) {

                        //atualiza a proxima pergunta
                        $perguntas[$codigo_questao_anterior]['respostas'][$key]['codigo_proxima_pergunta'] = "{$codigo_questao}";

                    }//fim foreach

                }//fim if de proxima pergunta anterior com o codigo_questao

            }//fim codigo prx pergunta

            //verifica se é a ultima questao
            if(is_null($dados_perguntas['respostas'][0]['codigo_proxima_pergunta'])) {
                break;
            }

            //verifica se te
            $codigo_questao_anterior = $codigo_questao;
            $codigo_proxima_pergunta_anterior = $dados_perguntas['respostas'][0]['codigo_proxima_pergunta'];

        }//fim foreach perguntas

        return $perguntas;

    }//fim reorganizaPerguntas($perguntas)


    /**
     * [remontaRespostas description]
     * 
     * metodo para remotar a respostas do funcionarios pois podem existir questões que já foi respondida em outros questionarios
     * 
     * @return [type] [description]
     */
    public function remontaRespostas($codigo_usuario, $codigo_questionario, $payload)
    {

        ############### monta as respostas ##################
        //pega as questoes
        $monta_questionario = $this->listarPerguntasRespostas($codigo_usuario, $codigo_questionario);

        //varre todo o questionario para saber qual é as questoes já respondidas
        if(!empty($monta_questionario)) {

            //variavel auxiliar
            $codigo_questao_respondida = array();
            $respostas = array();

            //varre as questoes e respostas
            foreach($monta_questionario AS $mq) {

                //verifica se a questao passada já foi respondida
                $pergunta_respondida = $this->perguntaRespondida($mq['cte_codigo'],$codigo_usuario);

                //verifica se existe a questão respondida
                if(!empty($pergunta_respondida) && !isset($codigo_questao_respondida[$mq['cte_codigo']]) ) {
                    //seta a variavel auxiliar
                    $codigo_questao_respondida[$mq['cte_codigo']] = $mq['cte_codigo'];
                    
                    //insere no objeto                        
                    $payload['respostas'][] = ['codigo_pergunta' => $mq['cte_codigo'],'codigo_resposta' => $pergunta_respondida[0]['codigo']];
                }

            }//fim foreach

        }//fim monta_questionario        

        return $payload;

    }//fim remontaRespostas


    /**
     * ex. retorno
     *  [
     *   "cte_codigo" => "4"
     *   "cte_label" => "Qual seu sexo?"
     *   "codigo" => "9"
     *   "label" => "Feminino"
     *   "codigo_proxima_questao" => "12"
     *   "pontos" => "4" ou null
     * ]
     */
    function obterQuestaoDoQuestionario(int $codigo_questao, int $codigo_resposta, int $codigo_questionario, array $questionarios = []) {

        if(!empty($questionarios)){

            $iterator = new \RecursiveArrayIterator($questionarios);
            $recursive = new \RecursiveIteratorIterator($iterator, \RecursiveIteratorIterator::SELF_FIRST);
            $return = null;
            foreach ($recursive as $key => $value) {

                if(empty($return) && isset($value['cte_codigo']) && $value['cte_codigo'] == $codigo_questao && $value['codigo'] == $codigo_resposta){
                    $return = $value;
                }
            }
            return $return;
        }
        // TODO IMPLEMENTAR BUSCA NO BANCO
    }

    public function salvarRespostas(int $codigo_usuario, int $codigo_questionario, array $questionario_respostas, string $latitude=null, string $longitude=null){

        $entidades = [];
        $entidades_new = true; // designa uso do newEntity se true ou pathEntity se false
        $codigo_empresa = 1;
        $codigo_historico_ativo = null;


        //busca se tem funcionario pelo codigo do usuario
        // $this->
        // $funcionario = $this->Funcionario


        // obter o mesmo questionario que foi apresentado no preenchimento
        $questionario_dados = $this->listarPerguntasRespostas($codigo_usuario, $codigo_questionario);

        // verifica se tem perguntas foram respondidas para este questionario
        $questionario_dados_historico = $this->obterQuestionarioHistoricoAtivo($codigo_usuario, $codigo_questionario);

        // significa que não finalizou o questionario sob as regras de
        // usuario_questionario / obterHistoricoAtivo
        if(isset($questionario_dados_historico->codigo)){
            $codigo_historico_ativo = $questionario_dados_historico->codigo;

            if(empty($codigo_historico_ativo)){
                return ['error' => 'Codigo histórico não definido'];
            }

        } else {

            // se não tem histórico então cria para obter o
            // id e associar com as respostas a seguir
            $salva_historico_ativo = $this->salvarQuestionarioHistoricoAtivo($codigo_usuario, $codigo_questionario, $latitude, $longitude);

            if(!isset($salva_historico_ativo['error'])){
                $codigo_historico_ativo = $salva_historico_ativo['codigo'];
            } else {
                return ['error' => $salva_historico_ativo['error']];
            }
        }

        // interage com as respostas recebidas
        foreach ($questionario_respostas as $key => $resposta) {

            // codigo_questao enviada
            $codigo_questao = $resposta['codigo_pergunta'];
            $codigo_resposta = $resposta['codigo_resposta'];

            // obter questao do questionario para comparar
            $questionario_dados_questao = $this->obterQuestaoDoQuestionario($codigo_questao, $codigo_resposta, $codigo_questionario, $questionario_dados);

            //$entidades['questoes_avaliadas'][] = $questionario_dados_questao;
            $r = [];
            // preparar resposta para adicionar em usuario_questionario
            $r['codigo_questionario'] = $codigo_questionario;
            $r['codigo_usuario'] = $codigo_usuario;
            $r['codigo_empresa'] = $codigo_empresa;
            $r['codigo_questao'] = $codigo_questao;
            $r['codigo_resposta'] = $codigo_resposta;
            $r['codigo_label_questao'] = $questionario_dados_questao['codigo_label_questao'];
            $r['label_questao'] = $questionario_dados_questao['cte_label'];
            $r['pontos'] = $questionario_dados_questao['pontos'];
            $r['label'] = $questionario_dados_questao['label'];
            $r['codigo_historico_resposta'] = $codigo_historico_ativo;

            $entidades[] = $r;

            $questionario_dados_questao = null;

        }

        $respostas_salvas = $this->salvarRespostasEmLote($entidades, $entidades_new );

        return $respostas_salvas;
    }

    private function validaQuestionarioHistoricoAtivo(int $codigo_usuario, int $codigo_questionario){

        $UsuariosQuestionario = TableRegistry::get('UsuariosQuestionarios');

        return $UsuariosQuestionario->validaHistoricoAtivo($codigo_usuario, $codigo_questionario);
    }

    private function obterQuestionarioHistoricoAtivo($codigo_usuario, $codigo_questionario){

        $UsuariosQuestionario = TableRegistry::get('UsuariosQuestionarios');

        return $UsuariosQuestionario->obterHistoricoAtivo($codigo_usuario, $codigo_questionario);
    }

    private function salvarQuestionarioHistoricoAtivo($codigo_usuario, $codigo_questionario, $latitude, $longitude){

        $UsuariosQuestionario = TableRegistry::get('UsuariosQuestionarios');

        return $UsuariosQuestionario->salvarHistoricoAtivo($codigo_usuario, $codigo_questionario, $latitude, $longitude);
    }

    /**
     *  Salvar conjunto de responstas
     *
     *  exemplo do array de respostas
     *   [
     *    "codigo_questionario" => 1
     *    "codigo_usuario" => 63064
     *    "codigo_empresa" => 1
     *    "codigo_questao" => "12"
     *    "codigo_resposta" => "14"
     *    "codigo_label_questao" => "12"
     *    "label_questao" => "> 65 anos?"
     *    "pontos" => "2"
     *    "label" => "Sim"
     *    "codigo_historico_resposta" => 2424
     *  ]
     *
     * @param array $respostas
     * @param int $codigo_questionario
     * @return array
     */
    private function salvarRespostasemLote(array $respostas, $newEntity = true)
    {

        $Respostas = TableRegistry::get('Respostas');

        $data = [];

        if(empty($respostas)){
            return ['error' => 'Não há respostas para processar.'];
        }

        foreach ($respostas as $key => $value) {

            $codigo_historico_resposta = $value['codigo_historico_resposta'];
            $codigo_usuario = $value['codigo_usuario'];
            $codigo_questao = $value['codigo_questao'];
            $codigo_questionario = $value['codigo_questionario'];


            // verifica se ja foi respondida
            $args = ['codigo_usuario' => $codigo_usuario,
                     'codigo_historico_resposta' => $codigo_historico_resposta,
                     'codigo_questao' => $codigo_questao
                    ];

            $respondido = $Respostas->find()->where($args)->first();

            if(!$respondido){
                $entidade = $Respostas->newEntity($value);

            } else {

                $respondido_atualizar = $Respostas->get(['codigo' => $respondido->codigo]);

                $entidade = $Respostas->patchEntity($respondido_atualizar, $value);

            }

            $entidade->set(['codigo_usuario_inclusao'=> $codigo_usuario]);

            if (!$Respostas->save($entidade)) {
                $error = $entidade->getValidationErrors();
                dd($error);
            }

            $entidade = null;

        }

        $pontos = $this->obterPontosDeQuestionario( $codigo_usuario, $codigo_questionario, $codigo_historico_resposta );

        $data['pontos'] = $pontos;

        $descricao = $this->obterDescricaoResultadoQuestionario( $codigo_questionario, (int)$pontos );

        $data['descricao'] = isset($descricao) ? $descricao : 'Risco não mensurado';

        $finaliza_questionario =  $this->finalizarQuestionario( $codigo_usuario, $codigo_historico_resposta, $codigo_questionario );

        $data['finalizado'] = $finaliza_questionario;

        return $data;

    }

    /**
     * Finaliza Questionario
     *
     * @param integer $codigo_usuario
     * @param integer $codigo_historico_resposta
     * @param integer $codigo_questionario
     * @return void
     */
    public function finalizarQuestionario(int $codigo_usuario, int $codigo_historico_resposta, int $codigo_questionario ){

        $UsuariosQuestionario = TableRegistry::get('UsuariosQuestionarios');

        $r = $UsuariosQuestionario->finalizarQuestionario($codigo_usuario, $codigo_historico_resposta, $codigo_questionario);

        if(isset($r['error'])){
            return false;
        }

        return true;
    }

    /**
     * Obter descrição de avaliação por pontos de um questionario terminado
     *
     * @param integer $codigo_questionario
     * @param integer $pontos
     * @return void
     */
    public function obterDescricaoResultadoQuestionario(int $codigo_questionario, int $pontos ){

        $Resultado = TableRegistry::get('Resultados');

        $r = $Resultado->find()
            ->select(['descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(descricao)'])
            ->where(['codigo_questionario' => $codigo_questionario, 'valor >= ' => $pontos])
            ->first();

        $descricao = isset($r->descricao) ? $r->descricao : 'Risco não mensurado';

        return $descricao;
    }

    /**
     * Undocumented function
     *
     * @param integer $codigo_usuario
     * @param integer $codigo_questionario
     * @param integer $codigo_historico_resposta
     * @return void
     */
    public function obterPontosDeQuestionario(int $codigo_usuario, int $codigo_questionario, int $codigo_historico_resposta ){

        $Respostas = TableRegistry::get('Respostas');

        $args = [
            'codigo_questionario' => $codigo_questionario,
            'codigo_usuario' => $codigo_usuario,
            'codigo_historico_resposta' => $codigo_historico_resposta
        ];

        $query = $Respostas->find();

        $pontos = $query->select(['soma_pontos' => $query->func()->sum('pontos')])->where($args)->first();

        return (int)$pontos->soma_pontos;
    }

    /**
     * [getPercentual description]
     *
     * metodo para pegar o percentual calculado do questionario respondido
     *
     * @param  [type] $codigo_usuario      [description]
     * @param  [type] $codigo_questionario [description]
     * @return [type]                      [description]
     */
    public function getPercentual($codigo_usuario, $codigo_questionario)
    {
        //fields para pegar a porcentagem do banco de dados
        $fields = array(
            'percentual' => "(((SELECT SUM(pontos) FROM respostas WHERE codigo_historico_resposta = (SELECT TOP 1 codigo FROM usuarios_questionarios WHERE codigo_usuario = ".$codigo_usuario . " AND codigo_questionario = ".$codigo_questionario." ORDER BY data_inclusao DESC)) * 100) / (SELECT SUM(pontos) FROM questoes WHERE codigo_questionario = ".$codigo_questionario." ))"
        );

        $results = $this->find()
                        ->select($fields)
                        ->where(['status'=> 1])
                        ->first();

        // debug($results);exit;
        if(is_null($results->percentual)){
            $results->percentual = 0;
        }

        return $results->percentual;

    }


}
