<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * Questionario Entity
 *
 * @property int $codigo
 * @property int|null $ordem
 * @property int $status
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property int $codigo_usuario_alteracao
 * @property string $descricao
 * @property string|null $observacoes
 * @property int $codigo_empresa
 * @property string|null $background
 * @property string|null $icone
 * @property int|null $quantidade_dias_notificacao
 * @property string|null $aplicacao_sexo
 * @property string|null $protocolo
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 *
 * @property \App\Model\Entity\Caracteristica[] $caracteristicas
 */
class Questionario extends AppEntity
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
        'ordem' => true,
        'status' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'descricao' => true,
        'observacoes' => true,
        'codigo_empresa' => true,
        'background' => true,
        'icone' => true,
        'quantidade_dias_notificacao' => true,
        'aplicacao_sexo' => true,
        'protocolo' => true,
        'data_alteracao' => true,
        'caracteristicas' => true
    ];

    protected function _getDescricao($registro)
    {
        return $this->iconv($registro);
    }

}
