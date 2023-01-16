<?php
namespace App\Model\Entity;

// use Cake\Auth\DefaultPasswordHasher;
use App\Auth\BuonnyPasswordHasher;

/**
 * Auth Entity
 *
 * @property int $codigo
 * @property string $nome
 * @property string $apelido
 * @property string|null $senha
 * @property string|null $email
 * @property bool|null $ativo
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_uperfil
 * @property string|null $celular
 * @property \Cake\I18n\FrozenTime|null $data_senha_expiracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_usuario_alteracao
 */
class Auth extends AppEntity 
{
    const STATUS_ATIVO = 1;
    const STATUS_INATIVO = 0;

    /**
     * Fields that can be mass assigned using newEntity() or patchEntity().
     *
     * Note that when '*' is set to true, this allows all unspecified fields to
     * be mass assigned. For security purposes, it is advised to set '*' to false
     * (or remove it), and explicitly make individual fields accessible as needed.
     *  protected $_accessible = [
     *      "*" => "*"
     *  ];
     * 
     * @var array
     */
    protected $_accessible = [
        'codigo' => true,
        'nome' => true,
        'apelido' => true,
        'senha' => true,
        'email' => true,
        'ativo' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_uperfil' => true,
        'celular' => true,
        'data_senha_expiracao' => true,
        'data_alteracao' => true,
        'codigo_usuario_alteracao' => true
    ];
    
    protected $_hidden = ['senha'];

    protected function _setPassword($password)
    {
        return (new BuonnyPasswordHasher)->hash($password);
    }

    protected function _setSenha($password)
    {
        return (new BuonnyPasswordHasher)->hash($password);
    }

}
