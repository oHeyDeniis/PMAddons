<?php

namespace oheydeniis\pma\items\pm;

use pocketmine\network\mcpe\convert\item\ItemTranslator;
use pocketmine\network\mcpe\convert\item\ItemTypeDictionary;
use pocketmine\network\mcpe\protocol\ItemComponentPacket;

class MultiPocketmineItemManager extends PocketmineItemManager
{
    /*
     * In the Brazil Servers is used pocketmine with multi version, this change the uses to support the multi api.
     */

    public function init(){
        $translator = new \ReflectionClass(ItemTranslator::class);
        $translatorInstance = ItemTranslator::getInstance();
        $variables = ["simpleCoreToNetMapping", "simpleNetToCoreMapping", "complexCoreToNetMapping", "complexNetToCoreMapping"];
        foreach ($variables as $variable){
            $property = $this->getProperty($translator, $variable);
            $value = $this->getPropertyValue($property, $translatorInstance);
            $this->$variable = $value;
        }
        $dictionary = new \ReflectionClass(ItemTypeDictionary::class);
        $dictionaryInstance = ItemTypeDictionary::getInstance();
        $itemTypesProperty = $this->getProperty($dictionary, "itemTypes");
        $this->itemTypes = $this->getPropertyValue($itemTypesProperty, $dictionaryInstance);
        $this->packetEntries = [];
        $this->componentPacket = ItemComponentPacket::create($this->packetEntries);
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
        $dictionaryInstance = ItemTypeDictionary::getInstance();
        $itemTypesProperty = $this->getProperty($dictionary, "itemTypes");
        $this->setProperty($itemTypesProperty, $this->itemTypeEntries, $dictionaryInstance);
    }

}