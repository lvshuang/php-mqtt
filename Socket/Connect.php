<?php
/**
 * Socket connect.
 *
 * @author lvshuang<lvshuang1201@gmail.com>
 */
namespace Mqtt\Socket;

use Mqtt\NetException;

class Connect
{

    protected $host;
    protected $port;
    protected $timeOut;
    protected $connection;
    protected $context;


    public function __construct($host, $port, $timeOut = 5)
    {
        $this->host = $host;
        $this->port = $port;
        $this->timeOut = $timeOut;
        $this->connect();
    }

    public function setContext($context)
    {
        $this->context = $context;
    }

    public function connect()
    {
        if (is_resource($this->connection)) {
            return;
        }

        $context = $this->context ? $this->context : stream_context_create();
        $errNo = null;
        $errStr = '';
        $socket = stream_socket_client($this->host . ':' . $this->port, $errNo, $errStr, $this->timeOut, STREAM_CLIENT_CONNECT, $context);

        if (!$socket) {
            throw new NetException(
                sprintf(
                    "Connect to host %s:%d failed after %d seconds, error number: %d, error message: %s",
                    $this->host,
                    $this->port,
                    $this->timeOut,
                    $errNo,
                    $errStr
                )
            );
        }
        stream_set_timeout($socket, $this->timeOut);
        stream_set_blocking($socket, 0);
        $this->connection = $socket;
    }

    public function write($string)
    {
        $waitWriteLen = strlen($string);
        for ($writtenLen = 0; $writtenLen < $waitWriteLen; $writtenLen += $writeLen) {
            $writeString = substr($string, $writtenLen);
            $writeLen = @fwrite($this->connection, $writeString, 8192);
            if ($writeLen === false) {
                echo "Write failed" . PHP_EOL;
                break;
            }
            if (strlen($writeString) == $writeLen) {
                echo "Written " . $writeString . PHP_EOL;
            }
        }
        return $writtenLen;
    }

    public function read()
    {
        $readBuffer = '';
        while (!feof($this->connection)) {
            $buffer = @fread($this->connection, 65535);
            if (!$buffer) {
                break;
            }
            $readBuffer .= $buffer;
        }
        return $readBuffer;
    }

    public function close()
    {
        fclose($this->connection);
    }

}
