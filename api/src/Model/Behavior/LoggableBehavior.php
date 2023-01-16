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
use Cake\Routing\Router;
use Cake\Utility\Text;
use Cake\Core\Exception\Exception;

/**
 * Atestados behavior
 */
class LoggableBehavior extends Behavior
{
    public $field;
    /**
     * Default configuration.
     *
     * @var array
     */
    protected $_defaultConfig = [];

    public  function foreign_key($field)
    {
        $this->field = $field;
        return $this->field;
    }

    public function afterSave(Event $event, EntityInterface $entity, ArrayObject $options)
    {
        $method_request = Router::getRequest();

        //Seta o nome da foreign_key a ser inserida na entity
        $foreign_key = $this->field;

        //Pega o nome do Model
        $tableName = $event->subject()->alias();

        //Declara o Model a ser usado para o get
        $modalTable = TableRegistry::getTableLocator()->get($tableName);
        $result = $modalTable->get($entity->codigo);

        //Formata datas, o behavior estava bagunçando e não estava salvando na log
        $datas = array('data_contratacao', 'data_cancelamento', 'periodo_tratamento_inicio', 'periodo_tratamento_termino');

        foreach($datas as $v){
            if( isset($result[$v]) && !empty($result[$v]) ){
                $result[$v] = date_format($result[$v], "Y-m-d");
            }
        }

        //Inseri a foreign_key no array
        $result->$foreign_key = $entity->codigo;

        //Remove o campo 'codigo' do array
        unset($result['codigo']);

        //Verifica qual o metodo do request
        switch ($method_request->method()) {
            case 'PUT':
                $result['acao_sistema'] = 2;
                break;
            default:
                $result['acao_sistema'] = 1;
                break;
        }

        //Declara o Model a ser usado para o insert
        $modalTableLog = TableRegistry::getTableLocator()->get($tableName . 'Log');

        //Converte o object $result em array para usar em newEntity
        $array = json_decode(json_encode($result), true);

        $newEntity = $modalTableLog->newEntity($array);

        if($tableName == "UsuarioGrupoCovid"){
            $newEntity['data_inclusao'] = date('Y-m-d H:i:s');
            $newEntity['data_alteracao'] = date('Y-m-d H:i:s');
        }
        else {
            $newEntity['data_inclusao'] = (isset($newEntity['data_inclusao'])) ? $newEntity['data_inclusao'] : date('Y-m-d H:i:s');
            $newEntity['data_alteracao'] = (isset($newEntity['data_alteracao'])) ? $newEntity['data_alteracao'] : date('Y-m-d H:i:s');   

            // $newEntity['codigo_usuario_alteracao'] = (isset($newEntity['codigo_usuario_alteracao'])) ? $newEntity['codigo_usuario_alteracao'] : date('Y-m-d H:i:s');
        }

        if (!$modalTableLog->save($newEntity)) {
            //print_r($newEntity->errors());
            //echo "<pre>";print_r($newEntity);
            throw new Exception("LoggableBehavior: Erro ao criar log na tabela: ".$tableName." , errors: ".print_r($newEntity->errors(),1));
            exit;
        }
    }
};
