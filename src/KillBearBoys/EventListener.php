<?php

namespace KillBearBoys;

use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\event\Listener;
use pocketmine\Player;
use pocketmine\level\Position;
use pocketmine\block\Block;

class EventListener implements Listener{
	const ACTION_PLACE = 0;
	const ACTION_BREAK = 1;
	
	private $plugin;
	private $database;
	private $insertPrepare;
	private $selectPrepare;

	public function __construct(KillBearBoys $plugin){
		$this->plugin = $plugin;
		if(!file_exists($this->plugin->getDataFolder() . "logs.db")){
			$this->database = new \SQLite3($this->plugin->getDataFolder() . "logs.db", SQLITE3_OPEN_READWRITE | SQLITE3_OPEN_CREATE);
			$resource = $this->plugin->getResource("sqlite3.sql");
			$this->database->exec(stream_get_contents($resource));
		}else{
			$this->database = new \SQLite3($this->plugin->getDataFolder() . "logs.db", SQLITE3_OPEN_READWRITE);
		}
		$this->insertPrepare = $this->database->prepare("INSERT INTO logs VALUES(NULL, :level, :x, :y, :z, :name, :blockId, :meta, :action, :time)");
		$this->selectPrepare = $this->database->prepare("SELECT * FROM logs WHERE level = :level AND x = :x AND y = :y AND z = :z ORDER BY id DESC");
	}

	public function onBlockBreak(BlockBreakEvent $event){
		$this->insertDatabase(EventListener::ACTION_BREAK, $event->getPlayer(), $event->getBlock());
	}

	public function onBlockPlace(BlockPlaceEvent $event){
		$this->insertDatabase(EventListener::ACTION_PLACE, $event->getPlayer(), $event->getBlock());
	}
	
	public function close(){
		$this->insertPrepare->close();
		$this->selectPrepare->close();
		$this->database->close();
	}
	
	private function insertDatabase($action, $player, $block){
		$this->insertPrepare->bindValue(":level", $block->getLevel()->getName(), SQLITE3_TEXT);
		$this->insertPrepare->bindValue(":x", $block->x, SQLITE3_INTEGER);
		$this->insertPrepare->bindValue(":y", $block->y, SQLITE3_INTEGER);
		$this->insertPrepare->bindValue(":z", $block->z, SQLITE3_INTEGER);
		$this->insertPrepare->bindValue(":name", $player->getName(), SQLITE3_TEXT);
		$this->insertPrepare->bindValue(":blockId", $block->getId(), SQLITE3_INTEGER);
		$this->insertPrepare->bindValue(":meta", $block->getDamage(), SQLITE3_INTEGER);
		$this->insertPrepare->bindValue(":action", $action, SQLITE3_INTEGER);
		$this->insertPrepare->bindValue(":time", time(), SQLITE3_INTEGER);
		$this->insertPrepare->execute();
	}
}
