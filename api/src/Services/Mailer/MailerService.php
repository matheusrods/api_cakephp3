<?php
namespace App\Services\Mailer;

use Cake\Mailer\Email;
use Cake\Mailer\TransportFactory;

use App\Services\AbstractService;
use Cake\Log\LogTrait;
use App\Utils\Encriptacao;
use Cake\ORM\TableRegistry;

class MailerService extends AbstractService {
    
    use LogTrait;
    
    protected $_options = [];

    public function __construct(array $opcoes = null)
    {
        
    }

    public function avaliarOpcoes(array $opcoes = null)
    {
        if (empty($opcoes['to'])) {
            return ['error'=>'Destinatário requerido'];
        }
                    
        if (isset($opcoes['cc'])){
            if (is_array($opcoes['cc'])) {
                $opcoes['cc'] = implode(';', $opcoes['cc']);
            }
            if (is_array($opcoes['to'])) {
                $opcoes['cc'] = implode(';', $opcoes['cc']);
            }
        }

        return $opcoes;        
    }

    public function enviar( array $opcoes = [] ){

        $opcoes = $this->avaliarOpcoes($opcoes);
        dd($opcoes);
        //$senha_desencriptada = (new Encriptacao())->desencriptar($senha);
        // $smtpServerOptions = array(
        //     'port'=>'25',
        //     'timeout'=>'30',
        //     'host' => 'webmail.buonny.com.br',
        // );
                
        // $defaultOptions = array(
        //     'from' => 'Buonny <retorno.perfil@buonny.com.br>',
        //     'to' => 'Desenvolvimento <grupotid@buonny.com.br>',
        //     'subject_prefix' => '[RHHealth] ',
        //     'subject' => '',
        //     'template' => null,
        //     'layout' => null,
        // );


        // $email = new Email('default');
        // $email->from(['retorno.perfil@buonny.com.br' => 'Buonny'])
        // ->to(['ri.martins@reply.com' => 'Desenvolvimento'])
        // ->subject('About')
        // ->send('My message');

        // return $email;

    }

    
    public function registrarEnvio( int $codigo ){

    }

    public function cancelarEnvio( int $codigo ){

    } 
    
	public function schedule($content, $options, $model = null, $foreign_key = null){

		if (!empty($options['to'])) {
		    if (isset($options['cc'])){
		    	if (is_array($options['cc'])) {
					$options['cc'] = implode(';', $options['cc']);
				}
				if (is_array($options['to'])) {
					$options['cc'] = implode(';', $options['cc']);
				}
		    }
			$new_mail = $options;
			$new_mail['content'] = $content;
	        $new_mail['model'] = $model;
	        $new_mail['foreign_key'] = $foreign_key;
			$this->Outbox->create();
			return $this->Outbox->save($new_mail);
		}

}}