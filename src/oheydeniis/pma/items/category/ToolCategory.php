<?php

namespace oheydeniis\pma\items\category;

use pocketmine\item\ToolTier;
use pocketmine\nbt\tag\CompoundTag;

class ToolCategory extends DurableCategory
{
    private array $convertNames = [
        "shovel" => 2,
        "pickaxe" => 3,
        "axe" => 4,
        "shears" => 16,
        "hoe" => 32
    ];
    private array $convertTier = [
        "wooden" => 1,
        "gold" => 2,
        "stone" => 3,
        "iron" => 4,
        "diamond" => 5
    ];
    public function getType() : int{
        return $this->convertNames[strtolower($this->bundle["tool_name"])];
    }
    public function getTier() : ToolTier{
        //TODO
        return ToolTier::DIAMOND();
        //return $this->convertTier[strtolower(($this->bundle["tool_type"] ?? "diamond"))];
    }
    public function getMiningSpeed() : int{
        return $this->bundle["mining_speed"] ?? 1;
    }
    public function writeCategory(CompoundTag &$nbt)
    {

        parent::writeCategory($nbt);
    }

    public function isValid(): bool
    {
        try {
            $this->getType();
            $this->getTier();
            return true;
        }catch (\Exception $e){
            $this->print($e);
            return false;
        }
    }
}