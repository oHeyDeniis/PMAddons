<?php

namespace oheydeniis\pma\texture\entries;

use oheydeniis\pma\items\ItemEntry;
use oheydeniis\pma\items\ItemLoader;
use pocketmine\Server;
use pocketmine\utils\Config;

class ItemsEntry
{

    private EntriesManager $manager;
    private ItemLoader $itemLoader;

    private string $file;
    private string $path;
    private LangsEntry $langsEntry;
    private string $resourcePackPath = "";

    public function __construct(EntriesManager $manager)
    {
        $this->manager = $manager;
        $this->itemLoader = $manager->getTextureManager()->getMain()->getLoaderManager()->getItemLoader();
        $rp = $manager->getTextureManager()->getResourcePath();
        $this->resourcePackPath = $rp;
        @mkdir($rp."textures/");
        @mkdir($rp."textures/pmaddons/");
        $this->path = $rp."textures/pmaddons/";
        $this->file = $manager->getTextureManager()->getResourcePath()."textures/item_texture.json";
        $this->langsEntry = new LangsEntry($manager);
    }
    public function build(){
        $textureData = $this->getTextureData();
        $this->langsEntry->startWrite();
        foreach ($this->itemLoader->getItemEntryList() as $itemEntry){
            $path = $itemEntry->getInteractorResult()->getRootPath();
            $textureData[$itemEntry->getTextureName()] = [
                "textures" => "textures/pmaddons/{$itemEntry->getTextureName()}"
            ];
            copy($path.$itemEntry->getTextureName().".png", $this->path.$itemEntry->getTextureName().".png");
            $this->langsEntry->write("item.{$itemEntry->getNamespace()}", $itemEntry->getName());
            $this->processDependencies($itemEntry);
        }
        $this->setTextureData($textureData);
        $this->langsEntry->endWrite();
    }
    public function processDependencies(ItemEntry $entry){
        foreach ($entry->getDependencies() as $file => $target){
            $path = $entry->getInteractorResult()->getRootPath();
            $pathTarget = $this->resourcePackPath.$target;
            if(is_file($path.$file)){
                @mkdir($pathTarget);
                copy($path.$file, $pathTarget.$file);
            }else{
                Server::getInstance()->getLogger()->warning("§eDependencies §c'$file'§e of item §c'{$entry->getName()}'§e in §c'$path' §enot found!\n");
            }
        }
    }
    public function getTextureData() : array{
        return $this->getConfig()->get("texture_data", []);
    }
    public function setTextureData(array $textureData) : void{
        $config = $this->getConfig();
        $config->set("texture_data", $textureData);
        $config->save();
    }
    public function getConfig() : Config{
        return new Config($this->file, Config::JSON, [
            "resource_pack_name" => "vanilla",
            "texture_name" => "atlas.items",
            "texture_data" => []
        ]);
    }
}