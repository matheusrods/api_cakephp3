<?php
namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use Cake\Validation\Validator;
use App\Utils\EncodingUtil;

/**
 * QrCodeLeitura Model
 *
 * @method \App\Model\Entity\QrCodeLeitura get($primaryKey, $options = [])
 * @method \App\Model\Entity\QrCodeLeitura newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\QrCodeLeitura[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\QrCodeLeitura|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\QrCodeLeitura saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\QrCodeLeitura patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\QrCodeLeitura[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\QrCodeLeitura findOrCreate($search, callable $callback = null, $options = [])
 */
class QrCodeLeituraTable extends AppTable
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

        $this->setTable('qr_code_leitura');
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
            ->integer('codigo_usuario')
            ->requirePresence('codigo_usuario', 'create')
            ->notEmptyString('codigo_usuario');

        $validator
            ->integer('codigo_resultado_covid')
            ->requirePresence('codigo_resultado_covid', 'create')
            ->notEmptyString('codigo_resultado_covid');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        return $validator;
    }

    /**
     * [setQrCodeLeitura metodo para inserir no banco quando o usuario ler um qrcode do lyn]
     * @param [type] $dados [description]
     */
    public function setQrCodeLeitura($dados)
    {

        //seta os dados para gravar na tabela
        $setDados = array(
            'codigo_usuario' => $dados['codigo_usuario'],
            'codigo_resultado_covid' => $dados['dado_qr_code'],
            'codigo_usuario_inclusao' => $dados['codigo_usuario'],
            'data_inclusao' => date('Y-m-d H:i:s'),
        );

        $qrCode = $this->newEntity($setDados);

        if ($this->save($qrCode)) {
            return true;
        }

        return false;

    }//fim setQrCodeLeitura

}
