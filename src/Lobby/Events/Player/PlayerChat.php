<?php

namespace Lobby\Events\Player;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerChatEvent;
use Lobby\Managers\PlayerManager;

class PlayerChat implements Listener{
    
    private static $coold = [];

    public function onChat(PlayerChatEvent $event): void{
        $player = $event->getPlayer();
        $pname = $player->getName();
        
        if(str_contains($event->getMessage(), "/f") && !$player->getServer()->isOp($player->getName())){ $event->cancel(); return;}
        
        if(isset(self::$coold[$pname]) && self::$coold[$pname] - time() > 0){
            $event->cancel();
            $player->sendMessage("§o§f[§6§l!!!§r§o]§r§f Éh Oh... §ftu écrit trop vite !");
        }else{
            self::$coold[$pname] = time() + 3;
        }
    }
}