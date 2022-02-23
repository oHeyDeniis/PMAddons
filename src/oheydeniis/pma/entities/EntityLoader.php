<?php

namespace oheydeniis\pma\entities;

use oheydeniis\pma\Main;
use pocketmine\resourcepacks\ResourcePackManager;

class EntityLoader
{

    private Main $main;

    public function __construct(Main $main)
    {
        $this->main = $main;
    }
}