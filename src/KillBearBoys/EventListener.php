<?php

namespace KillBearBoys;

use KillBearBoys\provider\SQLite3DataProvider;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\block\BlockEvent;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\level\Position;
use pocketmine\block\Block;

class EventListener implements Listener{
	const ACTION_PLACE = 0;
	const ACTION_BREAK = 1;

	private $provider;
	private $plugin;

	public function __construct(KillBearBoys $plugin){
		$this->plugin = $plugin;
		$this->provider = new SQLite3DataProvider($plugin);
	}

	public function onCommand(CommandSender $commandSender, Command $command, $label, array $args){
		$this->plugin->getLogger()->info("6666");
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

	public function onBlockBreak(BlockBreakEvent $event){
		if(!$this->plugin->co_enabled){
			$this->provider->putLogs($event->getPlayer(), $event->getBlock(), EventListener::ACTION_BREAK);
		}else{
			$action = "UNKNOWN";
			if($this->provider->getAction($event->getBlock()) == EventListener::ACTION_BREAK){
				$action = "BREAK";
			}else{
				$action = "PLACE";
			}
			$event->getPlayer()->sendMessage("---KBB Query---\n".
				"NAME: " . $this->provider->getName($event->getBlock()) . "\n" .
				"ACTION: " . $action . "\n" .
				"TIME: " . $this->provider->getTime($event->getBlock())
			);
			$event->setCancelled();
		}
	}

	public function onBlockPlace(BlockPlaceEvent $event){
		if(!$this->plugin->co_enabled){
			$this->provider->putLogs($event->getPlayer(), $event->getBlock(), EventListener::ACTION_PLACE);
		}else{
			$action = "UNKNOWN";
			if($this->provider->getAction($event->getBlock()) == EventListener::ACTION_BREAK){
				$action = "BREAK";
			}else{
				$action = "PLACE";
			}
			$event->getPlayer()->sendMessage("---KBB Query---\n".
				"NAME: " . $this->provider->getName($event->getBlock()) . "\n" .
				"ACTION: " . $action . "\n" .
				"TIME: " . $this->provider->getTime($event->getBlock())
			);
			$event->setCancelled();
		}
	}

	public function close(){
		$this->provider->close();
	}
}
