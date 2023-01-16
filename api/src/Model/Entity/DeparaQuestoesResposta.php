<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * DeparaQuestoesResposta Entity
 *
 * @property int $codigo
 * @property int|null $codigo_questao_questionario
 * @property int|null $codigo_resposta_questionario
 * @property string|null $resposta_ficha_clinica
 */
class DeparaQuestoesResposta extends Entity
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
        'codigo_questao_questionario' => true,
        'codigo_resposta_questionario' => true,
        'resposta_ficha_clinica' => true
    ];
}
