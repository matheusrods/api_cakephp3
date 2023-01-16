<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * AparelhosAudiometrico Entity
 *
 * @property int $codigo
 * @property string $descricao
 * @property string|null $fabricante
 * @property \Cake\I18n\FrozenTime $data_afericao
 * @property \Cake\I18n\FrozenTime|null $data_proxima_afericao
 * @property string|null $empresa_afericao
 * @property int $disponivel_empresas
 * @property int $aparelho_padrao
 * @property int $resultado_multiplo_5
 * @property int|null $codigo_unidade
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property bool $ativo
 * @property int|null $codigo_empresa
 *
 * @property \App\Model\Entity\Resultado[] $resultados
 */
class AparelhosAudiometrico extends Entity
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
        'descricao' => true,
        'fabricante' => true,
        'data_afericao' => true,
        'data_proxima_afericao' => true,
        'empresa_afericao' => true,
        'disponivel_empresas' => true,
        'aparelho_padrao' => true,
        'resultado_multiplo_5' => true,
        'codigo_unidade' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'ativo' => true,
        'codigo_empresa' => true,
        'resultados' => true
    ];
}
