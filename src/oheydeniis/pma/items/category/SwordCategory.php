<?php

namespace oheydeniis\pma\items\category;

use pocketmine\item\ToolTier;
use pocketmine\level\Level;
use pocketmine\nbt\tag\CompoundTag;

class SwordCategory extends DurableCategory
{

    public function isValid(): bool
    {
        try {
            $this->getAttackDamage();
            $this->isHandEquipped();
            return true;
        }catch (\Exception $e){
            $this->print($e);
            return false;
        }
    }
    public function writeCategory(CompoundTag &$nbt)
    {
        $nbt->getCompoundTag("components")->getCompoundTag("item_properties")->setByte("hand_equipped", $this->isHandEquipped(), true);
        parent::writeCategory($nbt);
    }
    public function getTier() : ToolTier{
        //TODO
        return ToolTier::DIAMOND();
        //return $this->convertTier[strtolower(($this->bundle["tool_type"] ?? "diamond"))];
    }
    public function getAttackDamage() : int{
        return $this->bundle["attack_damage"] ?? 9;
    }
    public function isHandEquipped() : bool{
        return $this->bundle["hand_equipped"] ?? false;
    }
}