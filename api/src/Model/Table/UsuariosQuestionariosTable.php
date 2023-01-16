<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * UsuariosQuestionarios Model
 *
 * @method \App\Model\Entity\UsuariosQuestionario get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuariosQuestionario newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuariosQuestionario[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosQuestionario|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosQuestionario saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosQuestionario patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosQuestionario[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosQuestionario findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuariosQuestionariosTable extends AppTable
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

        $this->setTable('usuarios_questionarios');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');
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
            ->integer('codigo_usuario')
            ->requirePresence('codigo_usuario', 'create')
            ->notEmptyString('codigo_usuario');

        $validator
            ->integer('codigo_questionario')
            ->requirePresence('codigo_questionario', 'create')
            ->notEmptyString('codigo_questionario');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->boolean('finalizado')
            ->allowEmptyString('finalizado');

        $validator
            ->dateTime('concluido')
            ->allowEmptyDateTime('concluido');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        return $validator;
    }

    /**
     * Obter dados de histórico ativo de um questionario para um codigo_usuario, 
     * colocar aqui as regras sobre um questionario a ser respondido
     *
     * @param int $codigo_usuario
     * @param int $codigo_questionario
     * @return array|null
     */
    public function obterHistoricoAtivo(int $codigo_usuario, int $codigo_questionario){

        //verifica se existe no historico
        $conditions = [
            'codigo_usuario' => $codigo_usuario,
            'codigo_questionario' => $codigo_questionario,
            'data_inclusao >= ' => date('Y-m-d', strtotime(date('Y-m-d'). '- 30 days')),
            'finalizado IS ' => NULL
        ];
        
        $questionario = $this->find()
            ->where($conditions)
            ->first();
        
        return $questionario;
    }

    /**
     * Verifica se existe histórico válido de questionario para um codigo_usuario
     *
     * @param int $codigo_usuario
     * @param int $codigo_questionario
     * @return boolean
     */
    public function validaHistoricoAtivo(int $codigo_usuario, int $codigo_questionario){

            $questionario = $this->obterHistoricoAtivo($codigo_usuario, $codigo_questionario);
            
            if(!empty($questionario)){
                return (bool)$questionario->finalizado;
            }

            return false;
    }

    public function salvarHistoricoAtivo(int $codigo_usuario, int $codigo_questionario, string $latitude=null, string $longitude=null, int $codigo_empresa = 1){

        $data = [];

        $params = [ 
            'codigo_usuario' => $codigo_usuario,
            'codigo_questionario' => $codigo_questionario,
            'codigo_empresa' => $codigo_empresa,
            'latitude' => $latitude,
            'longitude' => $longitude
        ];

        $r = $this->newEntity($params);
        $r->set(['codigo_usuario_inclusao'=> $codigo_usuario]);

        if (!$this->save($r)) {
            $error = $r->getValidationErrors();
            return ['error'=> $error];
        }
        
        $data['codigo'] = isset($r->codigo) ? $r->codigo : null;

        return $data;
    }    

    public function finalizarQuestionario(int $codigo_usuario, int $codigo_historico_resposta, int $codigo_questionario){

        $data = [];

        $entidade_atualizar = $this->get(['codigo' => $codigo_historico_resposta]);

        $params = ['finalizado'=> 1, 'concluido'=> date('Y-m-d H:i:s')];
        
        $entidade = $this->patchEntity($entidade_atualizar, $params );

        if (!$this->save($entidade)) {
            $error = $entidade->getValidationErrors();
            return ['error'=> $error];
        }

        return $data;
    } 

     /**
     * [getUltimosQuestionariosRespondidos description]
     * 
     * pega os dados do ultima vez que o usuario respondeu os questionarios retornando codigo_questionario, codigo_funcioario, codigo_usuario, data_concluido
     * 
     * @param  [type] $codigo_usuario      [description]
     * @param  [type] $codigo_questionario [description]
     * @return [type]                      [description]
     */
    public function getUltimosQuestionariosRespondidos($codigo_usuario, $codigo_questionario = null)
    {

        //verifica se o codigo_questionario esta null
        if(!empty($codigo_questionario)) {
            $conditions['UsuariosQuestionarios.codigo_questionario'] = $codigo_questionario;
        }//fim codigo_questionario

        //monta os fields
        $fields = array(
            'codigo_questionario'=>'UsuariosQuestionarios.codigo_questionario',
            'codigo_funcionario' => 'Funcionarios.codigo',
            'codigo_usuario'=>'UsuariosQuestionarios.codigo_usuario',
            'data_concluido'=>'(SELECT TOP 1 CONVERT(CHAR(8), concluido,112) FROM usuarios_questionarios WHERE codigo_usuario = UsuariosQuestionarios.codigo_usuario AND codigo_questionario = UsuariosQuestionarios.codigo_questionario GROUP BY CONVERT(CHAR(8),concluido,112) ORDER BY CONVERT(CHAR(8),concluido,112) DESC)',
        );

        //monta os relacionamentos
        $joins = array(
            array(
                'table' => 'usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'INNER',
                'conditions' => 'UsuariosQuestionarios.codigo_usuario = UsuariosDados.codigo_usuario'
            ),
            array(
                'table' => 'funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.cpf = Funcionarios.cpf'
            ),
        );
        //agrupamento da query
        $group_by = array('UsuariosQuestionarios.codigo_questionario','Funcionarios.codigo','UsuariosQuestionarios.codigo_usuario');
        
        $conditions['UsuariosQuestionarios.codigo_usuario'] = $codigo_usuario;
        //executa a query
        $dados = $this->find()
                        ->select($fields)
                        ->join($joins)
                        ->where($conditions)
                        ->group($group_by)
                        ->all();

        // debug($dados);exit;

        return $dados;

    }//fim getUltimosQuestionariosRespondidos

    /**
     * Busca o último histórico do questionário finalizado de um usuário
     *
     * @param int $codigo_usuario
     * @param int $codigo_questionario
     * @return array|null
     */
    public function getHistoricoQuestionarioFinalizado($codigo_usuario, $codigo_questionario, $hoje){

        //verifica se existe no historico
        $conditions = [
            'codigo_usuario' => $codigo_usuario,
            'codigo_questionario' => $codigo_questionario,
            'finalizado' => 1
        ];

        if($hoje == true){
            $conditions['DAY(concluido)'] = date('d');
            $conditions['MONTH(concluido)'] = date('m');
            $conditions['YEAR(concluido)'] = date('Y');
        }

        $order = ['data_inclusao' => 'desc'];
        
        $dados = $this->find()
            ->where($conditions)
            ->order($order)
            ->hydrate(false)
            ->first();

        return $dados;
        
    }//getHistoricoQuestionarioFinalizado

}
