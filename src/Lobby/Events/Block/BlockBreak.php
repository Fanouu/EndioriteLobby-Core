<?php

namespace Lobby\Events\Block;

use Lobby\Constants\WorldConstant;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\Listener;
use pocketmine\player\GameMode;

class BlockBreak implements Listener {

    public function onBreak(BlockBreakEvent $event){
        $player = $event->getPlayer();
        if($player->getWorld()->getFolderName() === WorldConstant::WORLD_LOBBY || $player->getWorld()->getFolderName() === "FFA2" || $player->getWorld()->getFolderName() === "COMBO"){
            if(!$player->isCreative()){
                $event->cancel();
            }
        }
    }

}
