<?php
namespace App\Controller\Api;

use App\Controller\AppController;

/**
 * FornecedoresDocumentos Controller
 *
 * @property \App\Model\Table\FornecedoresDocumentosTable $FornecedoresDocumentos
 *
 * @method \App\Model\Entity\FornecedoresDocumento[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class FornecedoresDocumentosController extends AppController
{


    /**
     * @param int $codigo_fornecedor
     */
    public function listaGet(int $codigo_fornecedor)
    {
        $this->request->allowMethod(['get']);
        $this->loadModel("FornecedoresDocumentos");

        $data = $this->FornecedoresDocumentos->listaDocumentos($codigo_fornecedor);
        $this->set(compact('data'));
    }

    public function uploadPost()
    {
        $this->request->allowMethod(['post']);
        $this->loadModel('FornecedoresDocumento');
        $dados = [
            'codigo_fornecedor'     => $this->request->getData('codigo_fornecedor'),
            'codigo_tipo_documento' => $this->request->getData('codigo_tipo_documento'),
            'data_validade'         => $this->request->getData('data_validade'),
            'caminho_arquivo'       => $this->request->getData('caminho_arquivo'),
            'descricao'       => $this->request->getData('descricao'),
        ];
        $filename = $dados['descricao'];
        $replaces = [' ', '_', '*'];
        foreach ($replaces as $item) {
            $filename = str_ireplace($item, '', $filename);
        }
        $retorno = $this->_upload($dados['caminho_arquivo'], $dados['codigo_fornecedor'], $filename);
        if ($retorno['upload']) {
            $dados['caminho_arquivo'] = $retorno['nome'];

            if ( $this->FornecedoresDocumento->save($dados) ) {

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

    public function excluirDelete()
    {
        //todo validar regra de deleção
        $this->request->allowMethod(['delete']);
        $this->loadModel("FornecedoresDocumentos");
        $data = [
            'codigo'            => $this->request->getData('codigo_documento'),
            'codigo_fornecedor' => $this->request->getData('codigo_fornecedor'),
        ];
//        $getDocumento = $this->FornecedoresDocumentos
//                        ->find()
//                        ->where(['codigo' => $dados['codigo']])
//                        #->where(['codigo_fornecedor' => $dados['codigo_fornecedor']])
//                        ->first()
//                        ->toArray();
        $this->set(compact('data'));
    }

}
