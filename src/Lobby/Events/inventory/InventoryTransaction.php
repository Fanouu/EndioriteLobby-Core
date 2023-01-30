<?php

namespace Lobby\Events\inventory;

use Lobby\Constants\WorldConstant;
use pocketmine\event\inventory\InventoryTransactionEvent;
use pocketmine\event\Listener;
use pocketmine\item\ItemIds;

class InventoryTransaction implements Listener
{

    public function onTransaction(InventoryTransactionEvent $event){
        $player = $event->getTransaction()->getSource();
        if ($player->getWorld()->getFolderName() === WorldConstant::WORLD_LOBBY){
            $event->cancel();
        }
    }

}