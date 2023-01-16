<?php
namespace App\Model\Table;

use App\Model\Table\AppTable;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use Cake\ORM\Table;
use Cake\Validation\Validator;

use App\Utils\EncodingUtil;
use Cake\ORM\TableRegistry;
use Cake\Core\Exception\Exception;

/**
 * PosSwtForm Model
 *
 * @method \App\Model\Entity\PosSwtForm get($primaryKey, $options = [])
 * @method \App\Model\Entity\PosSwtForm newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\PosSwtForm[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtForm|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosSwtForm saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\PosSwtForm patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtForm[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\PosSwtForm findOrCreate($search, callable $callback = null, $options = [])
 */
class PosSwtFormTable extends AppTable
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

        $this->setTable('pos_swt_form');
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
            ->integer('codigo_cliente')
            ->allowEmptyString('codigo_cliente');

        $validator
            ->scalar('descricao')
            ->maxLength('descricao', 255)
            ->allowEmptyString('descricao');

        $validator
            ->allowEmptyString('form_tipo');

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

        return $validator;
    }

    /**
     * [getQuestoesForm metodo para realizar a busca no banco com os dados de parametros passados e retornar a estrutura do formulario para o front-end]
     * @param  [type] $codigo_unidade [codigo da unidade do funcionario]
     * @param  [type] $form_tipo      [tipo do fomrulario, hoje recebemos os valores 1-> walk talk e 2 -> Qualidade]
     * @return [type]                 [description]
     */
    public function getQuestoesForm(int $codigo_unidade, int $form_tipo)
    {
        $dados = array();

        try {
            //verifica se tem a configuracao do formulário
            $this->GruposEconomicos = TableRegistry::get('GruposEconomicos');
            $codigo_cliente_matriz = $this->GruposEconomicos->getCampoPorClienteRqe("codigo_cliente", $codigo_unidade);

            //verifica se o codigo do cliente matriz e o tipo tem cadastro
            $form = $this->find()->where(['codigo_cliente' => $codigo_cliente_matriz,'form_tipo' => $form_tipo])->first();
            //verifica se tem codigo de formulário com o form_tipo cadastrado
            if(empty($form)) {
                throw new Exception("Não existe formulário cadastrado no backoofice da aplicação, favor entrar em contato com o Administrador!");
            }

            //seta a variavel o codigo do formulario
            $codigo_form = $form->codigo;

            //pega os valores de criticidade do SWT
            $this->PosCriticidade = TableRegistry::get('PosCriticidade');
            $criticidade = $this->PosCriticidade->find()->select(['codigo','label'=>'RHHealth.dbo.ufn_decode_utf8_string(descricao)','cor','valor_inicio','valor_fim'])->where(['codigo_cliente' => $codigo_cliente_matriz,'codigo_pos_ferramenta' => 2])->hydrate(false)->toArray();

            //monta a query para pegar as perguntas e respostas
            $this->PosSwtFormTitulo = TableRegistry::get('PosSwtFormTitulo');

            $fields = array(
                'PosSwtFormTitulo.codigo',
                'titulo'=>'RHHealth.dbo.ufn_decode_utf8_string(PosSwtFormTitulo.titulo)',
                'PosSwtFormTitulo.ordem',
                'PosSwtFormTitulo.ativo',
                'PosSwtFormQuestao.codigo',
                'questao'=>'RHHealth.dbo.ufn_decode_utf8_string(PosSwtFormQuestao.questao)',
                'saiba_mais'=>'RHHealth.dbo.ufn_decode_utf8_string(PosSwtFormQuestao.saiba_mais)',
            );

            $joins = array(
                array(
                    'table' => 'pos_swt_form_questao',
                    'alias' => 'PosSwtFormQuestao',
                    'type' => 'INNER',
                    'conditions' => array('PosSwtFormQuestao.codigo_form_titulo = PosSwtFormTitulo.codigo')
                ),
            );

            $conditions['PosSwtFormQuestao.codigo_form'] = $codigo_form;
            $conditions['PosSwtFormQuestao.ativo'] = 1;
            $conditions['PosSwtFormTitulo.ativo'] = 1;

            $order = array('PosSwtFormTitulo.ordem ASC','PosSwtFormQuestao.ordem ASC');

            $questoes = $this->PosSwtFormTitulo->find()
                ->select($fields)
                ->join($joins)
                ->where($conditions)
                ->order($order)
                // ->sql();
                ->hydrate(false)
                ->toArray();

            if(empty($questoes)) {
                throw new Exception("Não existe questões para o formulário, favor entrar em contato com o Administrador!");
            }

            //para retornar os dados
            $dados['codigo_form'] = $codigo_form;
            $dados['codigo_origem_ferramenta'] = null;

            //se o tipo for 1 pega pelo produto a origem da ferramenta
            if($form_tipo == 1) { //safety walk talk
                $this->Configuracao = TableRegistry::get('Configuracao');
                $codigo_produto = $this->Configuracao->getChave('SAFETY_WALK_TALK');

                //pega o codigo da origem ferramenta
                $this->OrigemFerramenta = TableRegistry::get('OrigemFerramentas');
                $of = $this->OrigemFerramenta->find()->select(['codigo'])->where(['codigo_cliente' => $codigo_cliente_matriz,'codigo_produto' => $codigo_produto,'ativo' => 1])->first();
                $dados['codigo_origem_ferramenta'] = (!empty($of) ? $of->codigo : null);
            }

            //varre os dados do formulario para deixar ele no formato correto
            $dados_aux = array();

            foreach($questoes as $key => $quest) {
                $codigo_titulo = intval($quest['codigo']);

                $dados_aux[$codigo_titulo]['codigo'] = $codigo_titulo;
                $dados_aux[$codigo_titulo]['ordem'] = $quest['ordem'];

                $dados_aux[$codigo_titulo]['titulo'] = $quest['titulo'];

                //acrescenta a criticidade
                $quest['PosSwtFormQuestao']['questao'] = $quest['questao'];
                $quest['PosSwtFormQuestao']['saiba_mais'] = $quest['saiba_mais'];
                $quest['PosSwtFormQuestao']['criticidade'] = $criticidade;

                //questoes
                $dados_aux[$codigo_titulo]['questoes'][] = $quest['PosSwtFormQuestao'];
            }//fim foreach

            //reorganiza o array, formatando a saida corretamente para o front
            $data = array();

            foreach ($dados_aux as $value) {
                array_push($data, $value);
            }

            foreach ($data as $key => $value) {
                $data[$key]['passos'] = ($key + 1) . " de " . count($data);

                $dados['quest'][] = $data[$key];
            }
        } catch(Exception $e) {
            $dados['error'] = $e->getMessage();
        }

        return (array) $dados;

    }//fim getQuestoesForm($codigo_unidade,$form_tipo)
}
