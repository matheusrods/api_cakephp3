<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * OnboardingClienteLog Entity
 *
 * @property int $codigo
 * @property int $codigo_onboarding
 * @property int $codigo_onboarding_cliente
 * @property int $codigo_cliente
 * @property string $titulo
 * @property string $texto
 * @property string|null $imagem
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property int|null $codigo_usuario_alteracao
 * @property int $ativo
 * @property int|null $acao_sistema
 */
class OnboardingClienteLog extends AppEntity
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
        'codigo_onboarding' => true,
        'codigo_onboarding_cliente' => true,
        'codigo_cliente' => true,
        'titulo' => true,
        'texto' => true,
        'imagem' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'data_alteracao' => true,
        'codigo_usuario_alteracao' => true,
        'ativo' => true,
        'acao_sistema' => true,
    ];
}
