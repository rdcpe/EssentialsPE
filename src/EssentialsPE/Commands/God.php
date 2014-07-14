<?php
namespace EssentialsPE\Commands;

use EssentialsPE\BaseCommand;
use EssentialsPE\Loader;
use pocketmine\command\CommandSender;
use pocketmine\Player;
use pocketmine\utils\TextFormat;

class God extends BaseCommand{
    public function __construct(Loader $plugin){
        parent::__construct($plugin, "god", "Prevent you to take any damage", "/god [player]", ["godmode", "tgm"]);
        $this->setPermission("essentials.command.god.use");
    }

    public function execute(CommandSender $sender, $alias, array $args){
        if(!$this->testPermission($sender)){
            return false;
        }
        if(count($args) > 1){
            if(!$sender instanceof Player){
                $sender->sendMessage(TextFormat::RED . "Usage: /god <player>");
            }else{
                $sender->sendMessage(TextFormat::RED . "Usage: " . $this->getUsage());
            }
            return false;
        }
        switch(count($args)){
            case 0:
                if(!$sender instanceof Player){
                    $sender->sendMessage(TextFormat::RED . "Usage: /god <player>");
                    return false;
                }
                $this->plugin->switchGodMode($sender);
                if(!$this->plugin->isGod($sender)){
                    $sender->sendMessage(TextFormat::AQUA . "God mode disabled");
                }else{
                    $sender->sendMessage(TextFormat::AQUA . "God mode enabled!");
                }
                return true;
                break;
            case 1:
                if(!$sender->hasPermission("essentials.command.god.other")){
                    $sender->sendMessage(TextFormat::RED . $this->getPermissionMessage());
                    return false;
                }
                $player = $this->plugin->getPlayer($args[0]);
                if($player === false){
                    $sender->sendMessage(TextFormat::RED . "[Error] Player not found");
                }else{
                    $this->plugin->switchGodMode($player);
                    if(!$this->plugin->isGod($player)){
                        $sender->sendMessage(TextFormat::AQUA . "God mode disabled for $args[0]");
                        $player->sendMessage(TextFormat::AQUA . "God mode disabled");
                    }else{
                        $sender->sendMessage(TextFormat::AQUA . "God mode enabled for $args[0]");
                        $player->sendMessage(TextFormat::AQUA . "God mode enabled!");
                    }
                }
                return true;
                break;
        }
        return true;
    }
} 