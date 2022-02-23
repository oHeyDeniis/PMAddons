<?php

namespace oheydeniis\pma\items\category;

use oheydeniis\pma\utils\Utils;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ShortTag;

class DurableCategory extends ItemCategory
{

    public function getMaxDurability() : int{
        return $this->bundle["max_durability"] ?? 600;
    }

    public function getMaxStack() : int{
        return 1;
    }
    public function getDamageChange() : int{
        return $this->bundle["damage_change"] ?? 1;
    }
    public function writeCategory(CompoundTag &$nbt)
    {
        $nbt->getCompoundTag("components")->setTag("minecraft:durability", Utils::convertArrayToCompound([
            "damage_change" => new ShortTag( $this->getDamageChange()),
            "max_durability" => new IntTag( $this->getMaxDurability())
        ]));
        parent::writeCategory($nbt);
    }
}