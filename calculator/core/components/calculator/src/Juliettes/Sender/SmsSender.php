<?
namespace Juliettes\Sender;
use modX;
use Juliettes\Sender\SmsRu;

class SmsSender implements MessageSenderInterface {
    protected $modx;

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
    }

    public function send(array $options, string $message) {
        $message = preg_replace('/[\r\n]+| {2,}/', ' ', $message);  
        $smsru = new SmsRu($this->modx->getOption('sms_ru_key'));
        $data = new \stdClass();
        $data->to = $this->modx->getOption('smsrecipient');
        $data->text = $message;
        $request = $smsru->send($data);
        if ($request->status == "OK") { 
            foreach ($request->sms as $phone => $sms) { 
                if ($sms->status != "OK") {
                    $this->modx->log(modX::LOG_LEVEL_ERROR, "Сообщение на номер {$phone} не отправлено. {$sms->status_code}. Текст ошибки: {$sms->status_text}.");                                        
                }
            }
            //echo "Ваш новый баланс: $request->balance";
        } else {
            $this->modx->log(modX::LOG_LEVEL_ERROR, "Ошибка при выполнении запроса. Код ошибки: {$request->status_code}. Текст ошибки: {$request->status_text}."); 
        }
    }
}
