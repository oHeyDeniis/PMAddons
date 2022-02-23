<?php

namespace oheydeniis\pma\items\category;

class NormalCategory extends ItemCategory
{

    const ACTION_SPAWNABLE = "spawnable";
    const ACTION_COMMAND = "command";

    public array $actions = [
        self::ACTION_COMMAND,
        self::ACTION_SPAWNABLE
    ];

    public function hasAction() : bool{
        return $this->getActionType() != "-";
    }
    public function getActionType() : string{
        return $this->bundle["action_type"] ?? "-";
    }
    public function getActionData() : string{
        return $this->bundle["action_data"];
    }
    public function isInfiniteAction() : bool{
        return (bool) ($this->bundle["action_infinite"] ?? false);
    }
    public function isValid(): bool
    {
        try {
            if($this->hasAction()){
                $this->getActionData();
                $this->actions[array_search($this->getActionType(), $this->actions)];
            }
            return true;
        }catch (\Exception $e){
            $this->print($e);
            return false;
        }
    }
}