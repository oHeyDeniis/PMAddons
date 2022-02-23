<?php

namespace oheydeniis\pma\items\category;

use oheydeniis\pma\utils\Utils;
use pocketmine\inventory\ArmorInventory;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;

class ArmorCategory extends DurableCategory
{
    private array $convertSlots = [
        "helmet" => ArmorInventory::SLOT_HEAD,
        "head" => ArmorInventory::SLOT_HEAD,
        "chestplate" => ArmorInventory::SLOT_CHEST,
        "chest" => ArmorInventory::SLOT_CHEST,
        "leggings" => ArmorInventory::SLOT_LEGS,
        "legs" => ArmorInventory::SLOT_LEGS,
        "boots" => ArmorInventory::SLOT_FEET,
        "feet" => ArmorInventory::SLOT_FEET
    ];
    private array $allowTypes = ["none", "gold", "leather", "chain", "iron", "diamond", "netherite", "elytra", "turtle"];

    public function getDefenderPoints() : int{
        return $this->bundle["defender_points"] ?? 9;
    }
    public function getArmorSlot() : int{
        return $this->convertSlots[strtolower($this->bundle["armor_slot"])];
    }
    public function getArmorType() : string{
        return $this->bundle["armor_type"] ?? "diamond";
    }
    public function getDamageChange() : int{
        return $this->bundle["damage_change"] ?? 1;
    }
    public function isDispensable() : bool{
        return $this->bundle["dispensable"] ?? true;
    }
    public function getProtection() : int{
        return $this->bundle["protection"] ?? $this->getDefenderPoints();
    }
    public function writeCategory(CompoundTag &$nbt){
        $nbt->getCompoundTag("components")?->setTag("minecraft:armor", Utils::convertArrayToCompound([
            "texture_type" => new StringTag( $this->getArmorType()),
            "protection" => new IntTag( $this->getProtection())
        ]));
        $nbt->getCompoundTag("components")?->setTag("minecraft:wearable", Utils::convertArrayToCompound([
            "slot" => new IntTag( ($this->getArmorSlot() + 2)),
            "dispensable" => new ByteTag( $this->isDispensable())
        ]));
        $nbt->getCompoundTag("components")?->setTag("minecraft:durability", Utils::convertArrayToCompound([
            "damage_change" => new ShortTag( $this->getDamageChange()),
            "max_durability" => new IntTag( $this->getMaxDurability())
        ]));
        parent::writeCategory($nbt);
    }
    public function isValid(): bool
    {
        try {
            $this->getDefenderPoints();
            $this->getArmorSlot();
            $this->getArmorType();
            return true;
        }catch (\Exception $e){
            $this->print($e);
            return false;
        }
    }
}