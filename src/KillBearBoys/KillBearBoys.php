<?php

namespace KillBearBoys;

use pocketmine\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\utils\TextFormat;

class KillBearBoys extends PluginBase{
	public $listener;
	public $co_enabled = array();

	public function onEnable(){
		@mkdir($this->getDataFolder());
		$this->listener = new EventListener($this);
		$this->getServer()->getPluginManager()->registerEvents($this->listener, $this);
	}
	
	public function onDisable(){
		$this->listener->close();
	}

	public function onCommand(CommandSender $commandSender, Command $command, $label, array $args){
		if(!$commandSender instanceof Player){
			$commandSender->sendMessage("Only players can use this command.");
		}
		if($command->getName() == "co"){
			if($this->co_enabled[strtolower($commandSender->getName())]){
				$this->co_enabled[strtolower($commandSender->getName())] = false;
				$commandSender->sendMessage("Query mode off");
				$this->getLogger()->info(TextFormat::GREEN . $commandSender->getName() . " disabled query mode.");
			}else{
				$this->co_enabled[strtolower($commandSender->getName())] = true;
				$commandSender->sendMessage("Query mode on\nTouch a block or place a block to query");
				$this->getLogger()->info(TextFormat::GREEN . $commandSender->getName() . " enabled query mode.");
			}
		}
		return true;
	}
}