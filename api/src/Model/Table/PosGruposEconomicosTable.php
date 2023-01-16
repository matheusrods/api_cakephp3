<?php
namespace App\Model\Table;

use App\Model\Table\GruposEconomicosTable;
use Cake\Http\Exception\InternalErrorException;
/**
 * PosGruposEconomicos
 * 
 * Operações com grupos economicos relativas ao aplicativos P.O.S
 * 
 */
class PosGruposEconomicosTable extends GruposEconomicosTable
{

    // Alias PosGruposEconomicos
    // table grupos_economicos

    /**
     * Retorna o código da Matriz de acordo com o código do Cliente fornecido
     * 
     * @param int $codigo_cliente
     * @return array
     */
    public function obterCodigoMatrizPeloCodigoFilial(int $codigo_cliente)
    {
        
        if(empty($codigo_cliente)) {
            throw new InternalErrorException('Argumento codigo_cliente inválido.');
        }

        $fields = [
            'codigo'=>'PosGruposEconomicos.codigo',
            'codigo_cliente_matriz'=>'PosGruposEconomicos.codigo_cliente',
            'descricao'=>'PosGruposEconomicos.descricao'
        ];

        $joins = [
            'GrupoEconomicoCliente'=> [
                'table' => 'grupos_economicos_clientes',
                'type' => 'INNER',
                'conditions' => [
                    'GrupoEconomicoCliente.codigo_grupo_economico = PosGruposEconomicos.codigo'
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
