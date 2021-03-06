<?php
/**
 * This code is free software; you can redistribute it and/or modify it under
 * the terms of the new BSD License.
 *
 * @author     Sebastian Staudt
 * @license    http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package    Steam Condenser (PHP)
 * @subpackage Packets
 * @version    $Id: RCONPacketFactory.php 154 2008-12-11 07:49:47Z koraktor $
 */

require_once "ByteBuffer.php";
require_once "steam/packets/SteamPacketFactory.php";
require_once "steam/packets/rcon/RCONAuthResponse.php";
require_once "steam/packets/rcon/RCONExecResponse.php";
require_once "steam/packets/rcon/RCONPacket.php";

/**
 * @package    Steam Condenser (PHP)
 * @subpackage Packets
 */
abstract class RCONPacketFactory extends SteamPacketFactory
{
	public static function getPacketFromData($rawData)
	{
		$byteBuffer = new ByteBuffer($rawData);

		$packetSize = $byteBuffer->getLong();
		$requestId = $byteBuffer->getLong();
		$header = $byteBuffer->getLong();
		$data = $byteBuffer->getString();

		switch($header)
		{
			case RCONPacket::SERVERDATA_AUTH_RESPONSE:
				return new RCONAuthResponse($requestId);
			case RCONPacket::SERVERDATA_RESPONSE_VALUE:
				return new RCONExecResponse($requestId, $data);
			default:
				throw new PacketFormatException("Unknown packet with header " . dechex($header) . " received.");
		}
	}
}
?>