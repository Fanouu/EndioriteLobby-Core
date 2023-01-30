<?php

namespace Lobby\Commands\joueur;

use Lobby\Constants\PrefixConstant;
use Lobby\Constants\WorldConstant;
use Lobby\Forms\Form\BasicForm;
use Lobby\Managers\AntiMultiManager;
use Lobby\Utils\Utils;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\player\Player;
use pocketmine\Server;

class LobbyCommand extends Command
{

    public function __construct() {
        parent::__construct("lobby", "", "/lobby", ["lobby", "spawn", "hub"]);
    }

    public function execute(CommandSender|Player $sender, string $commandLabel, array $args): void{
        if (!$sender instanceof Player) return;

        if (str_contains($sender->getWorld()->getFolderName(), "pitchout")){
            $sender->sendMessage("§b" . PrefixConstant::arrow . "vous ne pouvez pas utiliser cette commande en §cpitchout");
            return;
        }

        if (AntiMultiManager::getInstance()->existsPlayer($sender->getName()) && AntiMultiManager::getInstance()->inFight($sender->getName())){
            $sender->sendMessage("§b" . PrefixConstant::arrow . "vous êtes en §cfight §f!");
            return;
        }

        $sender->teleport(Server::getInstance()->getWorldManager()->getWorldByName(WorldConstant::WORLD_LOBBY)->getSpawnLocation());
    }
}