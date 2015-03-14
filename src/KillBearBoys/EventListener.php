<?php

namespace KillBearBoys;

use KillBearBoys\provider\SQLite3DataProvider;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\entity\EntityShootBowEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\Player;
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

	public function onBlockBreak(BlockBreakEvent $event){
		if(!$this->plugin->co_enabled[strtolower($event->getPlayer()->getName())]){
			$this->provider->putLogs($event->getPlayer(), $event->getBlock(), EventListener::ACTION_BREAK);
		}else{
			$this->sendQueryResult($event->getPlayer(), $event->getBlock());
			$event->setCancelled();
		}
	}

	public function onBlockPlace(BlockPlaceEvent $event){
		if(!$this->plugin->co_enabled[strtolower($event->getPlayer()->getName())]){
			$this->provider->putLogs($event->getPlayer(), $event->getBlock(), EventListener::ACTION_PLACE);
		}else{
			$this->sendQueryResult($event->getPlayer(), $event->getBlock());
			$event->setCancelled();
		}
	}

	public function onTouch(PlayerInteractEvent $event){
		if($this->plugin->co_enabled[strtolower($event->getPlayer()->getName())]){
			$this->sendQueryResult($event->getPlayer(), $event->getBlock());
			$event->setCancelled();
		}
	}

	public function onPlayerJoin(PlayerJoinEvent $event){
		$this->plugin->co_enabled[strtolower($event->getPlayer()->getName())] = false;
	}

	public function onPlayerQuit(PlayerQuitEvent $event){
		if(isset($this->plugin->co_enabled[strtolower($event->getPlayer()->getName())])){
			unset($this->plugin->co_enabled[strtolower($event->getPlayer()->getName())]);
		}
	}

	public function onPlayerShoot(EntityShootBowEvent $event){
		$event->setCancelled();
	}

	public function sendQueryResult(Player $player, Block $block){
		$result = "This block hasn't been recorded.";
		if($this->provider->isBlockRecorded($block)){
			$action = "UNKNOWN";
			if($this->provider->getAction($block) == EventListener::ACTION_BREAK){
				$action = "BREAK";
			}else{
				$action = "PLACE";
			}
			$result = "---KBB Query Result---\n".
				"NAME: " . $this->provider->getName($block) . "\n" .
				"ACTION: " . $action . "\n" .
				"TIME: " . date("Y-m-d H:i:s", $this->provider->getTime($block));
		}
		$player->sendMessage($result);
	}

	public function close(){
		$this->provider->close();
	}
}
