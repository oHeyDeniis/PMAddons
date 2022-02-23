<?php

namespace oheydeniis\pma\manager;

use oheydeniis\pma\Main;
use pocketmine\command\CommandSender;
use pocketmine\lang\Language;
use pocketmine\player\Player;
use pocketmine\Server;
use pocketmine\utils\Config;
use pocketmine\utils\SingletonTrait;
use pocketmine\utils\TextFormat;

class Translator
{
    use SingletonTrait;

    const LANGUAGE_FOLDER = "languages/";

    private Main $main;
    private array $languages = [];
    private string $defaultLanguage;

    public function __construct(Main $main, array $supportedLanguages, string $defaultLanguage = "pt_br")
    {
        self::setInstance($this);
        $this->main = $main;
        $this->defaultLanguage = $defaultLanguage;
        $this->loadLanguages($supportedLanguages);

    }
    public static function send(CommandSender $player, string $messageId, array $tags = []) : void{
        $config = self::getInstance()->getLanguageByCode($player instanceof Player ? $player->getLocale() : self::getInstance()->defaultLanguage);
        $message = $config->get($messageId, "message '$messageId' not found");
        $message = str_replace(array_keys($tags), $tags, $message);
        $player->sendMessage(TextFormat::colorize($message));
    }
    public static function sendLogger(string $messageId, array $tags = []){
        $config = self::getInstance()->getLanguageByCode(self::getInstance()->defaultLanguage);
        $message = $config->get($messageId, "message '$messageId' not found");
        $message = str_replace(array_keys($tags), $tags, $message);
        Server::getInstance()->getLogger()->info(TextFormat::colorize($message));
    }
    public function getLanguageByCode(string $code) : Config{
        $code = strtolower($code);
        return $this->languages[$code] ?? $this->languages[$this->defaultLanguage];
    }
    private function loadLanguages(array $list){
        foreach ($list as $code => $file){
            $who = $this->main->getDataFolder().self::LANGUAGE_FOLDER.$file;
            if(is_file($who)){
                $this->languages[strtolower($code)] = new Config($who, Config::YAML, []);
            }else{
                $this->main->getLogger()->info("ERROR: Language file '$file' not found in ".self::LANGUAGE_FOLDER);
            }
        }
    }
}