<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * ChamadosMelhorias Entity
 *
 * @property int $codigo
 * @property int $codigo_arrtpa_ri
 * @property string $descricao
 * @property \Cake\I18n\FrozenTime|null $data_prazo_conclusao
 * @property int $responsavel
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_usuario_alteracao
 * @property int|null $ativo
 */
class ChamadosMelhorias extends Entity
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
        'codigo_arrtpa_ri' => true,
        'codigo_medida_controle_hierarquia_tipo' => true,
        'descricao' => true,
        'data_prazo_conclusao' => true,
        'responsavel' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'data_alteracao' => true,
        'codigo_usuario_alteracao' => true,
        'ativo' => true,
    ];
}
