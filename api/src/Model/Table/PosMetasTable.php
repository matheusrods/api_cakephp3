<?php

namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use App\Utils\EncodingUtil;
use Cake\ORM\TableRegistry;

/**
 * PosMetas Model
 *
 * @method \App\Model\Entity\PosMeta get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosMeta newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosMeta[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosMeta|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosMeta saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosMeta patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosMeta[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosMeta findOrCreate($search, callable $callback = null, $options = [])
 */
class PosMetasTable extends AppTable
{
    /**
     * Initialize method
     *
     * @param array $config The configuration for the Table.
     * @return void
     */
    public function initialize(array $config)
    {
        parent::initialize($config);

        $this->setTable('pos_metas');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');
    }

    /**
     * Default validation rules.
     *
     * @param \Cake\Validation\Validator $validator Validator instance.
     * @return \Cake\Validation\Validator
     */
    public function validationDefault(Validator $validator)
    {
        $validator
            ->integer('codigo')
            ->allowEmptyString('codigo', null, 'create');

        $validator
            ->integer('codigo_empresa')
            ->allowEmptyString('codigo_empresa');

        $validator
            ->integer('codigo_setor')
            ->allowEmptyString('codigo_setor');

        $validator
            ->integer('codigo_pos_ferramenta')
            ->allowEmptyString('codigo_pos_ferramenta');

        $validator
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->integer('valor')
            ->requirePresence('valor', 'create')
            ->notEmptyString('valor');

        $validator
            ->integer('ativo')
            ->requirePresence('ativo', 'create')
            ->notEmptyString('ativo');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->requirePresence('codigo_usuario_inclusao', 'create')
            ->notEmptyString('codigo_usuario_inclusao');

        $validator
            ->dateTime('data_inclusao')
            ->notEmptyDateTime('data_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->integer('dia_follow_up')
            ->allowEmptyString('dia_follow_up');

        return $validator;
    }

    /**
     * [getQtdParticipante metodo para realizar a busca no banco com os dados de parametros passados e retornar o valor de qtd de participantes]
     * @param  [type] $codigo_unidade [codigo da unidade do funcionario]
     * @return [type]                 [description]
     */
    public function getGrafico(int $codigo_usuario, int $codigo_unidade, int $ultimos_meses = 6)
    {
        $dados = array();

        try {
            //verifica se tem a configuracao do formulário
            $this->GruposEconomicos = TableRegistry::get("GruposEconomicos");
            $codigo_cliente_matriz = $this->GruposEconomicos->getCampoPorClienteRqe("codigo_cliente", $codigo_unidade);

            //metodo para pegar a meta da area
            $meta_area = $this->getMetaArea($codigo_usuario, $codigo_cliente_matriz);

            //pegar a quantidade de meses para trás da variavel ultimos_meses
            $ultimos_meses = $ultimos_meses - 1; //para calcular corretamente o mes vigente tb
            $base_periodo = strtotime("-" . $ultimos_meses . " month", strtotime(date("Y-m-01")));
            $data_incicio = date("Ym01 00:00:00", $base_periodo);
            $data_fim = date("Ymt 23:59:59");

            $meses = date("Ym01", $base_periodo);
            $array_meses_grafico = array();

            for ($i = 0; $i <= $ultimos_meses; $i++) {
                $array_meses_grafico[] = ["mes" => date("m", strtotime($meses)), "total" => "0"];

                //soma mais um mes
                $bp = strtotime("+ 1 month", strtotime($meses));
                $meses = date("Ym01", $bp);
            }

            $this->PosSwtFormRespondido = TableRegistry::get("PosSwtFormRespondido");

            $fields = array(
                "mes" => "MONTH(PosSwtFormRespondido.data_inclusao)",
                "total" => "COUNT(PosSwtFormRespondido.codigo)"
            );

            $joins = array(
                array(
                    "table" => "usuario",
                    "alias" => "Usuario",
                    "type" => "INNER",
                    "conditions" => array("PosSwtFormRespondido.codigo_usuario_inclusao = Usuario.codigo")
                ),
                array(
                    "table" => "funcionarios",
                    "alias" => "Funcionario",
                    "type" => "INNER",
                    "conditions" => array("Usuario.apelido = Funcionario.cpf")
                ),
                array(
                    "table" => "cliente_funcionario",
                    "alias" => "ClienteFuncionario",
                    "type" => "INNER",
                    "conditions" => array(
                        "ClienteFuncionario.codigo_funcionario = Funcionario.codigo",
                        "ClienteFuncionario.ativo = 1",
                        "ClienteFuncionario.data_demissao IS NULL"
                    )
                ),
                array(
                    "table" => "funcionario_setores_cargos",
                    "alias" => "FSC",
                    "type" => "INNER",
                    "conditions" => array(
                        "FSC.codigo_cliente_funcionario = ClienteFuncionario.codigo",
                        "FSC.codigo_cliente = ClienteFuncionario.codigo_cliente",
                        "FSC.data_fim IS NULL"
                    )
                ),
                array(
                    "table" => "pos_metas",
                    "alias" => "PosMetas",
                    "type" => "INNER",
                    "conditions" => array(
                        "(
                            (
                                PosMetas.codigo_cliente_bu = ClienteFuncionario.codigo_cliente_bu
                                AND PosMetas.codigo_cliente_opco = ClienteFuncionario.codigo_cliente_opco
                            ) OR (
                                PosMetas.codigo_cliente_bu IS NULL
                                AND PosMetas.codigo_cliente_opco IS NULL
                                AND (
                                    SELECT COUNT(*) FROM pos_metas POS
                                    WHERE
                                        POS.codigo_cliente = ClienteFuncionario.codigo_cliente
                                        AND POS.codigo_setor = FSC.codigo_setor
                                        AND POS.ativo = 1
                                        AND POS.codigo_cliente_bu = ClienteFuncionario.codigo_cliente_bu
                                        AND POS.codigo_cliente_opco = ClienteFuncionario.codigo_cliente_opco
                                ) = 0
                            )
                        )",
                        "PosMetas.codigo_cliente = ClienteFuncionario.codigo_cliente",
                        "PosMetas.codigo_setor = FSC.codigo_setor",
                        "PosMetas.ativo = 1"
                    )
                )
            );

            $conditions["Usuario.codigo"] = $codigo_usuario;
            $conditions["PosMetas.codigo_cliente"] = $codigo_cliente_matriz;
            $conditions[] = array(
                "PosSwtFormRespondido.data_inclusao >= '" . $data_incicio . "'",
                "PosSwtFormRespondido.data_inclusao <= '" . $data_fim . "'",
            );
            $conditions[] = "PosSwtFormRespondido.codigo_form_respondido_swt IS NULL";

            $group = array("MONTH(PosSwtFormRespondido.data_inclusao)", "YEAR(PosSwtFormRespondido.data_inclusao)");
            $order = array("YEAR(PosSwtFormRespondido.data_inclusao)");

            $dados_grafico = $this->PosSwtFormRespondido->find()
                ->select($fields)
                ->join($joins)
                ->where($conditions)
                ->group($group)
                ->order($order)
                ->hydrate(false)
                ->toArray();

            $apresenta_msg = false;

            //verfica se tem valor
            if (!empty($dados_grafico)) {
                $dia_corrente = date("d");
                $mes_corrente = date("m");

                foreach ($dados_grafico as $value) {
                    foreach ($array_meses_grafico as $key => $val_dados) {
                        if ($val_dados["mes"] == $value["mes"]) {
                            $array_meses_grafico[$key]["total"] = $value["total"];
                        }
                    }

                    //verificacao se deve apresentar a mensagem que bateu a meta
                    if ($mes_corrente == $value["mes"]) {
                        if ($dia_corrente <= $meta_area["dia_follow_up"] && $value["total"] >= $meta_area["valor"]) {
                            $apresenta_msg = true;
                        }
                    }
                }
            }

            $status_meta = array('status' => '0', 'msg' => '');

            if (empty($meta_area)) {
                $status_meta['status'] = 2;
                $status_meta['msg'] = 'Área não possui meta cadastrada';
            }

            if ($apresenta_msg) {
                $status_meta['status'] = 1;
                $status_meta['msg'] = 'Parabéns! Você atingiu sua meta!';
            }


            $dados = array('status_meta' => $status_meta, 'meta' => $meta_area, 'dados_grafico' => $array_meses_grafico);

            // debug($dados);exit;

            if ($apresenta_msg) {
                $status_meta["status"] = 1;
                $status_meta["msg"] = "Parabéns! Você atingiu sua meta!";
            }

            $dados = array(
                "status_meta" => $status_meta,
                "meta" => $meta_area,
                "dados_grafico" => $array_meses_grafico
            );
        } catch (\Exception $e) {
            $dados["error"] = $e->getMessage();
        }

        return (array) $dados;
    }

    /**
     * [getMetaArea metodo para buscar pelo usuario sua matricula e funcao que tem o setor
     * depois pega a pega configurada para o grupo economico e setor]
     * @param  [type] $codigo_usuario [codigo do usuario logado]
     * @param  [type] $codigo_cliente_matriz [codigo do cliente matriz]
     * @return [type]                 [description]
     */
    public function getMetaArea(int $codigo_usuario, int $codigo_cliente_matriz = null)
    {
        $this->Usuario = TableRegistry::get("Usuario");

        $fields = array(
            "codigo" => "PosMetas.codigo",
            "codigo_cliente" => "PosMetas.codigo_cliente",
            "codigo_setor" => "PosMetas.codigo_setor",
            "valor" => "PosMetas.valor",
            "dia_follow_up" => "PosMetas.dia_follow_up",
            "data_inclusao" => "PosMetas.data_inclusao",
            "data_alteracao" => "PosMetas.data_alteracao"
        );

        $joins = array(
            array(
                "table" => "funcionarios",
                "alias" => "Funcionario",
                "type" => "INNER",
                "conditions" => array("Funcionario.cpf = Usuario.apelido")
            ),
            array(
                "table" => "cliente_funcionario",
                "alias" => "ClienteFuncionario",
                "type" => "INNER",
                "conditions" => array(
                    "ClienteFuncionario.codigo_funcionario = Funcionario.codigo",
                    "ClienteFuncionario.ativo = 1",
                    "ClienteFuncionario.data_demissao IS NULL"
                )
            ),
            array(
                "table" => "funcionario_setores_cargos",
                "alias" => "FSC",
                "type" => "INNER",
                "conditions" => array(
                    "FSC.codigo_cliente_funcionario = ClienteFuncionario.codigo",
                    "FSC.codigo_cliente = ClienteFuncionario.codigo_cliente",
                    "FSC.data_fim IS NULL"
                )
            ),
            array(
                "table" => "pos_metas",
                "alias" => "PosMetas",
                "type" => "INNER",
                "conditions" => array(
                    "(
                        (
                            PosMetas.codigo_cliente_bu = ClienteFuncionario.codigo_cliente_bu
                            AND PosMetas.codigo_cliente_opco = ClienteFuncionario.codigo_cliente_opco
                        ) OR (
                            PosMetas.codigo_cliente_bu IS NULL
                            AND PosMetas.codigo_cliente_opco IS NULL
                            AND (
                                SELECT COUNT(*) FROM pos_metas POS
                                WHERE
                                    POS.codigo_cliente = ClienteFuncionario.codigo_cliente
                                    AND POS.codigo_setor = FSC.codigo_setor
                                    AND POS.ativo = 1
                                    AND POS.codigo_cliente_bu = ClienteFuncionario.codigo_cliente_bu
                                    AND POS.codigo_cliente_opco = ClienteFuncionario.codigo_cliente_opco
                            ) = 0
                        )
                    )",
                    "PosMetas.codigo_cliente = ClienteFuncionario.codigo_cliente",
                    "PosMetas.codigo_setor = FSC.codigo_setor",
                    "PosMetas.ativo = 1"
                )
            )
        );

        $conditions["Usuario.codigo"] = $codigo_usuario;

        if (!is_null($codigo_cliente_matriz)) {
            $conditions["PosMetas.codigo_cliente"] = $codigo_cliente_matriz;
        }

        $dados = $this->Usuario->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->first();

        return (!empty($dados)) ? $dados->toArray() : $dados;
    }

    public function consultGoals(array $filters = array())
    {
        $fields = array(
            "codigo" => "PosMetas.codigo",
            "codigo_setor" => "PosMetas.codigo_setor",
            "codigo_cliente" => "PosMetas.codigo_cliente",
            "codigo_cliente_bu" => "PosMetas.codigo_cliente_bu",
            "codigo_cliente_opco" => "PosMetas.codigo_cliente_opco",
            "periodicidade" => "PosMetas.dia_follow_up",
            "valor_meta" => "PosMetas.valor",
            "data_configuracao" => "(
                CASE
                    WHEN PosMetas.data_alteracao IS NOT NULL THEN FORMAT(PosMetas.data_alteracao, 'yyyy-MM-dd')
                    ELSE FORMAT(PosMetas.data_inclusao, 'yyyy-MM-dd')
                END
            )"
        );

        $joins = array();

        $conditions = array(
            "PosMetas.ativo = 1",
            "(
                SELECT COUNT(*) FROM pos_metas POS WHERE
                    POS.codigo_cliente = PosMetas.codigo_cliente
                    AND POS.codigo_setor = PosMetas.codigo_setor
                    AND POS.ativo = 1
                    AND POS.codigo_cliente_bu IS NOT NULL
                    AND POS.codigo_cliente_opco IS NOT NULL
                    AND POS.codigo <> PosMetas.codigo
            ) = 0"
        );

        if (isset($filters["codigo_cliente_opco"]) && !empty($filters["codigo_cliente_opco"])) {
            $conditions["PosMetas.codigo_cliente_opco"] = $filters["codigo_cliente_opco"];
        }

        if (isset($filters["codigo_cliente_bu"]) && !empty($filters["codigo_cliente_bu"])) {
            $conditions["PosMetas.codigo_cliente_bu"] = $filters["codigo_cliente_bu"];
        }


        if (isset($filters["codigo_setor"]) && !empty($filters["codigo_setor"])) {
            $conditions["PosMetas.codigo_setor"] = $filters["codigo_setor"];
        }

        if (isset($filters["codigo_unidade"]) && !empty($filters["codigo_unidade"])) {
            $conditions["PosMetas.codigo_cliente"] = $filters["codigo_unidade"];
        } else {
            $joins[] = array(
                "table" => "grupos_economicos_clientes",
                "alias" => "GruposEconomicosClientes",
                "type" => "INNER",
                "conditions" => array(
                    "GruposEconomicosClientes.codigo_cliente = PosMetas.codigo_cliente"
                )
            );

            $joins[] = array(
                "table" => "grupos_economicos",
                "alias" => "GruposEconomicos",
                "type" => "INNER",
                "conditions" => array(
                    "GruposEconomicos.codigo = GruposEconomicosClientes.codigo_grupo_economico"
                )
            );

            $conditions["GruposEconomicos.codigo_cliente"] = $filters["codigo_cliente"];
        }

        $data = $this
            ->find()
            ->select($fields)
            ->join($joins)
            ->where($conditions)
            ->enableHydration(false)
            ->toArray();

        return $data;
    }

    public function conciliarDuplicatasClienteBu($codigoClienteBuConciliador, $arrCodigosDuplicatas)
    {

        try {

            $this->addBehavior('Loggable');

            $this->find()
                ->where(['codigo_cliente_bu IN' => $arrCodigosDuplicatas])
                ->update()
                ->set([
                    'codigo_cliente_bu' => $codigoClienteBuConciliador
                ])
                ->execute();
        } catch (\Exception $e) {

            throw $e;
        } finally {

            $this->behaviors()->unload('Loggable');
        }
    }

    public function conciliarDuplicatasClienteOpco($codigoClienteOpcoConciliador, $arrCodigosDuplicatas)
    {

        try {

            $this->addBehavior('Loggable');

            $this->find()
                ->where(['codigo_cliente_opco IN' => $arrCodigosDuplicatas])
                ->update()
                ->set([
                    'codigo_cliente_opco' => $codigoClienteOpcoConciliador
                ])
                ->execute();
        } catch (\Exception $e) {

            throw $e;
        } finally {

            $this->behaviors()->unload('Loggable');
        }
    }
}
