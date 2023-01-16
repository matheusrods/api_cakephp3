<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Documento Model
 *
 * @property \App\Model\Table\FornecedoresTable&\Cake\ORM\Association\BelongsToMany $Fornecedores
 * @property \App\Model\Table\PropostasCredenciamentoTable&\Cake\ORM\Association\BelongsToMany $PropostasCredenciamento
 *
 * @method \App\Model\Entity\Documento get($primaryKey, $options = [])
 * @method \App\Model\Entity\Documento newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Documento[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Documento|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Documento saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Documento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Documento[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Documento findOrCreate($search, callable $callback = null, $options = [])
 */
class DocumentoTable extends Table
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

        $this->setTable('documento');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->belongsToMany('Fornecedores', [
            'foreignKey' => 'documento_id',
            'targetForeignKey' => 'fornecedore_id',
            'joinTable' => 'fornecedores_documentos',
        ]);
        $this->belongsToMany('PropostasCredenciamento', [
            'foreignKey' => 'documento_id',
            'targetForeignKey' => 'propostas_credenciamento_id',
            'joinTable' => 'propostas_credenciamento_documentos',
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
            ->scalar('codigo')
            ->maxLength('codigo', 14)
            ->allowEmptyString('codigo', null, 'create');

        $validator
            ->requirePresence('codigo_pais', 'create')
            ->notEmptyString('codigo_pais');

        $validator
            ->boolean('tipo')
            ->notEmptyString('tipo');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        return $validator;
    }

    function isCPF($cpf)
    {
        //$c = str_split(preg_replace('/\D/', '', $cpf));
        $c = str_split(preg_replace('/[^A-Za-z0-9]/', '', $cpf));
        if (count($c) != 11) return false;
        if (preg_match('/^' . substr($cpf, 1, 1) . '{11}$/', $cpf)) return false;
        for ($s = 10, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);
        if ($c[9] != ((($n %= 11) < 2) ? 0 : 11 - $n)) return false;
        for ($s = 11, $n = 0, $i = 0; $s >= 2; $n += $c[$i++] * $s--);
        if ($c[10] != ((($n %= 11) < 2) ? 0 : 11 - $n)) return false;
        return true;
    }

    function isCNPJ($cnpj)
    {
        $b = array(6, 5, 4, 3, 2, 9, 8, 7, 6, 5, 4, 3, 2);
        $c = str_split(preg_replace('/\D/', '', $cnpj));
        if (count($c) != 14) return false;
        for ($i = 0, $n = 0; $i < 12; $n += $c[$i] * $b[++$i]);
        if ($c[12] != ((($n %= 11) < 2) ? 0 : 11 - $n)) return false;
        for ($i = 0, $n = 0; $i <= 12; $n += $c[$i] * $b[$i++]);
        if ($c[13] != ((($n %= 11) < 2) ? 0 : 11 - $n)) return false;
        return true;
    }
}
