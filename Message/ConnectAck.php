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

    /**
     * Set session present value.
     *
     * @param integer $sessionPresent Session present value.
     *
     * @throws \Mqtt\BusinessException
     *
     * @return void
     */
    public function setSessionPresent($sessionPresent)
    {
        $this->header->setSessionPresent($sessionPresent);
    }

    /**
     * Get session present.
     *
     * @return integer
     */
    public function getSessionPresent()
    {
        return $this->header->getSessionPresent();
    }

    /**
     * Set connect return code.
     *
     * @param integer $code Connect code.
     *
     * @throws \Mqtt\BusinessException
     *
     * @return void
     */
    public function setConnectCode($code)
    {
        $this->header->setConnectCode($code);
    }

    /**
     * Get connect return code.
     *
     * @return integer
     */
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

    /**
     * Parse string to a message.
     *
     * @param string $buffer String.
     *
     * @return void
     */
    final public function parse($buffer)
    {
        $pos = 1; // The remain length is start from second byte.
        \Mqtt\Message\Util::decodeRemainLength($buffer, $pos);
        $this->header->parseVarHeader($buffer, $pos);
        $this->parseBody($buffer, $pos);
    }

    /**
     * Parse the message body, connect ack message body is empty.
     *
     * @param string  $buffer String.
     * @param integer $pos    Start substr position.
     *
     * @return void
     */
    public function parseBody($buffer, &$pos)
    {
        $this->msgBody = '';
    }

}
