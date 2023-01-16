<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * IndicadoresImagens Model
 *
 * @method \App\Model\Entity\IndicadoresImagen get($primaryKey, $options = [])
 * @method \App\Model\Entity\IndicadoresImagen newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\IndicadoresImagen[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\IndicadoresImagen|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IndicadoresImagen saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\IndicadoresImagen patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\IndicadoresImagen[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\IndicadoresImagen findOrCreate($search, callable $callback = null, $options = [])
 */
class IndicadoresImagensTable extends AppTable
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

        $this->setTable('indicadores_imagens');
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
            ->scalar('sexo')
            ->maxLength('sexo', 1)
            ->allowEmptyString('sexo');

        $validator
            ->scalar('imagem')
            ->maxLength('imagem', 255)
            ->allowEmptyFile('imagem');

        $validator
            ->scalar('categoria')
            ->maxLength('categoria', 50)
            ->allowEmptyString('categoria');

        $validator
            ->scalar('valor_inicial')
            ->maxLength('valor_inicial', 50)
            ->allowEmptyString('valor_inicial');

        $validator
            ->scalar('valor_final')
            ->maxLength('valor_final', 50)
            ->allowEmptyString('valor_final');

        return $validator;
    }

    /***
     * Função de busca de imagens dos indicadores
     * @param $dados
     * @param $sexo
     * @return array|\Cake\Datasource\EntityInterface|null
     */
    public function getIndicadoresImagens($dados, $sexo) {
        $select = array(
            'imagem' => 'IndicadoresImagens.imagem',
            'categoria' => 'IndicadoresImagens.categoria'
        );

        $conditions = array(
            'IndicadoresImagens.sexo' => $sexo['sexo'],
            $dados . ' BETWEEN cast(IndicadoresImagens.valor_inicial AS FLOAT) AND cast(IndicadoresImagens.valor_final AS FLOAT)',
            'IndicadoresImagens.tipo' => 'imc'
        );

        $dados = $this->find()
            ->select($select)
            ->where($conditions)
            ->enableHydration(false)
            ->first()
            ;

        return $dados;
    }
}

