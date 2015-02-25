<?php

namespace KillBearBoys\provider;

interface DataProvider{
	public function getVersion();
	
	public function getCreatedTime();
	
	public function getBlock($world, $x, $y, $z);
	
	public function getBlockOfUser($user);
	
	public function getChat();
}