<?php

namespace tschrock\InvSee;

use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\plugin\PluginBase;

/**
 * The main plugin class.
 */
class InvSee extends PluginBase {

    public function onLoad() {
        
    }

    public function onEnable() {
        
    }

    public function onDisable() {
        
    }

    private $originalInvs = [];

    public function onCommand(CommandSender $sender, Command $command, string $label, array $args) : bool {
        switch ($command->getName()) {
            case "invsee":
                if ($sender instanceof \pocketmine\Player) {
                    if (count($args) === 0 || $args[0] == "clear") {
                        if (isset($this->originalInvs[$sender->getId()])) {
                            $sender->getInventory()->setContents($this->originalInvs[$sender->getId()]);
                            unset($this->originalInvs[$sender->getId()]);
                            return true;
                        }
                        else {
                        $sender->sendMessage("§5Please use: §2/invsee <player>   §5or   §2/invsee clear");
                           return true;
                        }
                    } else {
                        if (!isset($this->originalInvs[$sender->getId()])) {
                            $player = $this->getServer()->getPlayerExact(array_shift($args));
                            if ($player !== null) {
                                $this->originalInvs[$sender->getId()] = $sender->getInventory()->getContents();
                                $sender->getInventory()->setContents($player->getInventory()->getContents());
                                return true;
                            } else {
                                $sender->sendMessage("§cThat player doesn't exist or isn't online!");
                                return true;
                            }
                        } else {
                            $sender->sendMessage("§dYou are already looking at a player's inventory. Use §5`/invsee` §6to stop looking.");
                            return true;
                        }
                    }
                } else {
                    if (count($args) === 0) {
                        $sender->sendMessage("§5Please use: §2/invsee <player>");
                        return true;
                    } else {
                        $player = $this->getServer()->getPlayerExact(array_shift($args));
                        if ($player !== null) {
                            $contents = $player->getInventory()->getContents();
                            foreach ($contents as $item) {
                                $sender->sendMessage($item->getCount() . " " . $item->getName() . " (" . $item->getId() . ":" . $item->getDamage() . ")");
                                return true;
                            }
                        } else {
                                $sender->sendMessage("§cThat player doesn't exist or isn't online!");
                            return true;
                        }
                    }
                }
                return true;
            default:
                return false;
        }
    }

}
