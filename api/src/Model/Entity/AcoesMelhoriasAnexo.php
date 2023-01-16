<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AcoesMelhoriasAnexo Entity
 *
 * @property int $codigo
 * @property int $codigo_acao_melhoria
 * @property string $arquivo
 * @property string $arquivo_nome
 * @property string $arquivo_tamanho
 * @property string $arquivo_url
 * @property int $arquivo_tipo
 * @property bool $ativo
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_remocao
 */
class AcoesMelhoriasAnexo extends Entity
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
        'codigo_acao_melhoria' => true,
        'arquivo' => true,
        'arquivo_nome' => true,
        'arquivo_tamanho' => true,
        'arquivo_url' => true,
        'arquivo_tipo' => true,
        'ativo' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'data_remocao' => true,
    ];
}
