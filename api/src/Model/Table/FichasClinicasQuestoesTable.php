<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;

/**
 * FichasClinicasQuestoes Model
 *
 * @method \App\Model\Entity\FichasClinicasQuesto get($primaryKey, $options = [])
 * @method \App\Model\Entity\FichasClinicasQuesto newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FichasClinicasQuesto[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FichasClinicasQuesto|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichasClinicasQuesto saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FichasClinicasQuesto patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FichasClinicasQuesto[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FichasClinicasQuesto findOrCreate($search, callable $callback = null, $options = [])
 */
class FichasClinicasQuestoesTable extends AppTable
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

        $this->setTable('fichas_clinicas_questoes');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        // $this->belongsTo('FichasClinicasGrupoQuestoes')
        //     ->setForeignKey('codigo_ficha_clinica_grupo_questao');
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
            ->integer('codigo_ficha_clinica_grupo_questao')
            ->requirePresence('codigo_ficha_clinica_grupo_questao', 'create')
            ->notEmptyString('codigo_ficha_clinica_grupo_questao');

        $validator
            ->integer('codigo_ficha_clinica_questao')
            ->allowEmptyString('codigo_ficha_clinica_questao');

        $validator
            ->scalar('tipo')
            ->maxLength('tipo', 20)
            ->requirePresence('tipo', 'create')
            ->notEmptyString('tipo');

        $validator
            ->scalar('campo_livre_descricao')
            ->maxLength('campo_livre_descricao', 500)
            ->allowEmptyString('campo_livre_descricao');

        $validator
            ->scalar('campo_livre_label')
            ->maxLength('campo_livre_label', 255)
            ->allowEmptyString('campo_livre_label');

        $validator
            ->scalar('observacao')
            ->maxLength('observacao', 255)
            ->allowEmptyString('observacao');

        $validator
            ->integer('obrigatorio')
            ->requirePresence('obrigatorio', 'create')
            ->notEmptyString('obrigatorio');

        $validator
            ->scalar('ajuda')
            ->maxLength('ajuda', 500)
            ->allowEmptyString('ajuda');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        $validator
            ->scalar('span')
            ->maxLength('span', 20)
            ->allowEmptyString('span');

        $validator
            ->scalar('label')
            ->maxLength('label', 500)
            ->allowEmptyString('label');

        $validator
            ->scalar('conteudo')
            ->allowEmptyString('conteudo');

        $validator
            ->integer('parentesco_ativo')
            ->allowEmptyString('parentesco_ativo');

        $validator
            ->integer('quebra_linha')
            ->allowEmptyString('quebra_linha');

        $validator
            ->integer('ordenacao')
            ->allowEmptyString('ordenacao');

        $validator
            ->scalar('opcao_selecionada')
            ->maxLength('opcao_selecionada', 20)
            ->allowEmptyString('opcao_selecionada');

        $validator
            ->scalar('opcao_abre_menu_escondido')
            ->maxLength('opcao_abre_menu_escondido', 20)
            ->allowEmptyString('opcao_abre_menu_escondido');

        $validator
            ->integer('farmaco_ativo')
            ->allowEmptyString('farmaco_ativo');

        $validator
            ->scalar('opcao_exibe_label')
            ->maxLength('opcao_exibe_label', 20)
            ->allowEmptyString('opcao_exibe_label');

        $validator
            ->integer('multiplas_cids_ativo')
            ->allowEmptyString('multiplas_cids_ativo');

        $validator
            ->scalar('exibir_se_sexo')
            ->maxLength('exibir_se_sexo', 1)
            ->allowEmptyString('exibir_se_sexo');

        $validator
            ->integer('exibir_se_idade_maior_que')
            ->allowEmptyString('exibir_se_idade_maior_que');

        $validator
            ->integer('exibir_se_idade_menor_que')
            ->allowEmptyString('exibir_se_idade_menor_que');

        $validator
            ->integer('multiplas_cids_exibe_parentesco')
            ->allowEmptyString('multiplas_cids_exibe_parentesco');

        $validator
            ->integer('farmaco_campo_exibir')
            ->allowEmptyString('farmaco_campo_exibir');

        $validator
            ->integer('multiplas_cids_esconde_outros')
            ->allowEmptyString('multiplas_cids_esconde_outros');

        $validator
            ->integer('riscos_ativo')
            ->allowEmptyString('riscos_ativo');

        $validator
            ->integer('descricao_ativo')
            ->allowEmptyString('descricao_ativo');

        return $validator;
    }
}
