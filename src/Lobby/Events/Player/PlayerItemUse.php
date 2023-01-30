<?php

namespace Lobby\Events\Player;

use Lobby\Constants\WorldConstant;
use Lobby\Forms\Form\BasicForm;
use Lobby\Managers\QueueManager;
use Lobby\Utils\Utils;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerItemUseEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\Server;

class PlayerItemUse implements Listener {

    public function PlayerItemUseEvent(PlayerItemUseEvent $event) {

        $item = $event->getItem();
        $player = $event->getPlayer();
        $playerName = $event->getPlayer()->getName();

        if($player->getWorld()->getFolderName() !== WorldConstant::WORLD_LOBBY) return;

        if($item->getId() == 324 && $item->getMeta() == 0){
            if(QueueManager::getCurrentQueue($player) !== false){
                QueueManager::removePlayerToQueue($player, QueueManager::getCurrentQueue($player));
                $player->sendMessage("§7» Vous avez bien quitté la §9file d'attente");
            }
        }

        if ($item->getId() === ItemIds::COMPASS){
            Utils::gameInv($player);
            return;
        }

        if ($item->getId() === ItemIds::FEATHER){
            $motions = clone $player->getMotion();

            $motions->x += $player->getDirectionVector()->getX() * 1.4;
            $motions->y += $player->getEyeHeight() * 0.4;
            $motions->z += $player->getDirectionVector()->getZ() * 1.4;

            $player->setMotion($motions);

        }

        if ($item->getId() === ItemIds::EMERALD){
            Server::getInstance()->dispatchCommand($player, "cosmetics");
        }

        if ($item->getId() === ItemIds::FISHING_ROD){
            Server::getInstance()->dispatchCommand($player, "pitchout join");
        }

        if($item->getId() === ItemIds::GOLDEN_SWORD){
            BasicForm::ffa($player);
        }
    }
}
