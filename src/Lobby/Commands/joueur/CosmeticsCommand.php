<?php

namespace Lobby\Commands\joueur;

use Lobby\Constants\MessageConstant;
use Lobby\Constants\PrefixConstant;
use Lobby\Entities\NPCEntity;
use Lobby\Forms\Form\BasicForm;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;

class CosmeticsCommand extends Command
{

    public function __construct() {
        parent::__construct("cosmetics", "", "/cosmetics", ["cosmetic", "cosmetique"]);
    }

    public function execute(CommandSender|Player $sender, string $commandLabel, array $args): void{
        if (!$sender instanceof Player) return;

        BasicForm::test($sender);
    }
}