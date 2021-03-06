<?php
/**
 * This code is free software; you can redistribute it and/or modify it under
 * the terms of the new BSD License.
 *
 * @author Sebastian Staudt
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package Steam Condenser (PHP)
 * @subpackage Packets
 * @version $Id: S2C_CHALLENGE_Packet.php 154 2008-12-11 07:49:47Z koraktor $
 */

require_once "steam/packets/SteamPacket.php";

/**
 * @package Steam Condenser (PHP)
 * @subpackage Packets
 */
class S2C_CHALLENGE_Packet extends SteamPacket
{
	/**
	 * @param long $challengeNumber
	 */
	public function __construct($challengeNumber)
	{
		parent::__construct(SteamPacket::S2C_CHALLENGE_HEADER, $challengeNumber);
	}

	/**
	 * @return int
	 */
	public function getChallengeNumber()
	{
		return $this->contentData->rewind()->getLong();
	}
}
?>