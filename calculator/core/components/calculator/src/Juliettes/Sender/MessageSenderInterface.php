<?
namespace Juliettes\Sender;

interface MessageSenderInterface {
    public function send(array $options, string $message);
}