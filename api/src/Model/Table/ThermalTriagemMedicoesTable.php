<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use App\Utils\DatetimeUtil;

/**
 * ThermalTriagemMedicoes Model
 *
 * @method \App\Model\Entity\ThermalTriagemMedico get($primaryKey, $options = [])
 * @method \App\Model\Entity\ThermalTriagemMedico newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ThermalTriagemMedico[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ThermalTriagemMedico|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ThermalTriagemMedico saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ThermalTriagemMedico patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ThermalTriagemMedico[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ThermalTriagemMedico findOrCreate($search, callable $callback = null, $options = [])
 */
class ThermalTriagemMedicoesTable extends AppTable
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

        $this->setTable('thermal_triagem_medicoes');
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
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->scalar('cpf')
            ->maxLength('cpf', 11)
            ->requirePresence('cpf', 'create')
            ->notEmptyString('cpf');

        $validator
            ->scalar('nome')
            ->maxLength('nome', 255)
            ->allowEmptyString('nome');

        $validator
            ->scalar('temperatura_medida')
            ->maxLength('temperatura_medida', 3)
            ->requirePresence('temperatura_medida', 'create')
            ->notEmptyString('temperatura_medida');

        $validator
            ->dateTime('data_medicao')
            ->requirePresence('data_medicao', 'create')
            ->notEmptyDateTime('data_medicao');

        $validator
            ->decimal('latitude')
            ->allowEmptyString('latitude');

        $validator
            ->decimal('longitude')
            ->allowEmptyString('longitude');

        $validator
            ->scalar('imagem_medicao')
            ->allowEmptyFile('imagem_medicao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        return $validator;
    }


    /**
     * Obter lista de triagens feitas por data
     *
     * @param int $codigo_cliente_matriz
     * @param array $conditions
     * @param array $dataHora
     * @param string $tipoOperacao
     * @return ORM/Query
     */
    public function obterMedicoes(int $codigo_cliente_matriz, array $conditions = [], $dataHora, string $tipoOperacao = 'eq')
    {
        
        $_dataHora = [];
        $op = $this->evaluateConditional($tipoOperacao); // avalia qual operador será usado

        if(!isset($dataHora['inicio'])){
            $dataHora['inicio'] = $this->DATA_HORA_INICIO_PADRAO;
        }
        
        if(!isset($dataHora['fim'])){
            $dataHora['fim'] = $this->DATA_HORA_FIM_PADRAO;
        }
        
        $conditions['codigo_cliente'] = $codigo_cliente_matriz;
        $conditions["data_medicao <="] = $dataHora['fim'];
        $conditions["data_medicao >="] = $dataHora['inicio'];
    
        return $this->find()->where($conditions);
    }

}
