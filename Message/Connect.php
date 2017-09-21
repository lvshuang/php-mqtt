<?php
/**
 * Mqtt connect message.
 *
 * @author lvshuang<lvshuang1201@gmail.com>
 */
namespace Mqtt\Message;

use Mqtt\Message\Header\ConnectHeader;
use Mqtt\Message\Will\Will;

class Connect extends Message
{
    /**
     * @var int message type.
     */
    protected $messageType = MessageTypes::CONNECT;
    /**
     * @var string username.
     */
    protected $username;
    /**
     * @var string password.
     */
    protected $password;
    /**
     * @var Will A will instance.
     */
    protected $will;
    /**
     * @var boolean show clear session.
     */
    protected $clearSession;

    /**
     * @var string Message body.
     */
    protected $msgBody = '';

    /**
     * @var integer $keepalive.
     */
    protected $keepalive = 60;

    /**
     * Connect constructor, set header in the constructor.
     *
     * @return Connect
     */
    public function __construct()
    {
        $this->header = new ConnectHeader($this);
    }

    /**
     * Get the message type.
     *
     * @return int
     */
    public function getMessageType()
    {
        return $this->header->getMessageType();
    }

    /**
     * Set username.
     *
     * @param string $username Username.
     *
     * @return void
     */
    public function setUsername($username)
    {
        $this->username = $username;
    }

    /**
     * Return the auth username field.
     *
     * @return string username.
     */
    public function getUsername()
    {
        return $this->username;
    }

    /**
     * Set password.
     *
     * @param string $password Password.
     *
     * @return void
     */
    public function setPassword($password)
    {
        $this->password = $password;
    }

    /**
     * The password of auth field.
     *
     * @return string password.
     */
    public function getPassword()
    {
        return $this->password;
    }

    /**
     * Set will.
     *
     * @param Will $will Will.
     *
     * @return void
     */
    public function setWill(Will $will)
    {
        $this->will = $will;
    }

    /**
     * Return will object.
     *
     * @return Will will.
     */
    public function getWill()
    {
        return $this->will;
    }

    /**
     * Set session clear flag.
     *
     * @param boolean $clearSession Clear sessiong flag.
     *
     * @return void
     */
    public function setClearSession($clearSession)
    {
        $this->clearSession = $clearSession;
    }

    /**
     * Return clear session flag.
     *
     * @return integer
     */
    public function getClearSession()
    {
        return $this->clearSession;
    }

    /**
     * Set keep alive time.
     *
     * @param integer $keepalive Keep alive time.
     *
     * @return void
     */
    public function setKeepalive($keepalive)
    {
        $this->keepalive = (int) $keepalive;
    }

    /**
     * Return keep alive time.
     *
     * @return integer
     */
    public function getKeepalive()
    {
        return $this->keepalive;
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
     * Build body.
     *
     * @return string
     */
    final public function buildBody()
    {
        $clientId = Util::makeClientId();
        $body = '';
        $body .= Util::packWithLength($clientId);
        if ($this->will) {
            $body .= Util::packWithLength($this->will->getWillTopic());
            $body .= Util::packWithLength($this->will->getWillContent());
        }
        if ($this->username) {
            $body .= Util::packWithLength($this->username);
        }
        if ($this->password) {
            $body .= Util::packWithLength($this->password);
        }
        return $body;
    }

    /**
     * Parse message data from the received buffer.
     *
     * @param string $buffer Received buffer.
     *
     * @return void
     */
    final public function parse($buffer)
    {
        $pos = 1; // The remain length is start from second byte.
        $remainLen = \Mqtt\Message\Util::decodeRemainLength($buffer, $pos);
        $this->header->parseVarHeader($buffer, $pos);
    }

}