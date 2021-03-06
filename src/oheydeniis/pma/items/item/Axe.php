<?php

namespace oheydeniis\pma\items\item;

use oheydeniis\pma\items\category\ToolCategory;
use oheydeniis\pma\items\ItemEntry;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ToolTier;

class Axe extends \pocketmine\item\Axe
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
    protected function getBaseMiningEfficiency(): float
    {
        return $this->getCategory()->getMiningSpeed();
    }
    public function getMiningEfficiency(bool $isCorrectTool): float
    {
        return parent::getMiningEfficiency($isCorrectTool); // TODO: Change the autogenerated stub
    }

    public function getCategory() : ToolCategory{
        return $this->itemEntry->getCategory();
    }

    public function getTier(): ToolTier
    {
        return  ToolTier::DIAMOND(); //todo
    }
}