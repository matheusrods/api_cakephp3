<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * Resposta Entity
 *
 * @property int $codigo
 * @property int|null $codigo_funcionario
 * @property int $codigo_questao
 * @property int $codigo_resposta
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_empresa
 * @property int|null $pontos
 * @property string|null $label
 * @property string|null $label_questao
 * @property int $codigo_usuario_inclusao
 * @property int $codigo_questionario
 * @property string|null $descricao_questionario
 * @property int $codigo_usuario
 * @property int|null $codigo_historico_resposta
 * @property int|null $codigo_label_questao
 *
 * @property \App\Model\Entity\DeparaQuesto[] $depara_questoes
 * @property \App\Model\Entity\FichaPsicossocial[] $ficha_psicossocial
 * @property \App\Model\Entity\FichasAssistenciai[] $fichas_assistenciais
 * @property \App\Model\Entity\FichasClinica[] $fichas_clinicas
 */
class Resposta extends AppEntity
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
        'codigo_funcionario' => true,
        'codigo_questao' => true,
        'codigo_resposta' => true,
        'data_inclusao' => true,
        'codigo_empresa' => true,
        'pontos' => true,
        'label' => true,
        'label_questao' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_questionario' => true,
        'descricao_questionario' => true,
        'codigo_usuario' => true,
        'codigo_historico_resposta' => true,
        'codigo_label_questao' => true,
        'depara_questoes' => true,
        'ficha_psicossocial' => true,
        'fichas_assistenciais' => true,
        'fichas_clinicas' => true
    ];
}
