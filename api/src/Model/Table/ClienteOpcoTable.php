<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use Cake\Datasource\ConnectionManager;

/**
 * ClienteOpco Model
 *
 * @method \App\Model\Entity\ClienteOpco get($primaryKey, $options = [])
 * @method \App\Model\Entity\ClienteOpco newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\ClienteOpco[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\ClienteOpco|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClienteOpco saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\ClienteOpco patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\ClienteOpco[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\ClienteOpco findOrCreate($search, callable $callback = null, $options = [])
 */
class ClienteOpcoTable extends Table
{

    public $connect;

    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('cliente_opco');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->connect = ConnectionManager::get('default');
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
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->scalar('codigo_cliente_externo')
            ->maxLength('codigo_cliente_externo', 255)
            ->allowEmptyString('codigo_cliente_externo');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->allowEmptyString('descricao');

        $validator
            ->allowEmptyString('ativo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        return $validator;
    }

    public function obterArrayConciliacoes()
    {

        $duplicatas = $this->obterDuplicatas();

        $conciliacoes = [];
        foreach ($duplicatas as $duplicata) {
            $conciliacoes[$duplicata['codigo_cliente']][$duplicata['codigo_cliente_externo']][] = $duplicata['codigo'];
        }

        return $conciliacoes;
    }

    private function obterDuplicatas()
    {
        $stmt = $this->connect->execute('SELECT a.codigo, a.codigo_cliente_externo, a.codigo_cliente
        FROM cliente_opco a
        JOIN
          (SELECT codigo_cliente_externo,
                  codigo_cliente,  		  
                  COUNT(*) as qtde
           FROM cliente_opco
           WHERE ativo = 1
           GROUP BY codigo_cliente_externo, codigo_cliente
           HAVING count(*) > 1) b ON a.codigo_cliente_externo  = b.codigo_cliente_externo AND a.codigo_cliente  = b.codigo_cliente
        AND a.codigo_cliente_externo = b.codigo_cliente_externo
        AND a.codigo_cliente = b.codigo_cliente
        ORDER BY a.codigo_cliente,
                a.codigo_cliente_externo,
                a.codigo');

        return $stmt->fetchAll('assoc');
    }

    public function conciliarDuplicatas($arrCodigosDuplicatas)
    {

        try {

            $this->addBehavior('Loggable');

            $this->find()
                ->where([
                    'codigo IN' => $arrCodigosDuplicatas,
                ])
                ->update()
                ->set([
                    'ativo' => 0,
                ])
                ->execute();
        } catch (\Exception $e) {

            throw $e;
        } finally {

            $this->behaviors()->unload('Loggable');
        }
    }
}
