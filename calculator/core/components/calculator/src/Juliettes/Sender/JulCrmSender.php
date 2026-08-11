<?
namespace Juliettes\Sender;

use modX;

class JulCrmSender implements MessageSenderInterface {
     protected $modx;

    public function __construct(modX $modx)
    {
        $this->modx = $modx;
    }

    public function send(array $options, string $message) {
        $url = $this->modx->getOption('crm_url');
        $key = $this->modx->getOption('crm_key');
        if (empty($url) || empty($key)) return;

        $headers = array(
            'Content-type' => 'application/json', 
            'X-Webhook-Token' => $key
        );
        $client = $this->modx->getService('rest', 'rest.modRest');
        $client->setOption('format', 'json');
        $client->setOption('suppressSuffix', true);
        $response = $client->post($url, $options, $headers);
        $httpCode = $response->responseInfo->scalar;
        
        $responseData = $response->process(); 

        // Проверяем результат
        if ($responseData) {
            // Успешный ответ
            $this->modx->log(modX::LOG_LEVEL_ERROR, print_r($responseData, true));
        } else {
            // Ошибка
            $this->modx->log(modX::LOG_LEVEL_ERROR, 'Ошибка запроса к API');
        }
    }
}