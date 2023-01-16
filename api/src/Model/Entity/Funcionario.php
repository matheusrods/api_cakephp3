<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * Funcionario Entity
 *
 * @property int $codigo
 * @property string $nome
 * @property \Cake\I18n\FrozenDate $data_nascimento
 * @property string $rg
 * @property string $rg_orgao
 * @property string|null $cpf
 * @property string $sexo
 * @property int|null $status
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property string|null $ctps
 * @property \Cake\I18n\FrozenTime|null $ctps_data_emissao
 * @property string|null $gfip
 * @property string|null $rg_data_emissao
 * @property string|null $nit
 * @property string|null $ctps_serie
 * @property string|null $cns
 * @property string|null $ctps_uf
 * @property int|null $codigo_empresa
 * @property string|null $email
 * @property int|null $estado_civil
 * @property int|null $deficiencia
 * @property string|null $rg_uf
 * @property string|null $nome_mae
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 *
 * @property \App\Model\Entity\Medicamento[] $medicamentos
 * @property \App\Model\Entity\Medico[] $medicos
 */
class Funcionario extends AppEntity
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
        'nome' => true,
        'data_nascimento' => true,
        'rg' => true,
        'rg_orgao' => true,
        'cpf' => true,
        'sexo' => true,
        'status' => true,
        'data_inclusao' => true,
        'ctps' => true,
        'ctps_data_emissao' => true,
        'gfip' => true,
        'rg_data_emissao' => true,
        'nit' => true,
        'ctps_serie' => true,
        'cns' => true,
        'ctps_uf' => true,
        'codigo_empresa' => true,
        'email' => true,
        'estado_civil' => true,
        'deficiencia' => true,
        'rg_uf' => true,
        'nome_mae' => true,
        'data_alteracao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'medicamentos' => true,
        'medicos' => true
    ];
}
