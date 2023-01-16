<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Configuracao Entity
 *
 * @property int $codigo
 * @property string $chave
 * @property string|null $valor
 * @property string|null $observacao
 * @property int|null $codigo_empresa
 */
class Configuracao extends Entity
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
        'chave' => true,
        'valor' => true,
        'observacao' => true,
        'codigo_empresa' => true
    ];
}
