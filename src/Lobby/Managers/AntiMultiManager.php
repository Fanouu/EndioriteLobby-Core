<?php

namespace Lobby\Managers;

use pocketmine\utils\SingletonTrait;

class AntiMultiManager
{
    use SingletonTrait;

    public function __construct(public array $players = []){
        self::setInstance($this);
    }

    public function setPlayerToPlayer(string $playerName, string $toPlayer){
        $this->players[$playerName] = ["player" => $toPlayer, "time" => time() + 10];
    }

    public function removePlayerToPlayer(string $playerName){
        unset($this->players[$playerName]);
    }

    public function existsPlayer(string $playerName){
        return isset($this->players[$playerName]);
    }

    public function inFight(string $playerName){
        if (!$this->existsPlayer($playerName)) return false;
        if($this->players[$playerName]["time"] - time() <= 0){
            return false;
        }else{
            return true;
        }
    }

    public function getPlayerTo(string $playerName){
        if (!$this->existsPlayer($playerName)) return false;
        return $this->players[$playerName]["player"];
    }

    public function unsetPlayer(string $playerName){
        unset($this->players[$playerName]);
    }

    public function hisPlayerTo(string $playerDamaged, string $damager){
        if (!$this->existsPlayer($playerDamaged)) return false;
        if ($this->players[$playerDamaged]["player"] === $damager){
            return true;
        }

        return false;
    }

}