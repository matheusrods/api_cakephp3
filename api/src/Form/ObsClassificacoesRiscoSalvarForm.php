<?php

namespace App\Form;

use App\Form\AppForm as Form;
use Cake\Validation\Validator;

/**
 * Observador EHS
 * Validação para gravar uma classificação de risco de uma observação
 */
class ObsClassificacoesRiscoSalvarForm extends Form
{

    /**
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator = new Validator();

        $validator
            ->requirePresence('codigo_observacao', true, self::$MESSAGE_REQUIRED_FIELD)
            ->integer('codigo_observacao', self::$MESSAGE_INTEGER_FIELD)
            ->notEmptyString('codigo_observacao', self::$MESSAGE_REQUIRED_FIELD)
            ->add('codigo_observacao', [
                'length' => [
                    'rule' => ['maxLength', self::$CODIGO_INT_SIZE],
                    'message' => sprintf(self::$MESSAGE_MAXLENGHT_FIELD, self::$CODIGO_INT_SIZE),
                ]
            ])
            ->add('codigo_observacao', [
                'length' => [
                    'rule' => ['minLength', 1],
                    'message' => sprintf(self::$MESSAGE_MINLENGHT_FIELD, 1),
                ]
            ])
            ->add('codigo_observacao', 'custom', [
                'rule' => function ($value, $context) {
                    return !($value === 0 || intVal($value) === 0);
                },
                'message' => sprintf(self::$MESSAGE_MINLENGHT_FIELD, 1)
            ]);

        $validator
            ->requirePresence('criticidade', true, self::$MESSAGE_REQUIRED_FIELD)
            ->integer('criticidade', self::$MESSAGE_INTEGER_FIELD)
            ->notEmptyString('criticidade', self::$MESSAGE_REQUIRED_FIELD)
            ->add('criticidade', [
                'length' => [
                    'rule' => ['maxLength', self::$CODIGO_INT_SIZE],
                    'message' => sprintf(self::$MESSAGE_MAXLENGHT_FIELD, self::$CODIGO_INT_SIZE),
                ]
            ])
            ->add('criticidade', [
                'length' => [
                    'rule' => ['minLength', 1],
                    'message' => sprintf(self::$MESSAGE_MINLENGHT_FIELD, 1),
                ]
            ])
            ->add('criticidade', 'custom', [
                'rule' => function ($value, $context) {
                    return !($value === 0 || intVal($value) === 0);
                },
                'message' => sprintf(self::$MESSAGE_MINLENGHT_FIELD, 1)
            ]);

        $validator
            ->requirePresence('acoes_melhoria_vinculo', true, self::$MESSAGE_REQUIRED_FIELD)
            ->isArray('acoes_melhoria_vinculo', 'Formato deve ser um array')
            ->allowEmptyArray('acoes_melhoria_vinculo', self::$MESSAGE_REQUIRED_FIELD);

        $validator
            ->requirePresence('acoes_melhoria_registro', true, self::$MESSAGE_REQUIRED_FIELD)
            ->isArray('acoes_melhoria_registro', 'Formato deve ser um array')
            ->allowEmptyArray('acoes_melhoria_registro', self::$MESSAGE_REQUIRED_FIELD);

        $validator
            ->requirePresence('avaliacao', true, self::$MESSAGE_REQUIRED_FIELD)
            ->allowEmptyString('avaliacao', self::$MESSAGE_REQUIRED_FIELD);

        $validator
            ->requirePresence('descricao_complemento', true, self::$MESSAGE_REQUIRED_FIELD)
            ->allowEmptyString('descricao_complemento', self::$MESSAGE_REQUIRED_FIELD);

        $validator
            ->requirePresence('descricao_participantes_tratativa', true, self::$MESSAGE_REQUIRED_FIELD)
            ->allowEmptyString('descricao_participantes_tratativa', self::$MESSAGE_REQUIRED_FIELD);


        return $validator;
    }

    protected function _execute(array $data)
    {
        return true;
    }
}
