<?php

namespace oheydeniis\pma\manager;

use oheydeniis\pma\Main;

use oheydeniis\pma\texture\entries\EntriesManager;
use pocketmine\resourcepacks\ResourcePackManager;
use pocketmine\resourcepacks\ZippedResourcePack;
use pocketmine\utils\Config;
use RecursiveDirectoryIterator;
use RecursiveIteratorIterator;
use SplFileInfo;

class TextureManager
{
    public string $resourcePath = "";
    private string $resourceZip;
    private Main $main;
    private ResourcePackManager $resourcePackManager;
    private array $resourcePacks;
    private array $uuidList;
    private EntriesManager $entriesManager;
    private \ReflectionProperty $resourcePacksValue;
    private \ReflectionProperty $uuidListValue;


    public function __construct(Main $main)
    {
        $this->main = $main;
        $this->resourcePath = $main->getServer()->getDataPath() . "resource_packs" . DIRECTORY_SEPARATOR."PMAddons".DIRECTORY_SEPARATOR;
        $this->resourceZip = $main->getServer()->getDataPath() . "resource_packs" . DIRECTORY_SEPARATOR . "PMAddons.zip";
        @mkdir($this->resourcePath);
        $this->resourcePackManager = $main->getServer()->getResourcePackManager();
        $ri = new \ReflectionClass(ResourcePackManager::class);
        $this->resourcePacksValue = $ri->getProperty("resourcePacks");
        $this->resourcePacksValue->setAccessible(true);
        $this->resourcePacks = $this->resourcePacksValue->getValue($this->resourcePackManager);
        $this->uuidListValue = $ri->getProperty("uuidList");
        $this->uuidListValue->setAccessible(true);
        $this->uuidList = $this->uuidListValue->getValue($this->resourcePackManager);
        $this->entriesManager = new EntriesManager($main, $this);
    }
    public function build(){
        $this->entriesManager->build();
        $this->buildZip();
        $this->reloadTextures();
    }
    public function buildZip(){

        $rootPath = realpath($this->resourcePath);
        $zip = new \ZipArchive();
        $zip->open($this->resourceZip, \ZipArchive::CREATE | \ZipArchive::OVERWRITE);

        /** @var SplFileInfo[] $files */
        $files = new RecursiveIteratorIterator(
            new RecursiveDirectoryIterator($rootPath),
            RecursiveIteratorIterator::LEAVES_ONLY
        );
        foreach ($files as $name => $file)
        {
            if (!$file->isDir())
            {
                $filePath = $file->getRealPath();
                $relativePath = substr($filePath, strlen($rootPath) + 1);
                $zip->addFile($filePath, $relativePath);
            }
        }
        $zip->close();
    }
    public function reloadTextures(){
        $resourcePacks = [];
        $uuidList = [];

        $path = $this->main->getServer()->getDataPath() . "resource_packs" . DIRECTORY_SEPARATOR;
        $list = (new Config($path."resource_packs.yml", Config::YAML))->get("resource_stack", []);
        $list[] = "PMAddons.zip";
        foreach ($list as $pack){
            $zip = $path.$pack;
            if(is_file($zip)){
                $zipped = new ZippedResourcePack($zip);
                $resourcePacks[] = $zipped;
                $uuidList[strtolower($zipped->getPackId())] = $zipped;
            }
        }
        $this->resourcePacksValue->setValue($this->resourcePackManager, $resourcePacks);
        $this->uuidListValue->setValue($this->resourcePackManager, $uuidList);
    }
    /**
     * @return string
     */
    public function getResourcePath(): string
    {
        return $this->resourcePath;
    }

    /**
     * @return Main
     */
    public function getMain(): Main
    {
        return $this->main;
    }
}