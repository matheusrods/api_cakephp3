<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * UsuarioExamesImagens Model
 *
 * @method \App\Model\Entity\UsuarioExamesImagen get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuarioExamesImagen newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuarioExamesImagen[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioExamesImagen|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioExamesImagen saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuarioExamesImagen patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioExamesImagen[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuarioExamesImagen findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuarioExamesImagensTable extends AppTable
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

        $this->setTable('usuario_exames_imagens');
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
            ->integer('codigo_usuario_exames')
            ->allowEmptyString('codigo_usuario_exames');

        $validator
            ->scalar('imagem')
            ->maxLength('imagem', 255)
            ->allowEmptyFile('imagem');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        return $validator;
    }



    function imagensExames($codigoExame) {

        $fields = array(
            'url' => 'imagem'
        );
        
        $conditions = array(
            'codigo_usuario_exames' => $codigoExame
        );

        $dados = $this->find()
            ->select($fields)
            ->where($conditions);

        return $dados;
    }

}
