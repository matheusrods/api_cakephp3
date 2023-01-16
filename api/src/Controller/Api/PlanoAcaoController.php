<?php

namespace App\Controller\Api;

use Cake\Datasource\ConnectionManager;
use Cake\ORM\TableRegistry;
use App\Utils\Encriptacao;
use App\Utils\Comum;
use Cake\Core\Exception\Exception;
use DateTime;
use DateTimeZone;
use App\Controller\Api\ApiController;

/**
 * Agendamento Controller
 *
 * @property \App\Model\Table\AgendamentoExamesTable AgendamentoExamesTable
 *
 * @method \App\Model\Entity\AgendamentoExame[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */

class PlanoAcaoController extends ApiController
{
    public $connection;
    public function initialize()
    {
        parent::initialize();
        $this->connection = ConnectionManager::get('default');
        //$this->Auth->allow(['']);

        //$this->loadModel("TipoNotificacao");
    }
    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {

    }

    public function acoesPendentes($prazo_inicial = null, $prazo_final = null, $codigo_status = null, $codigo_origem = null)
    {

        $acoes = array(
            array(
                "codigo_acao" => 45427,
                "origem" => 'Gestão de risco',
                "codigo_origem" => 92182,
                "criticidade" => 'Alta',
                "tipo_acao" => 'Substituição',
                "prazo" => "2021-02-21",
                "codigo_usuario_responsavel" => 70455,
                "responsavel" => "Renato Saldanha",
                "codigo_usuario_identificador" => 72489,
                "identificado_por" => "Marcelo Ribeiro",
                "codigo_status" => 1,
                "status" => "Pendente",
                "descricao_desvio" => "Descrição do desvio",
                "descricao_acao" => "Descrição da ação",
                "local_acao" => "Local da ação",
                "localidade" => array(
                    "codigo_cliente" => 10011,
                    "razao_social" => "EMPRESA TREINAMENTO",
                    "endereco" => array(
                        "complemento" => "SALA 04",
                        "numero" => "685",
                        "logradouro" => "RUA TREZE DE MAIO",
                        "bairro" => "BELA VISTA",
                        "cidade" => "SÃO PAULO",
                        "estado_abreviacao" => "SP"
                    )
                )
            ),
            array(
                "codigo_acao" => 45428,
                "origem" => 'Gestão de risco',
                "codigo_origem" => 92183,
                "criticidade" => 'Moderada',
                "tipo_acao" => 'Substituição',
                "prazo" => "2021-02-27",
                "codigo_usuario_responsavel" => 70455,
                "responsavel" => "Renato Saldanha",
                "codigo_usuario_identificador" => 72490,
                "identificado_por" => "Luiz Gomes",
                "codigo_status" => 2,
                "status" => "Atrasado",
                "descricao_desvio" => "Descrição do desvio 2",
                "descricao_acao" => "Descrição da ação 2",
                "localidade" => array(
                    "codigo_cliente" => 10011,
                    "razao_social" => "EMPRESA TREINAMENTO",
                    "endereco" => array(
                        "complemento" => "SALA 04",
                        "numero" => "685",
                        "logradouro" => "RUA TREZE DE MAIO",
                        "bairro" => "BELA VISTA",
                        "cidade" => "SÃO PAULO",
                        "estado_abreviacao" => "SP"
                    )
                )
            ),
            array(
                "codigo_acao" => 45429,
                "origem" => 'Observador EHS',
                "codigo_origem" => 92184,
                "criticidade" => 'Baixa',
                "tipo_acao" => 'Substituição',
                "prazo" => "2021-02-29",
                "codigo_usuario_responsavel" => 70455,
                "responsavel" => "Renato Saldanha",
                "codigo_usuario_identificador" => 72490,
                "identificado_por" => "Luiz Gomes",
                "codigo_status" => 3,
                "status" => "Planejada",
                "descricao_desvio" => "Descrição do desvio 3",
                "descricao_acao" => "Descrição da ação 3",
                "localidade" => array(
                    "codigo_cliente" => 10011,
                    "razao_social" => "EMPRESA TREINAMENTO",
                    "endereco" => array(
                        "complemento" => "SALA 04",
                        "numero" => "685",
                        "logradouro" => "RUA TREZE DE MAIO",
                        "bairro" => "BELA VISTA",
                        "cidade" => "SÃO PAULO",
                        "estado_abreviacao" => "SP"
                    )
                )
            )
        );

        $result = array_filter($acoes, function ($acao) use ($prazo_inicial, $prazo_final, $codigo_status, $codigo_origem) {

            $data = array();

            $prazo = new DateTime($acao['prazo']);
            $prazo_inicial = new DateTime($prazo_inicial);
            $prazo_final = new DateTime($prazo_final);

            $codigo_status != 'null' ? $codigo_status : '';
            $codigo_origem != 'null' ? $codigo_origem : '';



            //Filtra por codigo_origem e codigo_status
            if ($prazo_inicial <= $prazo && $prazo_final >= $prazo && empty($codigo_origem) && empty($codigo_status)) {

                echo 2;
                $data[] = array_push($acao);
                return $data;
            }

            //Filtra por codigo_origem e codigo_status
            if (!empty($codigo_origem) && !empty($codigo_status) && $codigo_origem == $acao['codigo_origem']
                && $codigo_status == $acao['codigo_status'] && $prazo_inicial <= $prazo && $prazo_final >= $prazo) {

                $data[] = array_push($acao);
                return $data;
            }

            //Filtra por codigo_status
            if ( !empty($codigo_status) && $codigo_status == $acao['codigo_status']) {

                $data[] = array_push($acao);
                return $data;
            }

            //Filtra por codigo_origem
            if ( !empty($codigo_origem) && $codigo_origem == $acao['codigo_origem']) {

                $data[] = array_push($acao);
                return $data;
            }
        });

        $data = array_values($result);

        $this->set(compact('data'));
    }

}
