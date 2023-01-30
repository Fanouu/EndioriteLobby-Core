<?php

namespace Lobby\Entities;

use Lobby\Constants\MessageConstant;
use Lobby\Constants\PrefixConstant;
use Lobby\Main;
use Lobby\Managers\PlayerManager;
use Lobby\Managers\QueueManager;
use Lobby\Utils\Utils;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;

class NPCEntity extends Human {

    private ?string $npc_type = null;

    public function __construct(Location $location, Skin $skin, CompoundTag $nbt) {
        parent::__construct($location, $skin, $nbt);
        $this->setNameTagAlwaysVisible();
        $this->setImmobile();
    }

    public function onUpdate(int $currentTick) : bool {
        switch ($this->getType()){
            case "arazia":
                $this->setNameTag("§a" . PrefixConstant::arrow . "§l§fFaction §1Arazia§r\n§aEn attente ". QueueManager::playerCountInQueue($this->getType()) ."/50\n§c" . Utils::getPlayersCount("arazia") . " §7Joueurs\n§eClick ici");
            break;

            case "minestia":
                $this->setNameTag("§a" . PrefixConstant::arrow . "§l§fFaction §1Minestia§r\n§aEn attente ". QueueManager::playerCountInQueue($this->getType()) ."/50\n§c" . Utils::getPlayersCount("minestia") . " §7Joueurs\n§eClick ici");
            break;

            case "minage":
                $this->setNameTag("§8" . PrefixConstant::arrow . "§l§fServeur §1Minage§r\n§aEn attente ". QueueManager::playerCountInQueue($this->getType()) ."/50\n§c". Utils::getPlayersCount("minage") ." §7Joueurs\n§eClick ici");
            break;

            case "kitmap":
                $this->setNameTag("§8" . PrefixConstant::arrow . "§l§fKit§1Map§r\n§aEn attente ". QueueManager::playerCountInQueue($this->getType()) ."/50\n§c". Utils::getPlayersCount("kitmap") ." §7Joueurs\n§eClick ici");
            break;

            case "alias":
                $this->setNameTag("§8" . PrefixConstant::arrow . "§l§6Alias UHC§r\n§c". Utils::getPlayersCount("alias") ."/200 §7Joueurs\n§eClick ici");
            break;
            case "practice":
                $this->setNameTag("§8" . PrefixConstant::arrow . "§l§cNoLook Practice §eSoon§r\n§c". 0 ."/200 §7Joueurs\n§eClick ici");
            break;
        }
        return parent::onUpdate($currentTick);
    }

    protected function initEntity(CompoundTag $nbt) : void {
        parent::initEntity($nbt);
        $this->setNameTagAlwaysVisible();
        $this->setImmobile();
        $this->npc_type = $nbt->getString("npc_type_", "");
    }

    public function saveNBT() : CompoundTag {
        $nbt = parent::saveNBT();
        $nbt->setString("npc_type_", $this->npc_type);

        return $nbt;
    }

    public function setType(string $type){
        $this->npc_type = $type;
        $nbt = $this->saveNBT();
        $nbt->setString("npc_type_", $type);
    }

    public function getType(){
        $nbt = $this->saveNBT();
        return $nbt->getString("npc_type_");
    }

    public function onInteract(Player $player, Vector3 $clickPos) : bool {
        if(QueueManager::getCurrentQueue($player) === false){
            QueueManager::addPlayerToQueue($player, $this->getType());
            $player->sendMessage("§7»§f Vous avez bien rejoins la §9file d'attente\n§7» §o§fClick droit avec la porte dans votre inventaire pour quitté la fille d'attente");
        }else{
            $player->sendMessage("§7»§f Vous êtes déjà dans une §9file d'attente§f");
        }
        return parent::onInteract($player, $clickPos);
    }

    public function attack(EntityDamageEvent $source) : void {
        if($source instanceof EntityDamageByEntityEvent){
            $player = $source->getDamager();
            if($player instanceof PlayerManager){
                if($player->getInventory()->getItemInHand()->getId() === 511){
                    $this->kill();
                    $this->flagForDespawn();
                    $this->close();
                    return;
                }

                if(QueueManager::getCurrentQueue($player) === false){
                    QueueManager::addPlayerToQueue($player, $this->getType());
                    $player->sendMessage("§7»§f Vous avez bien rejoins la §9file d'attente\n§7» §o§fClick droit avec la porte dans votre inventaire pour quitté la fille d'attente");
                }else{
                    $player->sendMessage("§7»§f Vous êtes déjà dans une §9file d'attente§f");
                }
            }
        }
        $source->cancel();
    }

    public static function transfer(Player $player, string $serverName){
        switch ($serverName){
            case "arazia":
                NPCEntity::transferWithIp("arazia", "19135", "§fFaction §1Arazia", $player);
                break;

            case "minestia":
                NPCEntity::transferWithIp("minestia", "19134", "§fFaction §1Minestia", $player);
                break;

            case "minage":
                $rdm = mt_rand(2,2);
                $port = match ($rdm){
                    1 => "19136",
                    2 => "19137"
                };

                NPCEntity::transferWithIp("minage" . $rdm, $port, "§fMinage n°§1" . $rdm, $player);
                break;

            case "kitmap":
                NPCEntity::transferWithIp("endiorite.com", "19133", "§1KitMap", $player);
            break;
        }
    }

    public static function transferWithIp(string $server, string $port, string $serverName,  Player $player){
        if(Utils::checkServer($server, $port)){
            $pname = $player->getName();
            $player->sendMessage("§8" . PrefixConstant::arrow . "§r" . str_replace("{server}", $serverName, MessageConstant::in_transfer));
            Main::getInstance()->getScheduler()->scheduleDelayedTask(new ClosureTask(function () use ($pname, $server, $port, $serverName){
                if($p = Server::getInstance()->getPlayerExact($pname)){
                    if (str_contains($serverName, "KitMap")){
                        $p->transfer($server, 19133);
                    }
                    $p->transfer($server);
                }
            }), 3*20);
        }else{
           $player->sendMessage(PrefixConstant::error_prefix . str_replace("{server}", $serverName, MessageConstant::server_is_offline));
        }
    }

}
