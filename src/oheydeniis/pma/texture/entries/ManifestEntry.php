<?php

namespace oheydeniis\pma\texture\entries;

use oheydeniis\pma\utils\UUID;
use pocketmine\utils\Config;


class ManifestEntry
{

    private EntriesManager $entriesManager;
    private string $file;

    public function __construct(EntriesManager $entriesManager)
    {
        $this->entriesManager = $entriesManager;
        $this->file = $entriesManager->getTextureManager()->getResourcePath()."manifest.json";
    }
    public function build(array $newVersion = null){
        $header = $this->getHeader();
        if(is_null($newVersion)){
            $header["uuid"] = UUID::fromRandom()->toString();
        }else{
            $header["version"] = $newVersion;
        }
        $this->setHeader($header);
        $modules = $this->getModules();
        foreach ($modules as $index => $data){
            if(is_null($newVersion)){
                $modules[$index]["uuid"] = UUID::fromRandom()->toString();
            }else{
                $modules[$index]["version"] = $newVersion;
            }
        }
        $this->setModules($modules);
        $this->getConfig()->reload();
        $path = $this->entriesManager->getTextureManager()->getResourcePath();
        @mkdir($path);
        $dirs = [
            "textures",
            "models",
            "entity",
            "animation_controllers",
            "animation",
            "attachables",
            "sounds",
            "texts"
        ];
        foreach ($dirs as $dir){
            $mpath = $path.$dir."/";
            @mkdir($mpath);
        }
    }
    public function getHeader() : array{
        return $this->getConfig()->get("header", false);
    }
    public function setHeader(array $header){
        $this->getConfig()->set("header", $header);
        $this->getConfig()->save();
    }
    public function getModules() : array{
        return $this->getConfig()->get("modules", false);
    }
    public function setModules(array $modules){
        $this->getConfig()->set("modules", $modules);
        $this->getConfig()->save();
    }
    public function getConfig() : Config{
        return new Config($this->file, Config::JSON, [
            "format_version" => 2,
            "header" => [
                "name" => "PMAddons",
                "description" => "allow addons in your pocketmine server",
                "uuid" => UUID::fromRandom()->toString(),
                "version" => [0, 0, 1],
                "min_engine_version" => [1, 17, 0]
            ],
            "modules" => [
                [
                    "description" => "allow addons in your pocketmine server",
                    "type" => "resources",
                    "uuid" => UUID::fromRandom()->toString(),
                    "version" => [0, 0, 1]
                ]
            ]
        ]);
    }
}