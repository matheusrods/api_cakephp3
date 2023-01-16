<?php
namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * FornecedoresHistorico Controller
 *
 *
 * @method \App\Model\Entity\FornecedoresHistorico[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FornecedoresHistoricoController extends AppController
{
    function listaGet(int $codigo_fornecedor) {

        $this->request->allowMethod(['get']);
        $this->loadModel("FornecedoresHistorico");

        $data = $this->FornecedoresHistorico->listar($codigo_fornecedor);
        $this->
        set(compact('data'));
    }

    public function uploadPost()
    {
        $this->request->allowMethod(['post']);
        $this->loadModel('FornecedoresDocumento');
        $dados = [
            'codigo_fornecedor'     => $this->request->getData('codigo_fornecedor'),
            'usuario'               => $this->request->getData('usuario'),
            'data'                  => $this->request->getData('data'),
            'hora'                  => $this->request->getData('hora'),
            'caminho_arquivo'       => $this->request->getData('caminho_arquivo'),
        ];
        $data_inclusao = "{$dados['data']} {$dados['hora']}";
        $dados = [
            'codigo_fornecedor'     => $this->request->getData('codigo_fornecedor'),
            'usuario'               => $this->request->getData('usuario'),
            'data_inclusao'         => $data_inclusao,
            'caminho_arquivo'       => $this->request->getData('caminho_arquivo'),
            'ativo'                 => true
        ];




        $filename = $dados['descricao'];
        $replaces = [' ', '_', '*'];
        foreach ($replaces as $item) {
            $filename = str_ireplace($item, '', $filename);
        }
        $retorno = $this->_upload($dados['caminho_arquivo'], $dados['codigo_fornecedor'], $filename);
        if ($retorno['upload']) {
            $dados['caminho_arquivo'] = $retorno['nome'];

            if ( $this->FornecedoresHistorico->save($dados) ) {

                $data = array(
                    "success" => true,
                    "message" => "Dados da empresa salvos!"
                );

            } else {

                $data = array(
                    "success" => false,
                    "message" => "Não foi possível salvar os dados da empresa!"
                );

            }
            $this->set(compact('data'));
        }

    }

    private function _upload($file, $codigo_fornecedor, $novo_nome) {

        // destino do arquivo no servidor
        $destino = '.'. DS . 'files' . DS . 'documentacao' . DS . $codigo_fornecedor;
        // extensoes permitidas
        if( preg_match('@\.(jpg|png|gif|jpeg|bmp|pdf|doc|docx|pdf)$@i', $file['name']) ) {
            !file_exists($destino) ? mkdir($destino, 0775, true) : null;
            // upload
            $ext = explode('.', $file['name']);
            $ext = end($ext);
            $newName = "fornecedor_" . $codigo_fornecedor . "_" . $novo_nome . "." . $ext;
            $destino .= DS . $newName;

            if(move_uploaded_file($file['tmp_name'], $destino)) {
                return array('upload' => true, 'msg' => 'Arquivo enviado com sucesso!', 'nome' => $newName);
            } else {
                return array('upload' => false, 'msg' => 'Arquivo não Enviado, enviar arquivo com tamanho máximo de 10Mb!');
            }
        } else {
            return array('upload' => false, 'msg' => 'extensão não permitida, envie jpg, png, gif, jpeg, bmp, pdf, doc, docx ou pdf!');
        }
    }
}
