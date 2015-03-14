<?php

namespace KillBearBoys\provider;

use pocketmine\level\Level;

interface DataProvider{
	public function getVersion();
	
	public function getCreatedTime();
	
	public function getBlock(Level $world, $x, $y, $z);
	
	public function getBlockOfUser($user);
	
	public function getChat();
}