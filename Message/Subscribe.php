<?php
/**
 * The subscribe message.
 *
 * @author helv <lvshuang1201@gmail.com>
 */
namespace Mqtt\Message;

use Mqtt\BusinessException;
use Mqtt\Message\Header\SubscribeHeader;

class Subscribe extends Message
{
    /**
     * Subscribe topics.
     *
     * @var array
     */
    protected $subTopics = [];

    /**
     * Valid qos value.
     *
     * @var array
     */
    protected $validQos = [0, 1, 2];

    /**
     * Subscribe constructor.
     */
    public function __construct()
    {
        $this->header = new SubscribeHeader($this);
        // Subscribe message remain is 1
        $this->header->setRemain(1);
    }

    /**
     * Set message identifier.
     *
     * @param integer $msgIdentifier identifier.
     *
     * @throws BusinessException
     */
    public function setMsgIdentifier($msgIdentifier)
    {
        $this->header->setMsgIdentifier($msgIdentifier);
    }

    /**
     * Get message identifier.
     *
     * @return integer
     */
    public function getMsgIdentifier()
    {
        return $this->header->getMsgIdentifier();
    }

    /**
     * Set one subscribe topic.
     *
     * @param array $topic Topic info.
     *
     * @throws BusinessException
     */
    public function setSubTopic(array $topic)
    {
        if (!isset($topic['name'])) {
            throw new BusinessException('Subscribe topic name need');
        }
        if (!isset($topic['qos']) || !in_array($topic['qos'], $this->validQos)) {
            throw new BusinessException('Invalid qos');
        }
        $this->subTopics[] = $topic;
    }

    /**
     * Set subscribe topics.
     *
     * @param array $topics Topics.
     *
     * @throws BusinessException
     *
     * @return void
     */
    public function setSubTopics(array $topics)
    {
        if (empty($topics)) {
            throw new BusinessException('Subscribe topics empty');
        }
        foreach ($topics as $topic) {
            $this->setSubTopic($topic);
        }
    }

    /**
     * Get sub topics.
     *
     * @return array
     */
    public function getSubTopics()
    {
        return $this->subTopics;
    }

    /**
     * Build message body.
     *
     * @return string
     */
    public function buildBody()
    {
        $body = '';
        foreach ($this->subTopics as $topic) {
            $body .= Util::packWithLength($topic['name']);
            $body .= chr($topic['qos']);
        }
        $this->msgBody = $body;
        return $this->msgBody;
    }

    /**
     * Parse string to message.
     *
     * @param string $buffer
     *
     * @return void
     */
    public function parse($buffer)
    {
        $pos = 1; // The remain length is start from second byte.
        $remainLen = \Mqtt\Message\Util::decodeRemainLength($buffer, $pos);
        $this->header->setRemainLen($remainLen);
        $this->header->parseVarHeader($buffer, $pos);
        $this->parseBody($buffer, $pos);
    }

    /**
     * Parse message body.
     *
     * @param string  $buffer Parse string.
     * @param integer $pos    Start position.
     * 
     * @throws BusinessException
     */
    public function parseBody($buffer, &$pos)
    {
        $remainLen = $this->header->getRemainLen();
        $totalLen = 2 + $remainLen;
        while (true) {
            $lenBuffer = substr($buffer, $pos, 2);
            $pos += 2;
            $lenPack = unpack('n', $lenBuffer);
            $topicLen = $lenPack[1];
            $topicName = substr($buffer, $pos, $topicLen);
            $pos += $topicLen;
            $qos = substr($buffer, $pos, 1);
            $qos = ord($qos);
            $pos += 1;
            $this->setSubTopic(['name' => $topicName, 'qos' => $qos]);
            if ($pos >= $totalLen) {
                break;
            }
        }
    }


}
