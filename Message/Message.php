<?php
/**
 * The base message class and message factory.
 *
 * @author lvshuang <lvshuang1201@gmail.com>
 */
namespace Mqtt\Message;

use Mqtt\BusinessException;
use Mqtt\Message\Header\Header;

abstract class Message
{
    /**
     * @var Header
     */
    protected $header;
    /**
     * @var string Message body.
     */
    protected $msgBody = '';

    /**
     * Create message instance.
     *
     * @param integer $msgType Msg type which defined in MessageTypes.
     *
     * @return Message A message instance.
     *
     * @throws BusinessException Business exception.
     */
    public static function factory($msgType)
    {
        if (!isset(MessageTypes::$msgTypeMap[$msgType])) {
            throw new BusinessException('Error message type: ' . $msgType);
        }
        $className = MessageTypes::$msgTypeMap[$msgType];

        $class = "Mqtt\\Message\\" . ucfirst($className);
        if (!class_exists($class)) {
            throw new BusinessException('Class ' . $class . ' not found');
        }
        return new $class($msgType);
    }

    /**
     * Build the message and return in string.
     *
     * @return string
     */
    public function build()
    {
        // First to build variable header,
        // the func buildVarHeader will set variable header to the property $variableHeader.
        $this->header->buildVarHeader();
        // Second build message body and set to property $msgBody,
        // The header can access message body throw method getMessageBody().
        $this->msgBody = $this->buildBody();

        $header = $this->header->build();
        return $header . $this->msgBody;
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
        $this->header->setDup($dup);
    }

    /**
     * Return message type value.
     *
     * @return int
     */
    public function getMessageType()
    {
        return $this->header->getMessageType();
    }

    /**
     * Return dup flag.
     *
     * @return int
     */
    public function getDup()
    {
        return $this->header->getDup();
    }

    /**
     * Set qos.
     *
     * @param integer $qos qos level flag.
     *
     * @return void
     */
    public function setQos($qos)
    {
        $this->header->setQos($qos);
    }

    /**
     * Return qos flag.
     *
     * @return int
     */
    public function getQos()
    {
        return $this->header->getQos();
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
        $this->header->setRemain($remain);
    }

    /**
     * Return remain flag.
     *
     * @return int
     */
    public function getRemain()
    {
        return $this->header->getRemain();
    }

    /**
     * Return message body.
     *
     * @return string message body.
     */
    public function getMessageBody()
    {
        return $this->msgBody;
    }

    /**
     * Build the message body.
     *
     * @return mixed
     */
    abstract public function buildBody();

    /**
     * Parse message data from the received buffer.
     *
     * @param string $buffer Received buffer.
     *
     * @return void
     */
    abstract public function parse($buffer);

}
