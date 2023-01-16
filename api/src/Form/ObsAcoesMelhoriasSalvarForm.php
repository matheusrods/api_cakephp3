<?php

namespace App\Form;

use App\Form\AppForm as Form;
use Cake\Validation\Validator;

/**
 * Observador EHS
 * Validação para gravar uma ações de melhoria
 */
class ObsAcoesMelhoriasSalvarForm extends Form
{

    /**
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {

        $validator = new Validator();

        $validator
            ->integer('codigo_origem_ferramenta', 'O campo de origem da ferramenta precisa ser um número inteiro.')
            ->requirePresence('codigo_origem_ferramenta', 'create', 'O campo de origem da ferramenta é obrigatório.')
            ->notEmptyString('codigo_origem_ferramenta', 'O campo de origem da ferramenta não pode ser deixado em branco.');

        $validator
            ->scalar('formulario_resposta')
            ->requirePresence('formulario_resposta', 'create', 'O campo de formulário é obrigatório.')
            ->notEmptyString('formulario_resposta', 'O campo de formulário não pode ser deixado em branco.');

        $validator
            ->integer('codigo_cliente_observacao')
            ->requirePresence('codigo_cliente_observacao', 'create')
            ->notEmptyString('codigo_cliente_observacao');

        $validator
            ->integer('codigo_usuario_identificador', 'O campo de usuário identificador precisa ser um número inteiro.')
            ->requirePresence('codigo_usuario_identificador', 'create', 'O campo de usuário identificador é obrigatório.')
            ->notEmptyString('codigo_usuario_identificador', 'O campo de usuário identificador não pode ser deixado em branco.');

        $validator
            ->integer('codigo_usuario_responsavel', 'O campo de usuário responsável precisa ser um número inteiro.')
            ->allowEmptyString('codigo_usuario_responsavel');

        $validator
            ->integer('codigo_pos_criticidade', 'O campo de criticidade precisa ser um número inteiro.')
            ->requirePresence('codigo_pos_criticidade', 'create', 'O campo de criticidade é obrigatório.')
            ->notEmptyString('codigo_pos_criticidade', 'O campo de criticidade não pode ser deixado em branco.');

        $validator
            ->integer('codigo_acoes_melhorias_tipo', 'O campo de tipo da ação precisa ser um número inteiro.')
            ->requirePresence('codigo_acoes_melhorias_tipo', 'create', 'O campo de tipo da ação é obrigatório.')
            ->notEmptyString('codigo_acoes_melhorias_tipo', 'O campo de tipo da ação não pode ser deixado em branco.');

        $validator
            ->integer('codigo_acoes_melhorias_status', 'O campo de status da ação precisa ser um número inteiro.')
            ->requirePresence('codigo_acoes_melhorias_status', 'create', 'O campo de status da ação é obrigatório.')
            ->notEmptyString('codigo_acoes_melhorias_status', 'O campo de status da ação não pode ser deixado em branco.');

        $validator
            ->date('prazo', ['ymd'], 'O campo de prazo precisa ser uma data válida.')
            ->allowEmptyDateTime('prazo');

        $validator
            ->scalar('descricao_desvio')
            ->requirePresence('descricao_desvio', 'create', 'O campo de descrição do desvio é obrigatório.')
            ->notEmptyString('descricao_desvio', 'O campo de descrição do desvio não pode ser deixado em branco.');

        $validator
            ->scalar('descricao_acao')
            ->requirePresence('descricao_acao', 'create', 'O campo de descrição da ação é obrigatório.')
            ->notEmptyString('descricao_acao', 'O campo de descrição da ação não pode ser deixado em branco.');

        $validator
            ->scalar('descricao_local_acao')
            ->requirePresence('descricao_local_acao', 'create', 'O campo de descrição do local da ação é obrigatório.')
            ->notEmptyString('descricao_local_acao', 'O campo de descrição do local da ação não pode ser deixado em branco.');

        return $validator;
    }

    protected function _execute(array $data)
    {
        return true;
    }
}
