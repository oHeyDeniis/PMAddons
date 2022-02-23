<?php

namespace oheydeniis\pma;

use pocketmine\event\Listener;
use pocketmine\event\player\PlayerJoinEvent;

class EventListener implements Listener
{

    private Main $main;

    public function __construct(Main $main)
    {
        $this->main = $main;
        $main->getServer()->getPluginManager()->registerEvents($this, $main);
    }

    public function onPlayerJoin(PlayerJoinEvent $event) : void{
        $player = $event->getPlayer();
        $player->getNetworkSession()->sendDataPacket($this->main->getLoaderManager()->getItemLoader()->getPmManager()->getComponentPacket());
    }
    /**
     * @return Main
     */
    public function getMain(): Main
    {
        return $this->main;
    }
}