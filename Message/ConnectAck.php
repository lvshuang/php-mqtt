<?php
/**
 * The response message for connect.
 *
 * @author Helv <lvshuang1201@gmail.com>
 */
namespace Mqtt\Message;

use Mqtt\Message\Header\ConnectAckHeader;

/**
 * Class ConnectAck.
 *
 * @package Mqtt\Message
 */
class ConnectAck extends Message
{

    /**
     * ConnectAck constructor.
     *
     * @return ConnectAck
     */
    public function __construct()
    {
        $this->header = new ConnectAckHeader($this);
    }

    public function setSessionPresent($sessionPresent)
    {
        $this->header->setSessionPresent($sessionPresent);
    }

    public function getSessionPresent()
    {
        return $this->header->getSessionPresent();
    }

    public function setConnectCode($code)
    {
        $this->header->setConnectCode($code);
    }

    public function getConnectCode()
    {
        return $this->header->getConnectCode();
    }

    /**
     * Build message body, connect ack message body is empty.
     *
     * @return string
     */
    final public function buildBody()
    {
        return '';
    }

    final public function parse($buffer)
    {
        $pos = 1; // The remain length is start from second byte.
        \Mqtt\Message\Util::decodeRemainLength($buffer, $pos);
        $this->header->parseVarHeader($buffer, $pos);
        $this->parseBody($buffer, $pos);
    }

    public function parseBody($buffer, &$pos)
    {
        $this->msgBody = '';
    }

}
