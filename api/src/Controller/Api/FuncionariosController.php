<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Core\Exception\Exception;

/**
 * Funcionarios Controller
 *
 * @property \App\Model\Table\FuncionariosTable $Funcionarios
 *
 * @method \App\Model\Entity\Funcionario[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FuncionariosController extends ApiController
{

    public function initialize(){
        parent::initialize();
        $this->Auth->allow(['view']);
        $this->loadModel('Fornecedores');
    }

    /**
     * Index method
     *
     * @return \Cake\Http\Response|null
     */
    public function index()
    {
        $funcionarios = $this->paginate($this->Funcionarios);

        $this->set(compact('funcionarios'));
    }

    /**
     * View method
     *
     * @param string|null $id Funcionario id.
     * @return \Cake\Http\Response|null
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function view($codigo = null)
    {
        try {

            $query = $this->Funcionarios->getPacienteDetalhe($codigo);
            //die($query);
            if (count($query->all()) > 0) {

                foreach ($query as $v){
                    if(empty($v['foto'])){
                        $v['foto'] = "https://api.rhhealth.com.br/ithealth/2020/05/21/9CDD7B5D-588C-1E2E-FD49-403D2B1DABBC.png";
                    }
                }
                $this->set('data', $query);

            } else {
                $error = 'Paciente não encontrado.';
                $this->set(compact('error'));
                return;
            }

        } catch (Exception $e){
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }

    /**
     * Add method
     *
     * @return \Cake\Http\Response|null Redirects on successful add, renders view otherwise.
     */
    public function add()
    {
        $funcionario = $this->Funcionarios->newEntity();
        if ($this->request->is('post')) {
            $funcionario = $this->Funcionarios->patchEntity($funcionario, $this->request->getData());
            if ($this->Funcionarios->save($funcionario)) {
                $this->Flash->success(__('The funcionario has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The funcionario could not be saved. Please, try again.'));
        }
        $medicamentos = $this->Funcionarios->Medicamentos->find('list', ['limit' => 200]);
        $medicos = $this->Funcionarios->Medicos->find('list', ['limit' => 200]);
        $this->set(compact('funcionario', 'medicamentos', 'medicos'));
    }

    /**
     * Edit method
     *
     * @param string|null $id Funcionario id.
     * @return \Cake\Http\Response|null Redirects on successful edit, renders view otherwise.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function edit($id = null)
    {
        $funcionario = $this->Funcionarios->get($id, [
            'contain' => ['Medicamentos', 'Medicos']
        ]);
        if ($this->request->is(['patch', 'post', 'put'])) {
            $funcionario = $this->Funcionarios->patchEntity($funcionario, $this->request->getData());
            if ($this->Funcionarios->save($funcionario)) {
                $this->Flash->success(__('The funcionario has been saved.'));

                return $this->redirect(['action' => 'index']);
            }
            $this->Flash->error(__('The funcionario could not be saved. Please, try again.'));
        }
        $medicamentos = $this->Funcionarios->Medicamentos->find('list', ['limit' => 200]);
        $medicos = $this->Funcionarios->Medicos->find('list', ['limit' => 200]);
        $this->set(compact('funcionario', 'medicamentos', 'medicos'));
    }

    /**
     * Delete method
     *
     * @param string|null $id Funcionario id.
     * @return \Cake\Http\Response|null Redirects to index.
     * @throws \Cake\Datasource\Exception\RecordNotFoundException When record not found.
     */
    public function delete($id = null)
    {
        $this->request->allowMethod(['post', 'delete']);
        $funcionario = $this->Funcionarios->get($id);
        if ($this->Funcionarios->delete($funcionario)) {
            $this->Flash->success(__('The funcionario has been deleted.'));
        } else {
            $this->Flash->error(__('The funcionario could not be deleted. Please, try again.'));
        }

        return $this->redirect(['action' => 'index']);
    }

    /**
     * Busca os funcionarios por cpf, nome ou numero do pedido
     */
    public function getFuncionariosPorFornecedor( $codigo_fornecedor, $busca = null, int $tipo = 1)
    {
        $data = array();

        if (isset($codigo_fornecedor)) {

            $codigo_fornecedor = (int) $codigo_fornecedor;

            $fornecedores = $this->Fornecedores->find()->select(['codigo'])->where(['codigo' => $codigo_fornecedor])->first();

            if (empty($fornecedores)) {
                $error = 'Fornecedor não encontrado';
                $this->set(compact('error'));
                return;
            }
        } else {
            $error = 'Código Fornecedor inválido';
            $this->set(compact('error'));
            return;
        }

        //verifica se tem mais de 3 caracteres o campo para permitir a busca
        if(strlen($busca) > 0) {

            //pega os dados do token
            $dados_token = $this->getDadosToken();
            $codigo_usuario = $dados_token->codigo_usuario;

            $this->loadModel('PedidosExames');

            if($tipo == 1) {
                $dados = $this->PedidosExames->getDadosFuncionarios($codigo_fornecedor, $codigo_usuario, $this->replaceSpecialChar($busca));
            }
            else if($tipo == 2) {
                $dados = $this->PedidosExames->getDadosFuncionariosSimples($codigo_fornecedor, $codigo_usuario, $this->replaceSpecialChar($busca));
            }

            if(empty($dados)){
                $error = 'Não encontrado.';
                $this->set(compact('error'));
                return;
            }

            $data = $dados->hydrate(false)->all()->toArray();

            // debug($data);exit;

            foreach($data as $keyData => $v) {
                if(!empty($v['matricula'])) {
                    $data[$keyData]['vinculo'] = 1; //tipo do vinculo do paciente é funcionario
                    $data[$keyData]['vinculo_descricao'] = "Colaborador";
                    $data[$keyData]['codigo_cor'] =1;
                    $data[$keyData]['idade'] = $this->descobrirIdade($v['data_nascimento']);
                    $empresas = $this->Funcionarios->getFuncionariosEmpresasDetalhe($v['codigo_cliente_funcionario']);

                    // debug($empresas);exit;

                    foreach ($empresas as $key => $empresa) {

                        //Remove tags e remove keys vazias
                        $riscos = array_filter(preg_split('/<.+?>/', $empresa['riscos']));
                        //Reordena array e remove keys duplicadas
                        $empresas[$key]['riscos'] = array_values(array_unique($riscos));
                        $empresas[$key]['postos_trabalho'] = $this->Funcionarios->getPostosAtivos($v['codigo']);
                    }
                    $data[$keyData]['dados_empresa'] = $empresas;
                } else {
                    $data[$keyData]['vinculo'] = 2; //tipo do vinculo do paciente é terceirizado
                    $data[$keyData]['vinculo_descricao'] = "Terceirizado";
                    $data[$keyData]['codigo_cor'] =2;
                }
            }
        }

        // debug($data);exit;

        $this->set(compact('data'));
        return;
    }

    /**
     * Helper para trocar caracteres especiais de uma string
     */
    public function replaceSpecialChar($busca)
    {
        $busca = urldecode($busca);
        //Inserir letras e caracteres especiais que precisam ser trocados
        $arrChars = array(
            "Ã"  => "Ãƒ",
            "Ç"  => "Ã‡",
            "Ã" => "Á",
            "Ã‰" => "É",
            "Ã©" => "é"
        );

        foreach ($arrChars as $index => $char) {

            if (strpos($busca, $index) !== false) {
                $busca = str_replace($index, $char, $busca);
            }
        }

        //Remover '.' e '-' para caso da busca for por cpf
        $busca = str_replace(".", "", $busca);
        $busca = str_replace("-", "", $busca);

        return $busca;
    }

    /**
     *Detalhes de um colaborador/paciente
     **/
    public function getColaboradorDetalhe(int $codigo = null){

        try {

            $result = $this->Funcionarios->getColaboradorDetalhe($codigo);


            if(empty($result)){
                $error = 'Colaborador não encontrado.';
                $this->set(compact('error'));
                return;
            }

            // debug($result);exit;

            foreach($result as $v){

                if(empty($v['foto'])){
                    $v['foto'] = "https://api.rhhealth.com.br/ithealth/2020/05/21/9CDD7B5D-588C-1E2E-FD49-403D2B1DABBC.png";
                }

                $dados_pessoais = array(
                    "foto"      => $v['foto'],
                    "nome"      => $v['nome'],
                    "cpf"       => $v['cpf'],
                    "matricula" => $v['matricula'],
                    "sexo"      => $v['sexo'],
                    "data_nascimento"=> $v['data_nascimento']
                );

                $dados_profissionais = array(
                    "empresa"      => $v['empresa'],
                    "setor"      => $v['setor'],
                    "cargo"       => $v['cargo'],
                    "data_inicio" => $v['data_inicio'],
                    "data_fim" => $v['data_fim'],
                    "postos_trabalho" => $this->Funcionarios->getPostosAtivos($codigo)
                );

                $riscos = preg_split('/<.+?>/', $v['riscos']);
                $dados_medicos = preg_split('/<.+?>/', $v['dados_medicos']);
                $dados_medicacoes = preg_split('/<.+?>/', $v['dados_medicacoes']);
            }

            $riscos = array_filter($riscos);//remove keys vazias
            $riscos = array_values($riscos);//reordena
            $dados_medicos = array_filter($dados_medicos);//remove keys vazias
            $dados_medicos = array_values($dados_medicos);//reordena
            $dados_medicacoes = array_filter($dados_medicacoes);//remove keys vazias
            $dados_medicacoes = array_values($dados_medicacoes);//reordena
            //filtra somente os que tem farmaco
            $medicacoes = array();
            foreach($dados_medicacoes as $v){
                $someArray = json_decode($v, true);
                if( isset($someArray[0]) && !empty($someArray[0]["farmaco"]) ){
                    $medicacoes[] = $someArray[0];
                }elseif( isset($someArray) && !empty($someArray["farmaco"]) ){
                    $medicacoes[] = $someArray;
                }
            }

            $dados_profissionais['riscos'] = $riscos;

            $data = array_merge(
                array("dados_pessoais" => $dados_pessoais),
                array("dados_profissionais" => $dados_profissionais),
                array("dados_medicos" => $dados_medicos),
                array("dados_medicacoes" => $medicacoes)
            );
            // debug($data);exit;

            $this->set('data', $data);

        } catch (Exception $e){
            $error[] = $e->getMessage();
            $this->set(compact('error'));
        }
    }


    function descobrirIdade($date)
    {
        $time = strtotime($date);
        if($time === false){
            return '';
        }

        $year_diff = '';
        $date = date('Y-m-d', $time);
        list($year,$month,$day) = explode('-',$date);
        $year_diff = date('Y') - $year;
        $month_diff = date('m') - $month;
        $day_diff = date('d') - $day;
        if ($day_diff < 0 || $month_diff < 0) $year_diff;
        return $year_diff;
    }

}
