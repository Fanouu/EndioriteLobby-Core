<?php

namespace Lobby\Events\Player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerMoveEvent;
use pocketmine\math\Vector3;
use const pocketmine\RESOURCE_PATH;

class PlayerMove implements Listener
{

    public function onMove(PlayerMoveEvent $event){
        $player = $event->getPlayer();

        if ($event->getTo()->getFloorY() == 60 && $event->getTo()->getFloorZ() == 1){
            if (in_array($event->getTo()->getFloorX(), [0, -1, -2])){

                $motions = clone $player->getMotion();

                $motions->x += $player->getDirectionVector()->getX() * 2;
                $motions->y += $player->getEyeHeight() * 0.6;
                $motions->z += $player->getDirectionVector()->getZ() * 2;

                $player->setMotion($motions);

            }
        }
    }

}