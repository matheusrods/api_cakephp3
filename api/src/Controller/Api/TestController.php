<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;

use App\Utils\DatetimeUtil;
use App\Utils\Comum;
use Cake\Datasource\ConnectionManager;
use Cake\Collection\Collection;

class TestController extends ApiController
{

    public function initialize()
    {
        parent::initialize();

        $this->Auth->allow(['limparcache']);

    }

    // {{ BASE_URL }}devops/email?page=41&limit=100&order=desc&modelo=nomes

    // {{ BASE_URL }}devops/email?modelo=psico&codigo_usuario=73063&codigo_pedido_exame=191696&email=coreexpress@gmail.com

    public function email()
    {

        if(empty($this->request->query('modelo'))){
            return false;
        }

        $modelo = $this->request->query('modelo');

        if($modelo === 'psico'){

            return $this->testEmailPsico(
                $this->request->query('codigo_pedido_exame'),
                $this->request->query('codigo_usuario'),
                $this->request->query('email'));
        }

        if($modelo === 'nomes'){

            $fields = ['nome'];

            $this->loadModel('FornecedoresContato');

            $find = $this->FornecedoresContato->find()->select($fields);

            $data = $this->paginate($find);

            $collection = new Collection($data->toArray());
            $dataCollection = [];
            $dataCollection[] = $collection->extract(function ($fornecedorContato) {
                return $fornecedorContato->nome . '  <::>  ' . $this->decoding($fornecedorContato->nome);
            });

            return $this->responseJson($dataCollection);

        }

        return false;
    }


    private function decoding($strText ='SOLICITAÃ‡ÃƒO DE EXAMES'){

        return mb_convert_encoding($strText, 'Windows-1252', "auto");
        // SOLICITAÇÃO DE EXAMES
    }


    private function testEmailPsico($codigo_pedido_exame = null, $codigo_usuario = null, $email = null)
    {


        $this->loadModel('FichaPsicossocial');
        $this->loadModel('PedidosExames');
        $this->loadModel('FornecedoresContato');
        $this->loadModel('Cliente');


        $pedidosExamesData = $this->PedidosExames->obtemDadosComplementares($codigo_pedido_exame);
        $dados = $pedidosExamesData->toArray();

        $payloadAlerta = array(
            'codigo_pedido_exame' => $dados['codigo'],
            'empresa' => $dados['Empresa']['razao_social'],
            'fornecedor_nome' => $dados['Empresa']['razao_social'],
            'unidade' => $dados['Unidade']['razao_social'],
            'setor' => $dados['setor'],
            'funcionario' => $dados['Funcionario']['nome'],
            'cpf' => $dados['Funcionario']['cpf'],
            'idade' => $dados['idade'],
            'data_admissao' => $dados['ClienteFuncionario']['admissao'],
            'sexo' => $dados['sexo'],
            'cargo' => $dados['cargo'],
            'tipo_pedido_exame' => $dados['tipo_pedido_exame'],
        );
        //dd($payloadAlerta);
        // $this->FichaPsicossocial->enviarAlerta($payloadAlerta);


        $pedidoExame = $this->PedidosExames->getUsuariosResponderExame($codigo_usuario);

        $cliente = $this->Cliente->find()
            ->where(['codigo' => $pedidoExame['codigo_cliente'], 'ativo' => 1])
            ->first();

        $fornecedorContato = $this->FornecedoresContato->find()
        ->where(['codigo_fornecedor' => $pedidoExame['codigo_fornecedor'], 'codigo_tipo_retorno' => 2])
        ->order('codigo DESC')
        ->first();

        $emailContato = empty($email) ? $fornecedorContato['descricao'] : $email;

        $payloadEmail = [];
        $payloadEmail['codigo_pedido_exame']     = $pedidoExame['codigo_pedidos_exames'];
        // $payloadEmail['codigo_medico']           = $medico['codigo'];
        // $payloadEmail['medico_nome']             = $medico['nome'];
        $payloadEmail['codigo_cliente']          = $cliente['codigo'];
        $payloadEmail['cliente_razao_social']    = $cliente['razao_social'];
        $payloadEmail['codigo_usuario']          = $pedidoExame['codigo_usuario'];
        $payloadEmail['codigo_empresa']          = 1;
        $payloadEmail['funcionario_nome']        = $pedidoExame['funcionario_nome'];
        $payloadEmail['setor']                   = $pedidoExame['setor'];
        $payloadEmail['cargo']                   = $pedidoExame['cargo'];
        $payloadEmail['fornecedor_nome']         = $fornecedorContato['nome'];
        $payloadEmail['fornecedor_email']        = $emailContato;
        $payloadEmail['ativo'] = 1;

        //dd($payloadEmail);
        $this->FichaPsicossocial->enviarEmail($payloadEmail);

    }

    /**
     * [testPdaConfigRegra metodo para testar o plano de acao as regras implementadas]
     * @param  [type] $codigo [description]
     * @return [type]         [description]
     */
    public function testPdaConfigRegra($codigo, $regra)
    {

        $this->loadModel("PdaConfigRegra");


        switch ($regra) {
            case '1': //acao de melhoria
                $return = $this->PdaConfigRegra->getEmAcaoDeMelhoria($codigo);
                break;
            case '2': //implantacao
                $return = $this->PdaConfigRegra->getEmImplementacao($codigo);
                break;
            case '3': //eficacia
                $return = $this->PdaConfigRegra->getEmEficacia($codigo);
                break;
            case '4': //abrangencia
                $return = $this->PdaConfigRegra->getEmAbrangencia($codigo);
                break;
            case '5': //cancelamento
                $return = $this->PdaConfigRegra->getAprovacaoCancelamento($codigo);
                break;
            case '6': //postergação
                $return = $this->PdaConfigRegra->getAprovacaoPostergacao($codigo);
                break;
            case '7': //em atraso
                // $return = $this->PdaConfigRegra->getEmAtraso($codigo);
                $this->PdaConfigRegra->getAcaoesMelhoriasEmAtraso();
                break;
            default: //acao de melhoria
                echo "padrao";
                break;
        }

        debug($return);
        exit;

    }//fim testPdaConfigRegra

    public function testBaseUrl()
    {
        // echo BASE_URL."<br>";
        // echo "<br>";

        $string = '{
  "status": 200,
  "result": {
    "data": {
      "acoes_melhorias": [
        {
          "codigo": 3,
          "abrangente": null,
          "codigo_origem_ferramenta": 21,
          "codigo_cliente_observacao": 10011,
          "codigo_usuario_identificador": 76824,
          "codigo_usuario_responsavel": 76824,
          "codigo_pos_criticidade": 16,
          "formulario_resposta": "{}",
          "codigo_acoes_melhorias_tipo": 1,
          "codigo_acoes_melhorias_status": 3,
          "prazo": "2021-07-08T00:00:00+00:00",
          "descricao_desvio": "teste e-mail 2",
          "descricao_acao": "teste e-mail 2",
          "descricao_local_acao": "teste e-mail 2",
          "data_conclusao": null,
          "conclusao_observacao": null,
          "analise_implementacao_valida": null,
          "descricao_analise_implementacao": null,
          "codigo_usuario_responsavel_analise_implementacao": null,
          "data_analise_implementacao": null,
          "analise_eficacia_valida": null,
          "descricao_analise_eficacia": null,
          "codigo_usuario_responsavel_analise_eficacia": null,
          "data_analise_eficacia": null,
          "codigo_usuario_inclusao": 76824,
          "data_inclusao": "2021-07-02T12:28:02-03:00",
          "necessario_abrangencia": true,
          "necessario_eficacia": null,
          "necessario_implementacao": null,
          "endereco_completo_localidade": "RUA TREZE DE MAIO, 685 - BELA VISTA - SÃO PAULO\/SP",
          "matriz_responsabilidade": [
            {
              "codigo": 1,
              "codigo_cliente": 10011,
              "codigo_usuario": 76824
            }
          ],
          "identificador": {
            "codigo": 76824,
            "nome": "Brunna Potame",
            "dados": {
              "codigo": 2488,
              "avatar": null
            }
          },
          "responsavel": {
            "codigo": 76824,
            "nome": "Brunna Potame"
          },
          "localidade": {
            "codigo": 10011,
            "razao_social": "EMPRESA TREINAMENTO S\/A",
            "nome_fantasia": "EMPRESA TREINAMENTO",
            "endereco": {
              "codigo": 9803,
              "cep": "01327000",
              "logradouro": "RUA TREZE DE MAIO",
              "numero": 685,
              "bairro": "BELA VISTA",
              "cidade": "SÃƒO PAULO",
              "estado_descricao": "SP",
              "complemento": "SALA 04"
            }
          },
          "solicitacoes": [],
          "origem_ferramenta": {
            "codigo": 21,
            "codigo_cliente": 10011,
            "descricao": "Plano de AÃ§Ã£o"
          },
          "status": {
            "codigo": 3,
            "descricao": "Em andamento",
            "cor": "#5CB3FF"
          },
          "tipo": {
            "codigo": 1,
            "descricao": "AbrangÃªncia"
          },
          "criticidade": {
            "codigo": 16,
            "descricao": "MÃ©dia",
            "cor": "B1DBFF"
          }
        },
        {
          "codigo": 2,
          "abrangente": null,
          "codigo_origem_ferramenta": 21,
          "codigo_cliente_observacao": 10011,
          "codigo_usuario_identificador": 76824,
          "codigo_usuario_responsavel": 76824,
          "codigo_pos_criticidade": 16,
          "formulario_resposta": "{}",
          "codigo_acoes_melhorias_tipo": 1,
          "codigo_acoes_melhorias_status": 3,
          "prazo": "2021-07-09T00:00:00+00:00",
          "descricao_desvio": "Teste disparo de e-mail",
          "descricao_acao": "Teste disparo de e-mail",
          "descricao_local_acao": "Teste disparo de e-mail",
          "data_conclusao": null,
          "conclusao_observacao": null,
          "analise_implementacao_valida": null,
          "descricao_analise_implementacao": null,
          "codigo_usuario_responsavel_analise_implementacao": null,
          "data_analise_implementacao": null,
          "analise_eficacia_valida": null,
          "descricao_analise_eficacia": null,
          "codigo_usuario_responsavel_analise_eficacia": null,
          "data_analise_eficacia": null,
          "codigo_usuario_inclusao": 76824,
          "data_inclusao": "2021-07-02T10:56:15-03:00",
          "necessario_abrangencia": true,
          "necessario_eficacia": null,
          "necessario_implementacao": null,
          "endereco_completo_localidade": "RUA TREZE DE MAIO, 685 - BELA VISTA - SÃO PAULO\/SP",
          "matriz_responsabilidade": [
            {
              "codigo": 1,
              "codigo_cliente": 10011,
              "codigo_usuario": 76824
            }
          ],
          "identificador": {
            "codigo": 76824,
            "nome": "Brunna Potame",
            "dados": {
              "codigo": 2488,
              "avatar": null
            }
          },
          "responsavel": {
            "codigo": 76824,
            "nome": "Brunna Potame"
          },
          "localidade": {
            "codigo": 10011,
            "razao_social": "EMPRESA TREINAMENTO S\/A",
            "nome_fantasia": "EMPRESA TREINAMENTO",
            "endereco": {
              "codigo": 9803,
              "cep": "01327000",
              "logradouro": "RUA TREZE DE MAIO",
              "numero": 685,
              "bairro": "BELA VISTA",
              "cidade": "SÃƒO PAULO",
              "estado_descricao": "SP",
              "complemento": "SALA 04"
            }
          },
          "solicitacoes": [],
          "origem_ferramenta": {
            "codigo": 21,
            "codigo_cliente": 10011,
            "descricao": "Plano de AÃ§Ã£o"
          },
          "status": {
            "codigo": 3,
            "descricao": "Em andamento",
            "cor": "#5CB3FF"
          },
          "tipo": {
            "codigo": 1,
            "descricao": "AbrangÃªncia"
          },
          "criticidade": {
            "codigo": 16,
            "descricao": "MÃ©dia",
            "cor": "B1DBFF"
          }
        }
      ],
      "pendencias": [
        {
          "tipo": 1,
          "quantidade": 0
        },
        {
          "tipo": 2,
          "quantidade": 0
        },
        {
          "tipo": 3,
          "quantidade": 0
        },
        {
          "tipo": 4,
          "quantidade": 2
        },
        {
          "tipo": 5,
          "quantidade": 0
        },
        {
          "tipo": 6,
          "quantidade": 0
        }
      ]
    }
  }
}';



        echo "<br>";
        $opa = Comum::converterEncodingPara($string);
        echo $opa;
        // echo utf8_decode($json);
        // echo json_decode($json);


        exit;
    }

    public function limparcache()
    {
        echo exec("/home/sistemas/ithealth_api/bin/cake cache clear_all");
        echo "\n\n\n";
        echo exec("/home/sistemas/rhhealth/api_rhhealth/api/bin/cake cache clear_al");
    }

}

