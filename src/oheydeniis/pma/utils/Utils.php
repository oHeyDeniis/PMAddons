<?php

namespace oheydeniis\pma\utils;

use pocketmine\nbt\tag\CompoundTag;
use pocketmine\nbt\tag\Tag;

class Utils
{

    /**
     * @param Tag[] $tagList
     */
    public static function convertArrayToCompound(array $tagList) : CompoundTag{
        $ctag = new CompoundTag();
        foreach ($tagList as $name => $tag){
            $ctag->setTag($name, $tag);
        }
        return $ctag;
    }
}