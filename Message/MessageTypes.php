<?php
/**
 * Message types.
 *
 * @author lvshuang<lvshuang1201@gmail.com>
 */
namespace Mqtt\Message;

class MessageTypes
{

    /**
     * Message Type: CONNECT
     */
    const CONNECT       = 0x01;
    /**
     * Message Type: CONNACK
     */
    const CONNACK       = 0x02;
    /**
     * Message Type: PUBLISH
     */
    const PUBLISH       = 0x03;
    /**
     * Message Type: PUBACK
     */
    const PUBACK        = 0x04;
    /**
     * Message Type: PUBREC
     */
    const PUBREC        = 0x05;
    /**
     * Message Type: PUBREL
     */
    const PUBREL        = 0x06;
    /**
     * Message Type: PUBCOMP
     */
    const PUBCOMP       = 0x07;
    /**
     * Message Type: SUBSCRIBE
     */
    const SUBSCRIBE     = 0x08;
    /**
     * Message Type: SUBACK
     */
    const SUBACK        = 0x09;
    /**
     * Message Type: UNSUBSCRIBE
     */
    const UNSUBSCRIBE   = 0x0A;
    /**
     * Message Type: UNSUBACK
     */
    const UNSUBACK      = 0x0B;
    /**
     * Message Type: PINGREQ
     */
    const PINGREQ       = 0x0C;
    /**
     * Message Type: PINGRESP
     */
    const PINGRESP      = 0x0D;
    /**
     * Message Type: DISCONNECT
     */
    const DISCONNECT    = 0x0E;

    public static $msgTypeMap = [
        self::CONNECT => 'CONNECT',
        self::CONNACK => 'CONNACK',
        self::PUBLISH => 'PUBLISH',
        self::PUBACK => 'PUBACK',
        self::PUBREC => 'PUBREC',
        self::PUBREL => 'PUBREL',
        self::PUBCOMP => 'PUBCOMP',
        self::SUBSCRIBE => 'SUBSCRIBE',
        self::SUBACK => 'SUBACK',
        self::UNSUBSCRIBE => 'UNSUBSCRIBE',
        self::UNSUBACK => 'UNSUBACK',
        self::PINGREQ => 'PINGREQ',
        self::PINGRESP => 'PINGRESP',
        self::DISCONNECT => 'DISCONNECT',
    ];

}