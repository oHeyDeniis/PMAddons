<?php

namespace oheydeniis\pma\items\category;

use oheydeniis\pma\utils\Utils;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\StringTag;

class FoodCategory extends ItemCategory
{

    public function isCreativeEat() : bool{
        return (bool) $this->bundle["creative_eat"] ?? true;
    }
    public function getNutrition() : int{
        return $this->bundle["eat_nutrition"] ?? 1;
    }
    public function getSaturation() : int{
        return $this->bundle["eat_saturation"] ?? 1;
    }
    public function getUseDuration() : int{
        return $this->bundle["use_duration"] ?? 1;
    }
    public function getSaturationModifier() : string{
        return $this->bundle["saturation_modifier"] ?? "low";
    }
    /**
     * @return Item
     */
    public function getResidue() : Item{
        $arr = $this->bundle["residue"];
        return ItemFactory::getInstance()->get($arr["id"] ?? 0, $arr["meta"] ?? 0, $arr["count"] ?? 0);
    }
    public function writeCategory(CompoundTag &$nbt)
    {
        $nbt->getCompoundTag("components")->setTag("minecraft:food", Utils::convertArrayToCompound([
            "can_always_eat" => new ByteTag( $this->isCreativeEat()),
            "nutrition" => new FloatTag( $this->getNutrition()),
            "saturation_modifier" => new StringTag( $this->getSaturationModifier())
        ]));
        $nbt->getCompoundTag("components")->setTag("minecraft:use_duration", Utils::convertArrayToCompound([
            "value" => new IntTag( $this->getUseDuration())
        ]));
        parent::writeCategory($nbt);
    }

    public function isValid(): bool
    {
        try {
            $this->getNutrition();
            $this->getSaturation();
            $this->getResidue();
        return true;
        }catch (\Exception $e){
            $this->print($e);
            return false;
        }
    }
}