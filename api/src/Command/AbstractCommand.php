<?php
namespace App\Command;

use Cake\Console\Command;

use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

abstract class AbstractCommand extends Command{

    public function initialize()
    {
        parent::initialize();
        $this->loadModel('PushOutbox');
        $this->loadModel('Usuario');
    }

    /**
     * Obter registros aptos a recebimento de mensagem,
     * todos os usuário com vinculos ou não com empresas
     *
     * @param array $conditions
     * @return array|null
     */
    public function obterUsuarios(array $conditions = []){

        $usuarios = $this->Usuario->obterDadosDeUsuarios($conditions);

        return $usuarios;
    }

    /**
     * Agendar um Push
     *
     * @param array $opcoes
     * @return array
     */
    public function agendarPush( array $opcoes =[] ){

        if(isset($opcoes['titulo']) && empty($opcoes['titulo'])){
            return ['error'=> 'Parâmetro titulo requerido'];
        }

        if(isset($opcoes['mensagem']) && empty($opcoes['mensagem'])){
            return ['error'=> 'Parâmetro mensagem requerido'];
        }

        if(isset($opcoes['fone_para']) && empty($opcoes['fone_para'])){
            return ['error'=> 'Parâmetro fone_para requerido'];
        }

        if(isset($opcoes['sistema_origem']) && empty($opcoes['sistema_origem'])){
            return ['error'=> 'Parâmetro sistema_origem requerido'];
        }

        if(isset($opcoes['modulo_origem']) && empty($opcoes['modulo_origem'])){
            return ['error'=> 'Parâmetro modulo_origem requerido'];
        }

        if(isset($opcoes['codigo_key']) && empty($opcoes['codigo_key'])){
            return ['error'=> 'Parâmetro codigo_key requerido'];
        }

        if(isset($opcoes['extra_data']) && empty($opcoes['extra_data'])){
            return ['error'=> 'Parâmetro extra_data requerido'];
        }

        if(isset($opcoes['codigo_usuario_inclusao']) && empty($opcoes['codigo_usuario_inclusao'])){
            return ['error'=> 'Parâmetro codigo_usuario_inclusao requerido'];
        }

        if(isset($opcoes['data_inclusao']) && empty($opcoes['data_inclusao'])){
            return ['error'=> 'Parâmetro data_inclusao requerido'];
        }

        try {

            $registro = $this->PushOutbox->newEntity($opcoes);

            if (!$this->PushOutbox->save($registro)) {
                // debug($registro->getErrors());
                return ['error'=>$registro->getErrors()];
            }

        } catch (\Exception $e) {
            // debug($e->getMessage());
            return ['error'=>$e->getMessage()];
        }

        // retorna id do registro agendado
        $data['codigo'] = $registro->codigo;

        return $data;
    }


    public function obterDadosDispositivos(){
        return [];
    }

}
