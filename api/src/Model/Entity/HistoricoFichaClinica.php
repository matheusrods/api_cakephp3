<?php
namespace App\Model\Entity;

use Cake\ORM\Entity;

/**
 * HistoricoFichaClinica Entity
 *
 * @property int $codigo
 * @property string|null $codigo_nexo
 * @property string|null $codigo_pedido_exame_nexo
 * @property string|null $cnpj_grupo_economico
 * @property string|null $cnpj_unidade
 * @property string|null $setor
 * @property string|null $cargo
 * @property string|null $funcionario_matricula
 * @property string|null $cpf
 * @property string|null $idade
 * @property string|null $sexo
 * @property string|null $exame_ocupacional
 * @property string|null $tipo_atendimento
 * @property string|null $cd_usu
 * @property string|null $medico
 * @property \Cake\I18n\FrozenDate|null $data_atendimento
 * @property string|null $observacoes
 */
class HistoricoFichaClinica extends Entity
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
        'codigo_nexo' => true,
        'codigo_pedido_exame_nexo' => true,
        'cnpj_grupo_economico' => true,
        'cnpj_unidade' => true,
        'setor' => true,
        'cargo' => true,
        'funcionario_matricula' => true,
        'cpf' => true,
        'idade' => true,
        'sexo' => true,
        'exame_ocupacional' => true,
        'tipo_atendimento' => true,
        'cd_usu' => true,
        'medico' => true,
        'data_atendimento' => true,
        'observacoes' => true
    ];
}
