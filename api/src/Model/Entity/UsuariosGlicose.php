<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * UsuariosGlicose Entity
 *
 * @property int $codigo
 * @property int|null $glicose
 * @property float|null $hemoglobina_glicada
 * @property string|null $classificacao
 * @property int|null $codigo_usuario
 * @property \Cake\I18n\FrozenTime|null $data_inclusao
 */
class UsuariosGlicose extends AppEntity
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
        'codigo' => true,
        'glicose' => true,
        'hemoglobina_glicada' => true,
        'classificacao' => true,
        'codigo_usuario' => true,
        'data_inclusao' => true
    ];

    protected function _getDataInclusao($datetime)
    {
        // se for object pode ser o FrozenDatetime do cake
        if(gettype($datetime) =='object'){
            return $datetime->format('Y-m-d H:i:s');
        }

        return $datetime;
    }

}
