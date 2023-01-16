<?php
namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * Configuracao Model
 *
 * @method \App\Model\Entity\Configuracao get($primaryKey, $options = [])
 * @method \App\Model\Entity\Configuracao newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\Configuracao[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\Configuracao|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Configuracao saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\Configuracao patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\Configuracao[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\Configuracao findOrCreate($search, callable $callback = null, $options = [])
 */
class ConfiguracaoTable extends Table
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

        $this->setTable('configuracao');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');
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
            ->scalar('chave')
            ->maxLength('chave', 255)
            ->requirePresence('chave', 'create')
            ->notEmptyString('chave');

        $validator
            ->scalar('valor')
            ->maxLength('valor', 500)
            ->allowEmptyString('valor');

        $validator
            ->scalar('observacao')
            ->maxLength('observacao', 500)
            ->allowEmptyString('observacao');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        return $validator;
    }

    /**
     * [getChave metodo para pegar a chave configurado]
     * @param  [type] $chave [codigo da chave]
     * @return [type]        [description]
     */
    public function getChave($chave){
        $codigo_empresa = 1;

        $valor = $this->find()->select('valor')->where(['chave' => strtoupper($chave), 'codigo_empresa' => $codigo_empresa])->hydrate(false)->first();
        if(empty($valor) || is_null($valor) || $valor == 0)
            return null;
        
        return $valor['valor'];

    }// fim getChave

    /**
     * [getConfiguracaoTiposExames metodo para montar o array com as configuracoes dos exames]
     * @return [type] [description]
     */
    public function getConfiguracaoTiposExames()
    {
        //variavel auxiliar
        $config = array();
        //seta os valores
        $config['fichaclinica'] = $this->getChave('INSERE_EXAME_CLINICO');
        $config['audiometria'] = $this->getChave('INSERE_EXAME_AUDIOMETRICO');
        $config['assistencial'] = $this->getChave('FICHA_ASSISTENCIAL');
        $config['psicossocial'] = $this->getChave('FICHA_PSICOSSOCIAL');

        //varre as configuracoes e formata corretamente
        $arrConfig = array();
        foreach($config AS $key => $valConfig){

            //separa os codigo caso haja
            $codigos = explode(',',$valConfig);

            foreach ($codigos as $cod) {
                $arrConfig[trim($cod)] = $key;
            }
        }//fim foreach

        return $arrConfig;

    }//fim getConfiguracaoTiposExames()


}
