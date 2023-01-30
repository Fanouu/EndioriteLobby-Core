<?php

namespace Lobby\Commands\staff;

use Lobby\Constants\MessageConstant;
use Lobby\Constants\PrefixConstant;
use Lobby\Entities\NPCEntity;
use Lobby\Entities\TitleEntity;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\command\utils\CommandException;
use pocketmine\entity\Location;
use pocketmine\lang\Translatable;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;

class NPCCommand extends Command{

    public function __construct() {
        parent::__construct("npc", "", "/npc <name> <type>", []);
        $this->setPermission("npc.use");
    }

    public function execute(CommandSender|Player $sender, string $commandLabel, array $args): void{
        if(!$sender->hasPermission("npc.use")) {
            $sender->sendMessage(MessageConstant::no_permission);
            return;
        }

        if(!isset($args[0])) {
            $sender->sendMessage(MessageConstant::incorrect_usage . $this->getUsage());
            return;
        }

        if($args[0] === "tile"){
            $entity = new TitleEntity($sender->getLocation(), TitleEntity::getSkinT());
            $entity->setScale($args[1] ?? 5);
            $entity->spawnToAll();
            return;
        }
        if(!isset($args[1])) {
            $sender->sendMessage(MessageConstant::incorrect_usage . $this->getUsage());
            return;
        }

        $nbt = CompoundTag::create()
            ->setString("npc_type_", $args[1]);
        $entity = new NPCEntity($sender->getLocation(), $sender->getSkin(), $nbt);
        $entity->spawnToAll();

        $sender->sendMessage(PrefixConstant::succes_prefix . "§bVous avez bien fait spawn un §eNPC §bde type §e" . $args[1] . "§b!");
    }

}
