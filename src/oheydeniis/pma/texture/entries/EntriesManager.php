<?php

namespace oheydeniis\pma\texture\entries;

use oheydeniis\pma\Main;
use oheydeniis\pma\manager\TextureManager;

class EntriesManager
{

    private Main $main;
    public array $entries = [];
    private TextureManager $textureManager;

    public function __construct(Main $main, TextureManager $textureManager)
    {
        $this->main = $main;
        $this->textureManager = $textureManager;
    }
    public function build(){
        (new ManifestEntry($this))->build();
        (new ItemsEntry($this))->build();
    }
    /**
     * @return array
     */
    public function getEntries(): array
    {
        return $this->entries;
    }

    /**
     * @return TextureManager
     */
    public function getTextureManager(): TextureManager
    {
        return $this->textureManager;
    }
}