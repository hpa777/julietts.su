<?php

use Juliettes\Sender\EmailSender;
use Juliettes\Sender\SmsSender;
use Juliettes\Sender\JulCrmSender;

//require MODX_CORE_PATH . 'components/calculator/vendor/autoload.php';
require __DIR__ . '/../vendor/autoload.php';

class calculator
{
    /** @var modX $modx */
    public $modx;


    /**
     * @param modX $modx
     * @param array $config
     */
    function __construct(modX &$modx, array $config = [])
    {
        $this->modx =& $modx;
        //$corePath = MODX_CORE_PATH . 'components/calculator/';
        $corePath = dirname(__DIR__) . '/';
        $assetsUrl = MODX_ASSETS_URL . 'components/calculator/';

        $this->config = array_merge([
            'corePath' => $corePath,
            'modelPath' => $corePath . 'model/',
            'processorsPath' => $corePath . 'processors/',

            'connectorUrl' => $assetsUrl . 'connector.php',
            'assetsUrl' => $assetsUrl,
            'cssUrl' => $assetsUrl . 'css/',
            'jsUrl' => $assetsUrl . 'js/',
        ], $config);

        $this->modx->addPackage('calculator', $this->config['modelPath']);
        $this->modx->lexicon->load('ru:calculator:default');
    }

    private function prepareEmailOptions($order) 
    {
        $options = [];
        $options['title'] = $this->modx->lexicon('jul_email_title');
        $options['cost_engraving'] = $this->modx->lexicon('jul_cost_engraving');
        $options['email_images'] = [
            'em_logo' => MODX_ASSETS_PATH . 'mail_img/em_logo1.png',
            'vk' => MODX_ASSETS_PATH . 'mail_img/vk.png',
            'instagram' => MODX_ASSETS_PATH . 'mail_img/instagram.png',
            'telegram' => MODX_ASSETS_PATH . 'mail_img/telegram.png',
            'viber' => MODX_ASSETS_PATH . 'mail_img/viber.png',
            'whatsapp' => MODX_ASSETS_PATH . 'mail_img/whatsapp.png',            
            'order_image' => $this->resizeImage($order['image'])
        ];
        foreach ($order['items'] as &$item) {
            $item['category'] = $this->modx->runSnippet('pdoField', [
                'id' => $item['id'],
                'topLevel' => 2,
                'field' => 'pagetitle'
            ]);
        }
        return array_merge($options, $order);
    }

    private function resizeImage($fileName) {
        $resizedImg = $this->modx->runSnippet('pThumb', [
            'input' => '/assets/orders/images/' . $fileName,
            'options' => 'w=480' 
        ]);
        return MODX_BASE_PATH . $resizedImg;
    }

    public function sendMessage() {
        $pdoTools = $this->modx->getService('pdoTools');                
        $smsSender = new SmsSender($this->modx);
        $emailSender = new EmailSender($this->modx);
        $julCrmSender = new JulCrmSender($this->modx); 
        foreach($this->modx->getCollection('calculatorOder', ['status' => 1]) as $order) {
            $options = $this->prepareEmailOptions( $order->toArray());                        
            $msg = $pdoTools->getChunk('tpl.calculator.sms', $options);            
            //$smsSender->send($options, $msg);

            $msg = $pdoTools->getChunk('tpl.calculator.email', $options);
            $emailSender->send($options, $msg);

            $order->set('status', 2);
            $order->save();
            $julCrmSender->send($order->toArray(), '');
        }
    }

    public function checkMessageQueue() {
        $this->modx->getService('registry', 'registry.modRegistry');
        $this->modx->registry->addRegister('checkQueue', 'registry.modFileRegister', array('directory' => 'check_queue'));
        $this->modx->registry->checkQueue->connect();
        $msg = "";
        if ($count = $this->checkMsg("/image/", 0)) {
            $msg.= "<p>Необработанных картинок изделий - {$count}</p>";
        }
        if ($count = $this->checkMsg("/mail/", 1)) {
            $msg.= "<p>Зависших писем - {$count}</p>";
        }
        if (!empty($msg)) {
            $emailSender = new EmailSender($this->modx);
            $emailSender->send([
                'title' => 'Калькулятор juliettes.ru Зависшие сообщения'
            ], $msg); 
        }
        
    }

    private function checkMsg($topic, $status) {
        $this->modx->registry->checkQueue->subscribe($topic);
        $msg = $this->modx->registry->checkQueue->read(['remove_read' => false]);
        if (!$msg && $count = $this->modx->getCount('calculatorOder', ["status = '{$status}' AND TIMESTAMPDIFF(MINUTE,timestamp,NOW()) > 5"])) {
            $this->modx->registry->checkQueue->send($topic, $count, ['ttl' => 1800]);
            return $count;
        }     
    }


}