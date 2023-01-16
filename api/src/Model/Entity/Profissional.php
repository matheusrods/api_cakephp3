<?php
namespace App\Model\Entity;

use App\Model\Entity\AppEntity;

/**
 * Profissional Entity
 *
 * @property int $codigo
 * @property string $nome
 * @property string $numero_conselho
 * @property \Cake\I18n\FrozenTime $data_inclusao
 * @property string|null $conselho_uf
 * @property int|null $codigo_conselho_profissional
 * @property int|null $codigo_empresa
 * @property string|null $especialidade
 * @property string|null $nit
 * @property bool|null $ativo
 * @property int|null $codigo_usuario_inclusao
 * @property int|null $codigo_usuario_alteracao
 * @property \Cake\I18n\FrozenTime|null $data_alteracao
 * @property string|null $nis
 * @property string|null $cpf
 *
 * @property \App\Model\Entity\Fornecedore[] $fornecedores
 * @property \App\Model\Entity\Funcionario[] $funcionarios
 * @property \App\Model\Entity\Endereco[] $endereco
 * @property \App\Model\Entity\PropostasCredenciamento[] $propostas_credenciamento
 */
class Profissional extends AppEntity
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
        'nome' => true,
        'numero_conselho' => true,
        'data_inclusao' => true,
        'conselho_uf' => true,
        'codigo_conselho_profissional' => true,
        'codigo_empresa' => true,
        'especialidade' => true,
        'nit' => true,
        'ativo' => true,
        'codigo_usuario_inclusao' => true,
        'codigo_usuario_alteracao' => true,
        'data_alteracao' => true,
        'nis' => true,
        'cpf' => true,
        'fornecedores' => true,
        'funcionarios' => true,
        'endereco' => true,
        'propostas_credenciamento' => true
    ];


    protected function _getNome($registro)
    {
        return $this->iconv($registro);
    }    
}
