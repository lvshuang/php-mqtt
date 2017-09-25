<?php
/**
 * Mqtt connect message.
 *
 * @author lvshuang<lvshuang1201@gmail.com>
 */
namespace Mqtt\Message;

use Mqtt\BusinessException;
use Mqtt\Message\Header\ConnectHeader;
use Mqtt\Message\Will\Will;

class Connect extends Message
{
    /**
     * @var int message type.
     */
    protected $messageType = MessageTypes::CONNECT;
    /**
     * @var string Client id.
     */
    protected $clientId;
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
     * Set client id.
     *
     * @param string $clientId client id.
     *
     * @throws BusinessException
     *
     * @return void
     */
    public function setClientId($clientId)
    {
        if (!$clientId) {
            throw new BusinessException('Client id Error');
        }
        $this->clientId = $clientId;
    }

    /**
     * Get client id.
     *
     * @return string
     */
    public function getClientId()
    {
        return $this->clientId;
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
     * Return protocol name.
     *
     * @return string
     */
    public function getProtocol()
    {
        return $this->header->getProtocol();
    }

    /**
     * Return protocol version.
     *
     * @return integer
     */
    public function getProtocolVersion()
    {
        return $this->header->getProtocolVersion();
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
        $body = '';
        $body .= Util::packWithLength($this->getClientId());
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
        $this->parseBody($buffer, $pos);
    }

    /**
     * Parse the message body.
     *
     * @param string  $buffer String.
     * @param integer $pos    Start position.
     *
     * @return void
     */
    final protected function parseBody($buffer, &$pos)
    {
        $clientId = Util::unPackWithLength($buffer, $pos);
        $this->clientId = $clientId;
        if ($this->header->getWillFlag()) {
            $will = new Will();
            $will->setWillTopic(Util::unPackWithLength($buffer, $pos));
            $will->setWillContent(Util::unPackWithLength($buffer, $pos));
            $will->setQos($this->header->getQos());
            $will->setRetain($this->header->getWillRemain());
            $this->will = $will;
        }

        if ($this->header->getUserFlag()) {
            $this->username = Util::unPackWithLength($buffer, $pos);
        }
        if ($this->header->getPassFlag()) {
            $this->password = Util::unPackWithLength($buffer, $pos);
        }
    }

}
