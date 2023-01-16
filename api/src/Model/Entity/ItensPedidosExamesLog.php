<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ItensPedidosExamesLog Entity
 *
 * @property int $codigo
 * @property int $codigo_itens_pedidos_exames
 * @property int|null $codigo_pedidos_exames
 * @property int|null $codigo_exame
 * @property float|null $valor
 * @property int $codigo_fornecedor
 * @property int|null $tipo_atendimento
 * @property \Cake\I18n\FrozenDate|null $data_agendamento
 * @property string|null $hora_agendamento
 * @property int|null $codigo_tipos_exames_pedidos
 * @property int|null $tipo_agendamento
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int|null $codigo_cliente_assinatura
 * @property \Cake\I18n\FrozenDate|null $data_realizacao_exame
 * @property int|null $compareceu
 * @property bool $recebimento_digital
 * @property bool $recebimento_enviado
 * @property int|null $acao_sistema
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_notificacao_nc
 * @property float|null $valor_custo
 */
class ItensPedidosExamesLog extends Entity
{
    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *
     * @var array
     */
    protected $_accessible = [
        'codigo_itens_pedidos_exames' => true,
        'codigo_pedidos_exames' => true,
        'codigo_exame' => true,
        'valor' => true,
        'codigo_fornecedor' => true,
        'tipo_atendimento' => true,
        'data_agendamento' => true,
        'hora_agendamento' => true,
        'codigo_tipos_exames_pedidos' => true,
        'tipo_agendamento' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_cliente_assinatura' => true,
        'data_realizacao_exame' => true,
        'compareceu' => true,
        'recebimento_digital' => true,
        'recebimento_enviado' => true,
        'acao_sistema' => true,
        'data_alteracao' => true,
        'codigo_usuario_alteracao' => true,
        'data_notificacao_nc' => true,
        'valor_custo' => true,
    ];
}
