<?php

namespace Lobby\Tasks;

use pocketmine\scheduler\AsyncTask;
use libpmquery\PMQuery;
use libpmquery\PmQueryException;
use pocketmine\utils\Config;

class Test extends AsyncTask
{

    public function onRun() : void{
        $res = ['count' => 0, 'maxPlayers' => 0, 'errors' => []];
        foreach(["endiorite.com:19134", "endiorite.com:19135", "endiorite.com:19136", "endiorite.com:19137"] as $serverConfigString){
            $serverData = explode(':', $serverConfigString);
            $ip = $serverData[0];
            $port = (int) $serverData[1];
            try{
                $qData = PMQuery::query($ip, $port);
            }catch(PmQueryException $e){
                $res['errors'][] = 'Failed to query '.$serverConfigString.': '.$e->getMessage();
                continue;
            }
            $res['count'] += $qData['Players'];
            $res['maxPlayers'] += $qData['MaxPlayers'];
        }
        $this->setResult($res);
    }

    public function onCompletion() : void{
        cfg = new Config()
        $res = $this->getResult();
        foreach($res['errors'] as $e){

        }
        $plugin = $server->getPluginManager()->getPlugin('MultiServerCounter');
        if($plugin !== null && $plugin->isEnabled()){
            /** @var $plugin Main */
            $plugin->setCachedPlayers($res['count']);
            $plugin->setCachedMaxPlayers($res['maxPlayers']);
        }
    }
}