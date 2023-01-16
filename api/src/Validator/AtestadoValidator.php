<?php
namespace App\Validator;

use Cake\ORM\TableRegistry;
use App\Validator\AbstractValidator;

class AtestadoValidator extends AbstractValidator {
    

    /**
     * Valida os dados recebido para cadastro ou atualização de um atestado medico
     *
     * @param object $payload             objeto json com os parametros
     * @param int $codigo_usuario         codigo do usuario
     * @param boolean $atualizando        flag se esta inserindo ou atualizando
     * @return array retorna array com atestados validados
     */
    public function validaPayloadSalvar($payload, int $codigo_usuario, bool $atualizando = false)
    {
        $payload_validado = [];

        // se não tem payload
        if(empty($payload)){
            return ['error' => 'Payload não encontrado'];
        }

        // se não tem codigo_usuario
        if(empty($payload)){
            return ['error' => 'Codigo cliente não encontrado'];
        }

        // valida se objeto é um json válido
        try {
            $payload_encoded = json_decode(json_encode( $payload, JSON_FORCE_OBJECT ));
        } catch (\Exception $e) {
            return ['error' => 'Payload inválido, não foi possível decodifica-lo'];
        }
        
        if(!isset($payload_encoded->cliente)
            || !isset($payload_encoded->cliente->codigo_cliente)
            && empty($payload_encoded->cliente->codigo_cliente)
            || (!is_array($payload_encoded->cliente->codigo_cliente)
                && count((array)$payload_encoded->cliente->codigo_cliente) == 0)){
                return ['error'=>'Parâmetro ou valor requerido em codigo_cliente'];
        }

        // TODO - ir no banco ver se existe o codigo informado quando PUT
        // se $atualizando == true entao no payload deve conter o $codigo_atestado
        if($atualizando == true && !isset($payload_encoded->codigo_atestado)){
            return ['error' => 'Código do atestado não encontrado'];
        }
        
        // obter a lista de codigos clientes passados no payload
        $codigo_cliente = (array)$payload_encoded->cliente->codigo_cliente;

        // obter os códigos no qual são relacionados com o usuario
        $usuario_dados = $this->obterDadosDoUsuario($codigo_usuario);
        $usuario_dados_clientes = (array)$usuario_dados->cliente;
        
        if(empty($usuario_dados_clientes)
            || is_array($usuario_dados_clientes) && count($usuario_dados_clientes) == 0){
                return ['error' => 'Divergência no relacionamento, código cliente não encontrado'];
        }

        // valida se codigo cliente passado no payload realmente faz parte do relacionamento deste usuário
        $clientes_validos = [];
        $clientes_invalidos = [];
        $validado = null;
        
        foreach ($codigo_cliente as $k => $v) {

            $validado = $this->validaSeValorExisteEmArray( $v, (array)$usuario_dados_clientes, 'codigo', true );

            if(isset($validado['error'])){
                return ['error'=> $validado['error']];
            }

            // se valido
            if($validado == true){
                $clientes_validos[] = $codigo_cliente[$k];
            } else {
                $clientes_invalidos[] = $codigo_cliente[$k];
            }
        }
        
        if(count($clientes_invalidos) > 0){
            return ['error'=> sprintf("Código cliente [ %s ] não tem relação com este usuário", join($clientes_invalidos, ', '))];
        }
        
        $relacao_codigo_cliente_funcionario = [];

        // obtem codigo funcionario
        foreach ($clientes_validos as $k => $v) {

            //busca o codigo do cliente matriz
            $GEC = TableRegistry::get('GruposEconomicosClientes');
            $gec = $GEC->getCodigoClienteMatriz($v);
            $v = (int)$gec->codigo_cliente_matriz;
            
            // $v == integer codigo_cliente
            $codigo_cliente_funcionario = $this->obterCodigoClienteFuncionario($codigo_usuario, $v);    
            
            if(empty($codigo_cliente_funcionario)){
                return ['error'=> "Não existe codigo_cliente_funcionario para este usuário"]; // break;
            }

            $relacao_codigo_cliente_funcionario[] = [
                'codigo_cliente' => $v,
                'codigo_cliente_funcionario'=> $codigo_cliente_funcionario
            ];
        }

        // valida PROFISSIONAL
        // FOI SOLICITADO UTILIZAR 11860 quando não retornar profissional / codigo_profissional for vazio para o atestado
        if(!isset($payload_encoded->profissional)) {
            $profissional_dados =  [ "codigo_medico" => 11860 ]; // 11860 - codigo para medico inexistente/fixo
        }
        else {

            if(empty($payload_encoded->profissional->codigo_medico)) {                
                $profissional_dados =  [ "codigo_medico" => 11860 ]; // 11860 - codigo para medico inexistente/fixo
            }  
            else if(is_null($payload_encoded->profissional->codigo_medico)) {
                $profissional_dados =  [ "codigo_medico" => 11860 ]; // 11860 - codigo para medico inexistente/fixo
            } 
            else {
                $profissional_dados =  (array)$payload_encoded->profissional;
            } 

        }//fim profissional dados

        $r = $this->validarProfissional($codigo_usuario, $codigo_cliente[0], $profissional_dados);
        if(isset($r['error'])) {
            return ['error' => $r['error']];
        }

        // NÃO VALIDAR ESTABELECIMENTO - de acordo com o portal esta passando vazio     
        // if(!isset($payload_encoded->estabelecimento->codigo_estabelecimento)){
        //     return ['error' => 'Estabelecimento não encontrado'];
        // } else {
        //     $r = $this->validarEstabelecimento($codigo_usuario, $codigo_cliente, (array)$payload_encoded->estabelecimento, (array)$payload_encoded->profissional);
        //     if(isset($r['error'])) {
        //         return ['error' => $r['error']];
        //     }
        // }

        // estabelecimento
        $estabelecimento_endereco_informado = $payload['estabelecimento']['endereco_informado'];
        $codigo_estabelecimento = (isset($payload['estabelecimento']['codigo_estabelecimento'])) ? $payload['estabelecimento']['codigo_estabelecimento'] : null;

        // codigo_medico
        $codigo_medico = $profissional_dados['codigo_medico'];

        // codigo_medico -- agregando na validação
        $payload_validado['codigo_medico'] = $codigo_medico;
        $payload_validado['codigo_estabelecimento'] = $codigo_estabelecimento;
        $payload_validado['estabelecimento'] = $estabelecimento_endereco_informado;        
        
        // valida PERIODO
        if(!isset($payload_encoded->periodo)){
            return ['error' => 'Período não encontrado'];
        } else {
            $r = $this->validarPeriodo($codigo_usuario, (array)$payload_encoded->periodo);
            if(isset($r['error'])) {
                return ['error' => $r['error']];
            } 
        }
        
        $afastamento_horas =  (boolval($payload_encoded->periodo->afastamento_em_horas) == true );
        
        //  Horas
        if($afastamento_horas){

            if(!isset($payload_encoded->periodo->range->de)){
                return ['error' => 'Range [De] não encontrado'];
            }
            if(!isset($payload_encoded->periodo->range->ate)){
                return ['error' => 'Range [Ate] não encontrado'];
            }

            $payload_validado['hora_afastamento'] = $payload_encoded->periodo->range->de;
            $payload_validado['hora_retorno'] = $payload_encoded->periodo->range->ate;

            $payload_validado['data_afastamento_periodo'] = $payload_encoded->periodo->data;
            $payload_validado['data_retorno_periodo'] = $payload_encoded->periodo->data;

            $payload_validado['afastamento_em_horas'] = $payload_encoded->periodo->range->em_horas;

        } else {
        
        // Dias
            if(!isset($payload_encoded->periodo->range->de)){
                return ['error' => 'Range [De] não encontrado'];
            }
            if(!isset($payload_encoded->periodo->range->ate)){
                return ['error' => 'Range [Ate] não encontrado'];
            }

            $payload_validado['data_afastamento_periodo'] = $payload_encoded->periodo->range->de;
            $payload_validado['data_retorno_periodo'] = $payload_encoded->periodo->range->ate;

            $payload_validado['afastamento_em_dias'] = $payload_encoded->periodo->range->em_dias;

        }

        // valida MOTIVO
        if(!isset($payload_encoded->motivo)){
            return ['error' => 'Motivo não encontrado'];
        } else {
            $r = $this->validarMotivo($codigo_usuario, (array)$payload_encoded->motivo);
            if(isset($r['error'])) {
                return ['error' => $r['error']];
            } 
        }

        // codigo_motivo_licenca -- agregando na validação
        $codigo_motivo_licenca =  $payload_encoded->motivo->codigo_motivo_licenca;
        $payload_validado['codigo_motivo_licenca'] = $codigo_motivo_licenca;

        // codigo_motivo_esocial -- agregando na validação
        $codigo_motivo_esocial = isset($payload_encoded->motivo->codigo_motivo_esocial) && !empty(($payload_encoded->motivo->codigo_motivo_esocial)) ? $payload_encoded->motivo->codigo_motivo_esocial : "";
        $payload_validado['codigo_motivo_esocial'] = $codigo_motivo_esocial;

        if(isset($payload_encoded->motivo->descricao) && !empty($payload_encoded->motivo->descricao)){
            $payload_validado['observacao'] = $payload_encoded->motivo->descricao;
        }


        // NAO DEIXAR OBRIGATORIO DE ACORDO COM O PORTAL
        // valida CID10
        // if(!isset($payload_encoded->cid10) && !isset($payload_encoded->cid10->codigo)){
        //     return ['error' => 'Cid10 não encontrado'];
        // } else {
        //     $r = $this->validarCid($codigo_usuario, (array)$payload_encoded->cid10);
        //     if(isset($r['error'])) {
        //         return ['error' => $r['error']];
        //     } 
        // }
        
        // codigo_cid -- agregando na validação
        if(isset($payload_encoded->cid10)) {
            $codigo_cid = $payload_encoded->cid10->codigo;
            $payload_validado['codigo_cid'] = $codigo_cid;
        }

        // valida FOTO
        if(!isset($payload_encoded->foto_atestado)){
            return ['error' => 'Foto não encontrada'];
        } else {
    
            $r = $this->validarFoto($codigo_usuario, (array)$payload_encoded->foto_atestado);
            if(isset($r['error'])) {
                return ['error' => $r['error']];
            } 
        }
        if(isset($payload_encoded->foto_atestado->imagem_base64)){
            
            $imagem_atestado = $payload_encoded->foto_atestado->imagem_base64;

            if(!empty($payload_encoded->foto_atestado->imagem_base64) 
                    && json_encode($imagem_atestado) != "{}" ){
                $payload_validado['imagem_atestado'] = $imagem_atestado;
            }
        }

        // codigo_usuario_inclusao
        $codigo_usuario_inclusao = $codigo_usuario;
        $payload_validado['codigo_usuario_inclusao'] = $codigo_usuario_inclusao;

        // TRANSFORMANDO UM ATESTADO EM MUITOS
        // isso porque estamos usando a entity no salvar dai o conteudo ja fica tratado, validado para ser gravado
        $atestados_validos = [];
        foreach ($relacao_codigo_cliente_funcionario as $key => $value) {
            $payload_validado['codigo_cliente_funcionario'] = $value['codigo_cliente_funcionario'];
            $atestados_validos[$key] = $payload_validado;
        }
        
        return $atestados_validos;

    }

        /**
     * Valida um periodo
     *
     * @param integer $codigo_usuario
     * @param array $periodo
     * @return array
     */
    private function validarPeriodo(int $codigo_usuario, $periodo){
        
        if(!isset($periodo['afastamento_em_horas']) || gettype($periodo['afastamento_em_horas']) !='boolean'){
            return ['error'=>'afastamento_em_horas inválido'];
        }
        
        $afastamento_horas =  (boolval($periodo['afastamento_em_horas']) == true );

        // verificar Horas
        if($afastamento_horas){

            if(!isset($periodo['data'])){
                return ['error'=>'data não definida'];
            }
           
            if(!isset($periodo['range'])){
                return ['error'=>'range não definido'];
            }

            $periodo_range = gettype($periodo['range'] == 'object') ? (array)$periodo['range'] :$periodo['range'];

            if(!isset($periodo_range['de']) 
                || !isset($periodo_range['ate']) 
                || !isset($periodo_range['em_horas'])){
                    
                return ['error'=>'range horas não definido corretamente'];
            }
            
            return []; // não há error
        }

        // verificar Datas
        if(!isset($periodo['range'])){
            return ['error'=>['range não definido']];
        }

        $periodo_range = gettype($periodo['range'] == 'object') ? (array)$periodo['range'] :$periodo['range'];

        if(!isset($periodo_range['de']) 
            || !isset($periodo_range['ate']) 
            || !isset($periodo_range['em_dias'])){
                
            return ['error'=>'range dias não definido corretamente'];
        }

        return []; // não há error
    }

    private function validarFoto(int $codigo_usuario, array $foto){

        if(!isset($foto['imagem_base64'])) {
            return ['error'=>'Imagem do atestado não encontrada'];
        }

        return []; // não há error
    }

    private function obterCodigoClienteFuncionario( int $codigo_usuario, int $codigo_cliente = null ){
       
        //pega os dados do usuario
        $UsuarioTable = TableRegistry::get('Usuario');
        $usuario = $UsuarioTable->getUsuariosDadosFuncionario($codigo_usuario, $codigo_cliente);
        $codigo_funcionario = $usuario->codigo_funcionario;

        //pega o codigo_cliente_funcionario para os atestados
        $ClienteFuncionarioTable = TableRegistry::get('ClienteFuncionario');
        $cliente_funcionario = $ClienteFuncionarioTable->find()->where(['codigo_cliente_matricula' => $codigo_cliente,'codigo_funcionario' => $codigo_funcionario])->first();

        if(!empty($cliente_funcionario)){
            return $cliente_funcionario->codigo;
        }

        return null;
    }

    private function validarProfissional(int $codigo_usuario, int $codigo_cliente, array $profissional){
        
        if(!isset($profissional['codigo_medico'])) {
            return ['error'=>'codigo_medico não encontrado'];
        }

        // if(!isset($profissional['numero_conselho'])) {
        //     return ['error'=>'numero_conselho não encontrado'];
        // }

        // if(!isset($profissional['uf_conselho'])) {
        //     return ['error'=>'uf_conselho não encontrado'];
        // }

        // if(!isset($profissional['nome'])) {
        //     return ['error'=>'nome não encontrado'];
        // }

        return []; // não há error
    }

    private function validarMotivo(int $codigo_usuario, array $motivo){

        if(!isset($motivo['medico'])) {
            return ['error'=>'motivo medico não encontrado'];
        }

        if(!isset($motivo['codigo_motivo_licenca'])) {
            return ['error'=>'codigo_motivo_licenca não encontrado'];
        }
        
        // if(!isset($motivo['descricao'])) {
        //     return ['error'=>'descricao não encontrado'];
        // }

        // if(!isset($motivo['codigo_motivo_esocial'])) {
        //     return ['error'=>'codigo_motivo_esocial não encontrado'];
        // }
        
        return []; // não há error        
    }

}