<?php

namespace oheydeniis\pma\items;


use muqsit\pmarmorstand\behaviour\ArmorPieceArmorStandBehaviour;
use muqsit\pmarmorstand\Loader;
use oheydeniis\pma\items\interactor\ItemInteractor;
use oheydeniis\pma\items\item\AddonItem;
use oheydeniis\pma\items\item\Armor;
use oheydeniis\pma\items\item\Axe;
use oheydeniis\pma\items\item\Food;
use oheydeniis\pma\items\item\Normal;
use oheydeniis\pma\items\item\Pickaxe;
use oheydeniis\pma\items\item\Shovel;
use oheydeniis\pma\items\item\Sword;
use oheydeniis\pma\items\item\Tool;
use oheydeniis\pma\items\pm\MultiPocketmineItemManager;
use oheydeniis\pma\items\pm\PocketmineItemManager;
use oheydeniis\pma\Main;
use oheydeniis\pma\manager\BaseLoader;
use oheydeniis\pma\manager\Translator;
use pocketmine\crafting\ShapedRecipe;
use pocketmine\crafting\ShapelessRecipe;
use pocketmine\item\Item;
use pocketmine\utils\Config;

class ItemLoader extends BaseLoader
{
    /**
     * @var ItemEntry[]
     */
    protected array $itemEntryList = [];
    /**
     * @var AddonItem[]|Item[]
     */
    protected array $itemList = [];

    private ?PocketmineItemManager $pmmanager = null;

    private string $path;
    private ItemInteractor $itemInteractor;
    private Main $main;

    private ?Loader $armorStand = null;

    public function __construct(Main $main)
    {
        $this->main = $main;
        if(($pl = $main->getServer()->getPluginManager()->getPlugin("PMArmorStand")) instanceof Loader){
            $this->armorStand = $pl;
        }
        $this->path = $main->getDataFolder()."Items/";
        parent::__construct($main);
        $this->getPocketmineManager();
        $this->itemInteractor = new ItemInteractor($this);
        $this->itemInteractor->load();

    }
    public function getItemByNamespace(string $namespace) : ?Item{
        foreach ($this->itemList as $item){
            if($item->getItemEntry()->getNamespace() == $namespace){
                return clone $item;
            }
        }
        return null;
    }
    public function getItemsRootPath() : array{
        $list = [];
        foreach ($this->itemList as $item){
            $list[$item->getItemEntry()->getInteractorResult()->getRootPathBaseName()] = $item->getItemEntry()->getInteractorResult()->getRootPathBaseName();
        }
        return $list;
    }
    /**
     * returns items by root path
     * @param string $rootPath
     * @return Item[]
     */
    public function getItemsByRootPath(string $rootPath) : array{
        $list = [];
        foreach ($this->itemList as $item){
            $itemRootPath = $item->getItemEntry()->getInteractorResult()->getRootPathBaseName();
            if(strtolower($itemRootPath) == ($rootPath) or $rootPath == "#all"){
                $list[$item->getItemEntry()->getName()] = clone $item;
            }
        }
        return $list;
    }
    public function isRegistered(int $id, int $meta = -1) : bool{
        foreach ($this->itemList as $item){
            if($item->getId() == $id){
                if($meta === -1){
                    return true;
                }
                if($meta == $item->getMeta()){
                    return true;
                }
            }
        }
        return false;
    }
    public function load() : void{
        $this->pmmanager->init();
        $loaded = 0;
        $err = 0;
        foreach ($this->itemInteractor->getInteractResults() as $result){
            $list = $result->getItemList();
            foreach ($list as $itemId => $itemBundle){
                $entry = new ItemEntry($itemBundle, $result);
                if($entry->isValid()){
                    $this->itemEntryList[$itemId] = $entry;
                    $loaded++;
                }else{
                    Translator::sendLogger("load_item_error", [
                        "{item}" => $itemId,
                        "{path}" => $result->getRootPath()
                    ]);
                    $err++;
                }
            }
        }
        $this->loadRecipes();
        if($this->armorStand)
            $this->loadArmorStands();
        else
            Translator::sendLogger("armor_stand_not_found");
        Translator::sendLogger("loaded_items_info", [
            "{loaded}" => $loaded,
            "{errors}" => $err
        ]);
    }
    public function loadRecipes(){
        foreach ($this->itemEntryList as $entry){
            $recipe = $entry->getCraft()?->getRecipe();
            if($recipe == null) continue;
            if($recipe instanceof ShapedRecipe){
                $this->main->getServer()->getCraftingManager()->registerShapedRecipe($recipe);
            }elseif($recipe instanceof ShapelessRecipe){
                $this->main->getServer()->getCraftingManager()->registerShapelessRecipe($recipe);

            }
        }
    }
    public function loadArmorStands() : void{
        $i = 0;
        foreach ($this->getItemEntryList() as $itemEntry){
            $item = $itemEntry->getItem();
            if($item instanceof Armor){
                $this->armorStand->getBehaviourRegistry()->register($item, new ArmorPieceArmorStandBehaviour($item->getArmorSlot()));
                $i++;
            }
        }
        Translator::sendLogger("armor_stands_loaded", [
            "{count}" => $i
        ]);
    }
    public function convertItems() : void{
        $list = [];
        foreach ($this->itemEntryList as $entry){

            /** @var Sword|Pickaxe|Axe|Shovel|Tool|Armor|Normal $item */
            $item = $entry->getItem();
            $list[] = $item;
            $this->pmmanager->createItem($item);
        }
        $this->itemList = $list;
    }
    public function getPocketmineManager() : PocketmineItemManager{
        if(is_null($this->pmmanager)){
            if(Main::$isMultiAPI){
                $this->pmmanager = new MultiPocketmineItemManager($this);
            }else{
                $this->pmmanager = new PocketmineItemManager($this);
            }
        }
        return $this->pmmanager;
    }
    public function getConfig(): Config
    {
        return $this->config;
    }

    /**
     * @return ItemEntry[]
     */
    public function getItemEntryList(): array
    {
        return $this->itemEntryList;
    }

    /**
     * @return PocketmineItemManager
     */
    public function getPmManager(): PocketmineItemManager
    {
        return $this->pmmanager;
    }

    /**
     * @return array
     */
    public function getItemList(): array
    {
        return $this->itemList;
    }

    /**
     * @return string
     */
    public function getPath(): string
    {
        return $this->path;
    }

    /**
     * @return ItemInteractor
     */
    public function getItemInteractor(): ItemInteractor
    {
        return $this->itemInteractor;
    }
}