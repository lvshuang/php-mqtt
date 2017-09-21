<?php
/**
 * Connection message header.
 *
 * @author lvshuang <lvshuang1201@gmail.com>
 */
namespace Mqtt\Message\Header;

use Mqtt\Mqtt;
use Mqtt\Message\Connect;
use Mqtt\Message\MessageTypes;
use Mqtt\Message\Util;

class ConnectHeader extends Header
{

    protected $messageType = MessageTypes::CONNECT;
    protected $protocol;
    protected $protocolVersion;
    protected $userFlag;
    protected $passFlag;
    protected $willFlag;
    protected $willRetain;
    protected $willQos;
    protected $clearSessionFlag;
    protected $keepalive;

    public function __construct(Connect $message)
    {
        parent::__construct($message);
    }

    /**
     * Build the variable header.
     *
     * @return string
     */
    final public function buildVarHeader()
    {
        $varHeader = '';
        // Protocol Name
        $varHeader .= Util::packWithLength(Mqtt::PROTOCOL);
        // Protocol Version
        $varHeader .= chr(Mqtt::VERSION_3_1_1);

        $connectFlags = 0;

        if ($this->message->getUsername()) {
            $this->userFlag = 1;
            $connectFlags |= 128; // 0000 0000 | 1000 0000; 128 bin is 1000 0000, hex is 0x80
        }
        if ($this->message->getPassword()) {
            $this->passFlag = 1;
            $connectFlags |= 64; // $connectFlags bin | 0100 000; 64 bin is 0100 0000, hex is 0x40
        }
        if ($will = $this->message->getWill()) {
            $this->willFlag = 1;
            $willFlag = 0;
            if ($will->getRetain()) {
                $this->willRetain = 1;
                $willFlag |= 32; // 32 bin is 0010 0000, hex is 0x20;
            }
            $this->willQos = $will->getQos();
            $willQos = $will->getQos() << 3;
            $willFlag |= $willQos;
            $willFlag |= 0x04; // 0x04 bin is 0000 0100
        }
        $connectFlags |= $willFlag;
        if ($this->message->getClearSession()) {
            $this->clearSessionFlag = 1;
            $connectFlags |= 2; // 2 bin is 000 0010, hex is 0x02
        }
        $varHeader .= chr($connectFlags); // $connectFlags is a byte.
        $varHeader .= pack('n', $this->message->getKeepalive()); // Keep alive time.
        $this->variableHeader = $varHeader;
        return $this->variableHeader;
    }

    /**
     * Parse variable header from byte.
     *
     * @param string  $byte buffer.
     * @param integer $pos  The start position.
     */
    final public function parseVarHeader($byte, &$pos)
    {
        $twoBytes = substr($byte, $pos, 2);
        $pos += 2;
        $protocolLen = unpack('n', $twoBytes);
        $protocol = substr($byte, $pos, $protocolLen[1]);

        $pos += strlen($protocol);
        $version = ord(substr($byte, $pos, 1));
        $pos +=1;

        $this->protocol = $protocol;
        $this->protocolVersion = $version;

        $this->parseConnectFlag($byte, $pos);
        $this->parseKeepAlive($byte, $pos);
    }

    /**
     * Parse connection flags.
     *
     * @param string  $byte Buffer.
     * @param integer $pos  Start position.
     *
     * @return void
     */
    final public function parseConnectFlag($byte, &$pos)
    {
        $flag = ord(substr($byte, $pos, 1));
        $pos += 1;
        $userFlag = $flag >> 7;
        $passFlag = ($flag >> 6) & 0x01;
        $willRetain = ($flag >> 5) & 0x01;
        $willQos = ($flag >> 3) & 0x03;
        $willFlag = ($flag >> 2) & 0x01;
        $clearSessionFlag = ($flag >> 1) & 0x01;
        $this->userFlag = $userFlag;
        $this->passFlag = $passFlag;
        $this->willFlag = $willFlag;
        $this->willQos = $willQos;
        $this->willRetain = $willRetain;
        $this->clearSessionFlag = $clearSessionFlag;
    }

    /**
     * Parse keep alive time.
     *
     * @param string  $byte Buffer.
     * @param integer $pos  Start position.
     *
     * @return void
     */
    final public function parseKeepAlive($byte, &$pos)
    {
        $buffer = substr($byte, $pos, 2);
        $result = unpack('n', $buffer);
        $pos += 2;
        $this->keepalive = $result[1];
        $this->message->setKeepalive($this->keepalive);
    }

}
