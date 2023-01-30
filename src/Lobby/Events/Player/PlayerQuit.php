<?php

namespace Lobby\Events\Player;

use Lobby\Constants\MessageConstant;
use Lobby\Constants\PrefixConstant;
use Lobby\Managers\QueueManager;
use Lobby\Utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerQuitEvent;
use pocketmine\math\Vector3;
use pocketmine\Server;
use pocketmine\world\Position;
use Lobby\Main;

class PlayerQuit implements Listener{

    public function PlayerQuit(PlayerQuitEvent $event){
        $player_name = $event->getPlayer()->getName();
        $player = $event->getPlayer();
        $event->setQuitMessage("");

        Server::getInstance()->broadcastMessage("§b" . PrefixConstant::arrow . str_replace(["{player}"], [$player_name], MessageConstant::player_quit));
        unset(Main::$connected[$player_name]);

        if(QueueManager::getCurrentQueue($player) !== false){
            QueueManager::removePlayerToQueue($player, QueueManager::getCurrentQueue($player));
        }
    }
}
