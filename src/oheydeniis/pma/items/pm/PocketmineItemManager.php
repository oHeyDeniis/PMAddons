<?php

namespace oheydeniis\pma\items\pm;

use oheydeniis\pma\items\item\Armor;
use oheydeniis\pma\items\item\Axe;
use oheydeniis\pma\items\item\Food;
use oheydeniis\pma\items\item\Normal;
use oheydeniis\pma\items\item\Pickaxe;
use oheydeniis\pma\items\item\Shovel;
use oheydeniis\pma\items\item\Sword;
use oheydeniis\pma\items\item\Tool;
use oheydeniis\pma\items\ItemLoader;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
//use pocketmine\network\mcpe\convert\ItemTranslator;
//use pocketmine\network\mcpe\convert\ItemTypeDictionary;
use pocketmine\network\mcpe\convert\GlobalItemTypeDictionary;
use pocketmine\network\mcpe\convert\ItemTranslator;
use pocketmine\network\mcpe\protocol\ItemComponentPacket;
use pocketmine\network\mcpe\protocol\serializer\ItemTypeDictionary;
use pocketmine\network\mcpe\protocol\types\ItemComponentPacketEntry;
use pocketmine\network\mcpe\protocol\types\ItemTypeEntry;
use oheydeniis\pma\items\item\AddonItem;

class PocketmineItemManager
{

    public ItemLoader $loader;

    /**
     * @Reference ItemTranslator
     */
    public array $simpleCoreToNetMapping = [];
    public array $simpleNetToCoreMapping = [];
    public array $complexCoreToNetMapping = [];
    public array $complexNetToCoreMapping = [];

    public array $itemTypes = [];
    public \ReflectionProperty $it;

    /** @var ItemComponentPacketEntry[] */
    public array $packetEntries = [];
    /** @var ItemTypeEntry[] */
    public array $itemTypeEntries = [];

    public ItemComponentPacket $componentPacket;

    /**
     * @var Item[]
     */
    public array $registredItems = [];

    public function __construct(ItemLoader $loader)
    {
        $this->loader = $loader;
    }
    public function init(){
        $translator = new \ReflectionClass(ItemTranslator::class);
        $translatorInstance = ItemTranslator::getInstance();
        $variables = ["simpleCoreToNetMapping", "simpleNetToCoreMapping", "complexCoreToNetMapping", "complexNetToCoreMapping"];
        foreach ($variables as $variable){
            $property = $this->getProperty($translator, $variable);
            $value = $this->getPropertyValue($property, $translatorInstance);
            $this->$variable = $value;
        }
        //$globalDictionary = new \ReflectionClass(GlobalItemTypeDictionary::class);
        //$globalDictionaryInstance = GlobalItemTypeDictionary::getInstance();
        // $dictionary = $this->getProperty($globalDictionary, "dictionary");
        $this->itemTypes = GlobalItemTypeDictionary::getInstance()->getDictionary()->getEntries();
        $this->packetEntries = [];
        $this->componentPacket = ItemComponentPacket::create($this->packetEntries);
    }

    /**
     * @param Sword|Pickaxe|Axe|Shovel|Tool|Armor|Normal $i
     */
    public function createItem(Sword|Pickaxe|Axe|Shovel|Tool|Armor|Normal $i){
        $entry = $i->getItemEntry();
        if(!$entry->isComplex()) {
            $this->simpleNetToCoreMapping[$entry->getRuntimeId()] = $entry->getId();
            $this->simpleCoreToNetMapping[$entry->getId()] = $entry->getRuntimeId();
        }else{
            $this->complexNetToCoreMapping[$entry->getRuntimeId()] = [$entry->getId(), $entry->getMeta()];
            $this->complexCoreToNetMapping[$entry->getId()][$entry->getMeta()] = $entry->getRuntimeId();
        }
        $this->itemTypeEntries[] = new ItemTypeEntry($entry->getNamespace(), $entry->getRuntimeId(), true);
        $this->packetEntries[] = new ItemComponentPacketEntry($entry->getNamespace(), $entry->getNBT());
        $this->itemTypes = $this->itemTypeEntries;
        $this->putProperties();
        $this->componentPacket = ItemComponentPacket::create($this->packetEntries);
        $this->registredItems[] = $i;
        ItemFactory::getInstance()->register($i, true);
    }
    public function putProperties(){
        $translator = new \ReflectionClass(ItemTranslator::class);
        $translatorInstance = ItemTranslator::getInstance();
        $variables = ["simpleCoreToNetMapping", "simpleNetToCoreMapping", "complexCoreToNetMapping", "complexNetToCoreMapping"];
        foreach ($variables as $variable){
            $property = $this->getProperty($translator, $variable);
            $this->setProperty($property, $this->$variable, $translatorInstance);
        }
        $dictionary = new \ReflectionClass(ItemTypeDictionary::class);
        $dictionaryInstance = GlobalItemTypeDictionary::getInstance()->getDictionary();
        $itemTypesProperty = $this->getProperty($dictionary, "itemTypes");
        $this->setProperty($itemTypesProperty, $this->itemTypeEntries, $dictionaryInstance);
    }
    private function reload() : void{

        return;
        //$this->clear();


    }
    public function clear(){
        ItemTranslator::reset();
        GlobalItemTypeDictionary::reset();
    }
    public function getProperty(\ReflectionClass $class, string $variable) : \ReflectionProperty{
        $inst = $class->getProperty($variable);
        $inst->setAccessible(true);
        return $inst;
    }
    public function getPropertyValue(\ReflectionProperty $property, $classInstance) : array|string|int{
        return $property->getValue($classInstance);
    }
    public function setProperty(\ReflectionProperty $property, $value, $classInstance){
        $property->setValue($classInstance, $value);
    }
    /**
     * @return ItemComponentPacket
     */
    public function getComponentPacket(): ItemComponentPacket
    {
        return $this->componentPacket;
    }
}