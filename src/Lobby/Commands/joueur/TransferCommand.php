<?php

namespace Lobby\Commands\joueur;

use Lobby\Utils\Utils;
use pocketmine\command\CommandSender;
use pocketmine\permission\DefaultPermissionNames;
use pocketmine\player\Player;

class TransferCommand extends \pocketmine\command\Command
{

    public function __construct()
    {
        parent::__construct("transfer", "transfer", "/transfer <server>", ["transferserver", "tra"]);
        $this->setPermission(DefaultPermissionNames::COMMAND_TRANSFERSERVER);
        $this->setPermissionMessage("§9Endiorite. §fvous n'avez pas les permissions requise !");
    }

    public function execute(CommandSender $sender, string $commandLabel, array $args)
    {
        if($sender instanceof Player){
            if(!$sender->hasPermission(DefaultPermissionNames::COMMAND_TRANSFERSERVER)) return false;

            if(!isset($args[0])){
                $sender->sendMessage("§cUsage: " . $this->getUsage());
                return false;
            }

            Utils::transfer($sender, $args[0]);
        }

        return true;
    }
}