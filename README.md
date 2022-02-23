![Screenshot_20220219-134451_Chrome](https://user-images.githubusercontent.com/35070032/154810287-b18ade1e-ce6a-46e3-adfc-e9777dccc8dc.jpg)


# PMAddons
Allow addons of items, blocks, entities

```NOTE: This version is limited to creating custom items only, updates coming soon to new blocks and entities.```

## Create Custom Items

> Creating new items is done in a simple way, you must create a folder with the name of your item set in the folder:


![tree](https://user-images.githubusercontent.com/35070032/154786116-ae476b7b-c86f-41e4-a2e0-089dd5015ebf.png)

> Inside your 'conjunt_name' folder you should create an items.yml file
> From there, all the things that your item needs (Image, templates and others) should be in the same folder as your items.yml
> Inside the items.yml file you will define the components of your items following the default template:
 
 ```
 * name: 
 * id:
 * meta: 
 * namepspace:
 * texture:
 * category:
```

## name
Default name of your item (can be any)
## id
Custom id for your item (any id number that doesn't already exist)
## meta
meta for your item (can be 0)
## namespace
Signature of your item (any name that is not 'minecraft', 'minecon') + nickname of your item
example: "oheydeniiis:emerald_armor"
## texture
Name of the .png image (must be inside your 'conjunt_name' folder), this will be your item's icon
## category
Category of your item, according to the category of the item it can get more components to be defined, you can read about components here.

## craft
You can define the craft of this item using this option

Crafts can be defined as Simple (2x2) or Complex (3x3)

the pre-defined categories are:
### sword
Sword category<br/>
Additional components:
```
* attack_damage:
* hand_equipped:
* max_durability
```
### armor
Armor category<br/>
Additional components:
```
* max_durability:
* defender_points:
* armor_slot:
* armor_type:
* damage_change:
* dispensable
* protection
```
### tool
Tools like:
```
* Pickaxe
* Axe
* Shovel
* Hoe
* Scissors
```
Additional components:
```
* tool_name
* tool_type
* mining_speed
```
### food
All food in general <br/>
Additional components:
```
* use_duration
* eat_saturation
* eat_nutrition
* saturation_modifier
* residue: (id: meta:)
```
### normal
All other items as ores,eggs... <br/>
Additional components:
```
action_type: (spawnable, command
action_data: (ex: minecraft:creeper, /give {player} 1)
action_infinite: (true, false)
```
<br/><br/>

See examples of ready-made categories:

### sword
```
  name: my sword
  id: 974
  meta: 0
  namespace: "custom:my_sword"
  texture: my_sword
  category: sword
  attack_damage: 10
  hand_equipped: true
```
### armor
```
 name: my boots
  id: 975
  meta: 0
  namespace: "custom:my_boots"
  texture: my_boots
  category: armor
  armor_slot: boots
  defender_points: 11
```
### tool
```
  name: my pickaxe
  id: 976
  meta: 0
  namespace: "custom:my_pickaxe"
  texture: my_pickaxe
  category: tool
  tool_name: pickaxe
  mining_speed: 5
```
### food
```
  name: my food
  id: 977
  meta: 0
  namespace: "custom:my_food"
  texture: my_food
  category: food
  eat_nutrition: 5
  eat_saturation: 6
  use_duration: 40
  residue:
    id: 1
    meta: 0
```
### normal
```
  name: my item
  id: 978
  meta: 0
  namespace: "custom:my_item"
  texture: my_item
  category: normal
  action_type: spawnable
  action_data: "minecraft:zombie"
```
<br/>
You will find working examples inside the 'Examples' folder.<br/>
To get the items use:<br/><br/>

* /give [player] [id]

ou 

* /agive [player] [namespace|id]
* /agive [player] list [#all|list_path]
 

# All components of the items
Here you will find all the components of the items with a brief explanation:<br/>

(The translation ended up changing unexpected things, so the typos will soon be corrected)

## `name:`
* `TYPE: required (any name)`
* `DEFAULT: no pattern defined`
* `USABLE CATEGORIES: All`
* `EXPLANATION: Default name of items`
## `id:'
* `TYPE: Required (numbers only)`
* `DEFAULT: no pattern defined` 
* `USABLE CATEGORIES: All` 
* `EXPLANATION: Unique item id` 
## `meta:'
* `TYPE: Required (numbers only)`
* `DEFAULT: 0`
* `USABLE CATEGORIES: All`
* `EXPLANATION: Goal for complex items`
## `namepsace:'
* `TYPE: required` *
* `EXAMPLE: custom:my_item`
* `DEFAULT: no pattern defined`
* `USABLE CATEGORIES: All` *
* `EXPLANATION: Item identifier`
## `texture:'
* `TYPE: required` *
* `PATTER: no pattern defined`
* `USABLE CATEGORIES: All`
* `EXPLANATION: Name of the item texture present in the item folder`
##  max_stack:''
* `TYPE: Required (numbers only)`
* `DEFAULT: 1 or 64`
* `USABLE CATEGORIES: All`
* `EXPLANATION: Maximum stacking of items``
## `creative_inventory:'
* `TYPE: optional (true or false)`
* `DEFAULT: false``
* `USABLE CATEGORIES: All`
* `EXPLANATION: Add item to creative inventory`
##  max_durability:''
* `TYPE: optional (numbers only)`
* `DEFAULT: 100``
* `USABLE CATEGORIES: armor,sword,tool`
* `EXPLANATION: maximum durability of item``

## `attack_damage:'
* `TYPE: optional (numbers only)`
* `DEFAULT: 9`
* `USABLE CATEGORIES: sword`
* `EXPLANATION: sword base attack damage`
## `hand_equipped:'
* `TYPE: optional (true or false`)
* `DEFAULT: false`
* `USABLE CATEGORIES: all``
* `EXPLANATION: Makes the item stay in the hand equipped like a sword`
## `defender_points:'
* `TYPE: optional (numbers only)`
* `DEFAULT: 10`
* `USABLE CATEGORIES: armor`
* `EXPLANATION: points`
* `EXPLANATION: armor defense points`
## ``armor_slot:''
* `TYPE: mandatory (pre-defined types)`
* `TYPES: helmet, chestplate, leggings, boots`
* ` `PATtern: no defaults defined`
* `USABLE CATEGORIES: armor` 
* `EXPLANATION: Slot of armor to be equipped`
## `armor_type:'
* `TYPE: optional (pre-defined types)`
* `TYPES: gold'', leather'', chain'', iron'', diamond'', netherite'', elytra, turtle`
* `DEFAULT: diamond`
* `USABLE CATEGORIES: armor``
* `EXPLANATION: Armor Texture Type`
## `tool_name:'
* `TYPE: must (pre-defined types)`
* `TYPES: "shovel", "pickaxe", "axe", "shears", "hoe"`
* `DEFAULT: no default `
* `USABLE CATEGORIES: tool`
* `EXPLANATION: item type`
## `tool_type:'
* `TYPE: mandatory (pre-defined types)`
* `TYPES: "wooden", "gold", "stone", "iron", "diamond"`
* ` `DEFAULT: diamond``
* `USABLE CATEGORIES: tool`
* `EXPLANATION: item texture type`
## `creative_eat:'
* `TYPE: optional (true and false)`
* `DEFAULT: true``
* `USABLE CATEGORIES: food`
* `EXPLANATION: eat the item in creative`
## eat_nutrition:''
* `TYPE: required (numbers only)`
* `DEFAULT: 1`
* `USABLE CATEGORIES: food`
* `EXPLANATION: Nutrition when eating the item`
## eat_saturation:''
* `TYPE: Required (numbers only)`
* `DEFAULT: 1`
* `USABLE CATEGORIES: food`
* `EXPLANATION: Saturation when eating the item`
## `use_duration:'
* `TYPE: Required (numbers only) `
* `DEFAULT: 1`
* `USED CATEGORIES: food`
* `EXPLANATION: time to eat the item` 

## 'dependencies:'
* `TYPE: array of "target_folder: file`
* `DEFAULT: undefined`
* 'USABLE CATEGORIES: all`
* `EXPLANATION: If any items need additional images or files in the texture, they should be placed here to be automatically added to the texture` 
