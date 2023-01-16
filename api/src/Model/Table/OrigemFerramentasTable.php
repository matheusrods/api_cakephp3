<?php

namespace App\Model\Table;

use Cake\Log\Log;
use Cake\ORM\Table;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;

/**
 * OrigemFerramenta Model
 *
 * @method \App\Model\Entity\OrigemFerramenta get($primaryKey, $options = [])
 * @method \App\Model\Entity\OrigemFerramenta newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\OrigemFerramenta[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\OrigemFerramenta|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrigemFerramenta saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\OrigemFerramenta patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\OrigemFerramenta[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\OrigemFerramenta findOrCreate($search, callable $callback = null, $options = [])
 */
class OrigemFerramentasTable extends Table
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

        $this->setTable('origem_ferramentas');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_origem_ferramenta');
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
            ->integer('codigo_cliente', 'O campo de cliente precisa ser um número inteiro.')
            ->requirePresence('codigo_cliente', 'create', 'O campo de cliente é obrigatório.')
            ->notEmptyString('codigo_cliente', 'O campo de cliente não pode ser deixado em branco.');

        $validator
            ->scalar('formulario')
            ->requirePresence('formulario', 'create', 'O campo de formulário é obrigatório.')
            ->notEmptyString('formulario', 'O campo de formulário não pode ser deixado em branco.');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 255, 'O campo de descrição só poder ter no máximo 255 caracteres.')
            ->requirePresence('descricao', 'create', 'O campo de descrição é obrigatório.')
            ->notEmptyString('descricao', 'O campo de descrição não pode ser deixado em branco.');

        $validator
            ->boolean('ativo')
            ->allowEmptyString('ativo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->dateTime('data_remocao')
            ->allowEmptyDateTime('data_remocao');

        return $validator;
    }

    public function getAll($clientCode, $ativo = 1)
    {
        $fields = [
            'codigo'         => 'OrigemFerramentas.codigo',
            'codigo_cliente' => 'OrigemFerramentas.codigo_cliente',
            'descricao'      => 'OrigemFerramentas.descricao',
            'ativo'          => 'OrigemFerramentas.ativo',
            'chave'          => 'Configuracao.chave'
        ];

        $joins = [
            [
                'table'      => 'configuracao',
                'alias'      => 'Configuracao',
                'type'       => 'LEFT',
                'conditions' => 'Configuracao.valor = CAST(OrigemFerramentas.codigo_produto as varchar(255))',
            ]
        ];

        $conditions = [];

        if ($clientCode) {
            $this->Usuario = TableRegistry::getTableLocator()->get('Usuario');

            $economicGroup = $this->Usuario->getClienteGrupoEconomico((int) $clientCode);

            if (!empty($economicGroup)) {
                $conditions = [
                    'OrigemFerramentas.ativo'          => $ativo,
                    'OrigemFerramentas.codigo_cliente' => $economicGroup['codigo_cliente']
                ];
            } else {
                return [];
            }
        }

        try {
            $data = $this->find()
                ->select($fields)
                ->where($conditions)
                ->join($joins)
                ->orderAsc('descricao')
                ->all()
                ->toArray();

            return $data;
        } catch (\Exception $exception) {
            Log::debug($exception);
            return [];
        }
    }

    public function getById($originToolCode)
    {
        $data = [
            'error' => null,
            'registry' => null,
        ];

        $conditions = [
            'codigo' => $originToolCode,
            'ativo' => 1,
        ];

        try {
            $originTool = $this->find()
                ->where($conditions)
                ->first();

            if (!$originTool) {
                $data['error'] = [
                    'message' => 'Não foi encontrado dados referente ao código informado.',
                ];

                return $data;
            }

            $data['registry'] = $originTool;

            return $data;
        } catch (\Exception $exception) {
            $data = [
                'error' => [
                    'message' => $exception->getMessage(),
                ],
                'registry' => null,
            ];

            return $data;
        }
    }
}
