<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * MultiEmpresa Entity
 *
 * @property int $codigo
 * @property string $razao_social
 * @property string $nome_fantasia
 * @property string|null $codigo_documento
 * @property string|null $email
 * @property int $codigo_status_multi_empresa
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property string|null $logomarca
 * @property string|null $cor_menu
 * @property string|null $hash
 *
 * @property \App\Model\Entity\Endereco[] $endereco
 * @property \App\Model\Entity\Usuario[] $usuario
 */
class MultiEmpresa extends AppEntity
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
        'razao_social' => true,
        'nome_fantasia' => true,
        'codigo_documento' => true,
        'email' => true,
        'codigo_status_multi_empresa' => true,
        'data_inclusao' => true,
        'logomarca' => true,
        'cor_menu' => true,
        'hash' => true,
        'endereco' => true,
        'usuario' => true
    ];
}
