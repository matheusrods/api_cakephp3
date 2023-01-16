<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * FuncionariosContato Entity
 *
 * @property int $codigo
 * @property int $codigo_funcionario
 * @property int $codigo_tipo_contato
 * @property int $codigo_tipo_retorno
 * @property int|null $ddi
 * @property int|null $ddd
 * @property string|null $descricao
 * @property string|null $nome
 * @property int|null $ramal
 * @property int|null $autoriza_envio_sms
 * @property int|null $autoriza_envio_email
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_empresa
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 */
class FuncionariosContato extends Entity
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
        'codigo_funcionario' => true,
        'codigo_tipo_contato' => true,
        'codigo_tipo_retorno' => true,
        'ddi' => true,
        'ddd' => true,
        'descricao' => true,
        'nome' => true,
        'ramal' => true,
        'autoriza_envio_sms' => true,
        'autoriza_envio_email' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_empresa' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true
    ];
}
