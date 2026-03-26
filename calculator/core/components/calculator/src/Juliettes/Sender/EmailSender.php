<?
namespace Juliettes\Sender;

use modX;
use modMail;

class EmailSender implements MessageSenderInterface {

    protected $modx;

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
    }

    public function send(array $options, string $message) {
        $mail = $this->modx->getService('mail.modPHPMailer');
        $mail->set(modMail::MAIL_BODY, $message);
        $mail->set(modMail::MAIL_FROM, $this->modx->getOption('emailsender')); 
        $mail->set(modMail::MAIL_FROM_NAME, $this->modx->getOption('site_name'));
        $mail->set(modMail::MAIL_SUBJECT, $options['title']);
        $mail->setHTML(true); 
        foreach ($options['email_images'] as $key => $value) {
            $mail->embedImage($value, $key);
        }
        if ($clientMail = filter_var(trim($options['email']), FILTER_VALIDATE_EMAIL)) {
            $mail->address('to', $clientMail);
        }
        foreach(explode(',', $this->modx->getOption('emailrecipient')) as $staffMail) {
            $mail->address('to', trim($staffMail));
        }        
        if (!$mail->send()) {
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'An error occurred while trying to send the email: '. $mail->mailer->ErrorInfo);
        }
        $mail->reset();        
    }
}