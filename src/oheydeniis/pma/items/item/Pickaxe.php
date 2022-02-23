<?php

namespace oheydeniis\pma\items\item;

use oheydeniis\pma\items\category\SwordCategory;
use oheydeniis\pma\items\category\ToolCategory;
use oheydeniis\pma\items\ItemEntry;
use pocketmine\block\Block;
use pocketmine\block\BlockToolType;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ToolTier;

class Pickaxe extends \pocketmine\item\Pickaxe
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

    public function getCategory() :  ToolCategory{
        return $this->itemEntry->getCategory();
    }
    public function getBlockToolHarvestLevel(): int
    {
        return 5;
    }
    protected function getBaseMiningEfficiency(): float
    {
        return $this->getItemEntry()->getMiningSpeed() * 10;
    }
    public function getMiningEfficiency(bool $correctTool): float
    {
        return $this->getItemEntry()->getMiningSpeed();
    }
    public function getTier(): ToolTier
    {

        return  ToolTier::DIAMOND(); //todo
    }

    public function getBlockToolType(): int
    {
        return BlockToolType::PICKAXE;
    }
}