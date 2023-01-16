<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * Aprho Entity
 *
 * @property int $codigo
 * @property int $codigo_qualificacao
 * @property float|null $exposicao_duracao
 * @property string|null $exposicao_frequencia
 * @property int|null $codigo_fonte_geradora_exposicao_tipo
 * @property int|null $codigo_fonte_geradora_exposicao
 * @property int|null $codigo_agente_exposicao
 * @property int|null $qualitativo
 * @property string|null $relevancia
 * @property string|null $aceitabilidade
 * @property int|null $conselho_tecnico_resultado
 * @property \Cake\I18n\FrozenTime|null $conselho_tecnico_agenda
 * @property string|null $conselho_tecnico_descricao
 * @property int|null $codigo_conselho_tecnico_arquivo
 * @property int $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $medicoes_resultado
 * @property \Cake\I18n\FrozenTime|null $medicoes_agenda
 * @property string|null $arquivo_url
 */
class Aprho extends Entity
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
        'codigo_qualificacao' => true,
        'exposicao_duracao' => true,
        'exposicao_frequencia' => true,
        'codigo_fonte_geradora_exposicao_tipo' => true,
        'codigo_fonte_geradora_exposicao' => true,
        'codigo_agente_exposicao' => true,
        'qualitativo' => true,
        'relevancia' => true,
        'aceitabilidade' => true,
        'conselho_tecnico_resultado' => true,
        'conselho_tecnico_agenda' => true,
        'conselho_tecnico_descricao' => true,
        'codigo_conselho_tecnico_arquivo' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_inclusao' => true,
        'data_alteracao' => true,
        'medicoes_resultado' => true,
        'medicoes_agenda' => true,
        'arquivo_url' => true,
    ];
}
