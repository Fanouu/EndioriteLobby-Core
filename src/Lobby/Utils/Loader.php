<?php

namespace Lobby\Utils;

use Lobby\Commands\joueur\CosmeticsCommand;
use Lobby\Commands\joueur\LobbyCommand;
use Lobby\Commands\joueur\TransferCommand;
use Lobby\Commands\staff\NPCCommand;
use Lobby\Entities\NPCEntity;
use Lobby\Entities\TitleEntity;
use Lobby\Events\Block\BlockBreak;
use Lobby\Events\Block\BlockPlace;
use Lobby\Events\Entity\EntityDamage;
use Lobby\Events\inventory\InventoryTransaction;
use Lobby\Events\Player\PlayerCreation;
use Lobby\Events\Player\PlayerItemUse;
use Lobby\Events\Player\PlayerJoin;
use Lobby\Events\Player\PlayerMove;
use Lobby\Events\Player\PlayerQuit;
use Lobby\Events\Player\PlayerChat;
use Lobby\Main;
use pocketmine\command\defaults\TransferServerCommand;
use pocketmine\entity\EntityDataHelper;
use pocketmine\entity\EntityFactory;
use pocketmine\entity\Human;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;
use pocketmine\world\World;

class Loader{

    public static function loadListener(){

        $listener = [
            new PlayerJoin(),
            new PlayerCreation(),
            new EntityDamage(),
            new BlockPlace(),
            new BlockBreak(),
            new PlayerQuit(),
            new PlayerItemUse(),
            new PlayerChat(),
            new InventoryTransaction(),
            new PlayerMove()
        ];

        foreach ($listener as $event){
            Server::getInstance()->getPluginManager()->registerEvents($event, Main::getInstance());
        }

        $count = count($listener);
        Main::getInstance()->getLogger()->info("§c{$count}§f event register !");
    }

    public static function loadCommands(){

        $commands = [
            new NPCCommand(),
            new CosmeticsCommand(),
            new LobbyCommand(),
            new TransferCommand()
        ];

        foreach($commands as $cmd){
            Main::getInstance()->getServer()->getCommandMap()->register("alias_command", $cmd);
        }

        $count = count($commands);
        Main::getInstance()->getLogger()->info("§c{$count}§f command register !");
    }

    public static function loadEntity(){
        EntityFactory::getInstance()->register(NPCEntity::class, function(World $world, CompoundTag $nbt): NPCEntity{
            return new NPCEntity(EntityDataHelper::parseLocation($nbt, $world), Human::parseSkinNBT($nbt), $nbt);
        }, ["npc_alias", "minecraft:alias_npc"]);

        EntityFactory::getInstance()->register(TitleEntity::class, function(World $world, CompoundTag $nbt): TitleEntity{
            return new TitleEntity(EntityDataHelper::parseLocation($nbt, $world), Human::parseSkinNBT($nbt), $nbt);
        }, ["endiorite_title", "minecraft:endiorite_title"]);
    }

}
