<?php

namespace App\Model\Entity;

use App\Model\Entity\AppEntity as Entity;

/**
 * PosObsObservacao Entity
 *
 * @property int $codigo
 * @property int $codigo_empresa
 * @property int $codigo_cliente
 * @property int|null $codigo_unidade
 * @property int $codigo_pos_categoria
 * @property \Cake\I18n\FrozenTime $data_observacao
 * @property string $descricao_usuario_observou
 * @property string|null $descricao_usuario_acao
 * @property string|null $descricao_usuario_sugestao
 * @property int|null $codigo_local_descricao
 * @property int $codigo_pos_obs_local
 * @property string|null $descricao
 * @property int|null $observacao_criticidade
 * @property int|null $qualidade_avaliacao
 * @property string|null $qualidade_descricao_complemento
 * @property string|null $qualidade_descricao_participantes_tratativa
 * @property int $status
 * @property int $codigo_status
 * @property int $codigo_status_responsavel
 * @property int $codigo_usuario_status
 * @property \Cake\I18n\FrozenTime $data_status
 * @property string|null $descricao_status
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property bool $ativo
 *
 * @property \App\Model\Entity\Cliente $localidade
 * @property \App\Model\Entity\Usuario $responsavel
 */
class PosObsObservacao extends Entity
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
        'codigo_empresa'                              => true,
        'codigo_cliente'                              => true,
        'codigo_unidade'                              => true,
        'codigo_pos_categoria'                        => true,
        'codigo_pos_obs_local'                        => true,
        'codigo_status'                               => true,
        'status'                                      => true,
        'codigo_status_responsavel'                   => true,
        'data_observacao'                             => true,
        'descricao_usuario_observou'                  => true,
        'descricao_usuario_acao'                      => true,
        'descricao_usuario_sugestao'                  => true,
        'codigo_local_descricao'                      => true,
        'descricao'                                   => true,
        'observacao_criticidade'                      => true,
        'qualidade_avaliacao'                         => true,
        'qualidade_descricao_complemento'             => true,
        'qualidade_descricao_participantes_tratativa' => true,
        'codigo_usuario_status'                       => true,
        'data_status'                                 => true,
        'descricao_status'                            => true,
        'codigo_usuario_inclusao'                     => true,
        'data_inclusao'                               => true,
        'codigo_usuario_alteracao'                    => true,
        'data_alteracao'                              => true,
        'ativo'                                       => true,
        'dados'                                       => true,
    ];
}
