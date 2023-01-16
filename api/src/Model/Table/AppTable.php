<?php
namespace App\Model\Table;

use Cake\ORM\Table;
use Exception;
use App\Utils\DatetimeUtil;
use Cake\Event\Event;
use Cake\ORM\Entity;

class AppTable extends Table {

    const TABLE_QUERY_LIMIT_DEFAULT = 50; // limite de registros padrão
    
    public static $QUERY_EQ = 'eq'; // equal query conditional

    public static $QUERY_GT = 'gt'; // greater than query conditional

    public static $QUERY_LT = 'lt'; // lower than query conditional

    public static $QUERY_GTE = 'gte'; // greater than equal query conditional
    
    public static $QUERY_LTE = 'lte'; // lower than equal query conditional

    public $DATA_HORA_INICIO_PADRAO = null;

    public $DATA_HORA_FIM_PADRAO = null;

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->DATA_HORA_INICIO_PADRAO = $this->dataInicioFimPadrao()['inicio'];

        $this->DATE_HORA_FIM_PADRAO = $this->dataInicioFimPadrao()['fim'];
    }

    /**
     * valores padrão para salvar entidades
     *
     * @return bool
     */
    public function beforeSave(Event $event, Entity $entity, $options)
    {

        $dt = new DatetimeUtil();
        
        if($entity->isNew())
        {
            $dt = new DatetimeUtil();
            $entity->data_inclusao = $dt->now();
        }

        if(!$entity->isNew() && !$entity->data_alteracao)
        {
            $dt = new DatetimeUtil();
            $entity->data_alteracao = $dt->now();
        }
        // 
        if(!$entity->ativo){
            $entity->ativo = boolval($entity->ativo);
        }

        return true;
    }

    /**
     * Metodo para pesquisa reutilizavel a qualquer Model
     *
     * @param array $params
     * @return void
     */
    public function search(array $params, $avaliarQueryFields = false){
        
        $query = null;
        
        try {
            // montar a query
            $query = $this->find();

            // evaluateModelQueryFields deve ter em todas 
            // as classes Table quando necessitar usar este método Search
            if($avaliarQueryFields){
                $params = $this->evaluateModelQueryFields($params);
            }

            if(!empty($params))
                $query->where($params);
            

        } catch (Exception $e) {
            //@TODO corrigir debug para atuar em models
            // $this->debug($e->getCode(), $e->getMessage());
            throw new Exception('Erro na consulta');
        }

        return $query;
    }

    /**
     * configura uma data padrao de inicio e fim para pesquisa por datas obedecendo timezone
     *
     * @return array
     */
    public function dataInicioFimPadrao()
    {

        $dataHora = [];
        $dataHora['inicio'] = (new DatetimeUtil())->today();
        $dataHora['fim'] = (new DatetimeUtil())->now();

        return $dataHora;
    }
    
    /**
     * Avalia tipo de condicional a ser usada em uma comparação
     *
     * @param string $queryConditional
     * @return string
     */
    public function evaluateConditional( string $queryConditional ){

        switch ($queryConditional) {
            case self::$QUERY_EQ:
                $operator = '=';
                break;
            case self::$QUERY_GT:
                $operator = '>';
                break;
            case self::$QUERY_LT:
                $operator = '<';
                break;
            case self::$QUERY_GTE:
                $operator = '>=';
                break;
            case self::$QUERY_LTE:
                $operator = '<=';
                break;
            default:
                $operator = '=';
                break;
        }

        return $operator;
    }
}