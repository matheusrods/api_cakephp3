<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * ClienteFuncionario Entity
 *
 * @property int $codigo
 * @property int|null $codigo_cliente
 * @property int $codigo_funcionario
 * @property int|null $codigo_setor
 * @property int|null $codigo_cargo
 * @property \Cake\I18n\FrozenDate|null $admissao
 * @property int $ativo
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_empresa
 * @property string|null $matricula
 * @property \Cake\I18n\FrozenDate|null $data_demissao
 * @property string|null $centro_custo
 * @property \Cake\I18n\FrozenDate|null $data_ultima_aso
 * @property int|null $aptidao
 * @property int|null $turno
 * @property int|null $codigo_cliente_matricula
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_usuario_alteracao
 * @property int|null $matricula_candidato
 */
class ClienteFuncionario extends AppEntity
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
        'codigo_cliente' => true,
        'codigo_funcionario' => true,
        'codigo_setor' => true,
        'codigo_cargo' => true,
        'admissao' => true,
        'ativo' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_empresa' => true,
        'matricula' => true,
        'data_demissao' => true,
        'centro_custo' => true,
        'data_ultima_aso' => true,
        'aptidao' => true,
        'turno' => true,
        'codigo_cliente_matricula' => true,
        'data_alteracao' => true,
        'codigo_usuario_alteracao' => true,
        'matricula_candidato' => true
    ];
}
