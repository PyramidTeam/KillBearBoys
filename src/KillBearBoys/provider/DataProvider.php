<?php

namespace KillBearBoys\provider;

use pocketmine\block\Block;
use pocketmine\Player;

interface DataProvider{

	public function getTime(Block $block);
	
	public function getName(Block $block);
	
	public function getAction(Block $block);
	
	public function getLogs(Block $block);

	public function putLogs(Player $player, Block $block, $action);
	
	public function isBlockRecorded(Block $block);

	public function close();
}