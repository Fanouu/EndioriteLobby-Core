<?php

namespace Lobby\Entities;

use Lobby\Constants\MessageConstant;
use Lobby\Constants\PrefixConstant;
use Lobby\Main;
use Lobby\Managers\PlayerManager;
use Lobby\Managers\QueueManager;
use Lobby\Utils\Utils;
use pocketmine\entity\Entity;
use pocketmine\entity\Human;
use pocketmine\entity\Location;
use pocketmine\entity\Skin;
use pocketmine\event\entity\EntityDamageByEntityEvent;
use pocketmine\event\entity\EntityDamageEvent;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\player\Player;
use pocketmine\scheduler\ClosureTask;
use pocketmine\Server;

class TitleEntity extends Human {

    private ?string $npc_type = null;

    public function __construct(Location $location, Skin $skin, CompoundTag $nbt = null) {
        parent::__construct($location, $skin, $nbt);
        $this->setNameTagAlwaysVisible(false);
        $this->gravityEnabled = false;
        $this->setScale(3.5);
        $this->setImmobile();
    }

    protected function initEntity(CompoundTag $nbt) : void {
        parent::initEntity($nbt);
        $this->setNameTagAlwaysVisible(false);
        $this->gravityEnabled = false;
        $this->setScale(3.5);
        $this->setImmobile();
    }

    public function attack(EntityDamageEvent $source) : void {
        if($source instanceof EntityDamageByEntityEvent){
            $player = $source->getDamager();
            if($player instanceof PlayerManager){
                if($player->getInventory()->getItemInHand()->getId() === 511){
                    $this->kill();
                    $this->flagForDespawn();
                    $this->close();
                    return;
                }
            }
        }
        $source->cancel();
    }

    public static function getSkinT() : Skin{
        $data = self::PNGtoBYTES(Main::getInstance()->getDataFolder() . "texture.png");
        $geometry = file_get_contents(Main::getInstance()->getDataFolder() . "endiorite_title.geo.json");
        return new Skin("Title", $data, "","geometry.endiorite_title", $geometry);
    }

    public static function PNGtoBYTES($path) : string{
        $img = @imagecreatefrompng($path);
        $bytes = "";
        for ($y = 0; $y < (int) @getimagesize($path)[1]; $y++) {
            for ($x = 0; $x < (int) @getimagesize($path)[0]; $x++) {
                $rgba = @imagecolorat($img, $x, $y);
                $bytes .= chr(($rgba >> 16) & 0xff) . chr(($rgba >> 8) & 0xff) . chr($rgba & 0xff) . chr(((~(($rgba >> 24))) << 1) & 0xff);
            }
        }
        @imagedestroy($img);
        return $bytes;
    }

}
