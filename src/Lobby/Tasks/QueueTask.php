<?php

namespace Lobby\Tasks;

use Lobby\Entities\NPCEntity;
use Lobby\Main;
use Lobby\Managers\QueueManager;
use pocketmine\scheduler\CancelTaskException;
use pocketmine\scheduler\ClosureTask;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class QueueTask extends Task {

    /**
     * @inheritDoc
     */
    public function onRun() : void {
        foreach (QueueManager::getQueue() as $queueName => $queue){
            if(!isset($queue["playerToTransfer"])){
                $queue["playerToTransfer"] = null;
            }

            if(count($queue["players"]) >= 1){
                if(is_null($queue["playerToTransfer"])){
                    $firstPlayer = $queue["players"][0];
                    QueueManager::readyToQueue($firstPlayer, $queueName);
                    Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($firstPlayer, $queueName){
                        QueueManager::$queue[$queueName]["playerToTransfer"] = null;
                        $array_search = array_search($firstPlayer, QueueManager::getQueue()[$queueName]["players"]);
                        if($array_search !== false){
                            if($player = Server::getInstance()->getPlayerExact($firstPlayer)){
                                NPCEntity::transfer($player, $queueName);
                                QueueManager::removePlayerToQueue($player, $queueName);
                            }
                        }
                    }), 5*20);
                }


                $countPlayers = count($queue["players"]);
                foreach ($queue["players"] as $index => $playerName){
                    if($player = Server::getInstance()->getPlayerExact($playerName)){
                        $player->sendPopup("§7» §fVous êtes dans la file d'attente...\n§7» §fVotre position est §9" . $index + 1 . "§f/§1" . $countPlayers);
                    }
                }
            }
        }
    }

}
