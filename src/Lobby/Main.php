<?php

namespace Lobby;

use Lobby\Managers\AntiMultiManager;
use Lobby\Tasks\QueueTask;
use Lobby\Tasks\UpdateTask;
use Lobby\Utils\Loader;
use Lobby\Tasks\RepeatedTask;
use Lobby\Utils\Utils;
use muqsit\invmenu\InvMenuHandler;
use pocketmine\event\Listener;
use pocketmine\event\server\QueryRegenerateEvent;
use pocketmine\network\mcpe\protocol\LevelEventPacket;
use pocketmine\plugin\PluginBase;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use pocketmine\world\World;
use vezdehod\packs\ContentFactory;
use vezdehod\packs\PluginContent;

class Main extends PluginBase implements Listener {
    use SingletonTrait;

    public static array $connected = [];
    public static ?PluginContent $content;

    protected function onLoad(): void
    {
        new AntiMultiManager();
    }

    protected function onEnable() : void {
        if(!InvMenuHandler::isRegistered()){
            InvMenuHandler::register($this);
        }

        self::$instance = $this;
        $this->saveResource("cached.yml");
        $this->saveResource("texture.png");
        $this->saveResource("endiorite_title.geo.json");
        $this->getScheduler()->scheduleRepeatingTask(new UpdateTask(), 30 * 20);

        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        Loader::loadListener();
        Loader::loadCommands();
        Loader::loadEntity();
        $this->getScheduler()->scheduleRepeatingTask(new QueueTask(), 20);
        $this->getServer()->getWorldManager()->getDefaultWorld()->setTime(World::TIME_DAY);
        $this->getServer()->getWorldManager()->getDefaultWorld()->stopTime();

        Server::getInstance()->getNetwork()->setName("§9§lEndiorite §fV3");
        Server::getInstance()->getWorldManager()->loadWorld("FFA2");
        Server::getInstance()->getWorldManager()->loadWorld("COMBO");
        var_dump($this->getDataFolder());
        Server::getInstance()->getWorldManager()->getDefaultWorld();

    }

    public function queryRegenerate(QueryRegenerateEvent $event) : void{
        $event->getQueryInfo()->setPlayerCount(Utils::getCachedPlayers()+ count($this->getServer()->getOnlinePlayers()));
        $event->getQueryInfo()->setMaxPlayerCount(Utils::getCachedMaxPlayers()+ count($this->getServer()->getOnlinePlayers()));
    }

}
