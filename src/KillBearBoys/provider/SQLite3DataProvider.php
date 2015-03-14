<?php

namespace KillBearBoys\provider;

use KillBearBoys\KillBearBoys;

use pocketmine\block\Block;

class SQLite3DataProvider implements DataProvider{

    protected $plugin;
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
        $this->insertPrepare = $this->database->prepare("INSERT INTO blocks VALUES(:level, :x, :y, :z, :name, :blockId, :meta, :action, :time)");
        $this->selectPrepare = $this->database->prepare("SELECT * FROM blocks WHERE level = :level AND x = :x AND y = :y AND z = :z ORDER BY blockid DESC");
    }

    public function getTime(Block $block){
        if($this->getLogs($block) != null){
            return $this->getLogs($block)["time"];
        }
        return null;
    }

    public function getName(Block $block){
        if($this->getLogs($block) != null){
            return $this->getLogs($block)["name"];
        }
        return null;
    }

    public function getAction(Block $block){
        if($this->getLogs($block) != null){
            return $this->getLogs($block)["action"];
        }
        return null;
    }

    public function getLogs(Block $block){
        $this->selectPrepare->bindValue(":level", $block->getLevel()->getName(), SQLITE3_TEXT);
        $this->selectPrepare->bindValue(":x", $block->getX(), SQLITE3_INTEGER);
        $this->selectPrepare->bindValue(":y", $block->getY(), SQLITE3_INTEGER);
        $this->selectPrepare->bindValue(":z", $block->getZ(), SQLITE3_INTEGER);
        $result = $this->selectPrepare->execute();
        if($result instanceof \SQLite3Result){
            $data = $result->fetchArray(SQLITE3_ASSOC);
            $result->finalize();
            if(isset($data["name"]) and isset($data["time"]) and isset($data["action"])){
                return $data;
            }
        }
        return null;
    }

    public function putLogs(\pocketmine\Player $player,Block $block, $action){
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

    public function isBlockRecorded(Block $block){
        return $this->getLogs($block) != null;
    }

    public function close(){
        $this->insertPrepare->close();
        $this->selectPrepare->close();
        $this->database->close();
    }
}