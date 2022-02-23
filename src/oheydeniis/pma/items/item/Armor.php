<?php

namespace oheydeniis\pma\items\item;

use oheydeniis\pma\items\category\ArmorCategory;
use oheydeniis\pma\items\ItemEntry;
use pocketmine\item\ArmorTypeInfo;
use pocketmine\item\ItemIdentifier;

class Armor extends \pocketmine\item\Armor
{
    use AddonItem;

    public function construct(ItemIdentifier $identifier, string $name)
    {
        parent::__construct($identifier, $name, new ArmorTypeInfo($this->getDefensePoints(), $this->getMaxDurability(), $this->getCategory()->getArmorSlot()));
    }
    public function getMaxDurability(): int
    {
        return $this->getCategory()->getMaxDurability();
    }
    public function getDefensePoints(): int
    {
        return $this->getCategory()->getDefenderPoints();
    }

    public function getCategory() : ArmorCategory{
        return $this->itemEntry->getCategory();
    }
    public function getMaxStackSize(): int
    {
        return $this->getItemEntry()->getMaxStack();
    }
}