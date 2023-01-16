<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use function foo\func;

/**
 * PdaTema Model
 *
 * @method \App\Model\Entity\PdaTema get($primaryKey, $options = [])
 * @method \App\Model\Entity\PdaTema newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PdaTema[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PdaTema|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PdaTema saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PdaTema patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PdaTema[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PdaTema findOrCreate($search, callable $callback = null, $options = [])
 */
class PdaTemaTable extends Table
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

        $this->setTable('pda_tema');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');
        $this->hasOne('PdaTemaCondicao', [
            'bindingKey'   => 'codigo_pda_tema_condicao',
            'foreignKey'   => 'codigo',
            'joinTable'    => 'pda_tema_condicao',
            'propertyName' => 'condicao',
            'conditions'   => ['PdaTemaCondicao.ativo' => 1],
        ]);
        $this->hasMany('PdaConfigRegra', [
            'foreignKey'       => 'codigo_pda_tema',
            'bindingKey'       => 'codigo',
            'joinTable'        => 'pda_config_regra',
            'propertyName'     => 'regras',
            'conditions'       => [
                'PdaConfigRegra.ativo' => 1
            ],
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
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_pda_tema_condicao')
            ->allowEmptyString('codigo_pda_tema_condicao');

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

    /**
     * Procura por temas de acordo com o codigo da ferramenta
     * @uses Relacionamento PdaTemaCondicao
     * @uses Relacionamento PdaConfigRegra
     * @param int $codigo_tema
     * @param int $codigo_ferramenta
     * @param int $codigo_cliente
     * @return \Cake\Datasource\ResultSetInterface
     */
    public function buscarTemaComRelacionamentosPor(int $codigo_tema, int $codigo_ferramenta, int $codigo_cliente = null)
    {
        $temas = $this->find()
            ->where([
                'PdaTema.ativo'                 => 1,
                'PdaTema.codigo_pos_ferramenta' => $codigo_ferramenta,
                'PdaTema.codigo'                => $codigo_tema
            ])
            ->contain([
                'PdaTemaCondicao',
                'PdaConfigRegra' => [
                    'queryBuilder' => function ($query) use ($codigo_cliente) {
                        if (is_null($codigo_cliente)) {
                            return  $query->select([
                                'PdaConfigRegra.codigo',
                                'PdaConfigRegra.codigo_pda_tema',
                                'PdaConfigRegra.codigo_empresa',
                                'PdaConfigRegra.codigo_cliente',
                                'descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(PdaConfigRegra.descricao)',
                                'assunto'   => 'RHHealth.dbo.ufn_decode_utf8_string(PdaConfigRegra.assunto)',
                                'mensagem'  => 'RHHealth.dbo.ufn_decode_utf8_string(PdaConfigRegra.mensagem)'
                            ])
                            ->contain([
                                'PdaConfigRegraCondicao',
                                'PdaConfigRegraAcao'
                            ]);
                        }

                        return $query->select([
                            'PdaConfigRegra.codigo',
                            'PdaConfigRegra.codigo_pda_tema',
                            'PdaConfigRegra.codigo_empresa',
                            'PdaConfigRegra.codigo_cliente',
                            'descricao' => 'RHHealth.dbo.ufn_decode_utf8_string(PdaConfigRegra.descricao)',
                            'assunto'   => 'RHHealth.dbo.ufn_decode_utf8_string(PdaConfigRegra.assunto)',
                            'mensagem'  => 'RHHealth.dbo.ufn_decode_utf8_string(PdaConfigRegra.mensagem)'
                        ])
                        ->where([
                            'PdaConfigRegra.codigo_cliente' => $codigo_cliente
                        ])
                        ->contain([
                            'PdaConfigRegraCondicao',
                            'PdaConfigRegraAcao'
                        ]);
                    }
                ]
            ])
            ->first();

        return $temas;
    }
}
