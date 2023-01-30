<?php

namespace Lobby\Tasks;

use Lobby\Utils\Utils;
use pocketmine\scheduler\AsyncTask;
use libpmquery\PMQuery;
use libpmquery\PmQueryException;
use pocketmine\utils\Config;

class UpdatePlayers extends AsyncTask
{

    public function onRun() : void{
        $res = ['count' => 0, 'maxPlayers' => 0, 'servers' => ["minestia" => 0, "arazia" => 0, "minage" => 0,"alias" => 0, "kitmap" => 0]];
        foreach(["endiorite.com:19134:minestia", "endiorite.com:19135:arazia", "endiorite.com:19136:minage", "endiorite.com:19137:minage", "endiorite.com:19138:alias", "endiorite.com:19133:kitmap"] as $serverConfigString){
            $serverData = explode(':', $serverConfigString);
            $ip = $serverData[0];
            $port = (int) $serverData[1];

            $qData = Utils::getServer($ip, (string)$port);
            if (is_null($qData)){
                $res['count'] = -1;
                $res['maxPlayers'] += 50;
                $res['servers'][$serverData[2]] = -1;
            }else{
                if($qData->online === true){
                    $res['count'] += $qData->players->online;
                    $res['maxPlayers'] += $qData->players->max;
                    $res['servers'][$serverData[2]] += $qData->players->online;
                }
            }
        }
        $this->setResult($res);
    }

    public function onCompletion() : void{
        $res = $this->getResult();
        $cfg = new Config("/home/container/plugin_data/EndioriteLobby/cached.yml", Config::YAML);
        $cfg->set("players", $res['count']);
        $cfg->save();
        $cfg->set("maxPlayers", $res['maxPlayers']);
        $cfg->save();
        $cfg->set("servers", $res['servers']);
        $cfg->save();
    }
}