<?php


namespace DGAbgSistemaBundle\Service;

/**
 * Description of EmailResService
 *
 * @author digitalitygarage
 */
class EmailResService {
      protected $info; 
    protected $mail;
    protected $templating;
    protected $view;
    protected $subject;
    protected $from;
    protected $reply;
    protected $to;
    protected $body;
    
    public function __construct($mail, $templating ) {
	$this->templating = $templating;
      
        $this->mail   = $mail;
        $this->subject = 'Abogados.com.sv';
        $this->from   = 'info@abogados.com.sv'; 
        $this->info = 'Abogados de El Salvador';
    }  
    
    public function setEmail($to,$bcc=null){
        
        $this->view   = 'DGAbgSistemaBundle:Emails:test.html.twig';
        $this->to     = $to;
        $contenido    = 'Este correo es enviado desde el sistema de Abogados.com.sv';
        $this->body = $this->templating->render($this->view, array('body'=>$contenido));
        $this->sendEmail($this->to,null,$bcc,null,$this->body);
        
    }
    
    public function sendEmail($to, $cc, $bcc,$replay, $body,$subject){
        $email = \Swift_Message::newInstance();
        $email->setContentType('text/html');                    
        //$email->setFrom(array($this->from=> 'Abogados de El Salvador'));
        $email->setFrom($this->from,'Abogados de El Salvador');
        $this->subject=$subject;
        $email->setTo($to);
        if($cc != null ){
            $email->setCc($cc);
        }
        if($replay != null ){
            $email->setReplyTo($replay);
        }else{
            $email->setReplyTo('info@abogados.com.sv');            
        }
        if($bcc != null ){
            $email->setBcc($bcc);
        }
        $email->setSubject($this->subject);  
        $email->setBody($body); 
        $this->mail->send($email);
    }
}
