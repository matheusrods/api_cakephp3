<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * Fornecedore Entity
 *
 * @property int $codigo
 * @property string|null $codigo_documento
 * @property string $nome
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property int $codigo_usuario_inclusao
 * @property int $ativo
 * @property string|null $razao_social
 * @property string|null $responsavel_administrativo
 * @property int|null $tipo_atendimento
 * @property int|null $acesso_portal
 * @property int|null $exames_local_unico
 * @property string|null $numero_banco
 * @property string|null $tipo_conta
 * @property string|null $favorecido
 * @property string|null $agencia
 * @property string|null $numero_conta
 * @property int|null $interno
 * @property string|null $atendente
 * @property \Cake\I18n\FrozenDate|null $data_contratacao
 * @property \Cake\I18n\FrozenDate|null $data_cancelamento
 * @property int|null $contrato_ativo
 * @property int|null $codigo_soc
 * @property int|null $dia_do_pagamento
 * @property int|null $disponivel_para_todas_as_empresas
 * @property string|null $especialidades
 * @property string|null $tipo_de_pagamento
 * @property string|null $texto_livre
 * @property int|null $codigo_status_contrato_fornecedor
 * @property string|null $responsavel_tecnico
 * @property int|null $codigo_conselho_profissional
 * @property string|null $responsavel_tecnico_conselho_numero
 * @property string|null $responsavel_tecnico_conselho_uf
 * @property int|null $codigo_empresa
 * @property bool|null $utiliza_sistema_agendamento
 * @property string|null $tipo_unidade
 * @property int|null $codigo_fornecedor_fiscal
 * @property string|null $codigo_documento_real
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property string|null $cnes
 * @property int|null $codigo_fornecedor_recebedor
 * @property int|null $prestador_qualificado
 * @property int|null $modalidade_pagamento
 *
 * @property \App\Model\Entity\Endereco[] $endereco
 * @property \App\Model\Entity\Horario[] $horario
 * @property \App\Model\Entity\Medico[] $medicos
 */
class Fornecedore extends AppEntity
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
        'codigo_documento' => true,
        'nome' => true,
        'data_inclusao' => true,
        'codigo_usuario_inclusao' => true,
        'ativo' => true,
        'razao_social' => true,
        'responsavel_administrativo' => true,
        'tipo_atendimento' => true,
        'acesso_portal' => true,
        'exames_local_unico' => true,
        'numero_banco' => true,
        'tipo_conta' => true,
        'favorecido' => true,
        'agencia' => true,
        'numero_conta' => true,
        'interno' => true,
        'atendente' => true,
        'data_contratacao' => true,
        'data_cancelamento' => true,
        'contrato_ativo' => true,
        'codigo_soc' => true,
        'dia_do_pagamento' => true,
        'disponivel_para_todas_as_empresas' => true,
        'especialidades' => true,
        'tipo_de_pagamento' => true,
        'texto_livre' => true,
        'codigo_status_contrato_fornecedor' => true,
        'responsavel_tecnico' => true,
        'codigo_conselho_profissional' => true,
        'responsavel_tecnico_conselho_numero' => true,
        'responsavel_tecnico_conselho_uf' => true,
        'codigo_empresa' => true,
        'utiliza_sistema_agendamento' => true,
        'tipo_unidade' => true,
        'codigo_fornecedor_fiscal' => true,
        'codigo_documento_real' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'cnes' => true,
        'codigo_fornecedor_recebedor' => true,
        'endereco' => true,
        'horario' => true,
        'medicos' => true,
        'prestador_qualificado' => true,
        'modalidade_pagamento' => true
    ];

    protected function _getNome($registro)
    {
        return $this->iconv($registro);
    }

}
