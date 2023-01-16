<?php

namespace App\Model\Table;

use Cake\ORM\TableRegistry;
use App\Model\Table\PosTable as Table;
use Cake\Validation\Validator;
use Cake\Collection\Collection;
use App\Utils\Comum;

/**
 * PosObsParticipantes Model
 *
 * @method \App\Model\Entity\PosObsParticipante get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosObsParticipante newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosObsParticipante[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosObsParticipante|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsParticipante saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosObsParticipante patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsParticipante[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosObsParticipante findOrCreate($search, callable $callback = null, $options = [])
 */
class PosObsParticipantesTable extends Table
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

        $this->setTable('pos_obs_participantes');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->hasOne('UsuarioResponsavel', [
            'className'    => 'Usuario',
            'bindingKey'   => 'codigo_usuario',
            'foreignKey'   => 'codigo',
            'joinTable'    => 'usuario',
            'propertyName' => 'responsavel',
        ]);

        $this->hasOne('UsuarioIdentificador', [
            'className'    => 'Usuario',
            'bindingKey'   => 'codigo_usuario',
            'foreignKey'   => 'codigo',
            'joinTable'    => 'usuario',
            'propertyName' => 'identificador',
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
            ->integer('codigo_pos_obs_observacao')
            ->requirePresence('codigo_pos_obs_observacao', 'create')
            ->notEmptyString('codigo_pos_obs_observacao');

        $validator
            ->integer('codigo_usuario')
            ->requirePresence('codigo_usuario', 'create')
            ->notEmptyString('codigo_usuario');

        $validator
            ->scalar('cpf')
            ->maxLength('cpf', 14)
            ->requirePresence('cpf', 'create')
            ->notEmptyString('cpf');

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

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        return $validator;
    }

    public function salvarPorCodigoObservacao(int $codigo_observacao = null, array $data = [])
    {
        $editMode = !empty($codigo_observacao) ?? false;

        try {
            $dataCol = (new Collection($data));

            if ($editMode) {
                // removo relacionamentos desta observação que não serão utilizados nesta atualização
                $this->deleteAll([
                    'codigo_pos_obs_observacao' => $codigo_observacao,
                ]);
            }

            $this->UsuariosDados = TableRegistry::get('UsuariosDados');
            $this->Usuario = TableRegistry::get('Usuario');

            $colEntity = $dataCol->each(function ($observador, $key) use ($codigo_observacao) {
                $codigo_usuario_observador = $observador['codigo_usuario'];

                $dados_observador = $this->Usuario->find()
                    ->where([
                        'codigo' => $codigo_usuario_observador
                    ])
                    ->first();

                if (empty($dados_observador)) {
                    return null;
                }

                $cpf_observador = Comum::soNumero($dados_observador->apelido);

                $payloadData = [
                    'codigo_pos_obs_observacao' => $codigo_observacao,
                    'codigo_usuario'            => $codigo_usuario_observador,
                    'cpf'                       => $cpf_observador,
                ];

                return $this->salvar(null, $payloadData);
            });

            return $colEntity;
        } catch (\Exception $e) {
            throw $e;
        }
    }

    public function obterPorCodigoObservacao(int $codigo_observacao = null)
    {
        try {
            $query = $this->find()
                ->select([
                    'codigo' => 'PosObsParticipantes.codigo',
                    'codigo_usuario' => 'PosObsParticipantes.codigo_usuario',
                    'nome' => 'UsuarioResponsavel.nome',
                    'avatar' => 'UsuariosDados.avatar',
                ])
                ->where([
                    'PosObsParticipantes.codigo_pos_obs_observacao' => $codigo_observacao
                ])
                ->contain([
                    'UsuarioResponsavel',
                    'UsuarioIdentificador' => [
                        'queryBuilder' => function ($q) {
                            return $q->select(['avatar' => 'UsuariosDados.avatar'])->contain(['UsuariosDados']);
                        },
                    ]
                ])
                ->toArray();

                return $query;
        } catch (\Exception $e) {
            throw $e;
        }
    }
}
