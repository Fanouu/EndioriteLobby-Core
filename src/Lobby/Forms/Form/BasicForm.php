<?php

namespace Lobby\Forms\Form;

use Lobby\Forms\FormAPI\SimpleForm;
use Lobby\Main;
use Lobby\Utils\Utils;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\SingletonTrait;
use vezdehod\packs\ContentFactory;
use vezdehod\packs\ui\jsonui\binding\Binding;
use vezdehod\packs\ui\jsonui\binding\BindingType;
use vezdehod\packs\ui\jsonui\binding\DataBinding;
use vezdehod\packs\ui\jsonui\element\CustomElement;
use vezdehod\packs\ui\jsonui\element\ImageElement;
use vezdehod\packs\ui\jsonui\element\ScreenElement;
use vezdehod\packs\ui\jsonui\element\StackPanelElement;
use vezdehod\packs\ui\jsonui\element\types\Anchor;
use vezdehod\packs\ui\jsonui\element\types\FontType;
use vezdehod\packs\ui\jsonui\element\types\Offset;
use vezdehod\packs\ui\jsonui\element\types\Orientation;
use vezdehod\packs\ui\jsonui\element\types\PropertyBag;
use vezdehod\packs\ui\jsonui\element\types\Rotation;
use vezdehod\packs\ui\jsonui\element\types\Size;
use vezdehod\packs\ui\jsonui\element\types\TextAlignment;
use vezdehod\packs\ui\jsonui\vanilla\form\SimpleFormStyle;

class BasicForm{

    public static function ffa(Player $player){
        $form = self::createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }

            switch ($result){
                case 0:
                    $player->teleport(Server::getInstance()->getWorldManager()->getWorldByName("FFA2")->getSafeSpawn());
                break;

                case 1:
                    $player->teleport(Server::getInstance()->getWorldManager()->getWorldByName("COMBO")->getSafeSpawn());
                break;
            }

            Utils::ffaKit($player, $result);

            return true;

        });
        $form->setTitle("§e§lFFA");
        $form->addButton("§l§7BASIC", 0,"textures/items/diamond_sword");
        $form->addButton("§l§7COMBO", 0,"textures/items/golden_apple");

        $player->sendForm($form);
    }

    public static function test(Player $player){
        $form = self::createSimpleForm(function (Player $player, int $data = null){
            $result = $data;
            if($result === null){
                return true;
            }

            var_dump($result);


            return true;

        });
        $form->setTitle("test");
        $form->setContent("test");
        $form->addButton("estsetststst", 0,"textures/customui/jobs/buttons/Mineur");
        $form->addButton("estsetststst", 0,"textures/customui/jobs/buttons/Farmeur");
        $form->addButton("estsetststst", 0,"textures/customui/jobs/buttons/Hunter");
        $form->addButton("estsetststst", 0,"textures/customui/jobs/buttons/Alchimiste");
        $player->sendForm($form);
        //$player->sendForm(FormBuilder::newSimple("Player: {$target->getName()}", $target->getUniqueId()->toString())->withButton("Send message", fn() => ...)->build());
    }

    public static function createSimpleForm(callable $function = null) : SimpleForm {
        return new SimpleForm($function);
    }

}
