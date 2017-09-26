<?php
/**
 * The connect ack message header.
 *
 * @author helv  <lvshuang1201@gmail.com>
 */
namespace Mqtt\Message\Header;

use Mqtt\BusinessException;
use Mqtt\Message\ConnectCodes;
use Mqtt\Message\MessageTypes;

class ConnectAckHeader extends Header
{

    protected $messageType = MessageTypes::CONNACK;
    protected $connectCode;
    protected $sessionPresent = null;
    protected $validSessionPresent = [0, 1];

    public function setSessionPresent($sessionPresent)
    {
        if (!in_array($sessionPresent, $this->validSessionPresent)) {
            throw new BusinessException("Session present error");
        }
        $this->sessionPresent = $sessionPresent;
    }

    public function getSessionPresent()
    {
        return $this->sessionPresent;
    }

    public function setConnectCode($code)
    {
        if (!in_array($code, ConnectCodes::$validCodes)) {
            throw new BusinessException("Connect code invalid");
        }
        $this->connectCode = $code;
    }

    public function getConnectCode()
    {
        return $this->connectCode;
    }

    final public function buildVarHeader()
    {
        if (!isset($this->sessionPresent) ||
            $this->sessionPresent === null ||
            !in_array($this->sessionPresent, $this->validSessionPresent)
        ) {
            throw new BusinessException("Session present error");
        }
        if ($this->connectCode > 0 && $this->sessionPresent !== 0) {
            throw new BusinessException('Session present must be 0 where connect code great than 0');
        }
        $varHeader = '';
        $connectConfirmFlag = 0;
        $connectConfirmFlag |= $this->sessionPresent;
        $varHeader .= chr($connectConfirmFlag);
        $varHeader .= chr($this->connectCode);

        $this->variableHeader = $varHeader;
        return $this->variableHeader;
    }

    final public function parseVarHeader($byte, &$pos)
    {
        $connectConfirmFlag = ord(substr($byte, $pos, 1));
        $pos += 1;
        $connectCode = ord(substr($byte, $pos, 1));
        $pos += 1;
        $this->connectCode = $connectCode;
        $this->sessionPresent = $connectConfirmFlag;
    }

}
