<?php
namespace App\Controller\Api\Devops;

use App\Controller\Api\ApiController;


class LogsController extends ApiController
{
    public function initialize()
    {
        parent::initialize();
        // $this->Auth->allow(['obterLogs']);
    }

    private function lerArquivo(string $arquivo){

        $handle = @fopen($arquivo, "r");

        if ($handle) {
            while(!feof($handle)) {
                $fgets = fgets($handle, 4096);

                if(!empty($fgets)){
                    yield trim($fgets);
                }
            }
        
            fclose($handle);
        }

    }
    
    private function lerLogError(){

        $arquivo = LOGS."error.log";
        
        if(!file_exists($arquivo)){
            return ['error' => "Arquivo '{$arquivo}' não encontrado."];
        }

        $linhas = $this->lerArquivo($arquivo);
        
        $buffer = [];

        foreach ($linhas as $linha) {
            if(!isset($linha) || empty($linha)){
                
            } else {

                $explora_linha = explode(' ', $linha);
                if(isset($explora_linha[0]) && !empty($explora_linha[0])){
                    if (preg_match_all("/^[0-9]{4}-(0[1-9]|1[0-2])-(0[1-9]|[1-2][0-9]|3[0-1])$/",$explora_linha[0])) {
                        $buffer[] = [
                            'data'=>$explora_linha[0],
                            'hora'=>$explora_linha[1],
                            'tipo'=>$explora_linha[2],
                            'raw'=>$linha
                        ];
                    } else {

                    }
                }

            }
            
        }
        return $buffer;
    }

    public function obterLogs( $intervalo = 0)
    {
        $data = [];

        $data = $this->lerLogError();

        if(isset($data['error'])){
            $this->set('error', $data['error']);    
            return;
        }

        // ordena por data maior
        uasort($data, create_function('$a, $b', '
            
            $a = strtotime($a["data"]);
            $b = strtotime($b["data"]);

            if ($a == $b) {
                return 0;
            }
            return ($a < $b) ? +1 : -1;

            return strtotime($a["data"]) - strtotime($b["data"]);
        '));

        $this->set('data', $data);
    }

}
