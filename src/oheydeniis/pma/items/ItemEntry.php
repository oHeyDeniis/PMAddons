<?php

namespace oheydeniis\pma\items;

use oheydeniis\pma\items\category\NormalCategory;
use oheydeniis\pma\items\craft\ItemCraft;
use oheydeniis\pma\items\interactor\ItemInteractorResult;
use oheydeniis\pma\items\item\Armor;
use oheydeniis\pma\items\item\Axe;
use oheydeniis\pma\items\item\Food;
use oheydeniis\pma\items\item\Normal;
use oheydeniis\pma\items\item\Pickaxe;
use oheydeniis\pma\items\item\Shovel;
use oheydeniis\pma\items\item\Sword;
use oheydeniis\pma\items\item\Tool;
use oheydeniis\pma\items\category\ArmorCategory;
use oheydeniis\pma\items\category\FoodCategory;
use oheydeniis\pma\items\category\ItemCategory;
use oheydeniis\pma\items\category\SwordCategory;
use oheydeniis\pma\items\category\ToolCategory;
use oheydeniis\pma\utils\Utils;
use pocketmine\item\Item;
use pocketmine\nbt\tag\ByteTag;
use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ShortTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\network\mcpe\protocol\types\CacheableNbt;
use pocketmine\Server;

class ItemEntry
{

    private array $bundle;
    private ItemInteractorResult $interactorResult;

    public function __construct(array $bundle, ItemInteractorResult $interactorResult)
    {
        $this->bundle = $bundle;
        $this->interactorResult = $interactorResult;
    }
    public function isValid() : bool{
        if($this->getCategory()->isValid()){
            try {
                $this->getName();
                $this->getId();
                $this->getNamespace();
                return true;
            }catch (\Exception $e){
                $this->print($e);
                return false;
            }
        }
        return false;
    }
    public function getName() : string{
        return $this->bundle["name"];
    }
    public function getId() : int{
        return $this->bundle["id"];
    }
    public function getRuntimeId() : int{
        $id = $this->getId();
        return $id + ($id > 0 ? 5000 : -5000);
    }
    public function getMeta() : int{
        return $this->bundle["meta"] ?? 0;
    }
    public function isComplex() : bool{
        return $this->getMeta() >= 1;
    }
    public function getMaxStack() : int{
        return $this->bundle["max_stack"] ?? 1;
    }
    public function getMiningSpeed() : int{
        return $this->bundle["mining_speed"] ?? 1;
    }
    public function getNamespace() : string{
        return $this->bundle["namespace"];
    }

    public function getTextureName() : string{
        return $this->bundle["texture"];
    }

    public function allowCreativeInventory() : bool{
        return (bool) ($this->bundle["creative_inventory"] ?? false);
    }
    public function getCreativeCategory() : int{
        return $this->bundle["creative_category"] ?? 1;
    }
    public function getAllowOfHand() : bool{
        return (bool) ($this->bundle["allow_of_hand"] ?? true);
    }
    public function canDestroyInCreative() : bool{
        return (bool) ($this->bundle["can_destroy_in_creative"] ?? true);
    }
    public function getFoil() : int{
        return $this->bundle["foil"] ?? 0;
    }
    public function getUseDuration() : int{
        return $this->bundle["use_duration"] ?? 32;
    }
    public function isHandEquipped() : bool{
        return $this->bundle["hand_equipped"] ?? false;
    }
    public function allowAnimatesInToolbar() : bool{
        return $this->bundle["animates_in_toolbar"] ?? true;
    }
    public function allowOnUse() : bool{
        return $this->bundle["on_use"] ?? true;
    }
    public function allowOnUseOn() : bool{
        return $this->bundle["on_use_on"] ?? true;
    }
    public function getCategory() : ArmorCategory|SwordCategory|ToolCategory|FoodCategory|ItemCategory|NormalCategory{
        return match (($this->bundle["category"] ?? "")){
            "armor" => new ArmorCategory($this->bundle),
            "sword" => new SwordCategory($this->bundle),
            "tool" => new ToolCategory($this->bundle),
            "food" => new FoodCategory($this->bundle),
            default => new NormalCategory($this->bundle)
        };
    }
    public function getDependencies() : array{
        return $this->bundle["dependencies"] ?? [];
    }
    public function getCraft() : ?ItemCraft{
        if(isset($this->bundle["craft"])){
            return new ItemCraft($this->bundle["craft"], clone $this->getItem());
        }
        return null;
    }
    /**
     * @return array
     */
    public function getBundle(): array
    {
        return $this->bundle;
    }
    public function getItem() : Item{
        return match (get_class($this->getCategory())){
            SwordCategory::class => new Sword($this),
            ArmorCategory::class => new Armor($this),
            ToolCategory::class => match ($this->getCategory()->getType()){
                2 => new Shovel($this),
                3 => new Pickaxe($this),
                4 => new Axe($this),
                16, 32 => new Tool($this),
            },
            FoodCategory::class => new Food($this),
            default => new Normal($this)
        };
    }
    public function getNBT() : CacheableNbt{

        $nbt = Utils::convertArrayToCompound([
            "components" => Utils::convertArrayToCompound([
                "item_properties" => Utils::convertArrayToCompound([
                    "minecraft:icon" => Utils::convertArrayToCompound([
                        "texture" => new StringTag( $this->getTextureName()),
                        "legacy_id" => new StringTag( $this->getNamespace())
                    ]),
                    "use_duration" => new IntTag( $this->getUseDuration()),
                    "use_animation" => new IntTag( ($this->getCategory() instanceof FoodCategory ? 1 : 0)), // 2 is potion, but not now
                    "allow_off_hand" => new ByteTag( $this->getAllowOfHand()),
                    "can_destroy_in_creative" => new ByteTag( $this->canDestroyInCreative()),
                    "creative_category" => new ByteTag( $this->getCreativeCategory()),
                    "hand_equipped" => new ByteTag( $this->isHandEquipped()),
                    "max_stack_size" => new IntTag( $this->getMaxStack()),
                    "mining_speed"=> new FloatTag( $this->getMiningSpeed()),
                    "animates_in_toolbar" => new ByteTag( $this->allowAnimatesInToolbar()),
                    "foil" => new ByteTag( $this->getFoil())
                ])
            ]),
            "minecraft:identifier" => new ShortTag( $this->getRuntimeId()),
            "minecraft:display_name" => Utils::convertArrayToCompound([
                "value" => new StringTag($this->getName())
            ]),
            "minecraft:on_use" => Utils::convertArrayToCompound([
                "on_use" => new ByteTag( $this->allowOnUse())
            ]),
            "minecraft:on_use_on" => Utils::convertArrayToCompound([
                "on_use_on" => new ByteTag( $this->allowOnUseOn())
            ])
        ]);
        $this->getCategory()->writeCategory($nbt);
        return new CacheableNbt($nbt); //todo: its work's?
    }
    public function print(\Exception $e){
        Server::getInstance()->getLogger()->error(implode("\n", [
            "§e[PMAddons] ERROR ENCOUNTERED",
            "§aMessage: §c{$e->getMessage()}",
            "§aTrace: §7{$e->getTraceAsString()}"
        ]));
    }
    /**
     * @return ItemInteractorResult
     */
    public function getInteractorResult(): ItemInteractorResult
    {
        return $this->interactorResult;
    }
}