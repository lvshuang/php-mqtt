<?php
/**
 * Will interface.
 *
 * @author lvshuang <lvshuang1201@gmail.com>
 */
namespace Mqtt\Message\Will;

class Will
{
    protected $topic;
    protected $content;
    protected $qos;
    protected $retain;
    public function setWillTopic($topic)
    {
        $this->topic = $topic;
    }

    public function getWillTopic()
    {
        return $this->topic;
    }

    public function setWillContent($content)
    {
        $this->content = $content;
    }

    public function getWillContent()
    {
        return $this->content;
    }

    public function getQos()
    {
        return $this->qos;
    }

    public function setQos($qos)
    {
        $this->qos = (int) $qos;
    }

    public function getRetain()
    {
        return $this->retain;
    }

    public function setRetain($retain)
    {
        $this->retain = (boolean) $retain;
    }

}
