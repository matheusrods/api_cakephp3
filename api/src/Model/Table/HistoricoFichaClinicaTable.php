<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * HistoricoFichaClinica Model
 *
 * @method \App\Model\Entity\HistoricoFichaClinica get($primaryKey, $options = [])
 * @method \App\Model\Entity\HistoricoFichaClinica newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\HistoricoFichaClinica[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\HistoricoFichaClinica|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HistoricoFichaClinica saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\HistoricoFichaClinica patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\HistoricoFichaClinica[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\HistoricoFichaClinica findOrCreate($search, callable $callback = null, $options = [])
 */
class HistoricoFichaClinicaTable extends Table
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

        $this->setTable('historico_ficha_clinica');
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
            ->requirePresence('codigo', 'create')
            ->notEmptyString('codigo');

        $validator
            ->scalar('codigo_nexo')
            ->maxLength('codigo_nexo', 250)
            ->allowEmptyString('codigo_nexo');

        $validator
            ->scalar('codigo_pedido_exame_nexo')
            ->maxLength('codigo_pedido_exame_nexo', 250)
            ->allowEmptyString('codigo_pedido_exame_nexo');

        $validator
            ->scalar('cnpj_grupo_economico')
            ->maxLength('cnpj_grupo_economico', 250)
            ->allowEmptyString('cnpj_grupo_economico');

        $validator
            ->scalar('cnpj_unidade')
            ->maxLength('cnpj_unidade', 250)
            ->allowEmptyString('cnpj_unidade');

        $validator
            ->scalar('setor')
            ->maxLength('setor', 250)
            ->allowEmptyString('setor');

        $validator
            ->scalar('cargo')
            ->maxLength('cargo', 250)
            ->allowEmptyString('cargo');

        $validator
            ->scalar('funcionario_matricula')
            ->maxLength('funcionario_matricula', 250)
            ->allowEmptyString('funcionario_matricula');

        $validator
            ->scalar('cpf')
            ->maxLength('cpf', 250)
            ->allowEmptyString('cpf');

        $validator
            ->scalar('idade')
            ->maxLength('idade', 250)
            ->allowEmptyString('idade');

        $validator
            ->scalar('sexo')
            ->maxLength('sexo', 250)
            ->allowEmptyString('sexo');

        $validator
            ->scalar('exame_ocupacional')
            ->maxLength('exame_ocupacional', 250)
            ->allowEmptyString('exame_ocupacional');

        $validator
            ->scalar('tipo_atendimento')
            ->maxLength('tipo_atendimento', 250)
            ->allowEmptyString('tipo_atendimento');

        $validator
            ->scalar('cd_usu')
            ->maxLength('cd_usu', 250)
            ->allowEmptyString('cd_usu');

        $validator
            ->scalar('medico')
            ->maxLength('medico', 250)
            ->allowEmptyString('medico');

        $validator
            ->date('data_atendimento')
            ->allowEmptyDate('data_atendimento');

        $validator
            ->scalar('observacoes')
            ->allowEmptyString('observacoes');

        return $validator;
    }

    /**
     * [getDadosPedidoExames pega os dados pelo pedido de exame]
     * @param  [type] $codigo_pedido_exame [description]
     * @return [type]                      [description]
     */
    public function getDadosPedidoExames($codigo_pedido_exame)
    {

        //monta a query para pegar os dados de historico
        $dados['fields'] = array(
            'HistoricoFichaClinica.codigo',
            'Funcionario.cpf',
            'Funcionario.nome',
            'HistoricoFichaClinica.cnpj_unidade',
            'Cliente.razao_social',
            'Cliente.nome_fantasia',
            'HistoricoFichaClinica.setor',
            'HistoricoFichaClinica.cargo',
            'HistoricoFichaClinica.data_atendimento',
            'HistoricoFichaClinica.observacoes',
        );

        //faz o join do historico
        $dados['joins'] = array(
            array(
                'table' => 'funcionarios',
                'alias' => 'Funcionario',
                'type' => 'INNER',
                'conditions' => '(PedidosExames.codigo_funcionario = Funcionario.codigo)'
            ),
            array(
                'table' => 'historico_ficha_clinica',
                'alias' => 'HistoricoFichaClinica',
                'type' => 'INNER',
                'conditions' => '(Funcionario.cpf = HistoricoFichaClinica.cpf)'
            ),
            array(
                'table' => 'cliente',
                'alias' => 'Cliente',
                'type' => 'INNER',
                'conditions' => "(RIGHT(replicate('0',14) + CONVERT(VARCHAR,HistoricoFichaClinica.cnpj_unidade),14) = Cliente.codigo_documento)"
            ),
        );

        $dados['conditions']['PedidosExames.codigo'] = $codigo_pedido_exame;

        return $dados;

    }//fim getDadosPedidosExames
}
