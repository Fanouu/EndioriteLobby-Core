<?php

namespace Lobby\Events\Player;

use Lobby\Constants\MessageConstant;
use Lobby\Constants\PrefixConstant;
use Lobby\Constants\WorldConstant;
use Lobby\Main;
use Lobby\Utils\Utils;
use pocketmine\data\bedrock\BiomeIds;
use pocketmine\event\entity\EntityTeleportEvent;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerDropItemEvent;
use pocketmine\event\player\PlayerJoinEvent;
use pocketmine\event\world\ChunkLoadEvent;
use pocketmine\event\world\WorldLoadEvent;
use pocketmine\item\enchantment\EnchantmentInstance;
use pocketmine\item\enchantment\VanillaEnchantments;
use pocketmine\item\GoldenApple;
use pocketmine\item\VanillaItems;
use pocketmine\math\Vector3;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\network\mcpe\protocol\types\Enchant;
use pocketmine\network\mcpe\protocol\types\LevelEvent;
use pocketmine\player\GameMode;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\world\biome\Biome;
use pocketmine\world\Position;

class PlayerJoin implements Listener{

    public function PlayerJoin(PlayerJoinEvent $event){
        $player_name = $event->getPlayer()->getName();
        $player = $event->getPlayer();
        $event->setJoinMessage("");

        Server::getInstance()->broadcastMessage("§b" . PrefixConstant::arrow . str_replace(["{player}"], [$player_name], MessageConstant::player_join));
        $player->sendMessage("\n     §eBienvenue sur §9Endiorite Faction \n \n§7Vous êtes actuellement connecté sur le §cLobby #1\n§7A l'aide des §9PNJ §7et de la §9boussole §7vous pouvez intéragir entre les différents §1serveur§7\n \nBon jeu sur §9Endiorite §7!");
        $player->sendPopup("§econnected from §fLobby §1#1");
        $player->sendMessage("§b" . PrefixConstant::arrow . "Tester notre nouveau mini-jeu, §7PitchOut Solo§f en §ebêta§f !");

        if(!$player->hasPlayedBefore()){
           Utils::sendAchievements($player, MessageConstant::achievement_title, MessageConstant::achievement_firstjoin);
        }

        $player->teleport(Server::getInstance()->getWorldManager()->getWorldByName(WorldConstant::WORLD_LOBBY)->getSpawnLocation());

        Utils::addKitLobby($player);
        $player->setGamemode(GameMode::SURVIVAL());
    }

    public function playerDrop(PlayerDropItemEvent $event){
        if($event->getPlayer()->getWorld()->getFolderName() === WorldConstant::WORLD_LOBBY && $event->getPlayer()->getWorld()->getFolderName() === WorldConstant::WORLD_LOBBY){
            $event->cancel();
        }
    }

    public function onTeleport(EntityTeleportEvent $event){
        $player = $event->getEntity();
        if (!$player instanceof Player) return;
        if (str_contains($event->getTo()->getWorld()->getFolderName(), "pitchout")) return;
        if ($event->getFrom()->getWorld()->getFolderName() === WorldConstant::WORLD_LOBBY) return;

        if ($event->getTo()->getWorld()->getFolderName() === WorldConstant::WORLD_LOBBY){
            Utils::addKitLobby($player);
            $player->setGamemode(GameMode::SURVIVAL());
        }
    }
}
