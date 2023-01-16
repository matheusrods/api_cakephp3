<?php

namespace App\Model\Behavior;

use ArrayObject;
use Cake\Datasource\ConnectionManager;
use Cake\Datasource\EntityInterface;
use Cake\Event\Event;
use Cake\ORM\Behavior;
use Cake\ORM\Entity;
use Cake\ORM\Table;
use Cake\ORM\Query;
use Cake\ORM\TableRegistry;
use Cake\Utility\Text;
use Cake\Core\Exception\Exception;

/**
 * Atestados behavior
 */
class SincronizarCodigoDocumentoBehavior extends Behavior
{
    public $field;
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];


    public function beforeSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {

        //Declara o Model Documento
        $modelDocumento = TableRegistry::getTableLocator()->get("Documento");

        //Pega o nome do Model
        $tableName = $event->subject()->alias();

        $codigo_documento = $modelDocumento->find()->where(['codigo' => $entity['codigo_documento']])->first();

        if (empty($codigo_documento)) {

            $novo_documento = array(
                'codigo' => $entity['codigo_documento'],
                'codigo_pais' => 1,
                'tipo' => null,
                'data_inclusao' => date('Y-m-d H:i:s'),
                'codigo_usuario_inclusao' => $entity['codigo_usuario_inclusao']
            );

            //Verifica se codigo_documento pertence a um cpf
            if ($modelDocumento->isCPF($entity['codigo_documento'])) {
                $novo_documento['tipo'] = true;
            }

            if (($tableName == 'Clientes' || $tableName == 'Fornecedores') && isset($entity['tipo_unidade'])) {

                if (isset($entity['tipo_unidade'])) {

                    //Verifica se o novo fornecedor é do tipo fiscal
                    if ($entity['tipo_unidade'] == 'F') {

                        if ($modelDocumento->isCNPJ($entity['codigo_documento'])) {
                            $novo_documento['tipo'] = false;
                        }
                    } else {
                        $novo_documento['tipo'] = false;
                    }
                }
            } else {
                if ($modelDocumento->isCNPJ($entity['codigo_documento'])) {
                    $novo_documento['tipo'] = false;
                }
            }

            if (is_null($novo_documento['tipo'])) {
                return false;
            }

            //Converte o object $result em array para usar em newEntity
            $array = json_decode(json_encode($novo_documento), true);

            $newEntity = $modelDocumento->newEntity($array);

            $newEntity['codigo'] = $entity['codigo_documento'];

            if (!$modelDocumento->save($newEntity)) {
                // print_r($newEntity->errors());
                throw new Exception("SincronizarCodigoDocumentoBehavior: Erro ao inserir na tabela documento");
                exit;
            }
        }

        return true;
    }
};
