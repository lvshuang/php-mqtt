<?php
/**
 * Will interface.
 *
 * @author lvshuang <lvshuang1201@gmail.com>
 */
namespace Mqtt\Message\Will;

/**
 * Class Will.
 *
 * @package Mqtt\Message\Will
 */
class Will
{
    /**
     * @var string  Will topic.
     */
    protected $topic;
    /**
     * @var string Will content.
     */
    protected $content;

    /**
     * @var integer Will QoS level.
     */
    protected $qos;
    /**
     * @var integer|boolean Will retain.
     */
    protected $retain;

    /**
     * Set will topic.
     *
     * @param string $topic Topic.
     *
     * @return void
     */
    public function setWillTopic($topic)
    {
        $this->topic = $topic;
    }

    /**
     * Return will topic.
     *
     * @return string
     */
    public function getWillTopic()
    {
        return $this->topic;
    }

    /**
     * Set will topic content.
     *
     * @param string $content Will content.
     *
     * @return void
     */
    public function setWillContent($content)
    {
        $this->content = $content;
    }

    /**
     * Return will content.
     *
     * @return string
     */
    public function getWillContent()
    {
        return $this->content;
    }

    /**
     * Return Will Qos value.
     *
     * @return integer
     */
    public function getQos()
    {
        return $this->qos;
    }

    /**
     * Set will QoS value.
     *
     * @param integer $qos
     */
    public function setQos($qos)
    {
        $this->qos = (int) $qos;
    }

    /**
     * Return will retain flag.
     *
     * @return bool|int
     */
    public function getRetain()
    {
        return $this->retain;
    }

    /**
     * Set will retain flag.
     *
     * @param integer|boolean $retain
     */
    public function setRetain($retain)
    {
        $this->retain = (boolean) $retain;
    }

}
