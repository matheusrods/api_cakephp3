<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * Atestado Entity
 *
 * @property int $codigo
 * @property int|null $codigo_cliente_funcionario
 * @property int $codigo_medico
 * @property \Cake\I18n\FrozenDate|null $data_afastamento_periodo
 * @property \Cake\I18n\FrozenDate|null $data_retorno_periodo
 * @property string|null $afastamento_em_horas
 * @property \Cake\I18n\FrozenDate|null $data_afastamento_hr
 * @property \Cake\I18n\FrozenTime|null $hora_afastamento
 * @property \Cake\I18n\FrozenTime|null $hora_retorno
 * @property int|null $codigo_motivo_esocial
 * @property int $codigo_motivo_licenca
 * @property string|null $restricao
 * @property int|null $codigo_cid_contestato
 * @property int|null $imprimi_cid_atestado
 * @property int|null $acidente_trajeto
 * @property string|null $endereco
 * @property string|null $numero
 * @property string|null $complemento
 * @property string|null $bairro
 * @property string|null $cep
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_estado
 * @property int|null $codigo_cidade
 * @property int|null $codigo_tipo_local_atendimento
 * @property float|null $latitude
 * @property float|null $longitude
 * @property int|null $afastamento_em_dias
 * @property bool|null $habilita_afastamento_em_horas
 * @property int|null $codigo_func_setor_cargo
 * @property int|null $exibir_ficha_assistencial
 * @property string|null $estado
 * @property string|null $cidade
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_empresa
 * @property string|null $motivo_afastamento
 * @property string|null $origem_retificacao
 * @property string|null $tipo_acidente_transito
 * @property string|null $onus_remuneracao
 * @property string|null $onus_requisicao
 * @property string|null $numero_processo
 * @property string|null $tipo_processo
 * @property string|null $codigo_documento_entidade
 * @property int|null $codigo_paciente
 *
 * @property \App\Model\Entity\Cid[] $cid
 */
class Atestado extends AppEntity
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
        'codigo_func_setor_cargo' => true,
        'exibir_ficha_assistencial' => true,
        'estado' => true,
        'cidade' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'codigo_empresa' => true,
        'motivo_afastamento' => true,
        'origem_retificacao' => true,
        'tipo_acidente_transito' => true,
        'onus_remuneracao' => true,
        'onus_requisicao' => true,
        'numero_processo' => true,
        'tipo_processo' => true,
        'codigo_documento_entidade' => true,
        'cid' => true,
        'observacao' => true,
        'estabelecimento' => true,
        'pk_externo' => true,
        'ativo' => true,
        'codigo_paciente' => true
    ];

    protected function _getDataValidade($datetime)
    {
        // se for object pode ser o FrozenDatetime do cake
        if(gettype($datetime) =='object'){
            return $datetime->format('Y-m-d');
        }

        return $datetime;
    }

    protected function _getDataRetornoPeriodo($datetime)
    {
        // se for object pode ser o FrozenDatetime do cake
        if(gettype($datetime) =='object'){
            return $datetime->format('Y-m-d');
        }

        return $datetime;
    }
}
