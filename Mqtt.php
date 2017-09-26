<?php
/**
 * A lib of mqtt.
 *
 * @author lvshuang <lvshuang1201@gmail.com>
 */
namespace Mqtt;

use Mqtt\Message\Connect;
use Mqtt\Message\ConnectCodes;
use Mqtt\Message\Message;
use Mqtt\Message\MessageTypes;
use Mqtt\Message\Util;
use Mqtt\Message\Will\Will;

class Mqtt
{
    const PROTOCOL = 'MQTT';
    /**
     * Version Code
     */
    const VERSION_3     = 3;
    const VERSION_3_0   = 3;
    const VERSION_3_1   = 3;
    const VERSION_3_1_1 = 4;

    /**
     * Current version
     *
     * Default: MQTT 3.1.1
     *
     * @var int
     */
    protected $version = self::VERSION_3_1_1;
    protected $socket;
    protected $will;

    public $onConnect;

    public function __construct()
    {
    }

    public function setWill(Will $will)
    {
        $this->will = $will;
    }

    public function connect($host, $port, $username = '', $password = '', $timeOut = 5, $forceReConnect = false)
    {
        if (is_resource($this->socket) && !$forceReConnect) {
            return;
        }
        $this->socket = new \Mqtt\Socket\Connect($host, $port, $timeOut);

        /**
         * @var Connect
         */
        $connectMsg = Message::factory(MessageTypes::CONNECT);
        $connectMsg->setWill($this->will);
        $connectMsg->setProtocol(self::PROTOCOL);
        $connectMsg->setProtocolVersion(self::VERSION_3_1_1);
        $connectMsg->setUsername($username);
        $connectMsg->setPassword($password);
        $connectMsg->setClientId(Util::makeClientId());
        $connectMsg->setClearSession(true);

        $writeContent = $connectMsg->build();
        if ($this->socket) {
            $this->socket->write($writeContent);
        }
        $buffer = $this->socket->read();
        $message = Util::parse($buffer);

        // Todo: 确认连接建立后第一个包是否必须是 connect ack.
        if ($message->getMessageType() === MessageTypes::CONNACK) {
            if (is_callable($this->onConnect)) {
                call_user_func($this->onConnect, $message);
                return true;
            }
            if ($message->getConnectCode() !== ConnectCodes::CONNECT_OK) {
                $this->socket->close(); // Server refused connect, close it.
                throw new \Exception("Connect error, code: " . $this->getConnectCode() . ", session present is " . $message->getSessionPresent());
            }
            return $message;
        } else {
            //Todo: 如果连接建立后第一个包必须是 connect ack 包, 则需要在这里处理异常.
        }
    }

}