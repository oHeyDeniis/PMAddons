<?php

namespace oheydeniis\pma\items\craft;

use oheydeniis\pma\Main;
use pocketmine\inventory\ShapelessRecipe;
use pocketmine\item\Item;

class CraftingData
{

    private Main $main;

    public function __construct(Main $main)
    {

        $this->main = $main;
        $this->registerRecipes();
    }
    public function registerRecipes(){
        $craft = $this->main->getServer()->getCraftingManager();
        $recipes = [
            //enderite
            new ShapelessRecipe([
                Item::get(907), Item::get(907), Item::get(907),
                Item::get(907), Item::get(276), Item::get(907),
                Item::get(907), Item::get(907), Item::get(907)
            ],
            [Item::get(900)] //sword
            ),
            new ShapelessRecipe([
                Item::get(907), Item::get(907), Item::get(907),
                Item::get(907), Item::get(Item::DIAMOND_HELMET), Item::get(907),
                Item::get(907), Item::get(907), Item::get(907)
            ],
                [Item::get(903)] //helmet
            ),
            new ShapelessRecipe([
                Item::get(907), Item::get(907), Item::get(907),
                Item::get(907), Item::get(Item::DIAMOND_CHESTPLATE), Item::get(907),
                Item::get(907), Item::get(907), Item::get(907)
            ],
                [Item::get(904)] //chestplate
            ),
            new ShapelessRecipe([
                Item::get(907), Item::get(907), Item::get(907),
                Item::get(907), Item::get(Item::DIAMOND_LEGGINGS), Item::get(907),
                Item::get(907), Item::get(907), Item::get(907)
            ],
                [Item::get(905)] //leggings
            ),
            new ShapelessRecipe([
                Item::get(907), Item::get(907), Item::get(907),
                Item::get(907), Item::get(Item::DIAMOND_BOOTS), Item::get(907),
                Item::get(907), Item::get(907), Item::get(907)
            ],
                [Item::get(906)] //boots
            ),

            //ruby
            new ShapelessRecipe([
                Item::get(921), Item::get(921), Item::get(921),
                Item::get(921), Item::get(900), Item::get(921),
                Item::get(921), Item::get(921), Item::get(921)
            ],
                [Item::get(914)] //sword
            ),
            new ShapelessRecipe([
                Item::get(921), Item::get(921), Item::get(921),
                Item::get(921), Item::get(903), Item::get(921),
                Item::get(921), Item::get(921), Item::get(921)
            ],
                [Item::get(917)] //helmet
            ),
            new ShapelessRecipe([
                Item::get(921), Item::get(921), Item::get(921),
                Item::get(921), Item::get(904), Item::get(921),
                Item::get(921), Item::get(921), Item::get(921)
            ],
                [Item::get(918)] //chestplate
            ),
            new ShapelessRecipe([
                Item::get(921), Item::get(921), Item::get(921),
                Item::get(921), Item::get(905), Item::get(921),
                Item::get(921), Item::get(921), Item::get(921)
            ],
                [Item::get(919)] //leggings
            ),
            new ShapelessRecipe([
                Item::get(921), Item::get(921), Item::get(921),
                Item::get(921), Item::get(906), Item::get(921),
                Item::get(921), Item::get(921), Item::get(921)
            ],
                [Item::get(920)] //boots
            ),
            /*new ShapelessRecipe([
                Item::get(0), Item::get(0), Item::get(0),
                Item::get(0), Item::get(0), Item::get(0),
                Item::get(0), Item::get(0), Item::get(0)
            ],
                [Item::get(0)]
            ),*/
        ];
        foreach ($recipes as $recipe){
            $craft->registerRecipe($recipe);
        }

    }
}