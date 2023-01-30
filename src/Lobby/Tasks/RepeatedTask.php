<?php

namespace Lobby\Tasks;

use Lobby\Constants\PrefixConstant;
use Lobby\Constants\WorldConstant;
use Lobby\Events\Player\PlayerJoin;
use Lobby\Managers\ScoreboardManager;
use pocketmine\network\mcpe\protocol\PlaySoundPacket;
use pocketmine\player\Player;
use pocketmine\scheduler\Task;
use pocketmine\Server;
use pocketmine\world\World;
use Lobby\Main;

class RepeatedTask extends Task {

    public array $scoreboard = [];
    public static array $players = [];

    public function onRun() : void {

        foreach (Main::$connected as $index => $value){
            if($player = Server::getInstance()->getPlayerExact($index)){
                if($player->getWorld()->getFolderName() === WorldConstant::WORLD_LOBBY && !is_null($player->getNetworkSession())){
                    $player->sendScoreboard();
                }else{
                    $player->removeScoreboard();
                }
            }
        }
    }

}
