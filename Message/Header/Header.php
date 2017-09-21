<?php
/**
 * Base Header.
 *
 * @author <lvshuang1201@gmail.com>
 */
namespace Mqtt\Message\Header;

use Mqtt\Message\Util;
use Mqtt\BusinessException;
use Mqtt\Message\Message;

class Header
{
    /**
     * @var Message $message Message instance.
     */
    protected $message;
    /**
     * @var integer $messageType Message type.
     */
    protected $messageType;
    /**
     * @var integer $dup 1 or 0, mark the message is a dup message when set 1.
     */
    protected $dup = 0;
    /**
     * @var integer $qos 0 to 2, the qos level of mesage.
     */
    protected $qos = 0;
    /**
     * @var integer $retain 1 or 0, mark the message should be store for new subscribe when set 1.
     */
    protected $retain = 0;

    /**
     * @var int The length of variable header and body.
     */
    protected $remainLen;

    /**
     * @var string The variable header content.
     */
    protected $variableHeader = '';

    /**
     * Header constructor.
     *
     * @param Message $message A instance of Message.
     *
     * @return Header
     */
    public function __construct($message)
    {
        $this->message = $message;
    }

    public function build()
    {
        $firstByte = $this->messageType << 4; // Message type flag: 4 bit, from 0 to 15
        $firstByte |= 0 << $this->dup; // DUP flag: 1 bit
        $firstByte |= 0 << $this->qos; // Qos flag: 3 bit
        $firstByte |= $this->retain;

        $fixedHeader = chr($firstByte);
        $this->remainLen = strlen($this->variableHeader . $this->message->getMessageBody());
        $remainLength = Util::encodeRemainLength($this->remainLen);
        $fixedHeader .= $remainLength;
        return $fixedHeader . $this->variableHeader;
    }

    /**
     * Return message type.
     *
     * @return int
     */
    public function getMessageType()
    {
        return $this->messageType;
    }

    /**
     * Set the len of variable header and body.
     *
     * @param integer $len The len of variable header and body.
     *
     * @return void
     */
    public function setRemainLen($len)
    {
        $this->remainLen = (int) $len;
    }

    public function getRemainLen()
    {
        return $this->remainLen;
    }

    /**
     * Set dup.
     *
     * @param integer $dup dup flag.
     *
     * @return void
     */
    public function setDup($dup)
    {
        $this->dup = $dup ? 1 : 0;
    }

    /**
     * Return dup flag.
     *
     * @return int
     */
    public function getDup()
    {
        return $this->dup;
    }

    /**
     * Set qos.
     *
     * @param integer $qos qos level flag.
     *
     * @throws BusinessException
     *
     * @return void
     */
    public function setQos($qos)
    {
        if (!in_array($qos, [0, 1, 2])) {
            throw new BusinessException('Error qos level ' . $qos);
        }
        $this->qos = $qos;
    }

    /**
     * Return qos level flag.
     *
     * @return int
     */
    public function getQos()
    {
        return $this->qos;
    }

    /**
     * Set remain flag.
     *
     * @param integer|boolean $remain Remain flag.
     *
     * @return void
     */
    public function setRemain($remain)
    {
        $this->retain = $remain ? 1 : 0;
    }

    /**
     * Return remain flag.
     *
     * @return int
     */
    public function getRemain()
    {
        return $this->retain;
    }

}
