<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * FichasClinicasQuesto Entity
 *
 * @property int $codigo
 * @property int $codigo_ficha_clinica_grupo_questao
 * @property int|null $codigo_ficha_clinica_questao
 * @property string $tipo
 * @property string|null $campo_livre_descricao
 * @property string|null $campo_livre_label
 * @property string|null $observacao
 * @property int $obrigatorio
 * @property string|null $ajuda
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $ativo
 * @property string|null $span
 * @property string|null $label
 * @property string|null $conteudo
 * @property int|null $parentesco_ativo
 * @property int|null $quebra_linha
 * @property int|null $ordenacao
 * @property string|null $opcao_selecionada
 * @property string|null $opcao_abre_menu_escondido
 * @property int|null $farmaco_ativo
 * @property string|null $opcao_exibe_label
 * @property int|null $multiplas_cids_ativo
 * @property string|null $exibir_se_sexo
 * @property int|null $exibir_se_idade_maior_que
 * @property int|null $exibir_se_idade_menor_que
 * @property int|null $multiplas_cids_exibe_parentesco
 * @property int|null $farmaco_campo_exibir
 * @property int|null $multiplas_cids_esconde_outros
 * @property int|null $riscos_ativo
 * @property int|null $descricao_ativo
 */
class FichasClinicasQuesto extends AppEntity
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
        'codigo_ficha_clinica_grupo_questao' => true,
        'codigo_ficha_clinica_questao' => true,
        'tipo' => true,
        'campo_livre_descricao' => true,
        'campo_livre_label' => true,
        'observacao' => true,
        'obrigatorio' => true,
        'ajuda' => true,
        'data_inclusao' => true,
        'ativo' => true,
        'span' => true,
        'label' => true,
        'conteudo' => true,
        'parentesco_ativo' => true,
        'quebra_linha' => true,
        'ordenacao' => true,
        'opcao_selecionada' => true,
        'opcao_abre_menu_escondido' => true,
        'farmaco_ativo' => true,
        'opcao_exibe_label' => true,
        'multiplas_cids_ativo' => true,
        'exibir_se_sexo' => true,
        'exibir_se_idade_maior_que' => true,
        'exibir_se_idade_menor_que' => true,
        'multiplas_cids_exibe_parentesco' => true,
        'farmaco_campo_exibir' => true,
        'multiplas_cids_esconde_outros' => true,
        'riscos_ativo' => true,
        'descricao_ativo' => true
    ];
}
