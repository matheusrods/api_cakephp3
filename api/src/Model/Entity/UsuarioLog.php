<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * UsuarioLog Entity
 *
 * @property int $codigo
 * @property int $codigo_usuario
 * @property string $nome
 * @property string $apelido
 * @property string|null $senha
 * @property string|null $email
 * @property bool|null $ativo
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_uperfil
 * @property bool $alerta_portal
 * @property bool $alerta_email
 * @property bool $alerta_sms
 * @property string|null $celular
 * @property string|null $token
 * @property int|null $fuso_horario
 * @property bool|null $horario_verao
 * @property int|null $cracha
 * @property \Cake\I18n\FrozenTime|null $data_senha_expiracao
 * @property bool|null $admin
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_empresa
 * @property int|null $codigo_usuario_pai
 * @property int|null $restringe_base_cnpj
 * @property int|null $codigo_cliente
 * @property int|null $codigo_departamento
 * @property int|null $codigo_filial
 * @property int|null $codigo_proposta_credenciamento
 * @property int|null $codigo_fornecedor
 * @property int|null $usuario_dados_id
 * @property int|null $codigo_funcionario
 * @property bool|null $usuario_multi_empresa
 * @property int|null $codigo_corretora
 * @property int|null $alerta_sm_usuario
 * @property int|null $acao_sistema
 *
 * @property \App\Model\Entity\UsuarioDado $usuario_dado
 */
class UsuarioLog extends Entity
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
        'codigo_usuario' => true,
        'nome' => true,
        'apelido' => true,
        'senha' => true,
        'email' => true,
        'ativo' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_uperfil' => true,
        'alerta_portal' => true,
        'alerta_email' => true,
        'alerta_sms' => true,
        'celular' => true,
        'token' => true,
        'fuso_horario' => true,
        'horario_verao' => true,
        'cracha' => true,
        'data_senha_expiracao' => true,
        'admin' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'codigo_empresa' => true,
        'codigo_usuario_pai' => true,
        'restringe_base_cnpj' => true,
        'codigo_cliente' => true,
        'codigo_departamento' => true,
        'codigo_filial' => true,
        'codigo_proposta_credenciamento' => true,
        'codigo_fornecedor' => true,
        'usuario_dados_id' => true,
        'codigo_funcionario' => true,
        'usuario_multi_empresa' => true,
        'codigo_corretora' => true,
        'alerta_sm_usuario' => true,
        'acao_sistema' => true,
        'usuario_dado' => true,
    ];

    /**
     * Fields that are excluded from JSON versions of the entity.
     *
     * @var array
     */
    protected $_hidden = [
        'token',
    ];
}
