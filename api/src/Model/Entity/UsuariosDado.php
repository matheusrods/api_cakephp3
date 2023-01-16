<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * UsuariosDado Entity
 *
 * @property int $codigo
 * @property string $cpf
 * @property string $sexo
 * @property \Cake\I18n\FrozenDate $data_nascimento
 * @property string|null $apelido_sistema
 * @property string|null $telefone
 * @property string|null $celular
 * @property string|null $avatar
 * @property string|null $cep
 * @property string|null $estado
 * @property string|null $cidade
 * @property string|null $bairro
 * @property string|null $endereco
 * @property string|null $numero
 * @property string|null $complemento
 * @property int|null $codigo_usuario
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $ultimo_acesso
 * @property int|null $notificacao
 */
class UsuariosDado extends AppEntity
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
        'cpf' => true,
        'sexo' => true,
        'data_nascimento' => true,
        'apelido_sistema' => true,
        'telefone' => true,
        'celular' => true,
        'avatar' => true,
        'cep' => true,
        'estado' => true,
        'cidade' => true,
        'bairro' => true,
        'endereco' => true,
        'numero' => true,
        'complemento' => true,
        'codigo_usuario' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'data_alteracao' => true,
        'codigo_usuario_alteracao' => true,
        'ultimo_acesso' => true,
        'notificacao' => true
    ];
}
