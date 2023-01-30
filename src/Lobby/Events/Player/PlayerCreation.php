<?php

namespace Lobby\Events\Player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerCreationEvent;
use Lobby\Managers\PlayerManager;

class PlayerCreation implements Listener{

    public function onCreate(PlayerCreationEvent $event){
        $event->setPlayerClass(PlayerManager::class);
    }
}
