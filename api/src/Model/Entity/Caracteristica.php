<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Caracteristica Entity
 *
 * @property int $codigo
 * @property string|null $titulo
 * @property string|null $alerta
 * @property string|null $descricao
 * @property int|null $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int $codigo_empresa
 * @property int|null $ativo
 *
 * @property \App\Model\Entity\Questionario[] $questionarios
 * @property \App\Model\Entity\Questo[] $questoes
 * @property \App\Model\Entity\Setore[] $setores
 */
class Caracteristica extends Entity
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
        'codigo' => true,
        'titulo' => true,
        'alerta' => true,
        'descricao' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'codigo_empresa' => true,
        'ativo' => true,
        'questionarios' => true,
        'questoes' => true,
        'setores' => true
    ];
}
