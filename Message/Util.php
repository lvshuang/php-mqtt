<?php
/**
 * The util of mqtt message.
 *
 * @author lvshuang<lvshuang1201@gmail.com>
 */
namespace Mqtt\Message;

use Mqtt\BusinessException;

class Util
{

    public static function packWithLength($str)
    {
        $len = strlen($str);
        // pack n is 16 bit2, 2 bytes
        return pack('n', $len) . $str;
    }

    public static function unPackWithLength($buffer, &$pos)
    {
        $lenByte = substr($buffer, $pos, 2);
        $pos += 2;
        $lenArr = unpack('n', $lenByte);
        $string = substr($buffer, $pos, $lenArr[1]);
        $pos += strlen($string);
        return $string;
    }

    public static function encodeRemainLength($len)
    {
        $string = "";
        do {
            $digit = $len % 0x80;
            $len = $len >> 7;
            if ($len > 0) {
                $digit = ($digit | 0x80);
            }
            $string .= chr($digit);
        } while ($len > 0);

        return $string;
    }

    public static function decodeRemainLength($msg, &$pos)
    {
        $multiplier = 1;
        $value = 0 ;
        do {
            $digit = ord($msg[$pos]);
            $value += ($digit & 0x7F) * $multiplier;
            $multiplier *= 0x80;
            $pos++;
        } while (($digit & 0x80) != 0);
        return $value;
    }

    /**
     * Judge the $msg if contain the remain length.
     *
     * @param string  $msg String.
     * @param integer $pos Start position.
     *
     * @return bool
     *
     * @throws BusinessException
     */
    public static function isRemainLengthComplete($msg, &$pos = 1)
    {
        $completed = false;
        do {
            if (!isset($msg[$pos])) {
                break;
            }
            $digit = ord($msg[$pos]);
            $digit = $digit >> 7;
            if ($digit > 0) {
                if ($pos > 4) {
                    throw new BusinessException('Error remaining length');
                }
                $pos ++;
            } else {
                $completed = true;
                break;
            }
        } while (true);
        return $completed;
    }

    public static function makeClientId()
    {
        return uniqid('mqtt') . microtime(true) * 10000;
    }

    /**
     * Parse a string to an message object.
     *
     * @param string $buffer Buffer in string.
     *
     * @return Message A message instance.
     *
     * @throws BusinessException
     */
    public static function parse($buffer)
    {
        $header = self::parseHeader($buffer);
        $message = \Mqtt\Message\Message::factory($header['message_type']);
        $message->setDup($header['dup']);
        $message->setQos($header['qos']);
        $message->setRemain($header['remain']);
        $message->parse($buffer);

        return $message;
    }

    /**
     * Parse the fix header.
     *
     * @param string $buffer String.
     *
     * @return array Header message.
     */
    public static function parseHeader($buffer)
    {
        $data = ord(substr($buffer, 0, 1));
        $msgType = $data >> 4;
        $flags = $data & 0x0f; // 0x0f bin is 0000 1111
        $dup = $flags >> 3;
        $qosFlag = $flags & 0x07; // 0x07 bin is 0000 0111
        $qos = $qosFlag >> 1;
        $remain = $qosFlag & 0x01; // 0x01 bin is 0000 0001
        return [
            'message_type' => $msgType,
            'dup' => $dup,
            'qos' => $qos,
            'remain' => $remain
        ];
    }


}