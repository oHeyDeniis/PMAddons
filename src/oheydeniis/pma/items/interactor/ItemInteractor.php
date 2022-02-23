<?php

namespace oheydeniis\pma\items\interactor;


use oheydeniis\pma\items\ItemLoader;
use pocketmine\inventory\ShapelessRecipe;
use pocketmine\item\Item;

class ItemInteractor
{

    private ItemLoader $loader;

    /**
     * @var ItemInteractorResult[]
     */
    private array $interactResults = [];
    private string $path;

    public function __construct(ItemLoader $loader)
    {
        $this->path = $loader->getPath();
        $this->loader = $loader;
    }
    public function load(){
        @mkdir($this->path);
        foreach (scandir($this->path) as $scanResult){
            $path = $this->path.$scanResult."/";
            if(is_dir($path)){
                $this->interactResults[] = new ItemInteractorResult($path);
            }
        }
    }
    /**
     * @return ItemLoader
     */
    public function getLoader(): ItemLoader
    {
        return $this->loader;
    }

    /**
     * @return ItemInteractorResult[]
     */
    public function getInteractResults(): array
    {
        return $this->interactResults;
    }
}