<?php
namespace App\Controller\Api;

use App\Controller\Api\ApiController;
use Cake\Datasource\ConnectionManager;

/**
 * POSController Controller
 *
 *
 * @method \App\Model\Entity\POSController[]|\Cake\Datasource\ResultSetInterface paginate($object = null, array $settings = [])
 */
class POSControllerController extends ApiController
{
    private $connect;

    public function initialize()
    {
        parent::initialize();

        $this->connect = ConnectionManager::get('default');

        $this->loadModel('AcoesMelhorias');
        $this->loadModel('AcoesMelhoriasSolicitacoes');
        $this->loadModel('PosSwtFormRespondido');
        $this->loadModel('Subperfil');
    }

    private function getAuthenticatedUser()
    {
        $authenticatedUser = $this->getDadosToken();

        if (empty($authenticatedUser)) {
            $error = 'Não foi possível encontrar os dados no Token!';

            $this->set(compact('error'));

            return;
        } else {
            $userId = isset($authenticatedUser->codigo_usuario) ? $authenticatedUser->codigo_usuario : '';

            if (empty($userId)) {
                $error = 'Logar novamente o usuário';

                $this->set(compact('error'));

                return;
            } else {
                return $userId;
            }
        }
    }

    private function getPermissions(int $userId)
    {
        $permissionsData = $this->Subperfil->getPermissoesUsuario($userId);

        $permissions = [];

        foreach ($permissionsData as $permission) {
            array_push($permissions, (int) $permission['codigo_acao']);
        }

        return $permissions;
    }

    public function getRegisters()
    {
        $this->request->allowMethod(['GET']);

        $userId = $this->getAuthenticatedUser();

        $permissions = $this->getPermissions($userId);

        //conta quantos walk_talk tem o usuario logado
        $walk_talk = $this->PosSwtFormRespondido->find()
            ->where([
                "(PosSwtFormRespondido.codigo_usuario_observador = $userId OR PosSwtFormRespondido.codigo_usuario_inclusao = $userId)",
                'PosSwtFormRespondido.ativo = 1'
            ])
            ->all()
            ->count();

        $data = [
            'observador' => 0,
            'safety_walk_talk' => $walk_talk,
            'plano_acao' => $this->countPendenciesActions($userId, $permissions)
        ];

        $this->set(compact('data'));
    }

    private function countPendenciesActions(int $userId, array $permissions = [])
    {
        $solicitationsValue = 0;
        $solicitations = $this->AcoesMelhoriasSolicitacoes->pendencies($userId);

        foreach ($solicitations as $solicitation) {
            switch ((int) $solicitation->codigo_acao_melhoria_solicitacao_tipo) {
                case 1:
                    if (
                        !in_array(2, $permissions)
                        && !in_array(3, $permissions)
                        && !in_array(4, $permissions)
                    ) {
                        continue 2;
                    }

                    $solicitationsValue += (int) $solicitation->quantidade;
                    break;
                case 2:
                    if (!in_array(11, $permissions)) {
                        continue 2;
                    }

                    $solicitationsValue += (int) $solicitation->quantidade;
                    break;
                case 3:
                    if (!in_array(12, $permissions)) {
                        continue 2;
                    }

                    $solicitationsValue += (int) $solicitation->quantidade;
                    break;
                default:
                    break;
            }
        }

        $comprehensiveActions = (int) $this->AcoesMelhorias->countPendencies($userId, 4, false, $permissions);
        $othersActions = (int) $this->AcoesMelhorias->countPendencies($userId, 5, false, $permissions);

        return ($solicitationsValue + $comprehensiveActions + $othersActions);
    }
}
