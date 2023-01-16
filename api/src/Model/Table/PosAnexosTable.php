<?php

namespace App\Model\Table;

use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\PosTable as Table;
use Cake\Validation\Validator;
use Cake\Utility\Security;
use Cake\Log\Log;
use App\Utils\Comum;
use Exception;


/**
 * PosAnexos Model
 *
 * @method \App\Model\Entity\PosAnexo get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosAnexo newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosAnexo[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosAnexo|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosAnexo saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosAnexo patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosAnexo[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosAnexo findOrCreate($search, callable $callback = null, $options = [])
 */
class PosAnexosTable extends Table
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

        $this->setTable('pos_anexos');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->setEntityClass('App\Model\Entity\PosAnexo');
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
            ->integer('codigo_empresa')
            ->requirePresence('codigo_empresa', 'create')
            ->notEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_cliente')
            ->requirePresence('codigo_cliente', 'create')
            ->notEmptyString('codigo_cliente');

        $validator
            ->integer('codigo_pos_ferramenta')
            ->requirePresence('codigo_pos_ferramenta', 'create')
            ->notEmptyString('codigo_pos_ferramenta');

        $validator
            ->scalar('arquivo_url')
            ->maxLength('arquivo_url', 255)
            ->requirePresence('arquivo_url', 'create')
            ->notEmptyString('arquivo_url');

        $validator
            ->scalar('arquivo_url_curta')
            ->maxLength('arquivo_url_curta', 255)
            ->allowEmptyString('arquivo_url_curta');

        $validator
            ->integer('arquivo_tipo')
            ->allowEmptyString('arquivo_tipo');

        $validator
            ->scalar('arquivo_extensao')
            ->maxLength('arquivo_extensao', 10)
            ->allowEmptyString('arquivo_extensao');

        $validator
            ->scalar('arquivo_tamanho_bytes')
            ->maxLength('arquivo_tamanho_bytes', 100)
            ->allowEmptyString('arquivo_tamanho_bytes');

        $validator
            ->scalar('arquivo_hash')
            ->maxLength('arquivo_hash', 100)
            ->allowEmptyString('arquivo_hash');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->boolean('ativo')
            ->notEmptyString('ativo');

        return $validator;
    }

    public function salvarAnexo(
        string $file_base64           = null,
        string $prefix                = null,
        int    $codigo_pos_ferramenta = null,
        int    $codigo_cliente        = null,
        array  $data                  = []
    ) {

        if (empty($file_base64)) {
            throw new Exception("Arquivo não fornecido", 1);
        }

        if (empty($prefix)) {
            throw new Exception("Prefixo para upload do Arquivo não fornecido", 1);
        }

        if (empty($codigo_pos_ferramenta)) {
            throw new Exception("Código da ferramenta não fornecido", 1);
        }

        $arquivo_hash = Security::hash($file_base64, 'sha1', true);

        if (isset($data['codigo'])) {
            $anexo = $this->find()->where(['codigo' => $data['codigo']])->firstOrFail();
            return $this->patchEntity($anexo, $data);
        }

        // sobe imagem para servidor
        $fileData = [
            'file'   => $file_base64,
            'prefix' => $prefix,
            'type'   => 'base64',
        ];

        $sendFile = Comum::sendFileToServer($fileData);
        $path     = $sendFile->{'response'}->{'path'};

        if (empty($path)) {
            throw new Exception("Não foi possível enviar anexo ao Servidor de Arquivos", 1);
        }

        $anexoEntity = $this->newEntity($data);
        $anexoEntity->set(['codigo_usuario_inclusao' => $this->obterCodigoUsuarioAutenticado()]);
        $anexoEntity->set(['codigo_cliente' => $codigo_cliente]);
        $anexoEntity->set(['codigo_pos_ferramenta' => $codigo_pos_ferramenta]);
        $anexoEntity->set(['arquivo_hash' => $arquivo_hash]);
        $anexoEntity->set(['arquivo_url' => FILE_SERVER . $path]);

        if ($anexoEntity->hasErrors()) {
            Log::debug($anexoEntity->getErrors());
            throw new Exception("Não foi possível gravar o anexo", 1);
        }

        if (!$this->save($anexoEntity)) {
            throw new Exception("Não foi possível gravar o anexo", 1);
        }

        return $anexoEntity;
    }
}
