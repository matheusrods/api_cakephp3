<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Medicamento Entity
 *
 * @property int $codigo
 * @property string $descricao
 * @property string $principio_ativo
 * @property int $codigo_laboratorio
 * @property string|null $codigo_barras
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property bool $ativo
 * @property int|null $codigo_empresa
 * @property int $codigo_apresentacao
 * @property string|null $posologia
 *
 * @property \App\Model\Entity\Funcionario[] $funcionarios
 */
class Medicamento extends Entity
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
        'descricao' => true,
        'principio_ativo' => true,
        'codigo_laboratorio' => true,
        'codigo_barras' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'ativo' => true,
        'codigo_empresa' => true,
        'codigo_apresentacao' => true,
        'posologia' => true,
        'funcionarios' => true
    ];
}
