<?php

namespace Lobby\Tasks;

use Lobby\Constants\PrefixConstant;
use Lobby\Main;
use pocketmine\scheduler\Task;
use pocketmine\Server;

class UpdateTask extends Task
{

    public static $time = 0;
    /**
     * @inheritDoc
     */
    public function onRun(): void
    {
        Main::getInstance()->getServer()->getAsyncPool()->submitTask(new UpdatePlayers());
        $wd = Server::getInstance()->getWorldManager()->getWorldByName("Lobby");
        if (self::$time >= 5*2){
            Server::getInstance()->broadcastMessage("§b" . PrefixConstant::arrow . "Tester notre nouveau mini-jeu, §7PitchOut Solo§f en §ebêta§f !");
            self::$time = 0;
        }
        self::$time++;
    }
}