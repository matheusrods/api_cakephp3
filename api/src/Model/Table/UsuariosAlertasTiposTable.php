<?php
namespace App\Model\Table;

use Cake\Datasource\ConnectionManager;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * UsuariosAlertasTipos Model
 *
 * @method \App\Model\Entity\UsuariosAlertasTipo get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuariosAlertasTipo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuariosAlertasTipo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosAlertasTipo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosAlertasTipo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosAlertasTipo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosAlertasTipo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosAlertasTipo findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuariosAlertasTiposTable extends AppTable
{
    public $connection;
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);
        $this->connection = ConnectionManager::get('default');
        $this->setTable('usuarios_alertas_tipos');
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
            ->integer('codigo_alerta_tipo')
            ->requirePresence('codigo_alerta_tipo', 'create')
            ->notEmptyString('codigo_alerta_tipo');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        return $validator;
    }

    public function getAlertasTiposPorAgrupamento()
    {
        $query = "select at.codigo as codigo_alerta_tipo, at.descricao as alerta_tipo, at.codigo_alerta_agrupamento, aa.descricao as alerta_agrupamento from alertas_tipos at
                    inner join alertas_agrupamento aa
                    on at.codigo_alerta_agrupamento = aa.codigo and at.interno = 'S'
                    order by at.codigo_alerta_agrupamento asc";

        $result = $this->connection->execute($query)->fetchAll('assoc');

        return $result;
    }

    public function getAlertasTiposPorAgrupamentoUsuario($codigo_usuario, $alerta)
    {
        $query = "select at.codigo as codigo_alerta_tipo, at.descricao as alerta_tipo, at.codigo_alerta_agrupamento, aa.descricao as alerta_agrupamento  from alertas_tipos at
                inner join alertas_agrupamento aa on at.codigo_alerta_agrupamento = aa.codigo and at.interno = 'S'
                inner join usuarios_alertas_tipos uat on uat.codigo_alerta_tipo = at.codigo and uat.codigo_usuario = ".$codigo_usuario." and uat.codigo_alerta_tipo = ".$alerta['codigo_alerta_tipo']."";

        $result = $this->connection->execute($query)->fetchAll('assoc');

        return $result;
    }
}
