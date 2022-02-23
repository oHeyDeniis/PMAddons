<?php

namespace oheydeniis\pma\manager;

use oheydeniis\pma\Main;
use pocketmine\utils\Config;

abstract class BaseLoader
{

    private Main $main;
    protected Config $config;

    public function __construct(Main $main)
    {
        $this->main = $main;
    }

    /**
     * load all objects of a list
     */
    abstract function load() : void;

    /**
     * Returns a config with objets for loaded
     * @return Config
     */
    abstract function getConfig() : Config;

    /**
     * @return Main
     */
    public function getMain(): Main
    {
        return $this->main;
    }
}