<?php
/**
 * The implement of workerman ProtocolInterface.
 *
 * @author lvshuang <lvshuang1201@gmail.com>
 */
namespace Mqtt\Workerman\Protocols;

use Mqtt\Message\Util;

class Mqtt
{

    public static function input($recv_buffer, ConnectionInterface $connection)
    {
        $pos = 1;
        if (!isset($recv_buffer[0])) {
            return 0;
        }
        if (!Util::isRemainLengthComplete($recv_buffer, $pos)) {
            return 0;
        }
        $remainLength = Util::decodeRemainLength($recv_buffer, $pos);

        return 2 + $remainLength; // A complete package length
    }

    public static function decode($recv_buffer, ConnectionInterface $connection)
    {
        $firstByteOfHeader = Util::parseHeader($recv_buffer);
        Util::decodeRemainLength($recv_buffer, $pos);
        $buffer = substr($recv_buffer, $pos + 1); // Except fixed header.

    }

    public static function encode($data, ConnectionInterface $connection)
    {

    }

}