<?php

namespace Lobby\Managers;

use Lobby\Constants\PrefixConstant;
use pocketmine\network\mcpe\protocol\ToastRequestPacket;
use pocketmine\player\Player;
use pocketmine\Server;

class PlayerManager extends Player{

    public $scoreboard = null;

    public function sendAchievement(string $title = "null", string $body = "null"){
        $packet = ToastRequestPacket::create($title, $body);
        $this->getNetworkSession()->sendDataPacket($packet);
    }

    public function removeScoreboard(): void{
        if (!is_null($this->scoreboard)) {
            $scoreboard = $this->scoreboard;
            $scoreboard->removeScoreboard();
        }else return;
    }

    public function sendScoreboard(): void {
        if (is_null($this->scoreboard)) {
            $this->scoreboard = new ScoreboardManager($this);
        }
        $scoreboard = $this->scoreboard;

        $scoreboard->addScoreboard("§lALIAS");
        $scoreboard->setLine(0, "§9§f━━━━━━━━━━━━━━━━━━━━━━━━━");
        $scoreboard->setLine(1, "§6§lPROFIL");
        $scoreboard->setLine(2, "  Pseudo §3" . PrefixConstant::arrow . "§b" . $this->getName());
        $scoreboard->setLine(3, "  Rank §3" . PrefixConstant::arrow . "§bJoueur");
        $scoreboard->setLine(4, "  Token §3" . PrefixConstant::arrow . "§b100 ");
        $scoreboard->setLine(5, "§f ");
        $scoreboard->setLine(6, "§6§lSERVEUR");
        $scoreboard->setLine(7, "  Lobby §3" . PrefixConstant::arrow . "§b#1");
        $scoreboard->setLine(8, "  Connectés §3" . PrefixConstant::arrow . "§b" . count(Server::getInstance()->getOnlinePlayers()));
        $scoreboard->setLine(9, "§f━━━━━━━━━━━━━━━━━━━━━━━━━");
        $scoreboard->setLine(10, "play.alias-uhc.best §b[S1]");

    }
}
