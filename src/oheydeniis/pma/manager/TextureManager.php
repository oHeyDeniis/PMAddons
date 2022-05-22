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
    /*
    [12:51, 20/05/2022] Raidoxx [KRIPTO]: "binding_type":"view",
"target_property_name":"#visible"
[12:52, 20/05/2022] Raidoxx [KRIPTO]: "exemplo_form":{
 "$exemplo":"TESTE",
  "controls":[{
    "long_form@long_form":{
      "enabled":false,
      "visible":false,
      "bindings":[{
        "source_property_name":"((#title_text - $exemplo) = #title_text))",
        "binding_type":"view",
        "target_property_name":"#visible"
      }]
    }
  }]
}
[12:55, 20/05/2022] Raidoxx [KRIPTO]: "exemplo_painel@(sua_namespace).exemplo_painel":{
    "enabled":false,
    "visible":false,
    "bindings":[
       {
          "binding_type":"global",
          "binding_condition":"none",
          "binding_name":"#title_text",
          "binding_name_override":"#title_text"
       },
       {
          "source_property_name":"(not ((#title_text - $exemplo) = #title_text))",
          "binding_type":"view",
          "target_property_name":"#visible"
       },
       {
          "source_property_name":"(not ((#title_text - $exemplo) = #title_text))",
          "binding_type":"view",
          "target_property_name":"#enabled"
       }
    ]
 }
    */
    public function build(bool $delete = true){
        if($delete){
            if (is_file($this->resourceZip)){
                $this->info("deleting actual zip...");
                @unlink($this->resourceZip);
            }
        }
        $this->info("rebuilding zip entries!");
        $this->entriesManager->build();
        $this->info("Building ZIP archive!");
        $this->buildZip();
        $this->info("Apply texture in server");
        $this->reloadTextures();
        $this->info("texture update finished!");
    }
    public function buildZip(){

        $rootPath = realpath($this->resourcePath);
        $zip = new \ZipArchive();

        if(($code = $zip->open($this->resourceZip, \ZipArchive::CREATE|\ZipArchive::OVERWRITE|\ZipArchive::CHECKCONS|\ZipArchive::EXCL)) !== TRUE){
            $this->info("§cError while creating new ZIP code: $code (tryagain)");

            return;
        }

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
    public function info(string $messgae) : void{
        $this->getMain()->getServer()->getLogger()->info("§eTEXTURE > §6$messgae");
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