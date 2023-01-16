<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * Questo Entity
 *
 * @property int $codigo
 * @property int $codigo_questionario
 * @property int|null $ordem
 * @property int $status
 * @property int|null $codigo_proxima_questao
 * @property string|null $label
 * @property string|null $tipo
 * @property string|null $observacoes
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property int $codigo_usuario_alteracao
 * @property int|null $codigo_questao
 * @property int|null $codigo_label_questao
 * @property int|null $pontos
 * @property int $codigo_empresa
 *
 * @property \App\Model\Entity\Caracteristica[] $caracteristicas
 * @property \App\Model\Entity\FichasAssistenciai[] $fichas_assistenciais
 * @property \App\Model\Entity\FichasClinica[] $fichas_clinicas
 */
class Questoes extends AppEntity
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
        'codigo_questionario' => true,
        'ordem' => true,
        'status' => true,
        'codigo_proxima_questao' => true,
        'label' => true,
        'tipo' => true,
        'observacoes' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'codigo_questao' => true,
        'codigo_label_questao' => true,
        'pontos' => true,
        'codigo_empresa' => true,
        'caracteristicas' => true,
        'fichas_assistenciais' => true,
        'fichas_clinicas' => true
    ];

    protected function _getLabel($registro)
    {
        return $this->iconv($registro);
    }

    protected function _getCteLabel($registro)
    {
        return $this->iconv($registro);
    }
}
