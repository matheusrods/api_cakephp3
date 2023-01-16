<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use DateTime;
use DateTimeZone;
use Cake\ORM\TableRegistry;

/**
 * Respostas Model
 *
 * @property \App\Model\Table\DeparaQuestoesTable&\Cake\ORM\Association\BelongsToMany $DeparaQuestoes
 * @property \App\Model\Table\FichaPsicossocialTable&\Cake\ORM\Association\BelongsToMany $FichaPsicossocial
 * @property \App\Model\Table\FichasAssistenciaisTable&\Cake\ORM\Association\BelongsToMany $FichasAssistenciais
 * @property \App\Model\Table\FichasClinicasTable&\Cake\ORM\Association\BelongsToMany $FichasClinicas
 *
 * @method \App\Model\Entity\Resposta get($primaryKey, $options = [])
 * @method \App\Model\Entity\Resposta newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Resposta[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Resposta|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Resposta saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Resposta patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Resposta[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Resposta findOrCreate($search, callable $callback = null, $options = [])
 */
class RespostasTable extends AppTable
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

        $this->setTable('respostas');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('DeparaQuestoes', [
            'foreignKey' => 'resposta_id',
            'targetForeignKey' => 'depara_questo_id',
            'joinTable' => 'depara_questoes_respostas'
        ]);
        $this->belongsToMany('FichaPsicossocial', [
            'foreignKey' => 'resposta_id',
            'targetForeignKey' => 'ficha_psicossocial_id',
            'joinTable' => 'ficha_psicossocial_respostas'
        ]);
        $this->belongsToMany('FichasAssistenciais', [
            'foreignKey' => 'resposta_id',
            'targetForeignKey' => 'fichas_assistenciai_id',
            'joinTable' => 'fichas_assistenciais_respostas'
        ]);
        $this->belongsToMany('FichasClinicas', [
            'foreignKey' => 'resposta_id',
            'targetForeignKey' => 'fichas_clinica_id',
            'joinTable' => 'fichas_clinicas_respostas'
        ]);
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
            ->integer('codigo_funcionario')
            ->allowEmptyString('codigo_funcionario');

        $validator
            ->integer('codigo_questao')
            ->requirePresence('codigo_questao', 'create')
            ->notEmptyString('codigo_questao');

        $validator
            ->integer('codigo_resposta')
            ->requirePresence('codigo_resposta', 'create')
            ->notEmptyString('codigo_resposta');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->integer('pontos')
            ->allowEmptyString('pontos');

        $validator
            ->scalar('label')
            ->maxLength('label', 500)
            ->allowEmptyString('label');

        $validator
            ->scalar('label_questao')
            ->maxLength('label_questao', 500)
            ->allowEmptyString('label_questao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_questionario')
            ->requirePresence('codigo_questionario', 'create')
            ->notEmptyString('codigo_questionario');

        $validator
            ->scalar('descricao_questionario')
            ->maxLength('descricao_questionario', 500)
            ->allowEmptyString('descricao_questionario');

        $validator
            ->integer('codigo_usuario')
            ->requirePresence('codigo_usuario', 'create')
            ->notEmptyString('codigo_usuario');

        $validator
            ->integer('codigo_historico_resposta')
            ->allowEmptyString('codigo_historico_resposta');

        $validator
            ->integer('codigo_label_questao')
            ->allowEmptyString('codigo_label_questao');

        return $validator;
    } 

    public function defineGrupo($codigo_historico_resposta){  

        //Grupo Verde - Não tem nenhum fator de risco
        // $grupo_verde = array(514, 532, 535, 541, 544, 547, 550, 553, 556, 559);
        $grupo_verde = array(747,751,754,757,760,763,766,769,772,775,777,780);

        //Grupo Branco - Tem pelo menos um fator de risco
        // $grupo_branco = array(531, 534, 540, 543, 546, 549, 552, 555, 558);
        $grupo_branco = array(748,751,754,757,760,763,766,769,772,775,778,781);

        //Teve contato com a doença | depois dos 14 dias já está imune
        // $grupo_azul = array(519);
        $grupo_azul = array(745);

        //Não recebeu o seu primeiro resultado há mais de 14 dias ou testou positivo para Covid-19 por teste rápido de anticorpos
        // $grupo_vermelho = array(520,528);
        $grupo_vermelho = array(746);

        //Grupo Laranja - quando teve alguma resposta do sintomas diarios com sim
        // $grupo_laranja = array(468,471,474,477,480);
        $grupo_laranja = array(718,720,723,726,729,732,735,738);

        $grupos = array(
            "Verde"=>$grupo_verde,
            "Branco"=>$grupo_branco,
            "Azul"=>$grupo_azul,
            "Vermelho"=>$grupo_vermelho,
            "Laranja"=>$grupo_laranja,
        );

        $grupo='';

        foreach($grupos as $key => $v){ 

            $arr ='';

            $var_aux = implode(',', $v);
            $conditions = "codigo_historico_resposta = $codigo_historico_resposta AND codigo_resposta IN (".$var_aux.")";

            $questionario = $this->find()->select(['codigo_resposta'])->where($conditions)->hydrate(false)->toArray();
            $respostas = array();
            if(!empty($questionario)) {

                foreach ($questionario as $val_quest) {                    
                    if($key == 'Verde') {
                        $respostas[] = $val_quest['codigo_resposta'];
                    }
                    else if($key == 'Branco') {
                        if(in_array($val_quest['codigo_resposta'],$grupo_branco)) {
                            $grupo = $key; break;
                        }
                    }
                    else if($key == 'Azul') {
                        if(in_array($val_quest['codigo_resposta'],$grupo_azul)) {
                            $grupo = $key; break;
                        }
                    }
                    else if($key == 'Vermelho') {
                        if(in_array($val_quest['codigo_resposta'],$grupo_vermelho)) {
                            $grupo = $key; break;
                        }
                    }
                    else if($key == 'Laranja') { //suspeito de ter corona virus
                        if(in_array($val_quest['codigo_resposta'],$grupo_laranja)) {
                            $grupo = $key; break;
                        }
                    }
                }
            }

            if($key == 'Verde') {
                $var_aux2 = implode(',', $respostas);
                if($var_aux == $var_aux2){
                    $grupo = $key; break;
                }
            }
            
        }
        
        return $grupo;

    }

    public function montaBanner($codigo_usuario, $permissoes){ 

        $data = array();

        $codigo_questionario_retornoaotrabalho = 13;
        $codigo_questionario_sintomasdiarios = 16;

        //verifica se ele é de um grupo especifico
        $this->UsuarioGrupoCovid = TableRegistry::get('UsuarioGrupoCovid');
        $registro = $this->UsuarioGrupoCovid->find()->where(['codigo_usuario' => $codigo_usuario])->first();

        //verifica se tem algum grupo ja setado
        if(!empty($registro)) { 

            //verifica se o usuario passou dos 14 dias para passar ele para o grupo azul
            if($registro->codigo_grupo_covid == 4) {
                $now = date('Y-m-d');
                $data_comp = '';
                if(empty($registro->data_alteracao)) {
                    // new \DateTime
                    $data_comp = substr($registro->data_inclusao,0,10);
                }
                else {
                    $data_comp = substr($registro->data_alteracao,0,10);
                }

                //se verdade essa comparacao mudar o usuario para o grupo azul
                if(strtotime($now) > strtotime($data_comp)) {

                    //Salva o grupo azul
                    $dados_alt = array('codigo_grupo_covid' => 3); //grupo azul
                    $dados_alt['codigo_usuario_alteracao'] = $codigo_usuario;
                    $r_alt = $this->UsuarioGrupoCovid->patchEntity($registro, $dados_alt);
                    if (!$this->UsuarioGrupoCovid->save($r_alt)) {
                        $error[] = $r_alt->getValidationErrors();
                        debug($error);
                    }
                }//fim comparacao datas

            }//fim grupo vermelho

            //verifica se tem passaporte ativo na tabela de resultado_covid
            $this->ResultadoCovid = TableRegistry::get('ResultadoCovid');
            $dados_resultado = $this->ResultadoCovid->find()
                            ->where([
                                'codigo_usuario' => $codigo_usuario,                                
                                'passaporte' => 1,
                                'DAY(data_inclusao)' => date('d'),
                                'MONTH(data_inclusao)' => date('m'),
                                'YEAR(data_inclusao)' => date('Y')       
                            ])
                            ->order(['data_inclusao' => 'desc'])
                            ->hydrate(false)
                            ->first();
            //verifica se tem passaporte gerado para o dia de hoje
            if(!empty($dados_resultado)) {
                $data['grupo'] = "Azul";
                $data['titulo'] = "Passaporte de trabalho COVID-19";
            }
            else {
                //grupo azul
                if($registro->codigo_grupo_covid == 3) {
                    //coloca que gerou o passaporte hoje mesmo sendo grupo azul
                    $this->setResultadoCovid($codigo_usuario,'Azul');

                    $data['grupo'] = "Azul";
                    $data['titulo'] = "Passaporte de trabalho COVID-19";
                }
                else if($registro->codigo_grupo_covid == 4 || $registro->codigo_grupo_covid == 5) {

                    if($registro->codigo_grupo_covid == 4) {
                        $this->setResultadoCovid($codigo_usuario,'Vermelho');
                    }

                    // banner 4: vermelho com os dados da empresa
                    $data['grupo'] = "Vermelho";
                    $data['titulo'] = "Entre em contato com a sua empresa e com a área de saúde:";
                }
            }

        }

        if(empty($data)) {
            
            //Verifica se questionario do covid (13) foi respondido alguma vez (independente da data), pega o codigo do ultimo e vamos classifica-lo 
            $UsuariosQuestionarios = TableRegistry::getTableLocator()->get('UsuariosQuestionarios');
            $historico_resposta = $UsuariosQuestionarios->find()
                    ->where([
                        'codigo_usuario' => $codigo_usuario,
                        'codigo_questionario' => $codigo_questionario_retornoaotrabalho,
                        'finalizado' => 1        
                    ])
                    ->order(['data_inclusao' => 'desc'])
                    // ->sql();
                    ->hydrate(false)
                    ->first();

            if(!$historico_resposta){ //Não respondeu retorno ao trabalho
                if($permissoes['retornoaotrabalho']){  

                    // banner 1: retorne ao trabalho com segurança
                    $data['titulo'] = "Retorne ao trabalho com segurança";
                    $data['descricao'] = "Responda o questionário que preparamos para avaliar o melhor plano de retorno e obtenha dicas para estar seguro ao retornar ao trabalho presencial.";
                    $data['codigo_questionario'] = $codigo_questionario_retornoaotrabalho; 
                }

            }

            if($historico_resposta){
                if($permissoes['sintomasdiarios']){

                    $grupo = $this->defineGrupo($historico_resposta['codigo']);

                    if($grupo == "Branco" || $grupo == "Verde") {

                        //respondeu questionario sintomas diários hoje?
                        $historico_resposta = $UsuariosQuestionarios->find()
                            ->where([
                                'codigo_usuario' => $codigo_usuario,
                                'codigo_questionario' => $codigo_questionario_sintomasdiarios,
                                'finalizado' => 1,
                                'DAY(concluido)' => date('d'),
                                'MONTH(concluido)' => date('m'),
                                'YEAR(concluido)' => date('Y')       
                            ])
                            ->order(['data_inclusao' => 'desc'])
                            ->hydrate(false)
                            ->first();

                        if($historico_resposta){ //Sim, respondeu sintomas diários
                            
                            //Se nas ultimas respostas de sintomas diários tiver algum sintoma, muda para grupo laranja
                            $codigo_historico_resposta = $historico_resposta['codigo'];
                            $respostas = $this->find()
                                ->select(['codigo_historico_resposta', 'pontos' => 'SUM(pontos)'])
                                ->where([
                                    'codigo_historico_resposta'=> $codigo_historico_resposta, 
                                    'label'=> 'Sim',
                                    'DAY(data_inclusao)' => date('d'),
                                    'MONTH(data_inclusao)' => date('m'),
                                    'YEAR(data_inclusao)' => date('Y')
                                ])
                                ->group(['codigo_historico_resposta'])
                                ->hydrate(false)
                                ->first();
                            
                            // debug($respostas);exit;
                            
                            $passaporte = true;
                            if(!empty($respostas)){// Tem sintoma

                                if($respostas['pontos'] > 2) {
                                    //$grupo = "Vermelho";
                                    $passaporte = false;
                                }
                            }

                            if(!$passaporte){
                                // banner 4: vermelho com os dados da empresa
                                $data['titulo'] = "Entre em contato com a sua empresa e com a área de saúde:";
                                $grupo = "Vermelho";

                                //Salva o grupo identificado como laranja suspeito
                                $dados = array('codigo_grupo_covid' => 5); //grupo laranja
                                if (!empty($registro)) { 
                                    $dados['codigo_usuario_alteracao'] = $codigo_usuario;
                                    $r = $this->UsuarioGrupoCovid->patchEntity($registro, $dados);
                                    if (!$this->UsuarioGrupoCovid->save($r)) {
                                        $error[] = $r->getValidationErrors();
                                        debug($error);
                                    }
                                }
                            }
                            else {
                                // banner 3: passaporte renovado
                                $data['titulo'] = "Passaporte de trabalho COVID-19";
                                $grupo = "Azul";
                            }

                        } else { // Não, não respondeu sintomas diários
                            
                            //Precisa responder o questionario de sintomas diários   
                            $data['titulo'] = "CHECK-UP DIÁRIO DA COVID-19";
                            $data['descricao'] = "Mantenha-nos informados sobre como você está se sentindo";
                            $data['codigo_questionario'] = $codigo_questionario_sintomasdiarios;                            

                        }

                        $data['grupo'] = $grupo;
                    }

                    if($grupo == "Preto")
                    {
                        $data['grupo'] = "Preto";
                    }

                }
            }
            
        }

        return $data;

    }

    /**
     * [setUsuarioGrupoCovid description]
     * @param [type] $codigo_usuario [description]
     * @param [type] $cpf            [description]
     */
    public function setUsuarioGrupoCovid($codigo_usuario)
    {

        //dados do usuario
        $this->UsuariosDados = TableRegistry::get('UsuariosDados');
        $usuarios_dados = $this->UsuariosDados->find()->where(['codigo_usuario' => $codigo_usuario])->first();
        $cpf = $usuarios_dados->cpf;

        //dados para pegar o grupo do usuario
        $codigo_questionario_retornoaotrabalho = 13;
        $codigo_questionario_sintomasdiarios = 16;

        $UsuariosQuestionarios = TableRegistry::getTableLocator()->get('UsuariosQuestionarios');
        $historico_resposta = $UsuariosQuestionarios->find()
            ->where([
                'codigo_usuario' => $codigo_usuario,
                'codigo_questionario' => $codigo_questionario_retornoaotrabalho,
                'finalizado' => 1        
            ])
            ->order(['data_inclusao' => 'desc'])
            // ->sql();
            ->hydrate(false)
            ->first();
        
        $grupo = '';
        if($historico_resposta){ 
            $grupo = $this->defineGrupo($historico_resposta['codigo']); 
        }

        if(empty($grupo)) {
           return ; 
        }

        //var_dump("retorno ao trabalho ".$grupo);

        $this->GrupoCovid = TableRegistry::get('GrupoCovid');
        $codigo_grupo_covid = $this->GrupoCovid->find()->select('codigo')->where(['descricao' => $grupo])->hydrate(false)->first();
                                         
        //Salva o grupo identificado
        $this->UsuarioGrupoCovid = TableRegistry::get('UsuarioGrupoCovid');
        $dados = array('codigo_grupo_covid' => $codigo_grupo_covid['codigo']);

        $registro = $this->UsuarioGrupoCovid->find()->where(['cpf' => $cpf])->first();

        if (!empty($registro)) { 
            $dados['codigo_usuario_alteracao'] = $codigo_usuario;
            $r = $this->UsuarioGrupoCovid->patchEntity($registro, $dados);
        } else {
            $dados['codigo_usuario'] = $codigo_usuario;             
            $dados['data_inclusao'] = date("Y-m-d H:i:s");
            $dados['codigo_usuario_inclusao'] = $codigo_usuario;
            $dados['ativo'] = 1;
            $dados['cpf'] = $cpf;
            $r = $this->UsuarioGrupoCovid->newEntity($dados);
        }

        if (!$this->UsuarioGrupoCovid->save($r)) {

            $error[] = $r->getValidationErrors();
            debug($error);
            // $error[] = $dados->errors();
            // $this->set(compact('error'));
            // return $error;
        }

        //Grava resultado
        $this->setResultadoCovid($codigo_usuario, $grupo);

    }// fim setUsuarioGrupoCovid

    function setResultadoCovid($codigo_usuario, $grupo){ 
        
        # $grupo = do questionario retorno ao trabalho (13)
        # Azul = Passaporte
        # Vermelho = Sem passaporte
        # Verde, Branco > sintomas diários        

        //var_dump($grupo);

        if($grupo == "Branco" || $grupo == "Verde"){ 

            $codigo_questionario_sintomasdiarios = 16;
            
            //respondeu questionario sintomas diários hoje?
            $UsuariosQuestionarios = TableRegistry::getTableLocator()->get('UsuariosQuestionarios');
            $historico_resposta = $UsuariosQuestionarios->find()
                ->where([
                    'codigo_usuario' => $codigo_usuario,
                    'codigo_questionario' => $codigo_questionario_sintomasdiarios,
                    'finalizado' => 1,
                    'DAY(concluido)' => date('d'),
                    'MONTH(concluido)' => date('m'),
                    'YEAR(concluido)' => date('Y')       
                ])
                ->order(['data_inclusao' => 'desc'])
                ->hydrate(false)
                ->first();

            if($historico_resposta){ //Sim, respondeu sintomas diários
                
                //Se nas ultimas respostas de sintomas diários tiver algum sintoma, muda para grupo laranja, porem caso a pontuacao seja menor que 2 nao gera grupo laranja
                
                                
                //Verifica se tem algum sintoma
                $codigo_historico_resposta = $historico_resposta['codigo'];
                $respostas = $this->find()
                    ->select(['codigo_historico_resposta', 'pontos' => 'SUM(pontos)'])
                    ->where([
                        'codigo_historico_resposta'=> $codigo_historico_resposta, 
                        'label'=> 'Sim',
                        'DAY(data_inclusao)' => date('d'),
                        'MONTH(data_inclusao)' => date('m'),
                        'YEAR(data_inclusao)' => date('Y')
                    ])
                    ->group(['codigo_historico_resposta'])
                    ->hydrate(false)
                    ->first();

                // debug($respostas->sql());exit;

                if(!empty($respostas)){// Tem sintoma

                    if($respostas['pontos'] > 2) {
                        //$grupo = "Vermelho";
                        $grupo = "Laranja";
                    }
                }

                $this->GrupoCovid = TableRegistry::get('GrupoCovid');
                $codigo_grupo_covid = $this->GrupoCovid->find()->select('codigo')->where(['descricao' => $grupo])->hydrate(false)->first();

                $this->ResultadoCovid = TableRegistry::get('ResultadoCovid');

                $dados = array();            
                $dados['codigo_usuario'] = $codigo_usuario;      
                $dados['codigo_grupo_covid'] = $codigo_grupo_covid['codigo'];   
                $dados['passaporte'] = ($grupo != "Laranja" ? 1:0);      
                $dados['data_inclusao'] = date("Y-m-d H:i:s");
                $dados['codigo_usuario_inclusao'] = $codigo_usuario;
                
                $r = $this->ResultadoCovid->newEntity($dados);

                if (!$this->ResultadoCovid->save($r)) {
                    $error[] = $r->getValidationErrors();
                    debug($error);
                }

            } 

        }else if($grupo == "Vermelho" || $grupo == "Azul"){

            $this->GrupoCovid = TableRegistry::get('GrupoCovid');
            $codigo_grupo_covid = $this->GrupoCovid->find()->select('codigo')->where(['descricao' => $grupo])->hydrate(false)->first();

            $this->ResultadoCovid = TableRegistry::get('ResultadoCovid');

            $dados = array();            
            $dados['codigo_usuario'] = $codigo_usuario;      
            $dados['codigo_grupo_covid'] = $codigo_grupo_covid['codigo'];   
            $dados['passaporte'] = ($grupo != "Vermelho" ? 1:0);      
            $dados['data_inclusao'] = date("Y-m-d H:i:s");
            $dados['codigo_usuario_inclusao'] = $codigo_usuario;
            
            $r = $this->ResultadoCovid->newEntity($dados);

            if (!$this->ResultadoCovid->save($r)) {
                $error[] = $r->getValidationErrors();
                debug($error);
            }
        }

    }
 
}
