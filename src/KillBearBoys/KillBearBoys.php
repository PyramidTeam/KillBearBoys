<?php

namespace KillBearBoys;

use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;

class KillBearBoys extends PluginBase{
	public $listener;
	public $co_enabled = false;

	public function onEnable(){
		@mkdir($this->getDataFolder());
		$this->listener = new EventListener($this);
		$this->getServer()->getPluginManager()->registerEvents($this->listener, $this);
	}
	
	public function onDisable(){
		$this->listener->close();
	}

	public function onCommand(CommandSender $commandSender, Command $command, $label, array $args){
		if($command->getName() == "co"){
			$this->co_enabled = !$this->co_enabled;
			if($this->co_enabled){
				$commandSender->sendMessage("Query mode on");
			}else{
				$commandSender->sendMessage("Query mode off");
			}
		}
		return true;
	}
}