![Screenshot_20220219-134451_Chrome](https://user-images.githubusercontent.com/35070032/154810287-b18ade1e-ce6a-46e3-adfc-e9777dccc8dc.jpg)


# PMAddons
Allow addons of items, blocks, entities

```NOTE: This version is limited to creating custom items only, updates coming soon to new blocks and entities.```

## Create Custom Items

> A criação de novos items é feitada da maneira mais simples, você deve criar uma pasta com o nome do seu conjunto de item na pasta:


![tree](https://user-images.githubusercontent.com/35070032/154786116-ae476b7b-c86f-41e4-a2e0-089dd5015ebf.png)

> Dentro da sua pasta 'nome_do_conjunto' você deve criar um arquivo items.yml
> A apartir dai, todas as coisas que seu item precisa (Imagem, modelos e outros) devem ficar nessa mesma pasta que esta seu items.yml
> Dentro do arquivo items.yml você definirá os components de seus itens seguindo o modelo padrão:
 
 ```
 * name: 
 * id:
 * meta: 
 * namepspace:
 * texture:
 * category:
```

## name
Nome padrão do seu item (Pode ser qualquer um)
## id
Id customizado para seu item (qualquer numero de id que ainda não exista)
## meta
Meta para seu item (pode ser 0)
## namespace
Assinatura do seu item (qualquer nome que não seja 'minecraft', 'minecon') + apelido do seu item
exemplo: heydeniiis:emerald_armor
## texture
Nome da imagem .png (deve estar dentro da sua pasta 'Nome_do_conjunto'), isso vai ser o icone do seu item
## category
Categoria do seu item, de acordo com a categoria do item ele pode ganhar mais components para ser definido, você pode ler sobre os components aqui. 

## craft
Você pode definir qual será o craft desse item usando essa opção

Os crafts podem ser definidos em Simples (2x2) ou complexos (3x3)


as categorias pre definidas são:
### sword
Categoria de espadas<br/>
Components adicionais:
```
* attack_damage: 
* hand_equipped:
* max_durability
```
### armor
Categoria de armaduras<br/>
Componentes adicionais:
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
Ferramentas como:
```
* Picareta
* Machado
* Pá
* Enxada
* Tesoura
```
Componentes adicionais:
```
* tool_name
* tool_type
* mining_speed
```
### food
Todas as comidas em geral <br/>
Componentes adicionais:
```
* use_duration
* eat_saturation
* eat_nutrition
* saturation_modifier
* residue: (id: meta:)
```
### normal
Todos os outros item como minerios<br/>
Componentes adicionais:
```
action_type: (spawnable, command
action_data: (ex: minecraft:creeper, /give {player} 1)
action_infinite: (true, false)
```
<br/><br/>

Veja alguns exemplos dessas categorias prontas:
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
Você encontrará exemplos funcionais dentro da pasta 'Examples'.<br/>
Para pegar os itens use:<br/><br/>

* /give [player] [id]

ou 

* /agive [player] [namespace|id]
* /agive [player] list [#all|list_path]
 

# Todos os compoenentes dos items
Aqui você encontra todas os componentes dos items com uma breve explicação:<br/>

## 'name:'
* `TIPO: obrigatorio (qualquer nome)`
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: Todas`
* `EXPLICAÇÃO: Nome padrão do items`
## 'id:'
* `TIPO: obrigatorio (apenas numeros)`
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: todas`
* `EXPLICAÇÃO: Id unico do item`
## 'meta:'
* `TIPO: obrigatorio (apenas numeros`
* `PADRÃO: 0`
* `CATEGORIAS USAVÉIS: todas`
* `EXPLICAÇÃO: Meta para itens complexos`
## 'namepsace:'
* `TIPO: obrigatorio`
* `EXEMPLO: custom:my_item`
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: todas`
* `EXPLICAÇÃO: Identificador do item`
## 'texture:'
* `TIPO: obrigatorio`
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: todas`
* `EXPLICAÇÃO: Nome da textura do item presente na pasta do item`     
## 'max_stack:'
* `TIPO: obrigatorio (apenas numeros`
* `PADRÃO: 1 ou 64`
* `CATEGORIAS USAVÉIS: todas`
* `EXPLICAÇÃO: Empilhamento maximo dos itens`
## 'creative_inventory:'
* `TIPO: opicional (true ou false)`
* `PADRÃO: false`
* `CATEGORIAS USAVÉIS: todas`
* `EXPLICAÇÃO: Adicionar item ao inventario criativo` 
## 'max_durability:'
* `TIPO: opicional (apenas numeros)`
* `PADRÃO: 100`
* `CATEGORIAS USAVÉIS: armor,sword,tool`
* `EXPLICAÇÃO: Durabilidade maxima do item` 
## 'attack_damage:'
* `TIPO: opicional (apenas numeros)`
* `PADRÃO: 9`
* `CATEGORIAS USAVÉIS: sword`
* `EXPLICAÇÃO: dano de ataque base da espada`  
## 'hand_equipped:'
* `TIPO: opicional (true ou false`
* `PADRÃO: false`
* `CATEGORIAS USAVÉIS: todas`
* `EXPLICAÇÃO: Faz o item ficar na mão equipado como uma espada` 
## 'defender_points:'
* `TIPO: opicional (apenas numeros)`
* `PADRÃO: 10`
* `CATEGORIAS USAVÉIS: armor`
* `EXPLICAÇÃO: pontos de defesa da armadura`
## 'armor_slot:'
* `TIPO: obrigatorio (tipos pre definidos)`
* `TIOPS: "helmet", "chestplate", "leggings", "boots"`
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: armor`
* `EXPLICAÇÃO: Slot da armadura a ser equipado`   
## 'armor_type:'
* `TIPO: opcional (tipos pre definidos)`
* `TIOPS: "gold", "leather", "chain", "iron", "diamond", "netherite", "elytra", "turtle"`
* `PADRÃO: diamond`
* `CATEGORIAS USAVÉIS: armor`
* `EXPLICAÇÃO: Tipo da textura da armadura` 
## 'tool_name:'
* `TIPO: obrigatorio (tipos pre definidos)`
* `TIOPS: "shovel", "pickaxe", "axe", "shears", "hoe"`
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: tool`
* `EXPLICAÇÃO: tipo do item`  
## 'tool_type:'
* `TIPO: obrigatorio (tipos pre definidos)`
* `TIOPS: "wooden", "gold", "stone", "iron", "diamond"`
* `PADRÃO: diamond`
* `CATEGORIAS USAVÉIS: tool`
* `EXPLICAÇÃO: tipo da textura do item` 
## 'creative_eat:'
* `TIPO: opcional (true e false)`
* `PADRÃO: true`
* `CATEGORIAS USAVÉIS: food`
* `EXPLICAÇÃO: Comer o item no creativo`  
## 'eat_nutrition:'
* `TIPO: obrigatorio (apenas numeros)`
* `PADRÃO: 1`
* `CATEGORIAS USAVÉIS: food`
* `EXPLICAÇÃO: Nutrição ao comer o item` 
## 'eat_saturation:'
* `TIPO: obrigatorio (apenas numeros)`
* `PADRÃO: 1`
* `CATEGORIAS USAVÉIS: food`
* `EXPLICAÇÃO: Saturação ao comer o item`  
## 'use_duration:'
* `TIPO: obrigatorio (apenas numeros)`
* `PADRÃO: 1`
* `CATEGORIAS USAVÉIS: food`
* `EXPLICAÇÃO: tempo para comer o item` 
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: `  
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: ` 
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: `  
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: ` 
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: `  
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: ` 
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: `  
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: ` 
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: `  
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: ` 
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: `  
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: ` 
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: `  
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: ` 
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: `  
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: ` 
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: `  
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: ` 
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: `  
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: ` 
## ':'
* `TIPO: `
* `PADRÃO: sem padrão definido`
* `CATEGORIAS USAVÉIS: `
* `EXPLICAÇÃO: `  





















