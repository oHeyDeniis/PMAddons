<?php

namespace oheydeniis\pma\items\category;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\Server;

class ItemCategory
{
    protected array $bundle;

    public function __construct(array $bundle)
    {
        $this->bundle = $bundle;
    }

    public function isValid() : bool{
        return false;
    }
    /**
     * @return array
     */
    public function getBundle(): array
    {
        return $this->bundle;
    }
    public function writeCategory(CompoundTag &$nbt){

    }
    public function print(\Exception $e){
        Server::getInstance()->getLogger()->error(implode("\n", [
            "§e[PMAddons] ERROR ENCOUNTERED",
            "§aMessage: §c{$e->getMessage()}",
            "§aTrace: §7{$e->getTraceAsString()}"
        ]));
    }
}