<?php

namespace oheydeniis\pma\items\interactor;

use http\Encoding\Stream;
use pocketmine\utils\Config;

class ItemInteractorResult
{

    private string $rootPath;

    public function __construct(string $rootPath)
    {
        $this->rootPath = $rootPath;
    }

    public function getItemList() : array{
        return $this->getConfig()->getAll();
    }
    public function getConfig() : Config{
        return new Config($this->rootPath."items.yml", Config::YAML, []);
    }
    public function getRootPathBaseName() : string{
        return basename($this->getRootPath());
    }
    /**
     * @return string
     */
    public function getRootPath(): string
    {
        return $this->rootPath;
    }
}