<?php

namespace oheydeniis\pma\items\item;

use oheydeniis\pma\items\category\NormalCategory;
use oheydeniis\pma\items\ItemEntry;
use oheydeniis\pma\manager\Translator;
use oheydeniis\pma\utils\Utils;
use pocketmine\block\Block;
use pocketmine\console\ConsoleCommandSender;
use pocketmine\data\SavedDataLoadingException;
use pocketmine\entity\Entity;
use pocketmine\entity\EntityFactory;
use pocketmine\item\Item;
use pocketmine\item\ItemIdentifier;
use pocketmine\item\ItemUseResult;
use pocketmine\math\Vector3;
use pocketmine\nbt\tag\DoubleTag;
use pocketmine\nbt\tag\FloatTag;
use pocketmine\nbt\tag\IntTag;
use pocketmine\nbt\tag\ListTag;
use pocketmine\nbt\tag\StringTag;
use pocketmine\player\Player;

class Normal extends Item
{
    use AddonItem;

    public function construct(ItemIdentifier $identifier, string $name = "Unknown")
    {
        parent::__construct($identifier, $name);
    }
    public function onClickAir(Player $player, Vector3 $directionVector): ItemUseResult
    {
        return parent::onClickAir($player, $directionVector);
    }

    public function onInteractBlock(Player $player, Block $blockReplace, Block $blockClicked, int $face, Vector3 $clickVector): ItemUseResult
    {
        $category = $this->getItemEntry()->getCategory();
        if($category->hasAction()){
            switch ($category->getActionType()){
                case NormalCategory::ACTION_SPAWNABLE:
                    $id = match (is_numeric($category->getActionData())){
                        true => new IntTag($category->getActionData()),
                        false => new StringTag($category->getActionData())
                    };
                    try {
                        $entity = EntityFactory::getInstance()->createFromData($blockClicked->getPosition()->getWorld(), Utils::convertArrayToCompound([
                            ($id instanceof StringTag ? "identifier" : "id") => $id,
                            "Pos" => new ListTag([
                                new DoubleTag($blockClicked->getPosition()->x + 0.5),
                                new DoubleTag($blockClicked->getPosition()->y),
                                new DoubleTag($blockClicked->getPosition()->z + 0.5)
                            ]),
                            "Rotation" => new ListTag([
                                new FloatTag((float) lcg_value() * 360),
                                new FloatTag((float) 0)
                            ])
                        ]));
                        $entity?->spawnToAll();
                        if($entity == null){
                            Translator::sendLogger("invalid_entity_id", [
                                "{entity}" => $category->getActionData(),
                                "{player}" => $category->getActionData()
                            ]);
                        }
                    }catch (SavedDataLoadingException $e){
                        $player->getServer()->getLogger()->info($e->getMessage()."\n".$e->getTraceAsString()."\n§eEntity id: ".$category->getActionData());
                    }
                    break;
                case NormalCategory::ACTION_COMMAND:
                    $command = str_replace(["/", "{player}", "{p}"], ["", $player->getName(), $player->getName()], $category->getActionData());
                    $player->getServer()->dispatchCommand(new ConsoleCommandSender($player->getServer(), $player->getLanguage()), $command);
                    break;
            }
            if(!$category->isInfiniteAction()) {
                $item = $this->setCount($this->getCount() - 1);
                $player->getInventory()->setItemInHand($item);
            }
        }
        return parent::onInteractBlock($player, $blockReplace, $blockClicked, $face, $clickVector);
    }

}