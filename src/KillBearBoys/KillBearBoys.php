<?php

namespace KillBearBoys;

use pocketmine\plugin\PluginBase;

class KillBearBoys extends PluginBase{
	public $listener;

	public function onEnable(){
		@mkdir($this->getDataFolder());
		$this->listener = new EventListener($this);
		$this->getServer()->getPluginManager()->registerEvents($this->listener, $this);
	}
	
	public function onDisable(){
		$this->listener->close();
	}
}