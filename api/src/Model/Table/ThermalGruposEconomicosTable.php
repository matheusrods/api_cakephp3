<?php
namespace App\Model\Table;

use App\Model\Table\GruposEconomicosTable;

/**
 * ThermalGruposEconomicos
 * 
 * Operações com grupos economicos relativas ao aplicativo Thermal-Care
 * 
 */
class ThermalGruposEconomicosTable extends GruposEconomicosTable
{

    // Alias ThermalGruposEconomicos
    // table grupos_economicos

    /**
     * Retorna o código da Matriz de acordo com o código do Cliente fornecido
     * 
     * @param int $codigo_cliente
     * @return array
     */
    public function obterCodigoMatrizPeloCodigoFilial(int $codigo_cliente)
    {
        
        $fields = [
            'codigo'=>'ThermalGruposEconomicos.codigo',
            'codigo_cliente_matriz'=>'ThermalGruposEconomicos.codigo_cliente',
            'descricao'=>'ThermalGruposEconomicos.descricao'
        ];

        $joins = [
            'GrupoEconomicoCliente'=> [
                'table' => 'grupos_economicos_clientes',
                'type' => 'INNER',
                'conditions' => [
                    'GrupoEconomicoCliente.codigo_grupo_economico = ThermalGruposEconomicos.codigo'
                ]
            ]
        ];
        
        $conditions = [
            'GrupoEconomicoCliente.codigo_cliente' => $codigo_cliente
        ];

        $data =  $this->find()
        ->select($fields)
        ->join($joins)
        ->where($conditions);
        
        return $data;
    }

}
