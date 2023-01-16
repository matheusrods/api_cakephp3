<?php

namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\Datasource\ConnectionManager;

class ClienteDsTable extends AppTable
{

    public $connect;

    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('cliente_ds');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->connect = ConnectionManager::get('default');
    }

    private function obterDuplicatas()
    {
        $stmt = $this->connect->execute('SELECT a.codigo, a.codigo_cliente_externo, a.codigo_cliente
        FROM cliente_ds a
        JOIN
          (SELECT codigo_cliente_externo,
                  codigo_cliente,       
                  COUNT(*) as qtde
           FROM cliente_ds
           WHERE ativo = 1
           GROUP BY codigo_cliente_externo, codigo_cliente
           HAVING count(*) > 1) b ON a.codigo_cliente_externo  = b.codigo_cliente_externo AND a.codigo_cliente  = b.codigo_cliente
        AND a.codigo_cliente_externo = b.codigo_cliente_externo
        AND a.codigo_cliente = b.codigo_cliente
        ORDER BY a.codigo_cliente,
                a.codigo_cliente_externo,
                a.codigo');

        return $stmt->fetchAll('assoc');
    }

    public function obterArrayConciliacoes()
    {

        $duplicatas = $this->obterDuplicatas();

        $conciliacoes = [];
        foreach ($duplicatas as $duplicata) {
            $conciliacoes[$duplicata['codigo_cliente']][$duplicata['codigo_cliente_externo']][] = $duplicata['codigo'];
        }

        return $conciliacoes;
    }

    public function conciliarDuplicatas($arrCodigosDuplicatas)
    {

        try {

            $this->addBehavior('Loggable');

            $this->find()
                ->where([
                    'codigo IN' => $arrCodigosDuplicatas,
                ])
                ->update()
                ->set([
                    'ativo' => 0,
                ])
                ->execute();
        } catch (\Exception $e) {

            throw $e;
        } finally {

            $this->behaviors()->unload('Loggable');
        }
    }
}
