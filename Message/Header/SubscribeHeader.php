<?php
/**
 * The header of subscribe message.
 *
 * @author helv <lvshuang1201@gmail.com>
 */
namespace Mqtt\Message\Header;

use Mqtt\BusinessException;
use Mqtt\Message\MessageTypes;

class SubscribeHeader extends Header
{
    /**
     * Max message identifier length.
     */
    const MAX_MSG_IDE = 65535;

    /**
     * @var integer Message Type.
     */
    protected $messageType = MessageTypes::SUBSCRIBE;

    /**
     * Message identifier.
     *
     * @var integer
     */
    protected $msgIdentifier;

    /**
     * Set message identifier.
     *
     * @param integer $identifier identifier.
     *
     * @throws BusinessException
     */
    public function setMsgIdentifier($identifier)
    {
        if (!filter_var($identifier, FILTER_VALIDATE_INT)) {
            throw new BusinessException('Message identifier must be int');
        }
        if ($identifier > self::MAX_MSG_IDE) {
            throw new BusinessException('Message identifier too long, must <= ' . self::MAX_MSG_IDE);
        }
        $this->msgIdentifier = $identifier;
    }

    /**
     * Get message identifier.
     *
     * @return integer
     */
    public function getMsgIdentifier()
    {
        return $this->msgIdentifier;
    }

    /**
     * Build variable header.
     *
     * @throws BusinessException
     */
    public function buildVarHeader()
    {
        if ($this->qos > 0 && !$this->msgIdentifier) {
            throw new BusinessException('Qos > 0, message identifier must be set');
        }
        $this->variableHeader = pack('n', $this->msgIdentifier);
    }

    /**
     * Parse variable header.
     *
     * @param string $byte Byte.
     * @param int    $pos  Start position.
     *
     * return void
     */
    public function parseVarHeader($byte, &$pos)
    {
        $varHeaderBuffer = substr($byte, $pos, 2);
        $pos += 2;
        $unpackData = unpack('n', $varHeaderBuffer);
        $this->msgIdentifier  = $unpackData[1];
    }
}
