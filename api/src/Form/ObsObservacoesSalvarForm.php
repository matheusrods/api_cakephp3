<?php

namespace App\Form;

use App\Form\AppForm as Form;
use Cake\Validation\Validator;

/**
 * Observador EHS
 * Validação para gravar uma observação
 */
class ObsObservacoesSalvarForm extends Form
{

    /**
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {

        $validator = new Validator();

        $validator
            ->requirePresence('codigo_usuario', true, self::$MESSAGE_REQUIRED_FIELD)
            ->integer('codigo_usuario', self::$MESSAGE_INTEGER_FIELD)
            ->notEmptyString('codigo_usuario', self::$MESSAGE_REQUIRED_FIELD)
            ->add('codigo_usuario', [
                'length' => [
                    'rule' => ['maxLength', self::$CODIGO_INT_SIZE],
                    'message' => sprintf(self::$MESSAGE_MAXLENGHT_FIELD, self::$CODIGO_INT_SIZE),
                ]
            ])
            ->add('codigo_usuario', [
                'length' => [
                    'rule' => ['minLength', 1],
                    'message' => sprintf(self::$MESSAGE_MINLENGHT_FIELD, 1),
                ]
            ])
            ->add('codigo_usuario', 'custom', [
                'rule' => function ($value, $context) {
                    return !($value === 0 || intVal($value) === 0);
                },
                'message' => sprintf(self::$MESSAGE_MINLENGHT_FIELD, 1)
            ]);

        $validator
            ->requirePresence('codigo_categoria_observacao', true, self::$MESSAGE_REQUIRED_FIELD)
            ->numeric('codigo_categoria_observacao', self::$MESSAGE_NUMERIC_FIELD)
            ->notEmptyString('codigo_categoria_observacao', self::$MESSAGE_REQUIRED_FIELD)
            ->add('codigo_categoria_observacao', 'custom', [
                'rule' => function ($value, $context) {
                    return !($value === 0 || intVal($value) === 0);
                },
                'message' => sprintf(self::$MESSAGE_MINLENGHT_FIELD, 1)
            ]);

        $validator
            ->requirePresence('codigo_unidade', true, self::$MESSAGE_REQUIRED_FIELD)
            ->numeric('codigo_unidade', self::$MESSAGE_NUMERIC_FIELD)
            ->notEmptyString('codigo_unidade', self::$MESSAGE_REQUIRED_FIELD);

        $validator
            ->requirePresence('codigo_local', true, self::$MESSAGE_REQUIRED_FIELD)
            ->numeric('codigo_local', self::$MESSAGE_NUMERIC_FIELD)
            ->notEmptyString('codigo_local', self::$MESSAGE_REQUIRED_FIELD);

        $validator
            ->requirePresence('observadores', true, self::$MESSAGE_REQUIRED_FIELD)
            ->allowEmptyString('observadores', self::$MESSAGE_REQUIRED_FIELD);

        $validator
            ->requirePresence('localidades', true, self::$MESSAGE_REQUIRED_FIELD)
            ->allowEmptyString('localidades', self::$MESSAGE_REQUIRED_FIELD);

        $validator
            ->requirePresence('observacao_data', true, self::$MESSAGE_REQUIRED_FIELD)
            ->notEmptyString('observacao_data', self::$MESSAGE_REQUIRED_FIELD)
            ->add('observacao_data', [
                'date' => [
                    'rule' => ['date', 'dmy'],
                    'message' => 'Parâmetro deve conter o formato DD/MM/YYYY',
                ]
            ])
            ->add('observacao_data', [
                'length' => [
                    'rule' => ['minLength', 10],
                    'message' => 'Parâmetro deve conter o formato DD/MM/YYYY',
                ]
            ]);

        $validator
            ->requirePresence('observacao_hora', true, self::$MESSAGE_REQUIRED_FIELD)
            ->notEmptyString('observacao_hora', self::$MESSAGE_REQUIRED_FIELD)
            ->time('observacao_hora');

        $validator
            ->requirePresence('descricao_usuario_observou', true, self::$MESSAGE_REQUIRED_FIELD)
            ->notEmptyString('descricao_usuario_observou', self::$MESSAGE_REQUIRED_FIELD)
            ->scalar('descricao_usuario_observou');

        $validator
            ->requirePresence('descricao_usuario_acao', true, self::$MESSAGE_REQUIRED_FIELD)
            ->notEmptyString('descricao_usuario_acao', self::$MESSAGE_REQUIRED_FIELD)
            ->scalar('descricao_usuario_acao');

        $validator
            ->requirePresence('descricao_usuario_sugestao', true, self::$MESSAGE_REQUIRED_FIELD)
            ->allowEmptyString('descricao_usuario_sugestao', self::$MESSAGE_REQUIRED_FIELD)
            ->scalar('descricao_usuario_sugestao');

        $validator
            ->requirePresence('descricao', true, self::$MESSAGE_REQUIRED_FIELD)
            ->allowEmptyString('descricao', self::$MESSAGE_REQUIRED_FIELD)
            ->scalar('descricao');

        $validator
            ->requirePresence('anexos', true, self::$MESSAGE_REQUIRED_FIELD)
            ->allowEmptyString('anexos', self::$MESSAGE_REQUIRED_FIELD);

        $validator
            ->requirePresence('riscos', true, self::$MESSAGE_REQUIRED_FIELD)
            ->isArray('riscos', 'Formato deve ser um array')
            ->allowEmptyArray('riscos', self::$MESSAGE_REQUIRED_FIELD);

        $validator
            ->integer('codigo_observacao', self::$MESSAGE_NUMERIC_FIELD);

        return $validator;
    }

    protected function _execute(array $data)
    {
        return true;
    }
}
