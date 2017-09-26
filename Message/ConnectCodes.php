<?php
/**
 * The connect return codes.
 *
 * @author helv <lvshuang1201@gmail.com>
 */
namespace Mqtt\Message;

class ConnectCodes
{

    const CONNECT_OK             = 0x00; // Connection accept.
    const CONNECT_ERR_PROTO      = 0x01; // Connection refused, server not support protocol.
    const CONNECT_ERR_CLIENT_ID  = 0x02; // Connection refused, invalid client id.
    const CONNECT_ERR_SERVER_ERR = 0x03; // Connection refused, server not available.
    const CONNECT_ERR_AUTH_ERR   = 0x04; // Connection refused, username or password invalid.
    const CONNECT_ERR_PERMISSION = 0x05; // Connection refused, permission denied.

    public static $validCodes =[
        self::CONNECT_OK,
        self::CONNECT_ERR_PROTO,
        self::CONNECT_ERR_CLIENT_ID,
        self::CONNECT_ERR_SERVER_ERR,
        self::CONNECT_ERR_AUTH_ERR,
        self::CONNECT_ERR_PERMISSION
    ];

}
