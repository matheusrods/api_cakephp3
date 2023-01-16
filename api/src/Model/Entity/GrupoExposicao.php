<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * GrupoExposicao Entity
 *
 * @property int $codigo
 * @property int|null $codigo_cargo
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int|null $codigo_empresa
 * @property string|null $descricao_atividade
 * @property \Cake\I18n\FrozenTime|null $data_documento
 * @property string|null $observacao
 * @property int|null $codigo_cliente_setor
 * @property int|null $codigo_grupo_homogeneo
 * @property int|null $codigo_funcionario
 * @property string|null $medidas_controle
 * @property int|null $funcionario_entrevistado
 * @property \Cake\I18n\FrozenTime|null $data_inicio_vigencia
 * @property int|null $codigo_medico
 * @property string|null $funcionario_entrevistado_terceiro
 * @property int|null $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 *
 * @property \App\Model\Entity\RiscosAtributosDetalhe[] $riscos_atributos_detalhes
 */
class GrupoExposicao extends Entity
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
        'codigo_cargo' => true,
        'data_inclusao' => true,
        'codigo_empresa' => true,
        'descricao_atividade' => true,
        'data_documento' => true,
        'observacao' => true,
        'codigo_cliente_setor' => true,
        'codigo_grupo_homogeneo' => true,
        'codigo_funcionario' => true,
        'medidas_controle' => true,
        'funcionario_entrevistado' => true,
        'data_inicio_vigencia' => true,
        'codigo_medico' => true,
        'funcionario_entrevistado_terceiro' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'riscos_atributos_detalhes' => true
    ];
}
