<?php
namespace App\Model\Entity;

// use Cake\Auth\DefaultPasswordHasher;
use App\Model\Entity\AppEntity;

/**
 * UsuarioFornecedorPermisso Entity
 *
 * @property int $codigo
 * @property int $codigo_fornecedor_permissoes
 * @property int $codigo_usuario
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 */
class UsuarioFornecedorPermisso extends AppEntity
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
        'codigo_fornecedor_permissoes' => true,
        'codigo_usuario' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true
    ];
}
