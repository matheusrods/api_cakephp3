<?php
namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * Cliente Model
 *
 * @property \App\Model\Table\EnderecoTable&\Cake\ORM\Association\BelongsToMany $Endereco
 * @property \App\Model\Table\OperacaoTable&\Cake\ORM\Association\BelongsToMany $Operacao
 * @property \App\Model\Table\ProdutoTable&\Cake\ORM\Association\BelongsToMany $Produto
 * @property \App\Model\Table\ProdutoServicoTable&\Cake\ORM\Association\BelongsToMany $ProdutoServico
 * @property \App\Model\Table\GruposEconomicosTable&\Cake\ORM\Association\BelongsToMany $GruposEconomicos
 * @property \App\Model\Table\GruposEconomicosTable&\Cake\ORM\Association\BelongsToMany $GruposEconomicos
 * @property \App\Model\Table\GruposEconomicosTable&\Cake\ORM\Association\BelongsToMany $GruposEconomicos
 *
 * @method \App\Model\Entity\Cliente get($primaryKey, $options = [])
 * @method \App\Model\Entity\Cliente newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Cliente[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Cliente|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cliente saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Cliente patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Cliente[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Cliente findOrCreate($search, callable $callback = null, $options = [])
 */
class ClienteTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('cliente');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        // $this->belongsToMany('Endereco', [
        //     'foreignKey' => 'cliente_id',
        //     'targetForeignKey' => 'endereco_id',
        //     'joinTable' => 'cliente_endereco',
        // ]);

        $this->hasOne('Endereco', [
            'className' => 'ClienteEndereco',
            'bindingKey' => 'codigo',
            'foreignKey' => 'codigo_cliente',
            'joinTable' => 'cliente_endereco',
            'propertyName' => 'endereco',
        ]);

        $this->belongsToMany('Operacao', [
            'foreignKey' => 'cliente_id',
            'targetForeignKey' => 'operacao_id',
            'joinTable' => 'cliente_operacao',
        ]);
        $this->belongsToMany('Produto', [
            'foreignKey' => 'cliente_id',
            'targetForeignKey' => 'produto_id',
            'joinTable' => 'cliente_produto',
        ]);
        $this->belongsToMany('ProdutoServico', [
            'foreignKey' => 'cliente_id',
            'targetForeignKey' => 'produto_servico_id',
            'joinTable' => 'cliente_produto_servico',
        ]);
        $this->belongsToMany('GruposEconomicos', [
            'foreignKey' => 'cliente_id',
            'targetForeignKey' => 'grupos_economico_id',
            'joinTable' => 'grupos_economicos_clientes',
        ]);
        $this->belongsToMany('GruposEconomicos', [
            'foreignKey' => 'cliente_id',
            'targetForeignKey' => 'grupos_economico_id',
            'joinTable' => 'grupos_economicos_clientes_bkp',
        ]);
        $this->belongsToMany('GruposEconomicos', [
            'foreignKey' => 'cliente_id',
            'targetForeignKey' => 'grupos_economico_id',
            'joinTable' => 'grupos_economicos_clientes_log',
        ]);
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('codigo')
            ->allowEmptyString('codigo', null, 'create');

        $validator
            ->scalar('codigo_documento')
            ->maxLength('codigo_documento', 14)
            ->requirePresence('codigo_documento', 'create')
            ->notEmptyString('codigo_documento');

        $validator
            ->allowEmptyString('codigo_corporacao');

        $validator
            ->allowEmptyString('codigo_corretora');

        $validator
            ->scalar('razao_social')
            ->maxLength('razao_social', 256)
            ->requirePresence('razao_social', 'create')
            ->notEmptyString('razao_social');

        $validator
            ->scalar('nome_fantasia')
            ->maxLength('nome_fantasia', 256)
            ->requirePresence('nome_fantasia', 'create')
            ->notEmptyString('nome_fantasia');

        $validator
            ->scalar('inscricao_estadual')
            ->maxLength('inscricao_estadual', 20)
            ->allowEmptyString('inscricao_estadual');

        $validator
            ->scalar('ccm')
            ->maxLength('ccm', 20)
            ->allowEmptyString('ccm');

        $validator
            ->decimal('iss')
            ->allowEmptyString('iss');

        $validator
            ->allowEmptyString('codigo_endereco_regiao');

        $validator
            ->boolean('regiao_tipo_faturamento')
            ->allowEmptyString('regiao_tipo_faturamento');

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        $validator
            ->boolean('uso_interno')
            ->notEmptyString('uso_interno');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->numeric('comissao_gestor')
            ->allowEmptyString('comissao_gestor');

        $validator
            ->numeric('comissao_representante')
            ->allowEmptyString('comissao_representante');

        $validator
            ->scalar('cnae')
            ->maxLength('cnae', 7)
            ->allowEmptyString('cnae');

        $validator
            ->integer('codigo_gestor')
            ->allowEmptyString('codigo_gestor');

        $validator
            ->dateTime('data_inativacao')
            ->allowEmptyDateTime('data_inativacao');

        $validator
            ->dateTime('data_ativacao')
            ->allowEmptyDateTime('data_ativacao');

        $validator
            ->integer('codigo_area_atuacao')
            ->allowEmptyString('codigo_area_atuacao');

        $validator
            ->integer('codigo_sistema_monitoramento')
            ->allowEmptyString('codigo_sistema_monitoramento');

        $validator
            ->boolean('obrigar_loadplan')
            ->notEmptyString('obrigar_loadplan');

        $validator
            ->boolean('iniciar_por_checklist')
            ->notEmptyString('iniciar_por_checklist');

        $validator
            ->boolean('monitorar_retorno')
            ->notEmptyString('monitorar_retorno');

        $validator
            ->allowEmptyString('temperatura_de');

        $validator
            ->allowEmptyString('temperatura_ate');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->integer('codigo_gestor_npe')
            ->allowEmptyString('codigo_gestor_npe');

        $validator
            ->allowEmptyString('codigo_regime_tributario');

        $validator
            ->boolean('utiliza_mopp')
            ->allowEmptyString('utiliza_mopp');

        $validator
            ->allowEmptyString('tempo_minimo_mopp');

        $validator
            ->integer('codigo_gestor_operacao')
            ->allowEmptyString('codigo_gestor_operacao');

        $validator
            ->integer('codigo_gestor_contrato')
            ->allowEmptyString('codigo_gestor_contrato');

        $validator
            ->allowEmptyString('codigo_cliente_sub_tipo');

        $validator
            ->scalar('suframa')
            ->maxLength('suframa', 9)
            ->allowEmptyString('suframa');

        $validator
            ->integer('codigo_seguradora')
            ->allowEmptyString('codigo_seguradora');

        $validator
            ->integer('codigo_plano_saude')
            ->allowEmptyString('codigo_plano_saude');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_medico_pcmso')
            ->allowEmptyString('codigo_medico_pcmso');

        $validator
            ->integer('codigo_medico_responsavel')
            ->allowEmptyString('codigo_medico_responsavel');

        $validator
            ->scalar('codigo_externo')
            ->maxLength('codigo_externo', 50)
            ->allowEmptyString('codigo_externo');

        $validator
            ->scalar('codigo_documento_real')
            ->maxLength('codigo_documento_real', 14)
            ->allowEmptyString('codigo_documento_real');

        $validator
            ->scalar('tipo_unidade')
            ->maxLength('tipo_unidade', 1)
            ->allowEmptyString('tipo_unidade');

        $validator
            ->integer('codigo_naveg')
            ->allowEmptyString('codigo_naveg');

        $validator
            ->boolean('e_tomador')
            ->notEmptyString('e_tomador');

        $validator
            ->integer('aguardar_liberacao')
            ->allowEmptyString('aguardar_liberacao');

        return $validator;
    }

    /**
     * [getSkin metodo para pegar o logo do cliente, cor_primaria e secundaria]
     * @param  [type] $codigo_cliente [description]
     * @return [type]                 [description]
     */
    public function getSkin($codigo_cliente)
    {

        $path_logo = 'https://api.rhhealth.com.br/';

        $select = array(
            'codigo_cliente' => 'codigo',
            'nome_fantasia',
            'razao_social',
            'logo' => 'caminho_arquivo_logo',
            'cor_primaria',
            'cor_secundaria',
            'cor_auxiliar',
            'flag_logo_lyn',
            'flag_logo_gestao_risco',
            'flag_pda',
            'flag_swt',
            'flag_obs'
        );

        $dados = $this->find()
            ->select($select)
            ->where([
                'codigo' => $codigo_cliente,
                'OR' => [['flag_logo_lyn' => 1], ['flag_logo_gestao_risco' => 1], ['flag_pda' => 1], ['flag_swt' => 1], ['flag_obs' => 1]],
            ])
            ->first();
        // debug($dados->sql())

        if (!empty($dados)) {
            $dados = $dados->toArray();

            //logo sulamerica
            if ($codigo_cliente == '89173') {
                $dados['logo'] = "https://api.rhhealth.com.br/ithealth/2020/09/16/6E4F8A61-D625-1CB7-7E95-57C23ED31D40.png";
            } else {
                $dados['logo'] = $path_logo . $dados['logo'];
            } //fim logo

        } //fim empty dados

        return $dados;
    } //fim getSkin
}
