<?php

namespace Lobby\Events\Entity;

use Lobby\Constants\PrefixConstant;
use Lobby\Constants\WorldConstant;
use Lobby\Entities\NPCEntity;
use Lobby\Managers\AntiMultiManager;
use Lobby\Utils\Utils;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDeathEvent;
use pocketmine\event\player\PlayerRespawnEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\ItemFactory;
use pocketmine\item\ItemIds;
use pocketmine\item\VanillaItems;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\Position;
use Ramsey\Uuid\Provider\Time\SystemTimeProvider;

class EntityDamage implements Listener {

    /**
     * @param EntityDamageByEntityEvent $event
     * @return void
     * @priority HIGHEST
     */
    public function entityDamageByEntityEvent(EntityDamageByEntityEvent $event){
        $damager = $event->getDamager();
        $entity = $event->getEntity();
        if (is_null($damager)) return;
        if (!$damager instanceof Player || !$entity instanceof Player) return;

        if ($entity->getWorld()->getFolderName() === "COMBO"){
            $event->setAttackCooldown(2);
            $event->setKnockBack(0.280);
        }

        if (str_contains($entity->getWorld()->getFolderName(), "pitchout")) return;

        if ($event->isCancelled()) return;

        if (!AntiMultiManager::getInstance()->existsPlayer($damager->getName()) && !AntiMultiManager::getInstance()->existsPlayer($entity->getName())){
            AntiMultiManager::getInstance()->setPlayerToPlayer($damager->getName(), $entity->getName());
            AntiMultiManager::getInstance()->setPlayerToPlayer($entity->getName(), $damager->getName());
            return;
        }

        if (AntiMultiManager::getInstance()->inFight($damager->getName())){
            if (!AntiMultiManager::getInstance()->hisPlayerTo($damager->getName(), $entity->getName())){
                $damager->sendPopup("§b" . PrefixConstant::arrow . "vous n'êtes pas en combat avec cette personne ! ");
                $event->cancel();
                return;
            }
        }

        if (AntiMultiManager::getInstance()->inFight($entity->getName())){
            if (!AntiMultiManager::getInstance()->hisPlayerTo($entity->getName(), $damager->getName())){
                $damager->sendPopup("§b" . PrefixConstant::arrow . "vous ne pouvez pas §cinterrompre§f le combat ! ");
                $event->cancel();
                return;
            }
        }

        if (!AntiMultiManager::getInstance()->inFight($damager->getName()) && !AntiMultiManager::getInstance()->inFight($entity->getName())){
            $lastPlayerD = AntiMultiManager::getInstance()->getPlayerTo($damager->getName());
            $lastPlayerE = AntiMultiManager::getInstance()->getPlayerTo($damager->getName());
            if (AntiMultiManager::getInstance()->existsPlayer($lastPlayerD)){
                AntiMultiManager::getInstance()->unsetPlayer($lastPlayerD);
            }
            if (AntiMultiManager::getInstance()->existsPlayer($lastPlayerE)){
                AntiMultiManager::getInstance()->unsetPlayer($lastPlayerE);
            }
            AntiMultiManager::getInstance()->setPlayerToPlayer($damager->getName(), $entity->getName());
            AntiMultiManager::getInstance()->setPlayerToPlayer($entity->getName(), $damager->getName());
            return;
        }

    }

    public function onRespawn(PlayerRespawnEvent $event){
        Utils::addKitLobby($event->getPlayer());
    }

    public function onBreak(EntityDamageEvent $event) {
        $entity = $event->getEntity();
        if ($entity->getWorld()->getFolderName() === WorldConstant::WORLD_LOBBY && !$entity instanceof NPCEntity) {
            $event->cancel();
        }
    }

    public function playerDeath(PlayerDeathEvent $event){
        $event->setDrops([]);
        if (str_contains($event->getPlayer()->getWorld()->getFolderName(), "pitchout")) return;
        $player = $event->getPlayer();
        $event->getPlayer()->teleport(new Position(-1, 60, -1, Server::getInstance()->getWorldManager()->getWorldByName("Lobby")), 0, 0);

        $cause = $event->getEntity()->getLastDamageCause();

        if($cause instanceof EntityDamageByEntityEvent){
            if($cause->getCause() === EntityDamageByEntityEvent::CAUSE_ENTITY_ATTACK){
                $damager = $cause->getDamager();
                if ($damager instanceof Player){
                    if ($damager->getWorld()->getFolderName() === "COMBO"){
                        Utils::ffaKit($damager, 1);
                    }else{
                        Utils::ffaKit($damager, 0);
                    }

                    if(AntiMultiManager::getInstance()->existsPlayer($damager->getName())){
                        AntiMultiManager::getInstance()->unsetPlayer($damager->getName());
                    }
                }
            }
        }

        if(AntiMultiManager::getInstance()->existsPlayer($player->getName())){
            AntiMultiManager::getInstance()->unsetPlayer($player->getName());
        }
    }
}
