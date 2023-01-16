<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * PrePedidosExame Entity
 *
 * @property int $codigo
 * @property int|null $codigo_cliente_funcionario
 * @property int|null $codigo_empresa
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property string|null $endereco_parametro_busca
 * @property int|null $codigo_cliente
 * @property int|null $codigo_funcionario
 * @property int|null $exame_admissional
 * @property int|null $exame_periodico
 * @property int|null $exame_demissional
 * @property int|null $exame_retorno
 * @property int|null $exame_mudanca
 * @property int|null $qualidade_vida
 * @property int|null $codigo_status_pedidos_exames
 * @property int|null $portador_deficiencia
 * @property int|null $pontual
 * @property \Cake\I18n\FrozenTime|null $data_notificacao
 * @property \Cake\I18n\FrozenDate|null $data_solicitacao
 * @property int|null $codigo_pedidos_lote
 * @property int|null $em_emissao
 * @property int|null $codigo_motivo_cancelamento
 * @property int|null $codigo_func_setor_cargo
 * @property int|null $codigo_usuario_alteracao
 * @property int|null $exame_monitoracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_paciente
 * @property int|null $codigo_motivo_conclusao
 * @property string|null $descricao_motivo_conclusao
 * @property int|null $aso_embarcados
 */
class PrePedidosExame extends AppEntity
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
        'codigo_cliente_funcionario' => true,
        'codigo_empresa' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'endereco_parametro_busca' => true,
        'codigo_cliente' => true,
        'codigo_funcionario' => true,
        'exame_admissional' => true,
        'exame_periodico' => true,
        'exame_demissional' => true,
        'exame_retorno' => true,
        'exame_mudanca' => true,
        'qualidade_vida' => true,
        'codigo_status_pedidos_exames' => true,
        'portador_deficiencia' => true,
        'pontual' => true,
        'data_notificacao' => true,
        'data_solicitacao' => true,
        'codigo_pedidos_lote' => true,
        'em_emissao' => true,
        'codigo_motivo_cancelamento' => true,
        'codigo_func_setor_cargo' => true,
        'codigo_usuario_alteracao' => true,
        'exame_monitoracao' => true,
        'data_alteracao' => true,
        'codigo_pedido_exame' => true,
    ];
}
