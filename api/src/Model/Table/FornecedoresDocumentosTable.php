<?php
namespace App\Model\Table;

use Cake\Datasource\ConnectionManager;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

/**
 * FornecedoresDocumentos Model
 *
 * @method \App\Model\Entity\FornecedoresDocumento get($primaryKey, $options = [])
 * @method \App\Model\Entity\FornecedoresDocumento newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\FornecedoresDocumento[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresDocumento|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresDocumento saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\FornecedoresDocumento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresDocumento[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\FornecedoresDocumento findOrCreate($search, callable $callback = null, $options = [])
 */
class FornecedoresDocumentosTable extends Table
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

        $this->setTable('fornecedores_documentos');
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
            ->integer('codigo_fornecedor')
            ->requirePresence('codigo_fornecedor', 'create')
            ->notEmptyString('codigo_fornecedor');

        $validator
            ->integer('codigo_tipo_documento')
            ->requirePresence('codigo_tipo_documento', 'create')
            ->notEmptyString('codigo_tipo_documento');

        $validator
            ->scalar('caminho_arquivo')
            ->maxLength('caminho_arquivo', 255)
            ->requirePresence('caminho_arquivo', 'create')
            ->notEmptyString('caminho_arquivo');

        $validator
            ->dateTime('data_inclusao')
            ->requirePresence('data_inclusao', 'create')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('validado')
            ->allowEmptyString('validado');

        $validator
            ->date('data_validade')
            ->allowEmptyDate('data_validade');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        return $validator;
    }

    public function listaDocumentos(int $codigo_fornecedor)
    {
        $query = "
                (
                SELECT
                    fd.caminho_arquivo
                    , td.descricao documento
                    , td.status
                    , CASE WHEN fd.data_validade < getdate() THEN 'VENCIDO' ELSE 'OK' END data_validade
                FROM 
                    fornecedores_documentos fd
                    LEFT JOIN dbo.tipos_documentos td ON td.codigo = fd.codigo_tipo_documento
                    LEFT JOIN dbo.fornecedores f ON f.codigo = fd.codigo_fornecedor
                    LEFT JOIN dbo.propostas_credenciamento pc ON f.codigo_documento = pc.codigo_documento
                WHERE
                    fd.codigo_fornecedor = {$codigo_fornecedor}
                )  
                UNION   
                ( 
                SELECT
                    '' caminho_arquivo
                    , td.descricao documento
                    , td.status
                    , '' data_validade
                FROM
                    tipos_documentos td
                WHERE
                
                    td.status = 1 AND
                    td.codigo not in (
                            SELECT
                                codigo_tipo_documento
                            FROM
                                fornecedores_documentos
                            WHERE
                                codigo = {$codigo_fornecedor})
                                )
                ";
        $conn = ConnectionManager::get('default');
        $dados =  $conn->execute($query)->fetchAll('assoc');

        return $dados;
    }

    function retorna_documentos_enviados($codigo_fornecedor){
        $this->bindModel(array(
            'belongsTo' => array(
                'TipoDocumento' => array(
                    'alias' => 'TipoDocumento',
                    'foreignKey' => FALSE,
                    'type' => 'LEFT',
                    'conditions' => 'TipoDocumento.codigo = FornecedorDocumento.codigo_tipo_documento'
                ),
                'Fornecedor' => array(
                    'alias' => 'Fornecedor',
                    'foreignKey' => FALSE,
                    'type' => 'LEFT',
                    'conditions' => 'Fornecedor.codigo = FornecedorDocumento.codigo_fornecedor'
                ),
                'PropostaCredenciamento' => array(
                    'alias' => 'PropostaCredenciamento',
                    'foreignKey' => FALSE,
                    'type' => 'LEFT',
                    'conditions' => 'Fornecedor.codigo_documento = PropostaCredenciamento.codigo_documento'
                ),
            )
        ));

        $this->virtualFields['validade'] = "CASE WHEN FornecedorDocumento.data_validade < getdate() THEN 'VENCIDO' ELSE 'OK' END";
        $documentos_enviados = $this->find('all', array('conditions' => array('codigo_fornecedor' => $codigo_fornecedor), 'order' => 'ordem_exibicao'));
        return $documentos_enviados;

    }

    function retorna_documentos_pendentes($codigo_fornecedor){
        $TipoDocumento =& ClassRegistry::Init('TipoDocumento');

        $documentos_pendentes = $TipoDocumento->find('all', array(
                'conditions' => array(
                    'status' => 1,
                    'codigo NOT IN (
					SELECT codigo_tipo_documento 
					FROM '.$this->databaseTable.'.'.$this->tableSchema.'.'.$this->useTable.' 
					WHERE codigo_fornecedor = '.$codigo_fornecedor.' )'
                )
            )
        );

        return $documentos_pendentes;
    }

    function _upload($file, $codigo_fornecedor, $novo_nome) {

        // destino do arquivo no servidor
        $destino = APP.'webroot'.DS.'files'.DS.'documentacao'.DS . $codigo_fornecedor;

        // extensoes permitidas
        if( preg_match('@\.(jpg|png|gif|jpeg|bmp|pdf|doc|docx|pdf)$@i', $file['name']) ) {

            // cria diretorio
            if(!is_dir($destino))
                mkdir($destino);

            // upload
            if(move_uploaded_file($file['tmp_name'], $destino . DS . "fornecedor_" . $codigo_fornecedor . "_" . $novo_nome . "." . end(explode('.', $file['name'])))) {
                return array('upload' => true, 'msg' => 'Arquivo enviado com sucesso!', 'nome' => "fornecedor_" . $codigo_fornecedor . "_" . $novo_nome . "." . end(explode('.', $file['name'])));
            } else {
                return array('upload' => false, 'msg' => 'Arquivo não Enviado, enviar arquivo com tamanho máximo de 10Mb!');
            }
        } else {
            return array('upload' => false, 'msg' => 'extensão não permitida, envie jpg, png, gif, jpeg, bmp, pdf, doc, docx ou pdf!');
        }
    }
}
