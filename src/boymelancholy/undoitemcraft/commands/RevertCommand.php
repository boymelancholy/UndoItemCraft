<?php

declare(strict_types=1);

namespace boymelancholy\undoitemcraft\commands;

use boymelancholy\undoitemcraft\utils\TextContainer;
use boymelancholy\undoitemcraft\utils\UicProcess;
use pocketmine\command\Command;
use pocketmine\command\CommandSender;
use pocketmine\Player;

class RevertCommand extends Command {

    public function __construct() {
        parent::__construct('revert', '素材に戻します', '/revert', ['uic', 'rv']);
    }

    /**
     * @inheritDoc
     */
    public function execute(CommandSender $sender, string $commandLabel, array $args) : bool {
        if (!$sender instanceof Player) {
            $sender->sendMessage(TextContainer::get('console_command_sender'));
            return false;
        }
        if (UicProcess::fetch($sender)) {
            UicProcess::set($sender, false);
            $sender->sendMessage(TextContainer::get('process_status_false'));
        } else {
            UicProcess::set($sender);
            $sender->sendMessage(TextContainer::get('process_status_true'));
            $sender->sendMessage(TextContainer::get('command_process'));
        }
        return true;
    }
}