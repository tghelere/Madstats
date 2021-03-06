<?php
/**
 * This code is free software; you can redistribute it and/or modify it under
 * the terms of the new BSD License.
 *
 * Copyright (c) 2008-2009, Sebastian Staudt
 *
 * @author Sebastian Staudt
 * @license http://www.opensource.org/licenses/bsd-license.php New BSD License
 * @package Steam Condenser (PHP)
 * @subpackage SteamSocket
 * @version $Id: MasterServer.php 163 2009-03-02 11:50:40Z koraktor $
 */

require_once "InetAddress.php";
require_once "steam/packets/A2M_GET_SERVERS_BATCH2_Packet.php";
require_once "steam/sockets/MasterServerSocket.php";

/**
 * @package Steam Condenser (PHP)
 * @subpackage MasterServer
 */
class MasterServer
{
	const GOLDSRC_MASTER_SERVER = "hl1master.steampowered.com:27010";
	const SOURCE_MASTER_SERVER = "hl2master.steampowered.com:27011";

        const REGION_US_EAST_COAST = 0x00;
	const REGION_US_WEST_COAST = 0x01;
	const REGION_SOUTH_AMERICA = 0x02;
	const REGION_EUROPE = 0x03;
	const REGION_ASIA = 0x04;
	const REGION_AUSTRALIA = 0x05;
	const REGION_MIDDLE_EAST = 0x06;
	const REGION_AFRICA = 0x07;
	const REGION_ALL = 0xFF;

	/**
	 * @var MasterServerSocket
	 */
	private $socket;

	public function __construct($masterServer)
	{
		$masterServer = explode(":", $masterServer);
		$this->socket = new MasterServerSocket(new InetAddress($masterServer[0]), $masterServer[1]);
	}


	public function getServers($regionCode = MasterServer::REGION_ALL , $filter = "", $raiseTimeout = true)
	{
		$finished = false;
		$portNumber = 0;
		$hostName = "0.0.0.0";

		do
		{
			$this->socket->send(new A2M_GET_SERVERS_BATCH2_Packet($regionCode, "$hostName:$portNumber", $filter));
			try {
				$serverStringArray = $this->socket->getReply()->getServers();
			 
				foreach($serverStringArray as $serverString)
				{
					$serverString = explode(":", $serverString);
					$hostName = $serverString[0];
					$portNumber = $serverString[1];
	
					if($hostName != "0.0.0.0" && $portNumber != 0)
					{
						$serverArray[] = array($hostName, $portNumber);
					}
					else
					{
						$finished = true;
					}
				}
			}
			catch(TimeoutException $e) {
				if($raiseTimeout) {
					throw $e;
				}
				$finished = true;
			}
		}
		while(!$finished);

		return $serverArray;
	}
}
?>
