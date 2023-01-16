<?php
namespace App\Model\Table;

use App\Model\Entity\Auth;
use Cake\ORM\Query;
use Cake\ORM\RulesChecker;
use App\Model\Table\AppTable;
use Cake\Validation\Validator;
use Cake\I18n\Time;

use Cake\ORM\TableRegistry;
/**
 * UsuariosMedicamentos Model
 *
 * @method \App\Model\Entity\UsuariosMedicamento get($primaryKey, $options = [])
 * @method \App\Model\Entity\UsuariosMedicamento newEntity($data = null, array $options = [])
 * @method \App\Model\Entity\UsuariosMedicamento[] newEntities(array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosMedicamento|false save(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosMedicamento saveOrFail(\Cake\Datasource\EntityInterface $entity, $options = [])
 * @method \App\Model\Entity\UsuariosMedicamento patchEntity(\Cake\Datasource\EntityInterface $entity, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosMedicamento[] patchEntities($entities, array $data, array $options = [])
 * @method \App\Model\Entity\UsuariosMedicamento findOrCreate($search, callable $callback = null, $options = [])
 */
class UsuariosMedicamentosTable extends AppTable
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

        $this->setTable('usuarios_medicamentos');
        $this->setDisplayField('codigo');
        $this->setPrimaryKey('codigo');

        $this->addBehavior('Loggable');
        $this->foreign_key('codigo_usuarios_medicamentos');

        $this->addBehavior('Timestamp', [
            'events' => [
                'Model.beforeSave' => [
                    'data_inclusao' => 'new',
                    'data_alteracao' => 'always',
                ]
            ]
        ]);
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
            ->integer('codigo_medicamentos')
            ->allowEmptyString('codigo_medicamentos');

        $validator
            ->integer('codigo_usuario')
            ->allowEmptyString('codigo_usuario');

        $validator
            ->allowEmptyString('frequencia_dias');

        $validator
            ->allowEmptyString('frequencia_horarios');

        $validator
            ->allowEmptyString('uso_continuo');

        $validator
            ->scalar('dias_da_semana')
            ->maxLength('dias_da_semana', 50)
            ->allowEmptyString('dias_da_semana');

        $validator
            ->allowEmptyString('frequencia_uso');

        $validator
            ->scalar('horario_inicio_uso')
            ->maxLength('horario_inicio_uso', 5)
            ->allowEmptyString('horario_inicio_uso');

        $validator
            ->integer('quantidade')
            ->allowEmptyString('quantidade');

        $validator
            ->scalar('recomendacao_medica')
            ->allowEmptyString('recomendacao_medica');

        $validator
            ->scalar('foto_receita')
            ->maxLength('foto_receita', 255)
            ->allowEmptyString('foto_receita');

        $validator
            ->allowEmptyString('frequencia_dias_intercalados');

        $validator
            ->date('periodo_tratamento_inicio')
            ->allowEmptyDate('periodo_tratamento_inicio');

        $validator
            ->date('periodo_tratamento_termino')
            ->allowEmptyDate('periodo_tratamento_termino');

        $validator
            ->integer('codigo_usuario_inclusao')
            ->allowEmptyString('codigo_usuario_inclusao');

        $validator
            ->integer('codigo_usuario_alteracao')
            ->allowEmptyString('codigo_usuario_alteracao');

        $validator
            ->dateTime('data_alteracao')
            ->allowEmptyDateTime('data_alteracao');

        $validator
            ->dateTime('data_inclusao')
            ->allowEmptyDateTime('data_inclusao');

        return $validator;
    }

    public function getListaMedicamentos($usuario){

        $usuariosDados = TableRegistry::getTableLocator()->get('UsuariosDados');
        $usuariosMedicamentosStatus  = TableRegistry::getTableLocator()->get('UsuariosMedicamentosStatus');
        // Define as datas atuais
        $data_atual = date('Y-m-d');
        $dias_semana = ['dom', 'seg', 'ter', 'qua', 'qui', 'sex', 'sab', 'dom'];
        $dia_semana_atual = date('w', time());

        // Query que recupera os medicamentos dos usuarios
        $prog_medicamentos = $usuariosDados->getUsuariosMedicamento($usuario);


        // Valida se algum medicamento foi encontrao
        if (empty($prog_medicamentos)) {
            //$error[] = "Nenhuma programação de medicamento foi encontrada";

            return [];
        }

        $data_list = array();
        // Iteração sobre os medicamentos
        foreach ($prog_medicamentos as $prog_medicamento) {
            $count = 0;
            $medicamento_status = null;
            $quantidade_de_dose = null;
            $quantidade_de_doses_tomadas = null;
            $dias_semana_usuario = explode(',', $prog_medicamento['dias_da_semana']);
            

            $gera_lista_medicamento = true;
            // USO CONTINUO = SIM E DIA ATUAL BATE COM O DIA DA SEMANA DO MEDICAMENTO OU
            // USO CONTINUO = NAO E A DATA FINAL SEJA MAIOR QUE A ATUAL E A DE INICIO SEJA MENOR QUE A DATA ATUAL
            // E BATE COM O DIA DA SEMANA
            // if( ($prog_medicamento['uso_continuo'] == 1 && in_array($dias_semana[$dia_semana_atual], $dias_semana_usuario)) ) {
            //     $gera_lista_medicamento = true;
            // } 
            
            // if(($prog_medicamento['uso_continuo'] == 2 && $data_atual <= $prog_medicamento['periodo_tratamento_termino']
            //         && $data_atual >= $prog_medicamento['periodo_tratamento_inicio'] && in_array($dias_semana[$dia_semana_atual], $dias_semana_usuario))){
            //     $gera_lista_medicamento = true;
            // }

            if($gera_lista_medicamento) {

                if ($prog_medicamento['data_hora_uso'] != null) { // MEDICAMENTO UTILIZADO
                    $data_uso = explode(' ', $prog_medicamento['data_hora_uso']);
                    if ($data_uso[0] == $data_atual) {
                        $medicamento_status = true;
                    }
                } // FIM MEDICAMENTO UTILIZADO

                if ($prog_medicamento['frequencia_uso'] != null) { // Se tiver frequencia de uso

                    $uso = $prog_medicamento['quantidade'] > 1 ? $prog_medicamento['quantidade'] .  ' ' . $prog_medicamento['apresentacao']
                        . ' de ' . $prog_medicamento['frequencia_uso'] . ' em '
                        . $prog_medicamento['frequencia_uso'] : $prog_medicamento['quantidade'] .  ' ' . $prog_medicamento['apresentacao']
                        . ' de ' . $prog_medicamento['frequencia_uso'] . ' em '
                        . $prog_medicamento['frequencia_uso'];
                    $quantidade_de_dose = 24 / $prog_medicamento['frequencia_uso'];

                    global $count;
                    $count = 0;
                    if ($prog_medicamento['data_hora_uso'] != null) {
                        $medicamento_status = array_map(function ($dosagem) use ($prog_medicamento, $uso, $count) {

                            global $count;
                            $count++;
                            return [
                                "codigo_usuario_medicamento" => $prog_medicamento['codigo_usuario_medicamento'],
                                "medicamento" => $prog_medicamento['medicamento'] ,
                                "uso" => $uso,
                                "dosagem" => 'Dose ' . $count . ' às '  . date('H:i', strtotime($dosagem['data_hora_uso'])) ,
                            ];
                        }, $usuariosMedicamentosStatus->getQuantidadeDeDoses($prog_medicamento['codigo_usuario_medicamento']));

                        $quantidade_de_doses_tomadas = count($medicamento_status);
                    }

                    if ($quantidade_de_dose <= $quantidade_de_doses_tomadas ){
                        $dosagem = 'Você já tomou todas as doses.' ;
                        $finalizado = true;
                    } else {
                        $calculo_horario = ($prog_medicamento['frequencia_uso'] * $quantidade_de_doses_tomadas) ;
                        $horario = new \DateTime($prog_medicamento['horario_inicio_uso']);
                        $horario = $horario->add(new \DateInterval('PT' . $calculo_horario . 'H'))->format('H:i');
                        $dosagem = 'Dose ' . (1 + $quantidade_de_doses_tomadas) . ' às '  . $horario ;
                        $finalizado = false;
                    }


                } else { // se nao tiver frequencia de uso ou seja, qualquer horario do dia
                    $uso = $prog_medicamento['quantidade'] > 1 ? $prog_medicamento['quantidade'] . ' ' . $prog_medicamento['apresentacao']
                        . ' diário' : $prog_medicamento['quantidade'] . ' ' . $prog_medicamento['apresentacao']
                        . ' diário' ;

                    $dosagem = '';
                }// Fim se tiver frequencia de uso

                $data = array(
                    'codigo_usuario_medicamento' => $prog_medicamento['codigo_usuario_medicamento'],
                    'medicamento' => $prog_medicamento['medicamento'],
                    'uso' => $uso,
                    'dosagem' => $dosagem,
                    'finalizado' => $finalizado,
                    'medicamento_status' => $medicamento_status,
                );

                array_push($data_list, $data);

            } // FIM USO CONTINUO = SIM E DIA ATUAL BATE COM O DIA DA SEMANA DO MEDICAMENTO
            else {
                $error = 'Não existe medicamentos cadastrados para hoje.';
            }

        }

        // debug($data_list);
        // exit;

        return $data_list;
    }
}
