<?php

namespace oheydeniis\pma\items\item;


use oheydeniis\pma\items\category\SwordCategory;
use oheydeniis\pma\items\ItemEntry;
use oheydeniis\pma\texture\entries\ItemsEntry;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ToolTier;

class Sword extends \pocketmine\item\Sword
{
    use AddonItem;

    public function construct(ItemIdentifier $itemIdentifier, string $name)
    {
        parent::__construct($itemIdentifier, $name, $this->getTier());
    }

    public function getAttackPoints() : int{

        return $this->getCategory()->getAttackDamage();
    }
    public function getCategory() :  SwordCategory{
        return $this->itemEntry->getCategory();
    }
    public static function getDurabilityFromTier(int $tier): int
    {
        return 1600;
    }
    public function getMaxDurability(): int
    {
        return $this->itemEntry->getCategory()->getMaxDurability();
    }
    public function getTier(): ToolTier
    {
        return $this->getItemEntry()->getCategory()->getTier();
    }
    public function getMaxStackSize(): int
    {
        return $this->getItemEntry()->getCategory()->getMaxStack();
    }

}