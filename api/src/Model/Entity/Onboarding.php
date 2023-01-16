<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * Onboarding Entity
 *
 * @property int $codigo
 * @property int $codigo_sistema
 * @property string $titulo
 * @property string $texto
 * @property string|null $imagem
 * @property int $ativo
 *
 * @property \App\Model\Entity\Cliente[] $cliente
 * @property \App\Model\Entity\ClienteLog[] $cliente_log
 */
class Onboarding extends AppEntity
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
        'codigo_sistema' => true,
        'titulo' => true,
        'texto' => true,
        'imagem' => true,
        'ativo' => true,
        'cliente' => true,
        'cliente_log' => true,
    ];
}
