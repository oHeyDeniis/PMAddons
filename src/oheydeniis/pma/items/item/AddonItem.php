<?php

namespace oheydeniis\pma\items\item;

use oheydeniis\pma\items\ItemEntry;
use pocketmine\item\ItemIdentifier;

trait AddonItem
{

    protected ItemEntry $itemEntry;


    public function __construct(ItemEntry $itemEntry)
    {
        $this->itemEntry = $itemEntry;
        $this->identifier = new ItemIdentifier($itemEntry->getId(), $itemEntry->getMeta());
        $this->name = $itemEntry->getName();

        $this->tier = 5;
        if($itemEntry->allowCreativeInventory()) {
            // todo ItemFactory::getInstance()->addCreativeItem($this);
        }

        $this->construct($this->identifier, $this->name);
    }
    public function getCreativeCategory() : int{
        return $this->itemEntry->getCreativeCategory();
    }
    /**
     * @return ItemEntry
     */
    public function getItemEntry(): ItemEntry
    {
        return $this->itemEntry;
    }
    public function getVanillaName(): string
    {
        return $this->getItemEntry()->getName();
    }
}