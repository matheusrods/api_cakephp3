<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * ProcessosFerramentas Model
 *
 * @method \App\Model\Entity\ProcessosFerramenta get($primaryKey, $options = [])
 * @method \App\Model\Entity\ProcessosFerramenta newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ProcessosFerramenta[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ProcessosFerramenta|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProcessosFerramenta saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ProcessosFerramenta patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ProcessosFerramenta[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ProcessosFerramenta findOrCreate($search, callable $callback = null, $options = [])
 */
class ProcessosFerramentasTable extends Table
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

        $this->setTable('processos_ferramentas');
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
            ->integer('codigo_processo')
            ->requirePresence('codigo_processo', 'create')
            ->notEmptyString('codigo_processo');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->allowEmptyString('descricao');

        $validator
            ->scalar('equipamentos')
            ->maxLength('equipamentos', 255)
            ->allowEmptyString('equipamentos');

        $validator
            ->scalar('finalidades')
            ->maxLength('finalidades', 255)
            ->allowEmptyString('finalidades');

        $validator
            ->integer('posicao')
            ->allowEmptyString('posicao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }

    public function getEtapas($codigo_processo)
    {

        $fields = array(
            'codigo'  => 'ProcessosFerramentas.codigo',
            'codigo_processo'  => 'ProcessosFerramentas.codigo_processo',
            'descricao'  => 'ProcessosFerramentas.descricao',
            'posicao'  => 'ProcessosFerramentas.posicao'
        );

        $conditions = " ProcessosFerramentas.codigo_processo = " . $codigo_processo . " " ;

        $dados = $this->find()
            ->select($fields)
            ->where($conditions);

        return $dados;
    }

    public function getHazop($codigo_processo)
    {

        $fields = array(
            'codigo'  => 'ProcessosFerramentas.codigo',
            'codigo_processo'  => 'ProcessosFerramentas.codigo_processo',
            'descricao'  => 'ProcessosFerramentas.descricao',
            'equipamentos'  => 'ProcessosFerramentas.equipamentos',
            'finalidades'  => 'ProcessosFerramentas.finalidades'
        );

        $conditions = " ProcessosFerramentas.codigo_processo = " . $codigo_processo . " " ;

        $dados = $this->find()
            ->select($fields)
            ->where($conditions);

        return $dados;
    }
}
