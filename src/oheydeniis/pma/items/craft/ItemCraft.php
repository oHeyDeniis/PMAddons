<?php

namespace oheydeniis\pma\items\craft;

use oheydeniis\pma\manager\Translator;
use pocketmine\crafting\CraftingRecipe;
use pocketmine\crafting\ShapedRecipe;
use pocketmine\crafting\ShapelessRecipe;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;

class ItemCraft
{

    private array $bundle;
    private ShapedRecipe|ShapelessRecipe|null $recipe = null;
    private Item $item;

    public function __construct(array $bundle, Item $item)
    {
        $this->bundle = $bundle;
        $item->setCount($bundle["craft_count"] ?? 1);
        $this->item = $item;
        $this->load();
    }
    public function load() : bool{
        $recipes = $this->bundle["recipe"] ?? [];
        if(!isset($recipes[0])){
            Translator::sendLogger("recipe_error", [
                "{data}" => implode("\n", $recipes)
            ]);
            return false;
        }
        if(!isset($recipes[2])){
            $line1 = $this->getItemsInLine($recipes[0]);
            $line2 = $this->getItemsInLine($recipes[1]);
            $list = array_merge($line1, $line2);
            if((count($line1) + count($line2)) <= 4){
                // $this->recipe = new ShapedRecipe($list, );
            }
            return false; //todo
        }else{
            $line1 = $this->getItemsInLine($recipes[0]);
            $line2 = $this->getItemsInLine($recipes[1]);
            $line3 = $this->getItemsInLine($recipes[2]);
            $list = array_merge($line1, $line2, $line3);
            $this->recipe = new ShapelessRecipe($list, [$this->item]);
        }
        return true;
    }
    
    /**
     * @param string $line
     * @return Item[]
     */
    public function getItemsInLine(string $line) : array{
        $items = [];
        $line = str_replace(" ", "", $line);
        $list = explode("|", $line);
        foreach ($list as $str){
            $ex = explode(",", $str);
            $id = $ex[0];
            $meta = $ex[1] ?? 0;
            $count = $ex[2] ?? 1;
            $items[] = ItemFactory::getInstance()->get($id, $meta, $count);
        }
        return $items;
    }


    public function getRecipe() : ShapedRecipe|ShapelessRecipe|null
    {
        return $this->recipe;
    }

    /**
     * @return Item
     */
    public function getItem(): Item
    {
        return $this->item;
    }
}