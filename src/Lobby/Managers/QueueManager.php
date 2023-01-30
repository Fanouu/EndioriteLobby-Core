<?php

namespace Lobby\Managers;

use Lobby\Utils\Utils;
use pocketmine\block\DiamondOre;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\item\VanillaItems;
use pocketmine\network\mcpe\protocol\types\command\CommandOutputMessage;
use pocketmine\player\Player;
use Ramsey\Collection\Queue;

class QueueManager{

    public static array $queue = [];

    public static function addPlayerToQueue(Player $player, string $queue){
        if(!isset(self::$queue[$queue])){
            self::$queue[$queue]["players"] = [];
        }
        if(count(self::$queue[$queue]["players"]) >= 50){
            $player->sendMessage("§7»§c La fille d'attente est pleine §450/50!");
            return;
        }
        self::$queue[$queue]["players"][] = $player->getName();

        $player->getInventory()->clearAll();
        $player->getArmorInventory()->clearAll();
        $door = ItemFactory::getInstance()->get(324);
        $door->setCustomName("§7» §cQuitté la fille d'attente");
        $player->getInventory()->setItem(8, $door);
    }

    public static function playerCountInQueue(string $queue){
        if(!isset(self::$queue[$queue]["players"])){
            return 0;
        }
        return count(self::$queue[$queue]["players"]);
    }

    public static function removePlayerToQueue(Player $player, string $queue){
        $array_search = array_search($player->getName(), QueueManager::getQueue()[$queue]["players"]);
        if($array_search !== false) {
            unset(self::$queue[$queue]["players"][$array_search]);
            QueueManager::reindex($queue);
        }

        $door = ItemFactory::getInstance()->get(324);
        $door->setCustomName("§7» §cQuitté la fille d'attente");
        $player->getInventory()->remove($door);

        Utils::addKitLobby($player);
    }

    public static function getCurrentQueue(Player $player){
        $return = false;
        foreach (self::$queue as $queueName => $queue){
            foreach ($queue["players"] as $index => $playerName){
                if($playerName === $player->getName()){
                    $return = $queueName;
                    break;
                }
            }
        }

        return $return;
    }

    public static function readyToQueue(string $playerName, string $queue){
        self::$queue[$queue]["playerToTransfer"] = $playerName;
    }

    public static function playerIsInQueue(Player $player, string $queue){
        if(in_array($player->getName(), self::$queue[$queue]["players"])){
            return true;
        }else return false;
    }

    public static function getQueue() : array{
        return self::$queue;
    }

    public static function reindex(string $queue){
        $newValues = array_values(self::$queue[$queue]["players"]);
        self::$queue[$queue]["players"] = $newValues;

    }
}
