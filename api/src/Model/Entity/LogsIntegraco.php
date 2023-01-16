<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * LogsIntegraco Entity
 *
 * @property int $codigo
 * @property int|null $codigo_cliente
 * @property string $arquivo
 * @property string $conteudo
 * @property string $retorno
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property string $sistema_origem
 * @property string|null $status
 * @property string|null $descricao
 * @property string|null $tipo_operacao
 * @property \Cake\I18n\FrozenTime|null $reprocessado
 * @property \Cake\I18n\FrozenTime|null $finalizado
 * @property \Cake\I18n\FrozenTime|null $data_arquivo
 * @property int|null $codigo_usuario_inclusao
 */
class LogsIntegraco extends AppEntity
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
        'codigo_cliente' => true,
        'arquivo' => true,
        'conteudo' => true,
        'retorno' => true,
        'data_inclusao' => true,
        'sistema_origem' => true,
        'status' => true,
        'descricao' => true,
        'tipo_operacao' => true,
        'reprocessado' => true,
        'finalizado' => true,
        'data_arquivo' => true,
        'codigo_usuario_inclusao' => true,
        'model' => true,
        'foreign_key' => true
    ];
}
