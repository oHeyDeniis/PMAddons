<?php

namespace oheydeniis\pma;

use oheydeniis\pma\manager\LoaderManager;
use oheydeniis\pma\manager\TextureManager;
use oheydeniis\pma\manager\Translator;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\event\Listener;
use pocketmine\item\Item;
use pocketmine\item\ItemFactory;
use pocketmine\player\Player;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\Config;

class Main extends PluginBase implements Listener
{

    private LoaderManager $loaderManager;
    private EventListener $listener;
    private TextureManager $textureManager;
    private Translator $translator;

    private Config $rules;
    /**
     * In pocketmine 3.0.0 brazilian servers have a api with multi versions support, this is only for this
     * @var bool
     */
    public static bool $isMultiAPI = false;

    public function onEnable() : void
    {

        $this->getServer()->getPluginManager()->registerEvents($this, $this);

        $this->saveResource(Translator::LANGUAGE_FOLDER."portugues.yml");
        $this->saveResource(Translator::LANGUAGE_FOLDER."english.yml");

        $this->rules = new Config($this->getDataFolder()."Rules.yml", Config::YAML, [
            "supported_languages" => [
                "pt_br" => "portugues.yml",
                "eng_us" => "english.yml"
            ],
            "default_language" => "pt_br"
        ]);
        $this->translator = new Translator($this, $this->rules->get("supported_languages", []), $this->rules->get("default_language", "pt_br"));
        $this->loaderManager = new LoaderManager($this);
        $this->listener = new EventListener($this);
        $this->textureManager = new TextureManager($this);
        $this->textureManager->reloadTextures();

    }
    public function onCommand(CommandSender $sender, Command $command, string $label, array $args): bool
    {
        if ($command->getName() == "areload" and $sender->hasPermission("areload.use")) {
            $this->textureManager->build();
            Translator::send($sender, "textures_reloaded");
        }
        if ($command->getName() == "agive" and $sender->hasPermission("agive.use")) {
            if (!isset($args[0])) {
                Translator::send($sender, "agive_usages");
                return false;
            }
            switch (strtolower($args[0])) {
                case "list":
                    foreach ($this->getLoaderManager()->getItemLoader()->getItemsRootPath() as $rootPath){
                        $sender->sendMessage("§e$rootPath: ");
                        $list = array_keys($this->getLoaderManager()->getItemLoader()->getItemsByRootPath($rootPath));
                        $sender->sendMessage("§7-> ".implode(", ", $list));
                    }
                    break;
                default:
                    if (is_numeric($args[0]) or str_contains($args[0], ":")) {
                        $target = $sender;
                    } else {
                        $target = $this->getServer()->getPlayerByPrefix($args[0]);
                    }
                    if (!($target instanceof Player)) {
                        Translator::send($sender, "player_not_found");
                        break;
                    }
                    switch ($args[1]) {
                        case "list":
                            $rootPath = $args[2] ?? "#all";
                            $items = $this->getLoaderManager()->getItemLoader()->getItemsByRootPath($rootPath);
                            $names = [];
                            foreach ($items as $item) {
                                $target->getInventory()->addItem($item);
                                $names[] = $item->getName();
                            }
                            $list = implode(", ", $names);
                            Translator::send($sender, "given_list_items", [
                                "{rootPath}" => $rootPath,
                                "{target}" => $target->getName(),
                                "{list}" => $list
                            ]);
                            break;
                        default:
                            $item = $this->processAddonItem($target, $args[1], is_numeric(($args[2] ?? "")) ? $args[2] : 1);
                            if (!($item instanceof Item)) {
                                Translator::send($sender, "item_not_found", [
                                    "{item}" => $args[1]
                                ]);
                                break;
                            }

                            $target->getInventory()->addItem($item);
                            Translator::send($sender, "item_send_successfully", [
                                "{item}" => $item->getName(),
                                "{count}" => $item->getCount(),
                                "{target}" => $target->getName()
                            ]);
                            break;
                    }
                    break;
            }
        }
        return parent::onCommand($sender, $command, $label, $args);
    }
    public function processAddonItem(Player $p, string|int $item, int $count) : ?Item{
        $iloader = $this->getLoaderManager()->getItemLoader();
        if(is_numeric($item)){
            if($iloader->isRegistered($item)){
                return ItemFactory::getInstance()->get($item, 0, $count);
            }
        }else{
            $ex = explode(":", $item);
            $item = $ex[0];
            $meta = is_numeric(($ex[1] ?? "")) ? $ex[1] : 0;
            if(is_string($item)){
                return $iloader->getItemByNamespace($item);
            }elseif (is_numeric($item)){
                if($iloader->isRegistered($item, $meta)){
                    return ItemFactory::getInstance()->get($item, $meta, $count);
                }
            }
        }
        return null;
    }
    /**
     * @return LoaderManager
     */
    public function getLoaderManager(): LoaderManager
    {
        return $this->loaderManager;
    }
}