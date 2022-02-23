<?php

namespace oheydeniis\pma\texture\entries;

use pocketmine\utils\Config;

class LangsEntry
{

    private EntriesManager $entriesManager;
    private string $file;

    private array $writing = [];

    public function __construct(EntriesManager $entriesManager)
    {
        $this->entriesManager = $entriesManager;
        $this->file = $entriesManager->getTextureManager()->getResourcePath()."texts/";
        @mkdir($this->file);
        $this->file = $this->file."pt_BR.lang";
    }

    public function startWrite() : void{
        $this->writing = [];
    }
    public function write(string $key, string $value){
        $this->writing[$key] = $value;
    }
    public function endWrite(){
        $config = $this->getConfig();
        $all = $config->getAll();
        $result = array_merge($all, $this->writing);
        $config->setAll($result);
        $config->save();
    }
    public function getConfig() : Config{
        return new Config($this->file, Config::PROPERTIES, []);
    }
    /**
     * @return EntriesManager
     */
    public function getEntriesManager(): EntriesManager
    {
        return $this->entriesManager;
    }
}