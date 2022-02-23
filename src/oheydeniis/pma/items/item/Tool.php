<?php

namespace oheydeniis\pma\items\item;

use oheydeniis\pma\items\category\ToolCategory;
use oheydeniis\pma\items\ItemEntry;
use pocketmine\item\ItemIdentifier;

class Tool extends \pocketmine\item\Tool
{
    use AddonItem;

    public function construct(ItemIdentifier $identifier, string $name = "Unknown")
    {
        parent::__construct($identifier, $name);
    }

    public function getMaxDurability(): int
    {
        return $this->getCategory()->getMaxDurability();
    }


    public function getCategory() : ToolCategory{
        return $this->itemEntry->getCategory();
    }
}