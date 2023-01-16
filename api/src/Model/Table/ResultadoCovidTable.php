<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Cake\Datasource\ConnectionManager;

/**
 * ResultadoCovid Model
 *
 * @method \App\Model\Entity\ResultadoCovid get($primaryKey, $options = [])
 * @method \App\Model\Entity\ResultadoCovid newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ResultadoCovid[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ResultadoCovid|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ResultadoCovid saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ResultadoCovid patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ResultadoCovid[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ResultadoCovid findOrCreate($search, callable $callback = null, $options = [])
 */
class ResultadoCovidTable extends Table
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

        $this->setTable('resultado_covid');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_resultado_covid');
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
            ->integer('codigo_grupo_covid')
            ->requirePresence('codigo_grupo_covid', 'create')
            ->notEmptyString('codigo_grupo_covid');

        $validator
            ->integer('passaporte')
            ->allowEmptyString('passaporte');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        return $validator;
    }

    /**
     * [getDadosResultadoCovid pega os dados do usuario pelo reusltado covid]
     * @return [type] [description]
     */
    public function getDadosResultadoCovid($codigo_resultado_covid)
    {

        //fields
        $fields = array(
            'foto' => 'UsuariosDados.avatar',
            'nome' => 'RHHealth.dbo.ufn_decode_utf8_string(Funcionarios.nome)',
            'cpf' => 'RHHealth.publico.ufn_formata_cpf(UsuariosDados.cpf)',
            'data_gerado' => 'cast(ResultadoCovid.data_inclusao as DATE)',
            'hora_gerado' => 'CONVERT(VARCHAR,ResultadoCovid.data_inclusao,108)',

            // 'data_validacao' => 'dateadd(day,1,cast(ResultadoCovid.data_inclusao AS DATE))',
            'data_validacao' => "CONCAT(cast(ResultadoCovid.data_inclusao AS DATE),' 23:59:59')",
            'resultado_passaporte' => "CONVERT(VARCHAR(10),(CASE WHEN ResultadoCovid.passaporte = '1' THEN 'Verde' ELSE 'Vermelho' END))",
            'matricula' => "(SELECT  ( CASE WHEN cf.matricula IS NOT NULL AND cf.matricula <> '' THEN  LEFT(CONCAT(cf.matricula,','), LEN(CONCAT(cf.matricula,'|'))-1) ELSE 'Não Informado' END)
                                    FROM cliente_funcionario cf
                                    WHERE cf.codigo_funcionario = Funcionarios.codigo
                                        AND cf.ativo <> 0
                                    )",

        );

        //relacionamentos
        $joins = array(
            array(
                'table' => 'RHHealth.dbo.usuario',
                'alias' => 'Usuario',
                'type' => 'INNER',
                'conditions' => 'Usuario.codigo = ResultadoCovid.codigo_usuario'
            ),
            array(
                'table' => 'RHHealth.dbo.usuarios_dados',
                'alias' => 'UsuariosDados',
                'type' => 'INNER',
                'conditions' => 'Usuario.codigo = UsuariosDados.codigo_usuario'
            ),
            array(
                'table' => 'RHHealth.dbo.funcionarios',
                'alias' => 'Funcionarios',
                'type' => 'INNER',
                'conditions' => 'UsuariosDados.cpf = Funcionarios.cpf'
            )
        );

        //filtros
        $conditions = array(
            'ResultadoCovid.codigo' => $codigo_resultado_covid,
            "CAST(ResultadoCovid.data_inclusao AS DATE) >= '" . date('Y-m-d') ."'"
        );

        $dados = $this->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->first();
        
        // debug($dados->sql());exit;
        
        return $dados;

    }//fim getResultadoCovid

    /**
     * [getUsuariosSemResultado pega os usuarios que nao geram passaporte hoje]
     * @return [type] [description]
     */
    public function getUsuariosSemResultado($data)
    {

        //monta a query
        $query = "
            SELECT u.codigo, u.nome, ugc.codigo_grupo_covid, us.platform,us.token_push, ud.telefone
            FROM cliente_questionarios cq
                INNER JOIN cliente c ON cq.codigo_cliente = c.codigo
                INNER JOIN funcionario_setores_cargos fsc ON fsc.codigo_cliente_alocacao = cq.codigo_cliente
                    AND fsc.data_fim is null
                INNER JOIN cliente_funcionario cf ON fsc.codigo_cliente_funcionario = cf.codigo
                    AND cf.ativo <> 0
                INNER JOIN funcionarios f ON f.codigo = cf.codigo_funcionario
                INNER JOIN usuarios_dados ud ON ud.cpf = f.cpf AND ud.notificacao = 1
                INNER JOIN usuario u ON ud.codigo_usuario = u.codigo
                INNER JOIN usuario_sistema us ON u.codigo = us.codigo_usuario AND us.codigo_sistema = 1 -- lyn
                INNER JOIN usuario_grupo_covid ugc ON u.codigo = ugc.codigo_usuario
                    AND ugc.codigo_grupo_covid IN (1,2,3)
                LEFT JOIN  resultado_covid rc ON u.codigo = rc.codigo_usuario
                     AND rc.data_inclusao >= '".$data."'
            WHERE c.ativo = 1
                AND cq.codigo_questionario = 16
                AND rc.codigo IS NULL
                AND us.token_push IS NOT NULL
                AND us.platform IS NOT NULL
            GROUP BY u.codigo, u.nome, ugc.codigo_grupo_covid, us.platform,us.token_push, ud.telefone
        ";

        $connection = ConnectionManager::get('default');
        $dados = $connection->execute($query)->fetchAll('assoc');

        return $dados;

    }//fim getUsuarioSemResultado

}
