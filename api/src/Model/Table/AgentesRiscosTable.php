<?php
namespace App\Model\Table;

use Cake\Datasource\ConnectionManager;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * AgentesRiscos Model
 *
 * @method \App\Model\Entity\AgentesRisco get($primaryKey, $options = [])
 * @method \App\Model\Entity\AgentesRisco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\AgentesRisco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\AgentesRisco|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AgentesRisco saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\AgentesRisco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\AgentesRisco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\AgentesRisco findOrCreate($search, callable $callback = null, $options = [])
 */
class AgentesRiscosTable extends Table
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

        $this->setTable('agentes_riscos');
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
            ->scalar('descricao_risco')
            ->maxLength('descricao_risco', 255)
            ->allowEmptyString('descricao_risco');

        $validator
            ->scalar('descricao_exposicao')
            ->maxLength('descricao_exposicao', 255)
            ->allowEmptyString('descricao_exposicao');

        $validator
            ->integer('pessoas_expostas')
            ->allowEmptyString('pessoas_expostas');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_remocao')
            ->allowEmptyDateTime('data_remocao');

        return $validator;
    }

    public function getAgentesRiscosByGhe($codigo_setor, $codigo_cargo)
    {

        $dados = array();

        $query = "select g.codigo, cgh.codigo_ghe, cgh.codigo as codigo_csc_ghe, csc.codigo_setor, csc.codigo_cargo,
                  ap.codigo_ar_rt,
                  RHHealth.dbo.ufn_decode_utf8_string(rt.descricao) as tipo_risco_descricao,
                  rt.codigo as tipo_risco_codigo,
                  rt.icone as tipo_risco_icone,
                  ar.codigo_arrt_pa,
                  RHHealth.dbo.ufn_decode_utf8_string(pa.descricao) as perigos_aspectos_descricao,
                  pa.codigo as perigos_aspectos_codigo,
                  agh.codigo_arrtpa_ri,
                  RHHealth.dbo.ufn_decode_utf8_string(ri.descricao) as riscos_impactos_descricao,
                  ri.codigo as riscos_impactos_descricao_codigo,
                  ri.codigo_risco_impacto_tipo as riscos_impactos_tipo,
                  agrt.codigo_agente_risco
                  from ghe g
                  INNER JOIN csc_ghe cgh ON cgh.codigo_ghe = g.codigo
                  INNER JOIN clientes_setores_cargos csc ON csc.codigo = cgh.codigo_clientes_setores_cargos
                  INNER JOIN arrtpari_ghe agh ON agh.codigo_ghe = g.codigo
                  INNER JOIN arrtpa_ri ar ON agh.codigo_arrtpa_ri = ar.codigo
                  INNER JOIN riscos_impactos ri ON ar.codigo_risco_impacto = ri.codigo
                  INNER JOIN arrt_pa ap ON ar.codigo_arrt_pa = ap.codigo
                  INNER JOIN perigos_aspectos pa ON ap.codigo_perigo_aspecto = pa.codigo
                  INNER JOIN ar_rt agrt ON ap.codigo_ar_rt = agrt.codigo
                  INNER JOIN riscos_tipo rt ON agrt.codigo_risco_tipo = rt.codigo
                    where csc.codigo_setor = ".$codigo_setor." and csc.codigo_cargo = ".$codigo_cargo." ";

        //executa a query
        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');
        return $dados;
    }
}
