<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AtestadosLog Entity
 *
 * @property int $codigo
 * @property int $codigo_atestado
 * @property int $codigo_cliente_funcionario
 * @property int|null $codigo_medico
 * @property \Cake\I18n\FrozenDate|null $data_afastamento_periodo
 * @property \Cake\I18n\FrozenDate|null $data_retorno_periodo
 * @property string|null $afastamento_em_horas
 * @property \Cake\I18n\FrozenDate|null $data_afastamento_hr
 * @property \Cake\I18n\FrozenTime|null $hora_afastamento
 * @property \Cake\I18n\FrozenTime|null $hora_retorno
 * @property int|null $codigo_motivo_esocial
 * @property int|null $codigo_motivo_licenca
 * @property string|null $restricao
 * @property int|null $codigo_cid_contestato
 * @property int|null $imprimi_cid_atestado
 * @property int|null $acidente_trajeto
 * @property string|null $endereco
 * @property string|null $numero
 * @property string|null $complemento
 * @property string|null $bairro
 * @property string|null $cep
 * @property int|null $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 * @property int|null $codigo_estado
 * @property int|null $codigo_cidade
 * @property int|null $codigo_tipo_local_atendimento
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int|null $afastamento_em_dias
 * @property bool|null $habilita_afastamento_em_horas
 * @property int|null $acao_sistema
 * @property string|null $estado
 * @property string|null $cidade
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_empresa
 * @property int|null $ativo
 */
class AtestadosLog extends Entity
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
        'codigo_atestado' => true,
        'codigo_cliente_funcionario' => true,
        'codigo_medico' => true,
        'data_afastamento_periodo' => true,
        'data_retorno_periodo' => true,
        'afastamento_em_horas' => true,
        'data_afastamento_hr' => true,
        'hora_afastamento' => true,
        'hora_retorno' => true,
        'codigo_motivo_esocial' => true,
        'codigo_motivo_licenca' => true,
        'restricao' => true,
        'codigo_cid_contestato' => true,
        'imprimi_cid_atestado' => true,
        'acidente_trajeto' => true,
        'endereco' => true,
        'numero' => true,
        'complemento' => true,
        'bairro' => true,
        'cep' => true,
        'codigo_usuario_inclusao' => true,
        'data_inclusao' => true,
        'codigo_estado' => true,
        'codigo_cidade' => true,
        'codigo_tipo_local_atendimento' => true,
        'latitude' => true,
        'longitude' => true,
        'afastamento_em_dias' => true,
        'habilita_afastamento_em_horas' => true,
        'acao_sistema' => true,
        'estado' => true,
        'cidade' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'codigo_empresa' => true,
        'ativo' => true,
    ];
}
