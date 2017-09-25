<?php
/**
 * A lib of mqtt.
 *
 * @author lvshuang <lvshuang1201@gmail.com>
 */
namespace Mqtt;

use Mqtt\Message\Connect;
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
        $connectMsg->setUsername($username);
        $connectMsg->setPassword($password);
        $connectMsg->setClientId(Util::makeClientId());
        $connectMsg->setClearSession(true);

        $writeContent = $connectMsg->build();
        if ($this->socket) {
            $this->socket->write($writeContent);
        }

        $message = $this->socket->read();
        echo "Get from server: " . $message;
    }

}