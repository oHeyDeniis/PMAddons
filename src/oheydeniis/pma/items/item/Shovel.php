<?php

namespace oheydeniis\pma\items\item;

use oheydeniis\pma\items\category\ToolCategory;
use oheydeniis\pma\items\ItemEntry;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ToolTier;

class Shovel extends \pocketmine\item\Shovel
{
    use AddonItem;

    public function construct(ItemIdentifier $identifier, string $name)
    {
        parent::__construct($identifier, $name, $this->getTier());
    }

    public function getMaxDurability(): int
    {
        return $this->getCategory()->getMaxDurability();
    }

    public function getCategory() : ToolCategory{
        return $this->itemEntry->getCategory();
    }
    public function getTier(): ToolTier
    {
        return  ToolTier::DIAMOND(); //todo
    }
}