<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;
use App\Model\Entity\AppEntity;

/**
 * PacientesDadosTrabalho Entity
 *
 * @property int $codigo
 * @property int $codigo_paciente
 * @property int $codigo_cliente
 * @property int|null $codigo_setor
 * @property int|null $codigo_pacientes_categoria
 * @property int $codigo_fornecedor
 * @property int $codigo_empresa
 * @property int $ativo
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 */
class PacientesDadosTrabalho extends AppEntity
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
        'codigo_paciente' => true,
        'codigo_cliente' => true,
        'codigo_setor' => true,
        'codigo_pacientes_categoria' => true,
        'codigo_fornecedor' => true,
        'codigo_empresa' => true,
        'ativo' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
    ];
}
