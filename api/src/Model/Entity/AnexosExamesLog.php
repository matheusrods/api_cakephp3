<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AnexosExamesLog Entity
 *
 * @property int $codigo
 * @property int $codigo_anexos_exames
 * @property int $codigo_item_pedido_exame
 * @property string $caminho_arquivo
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $acao_sistema
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_empresa
 * @property int|null $status
 * @property string|null $motivo_recusa
 */
class AnexosExamesLog extends Entity
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
        'codigo_anexos_exames' => true,
        'codigo_item_pedido_exame' => true,
        'caminho_arquivo' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'acao_sistema' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'codigo_empresa' => true,
        'status' => true,
        'motivo_recusa' => true,
    ];
}
