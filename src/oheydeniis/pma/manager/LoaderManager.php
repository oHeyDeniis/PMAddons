<?php

namespace oheydeniis\pma\manager;

use oheydeniis\pma\items\ItemLoader;
use oheydeniis\pma\Main;

class LoaderManager
{

    private Main $main;
    private ItemLoader $itemLoader;

    public function __construct(Main $main)
    {
        $this->main = $main;
        $this->itemLoader = new ItemLoader($main);
        $this->itemLoader->load();
        $this->itemLoader->convertItems();
    }

    /**
     * @return ItemLoader
     */
    public function getItemLoader(): ItemLoader
    {
        return $this->itemLoader;
    }

    /**
     * @return Main
     */
    public function getMain(): Main
    {
        return $this->main;
    }
}