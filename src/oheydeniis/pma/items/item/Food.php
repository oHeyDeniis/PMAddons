<?php

namespace oheydeniis\pma\items\item;

use oheydeniis\pma\items\category\FoodCategory;
use oheydeniis\pma\items\ItemEntry;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;

class Food extends \pocketmine\item\Food
{
    use AddonItem;

    public function construct(ItemIdentifier $identifier, string $name = "Unknown")
    {
        parent::__construct($identifier, $name);
    }

    public function getFoodRestore(): int
    {
       return $this->getCategory()->getNutrition();
    }

    public function getSaturationRestore(): float
    {
        return $this->getCategory()->getSaturation();
    }
    public function getResidue() : Item
    {
        return $this->getCategory()->getResidue();
    }

    public function getCategory() : FoodCategory{
        return $this->itemEntry->getCategory();
    }


}