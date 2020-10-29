<?php

declare(strict_types=1);

namespace boymelancholy\undoitemcraft\utils;

use Exception;
use boymelancholy\undoitemcraft\UndoItemCraft;
use pocketmine\Player;
use pocketmine\scheduler\ClosureTask;

class PlayerFlag {

    /** @var Player */
    private $player;

    /** @var UndoItemCraft */
    private $main;

    private static $metadata = [];

    public function __construct(Player $player, UndoItemCraft $main) {
        $this->player = $player;
        $this->main = $main;
    }

    /**
     * フラグのセット
     */
    public function set() : void {
        $this->setMetadata(true);
        $this->main->getScheduler()->scheduleDelayedTask(
            new ClosureTask(
                function (int $currentTick) : void {
                    $this->setMetadata(false);
                }
            ), 7
        );
    }

    /**
     * 任意ブール値の付与
     *
     * @param bool $val
     */
    private function setMetadata(bool $val) : void {
        self::$metadata[$this->player->getId()] = $val;
    }

    /**
     * フラグを取得
     *
     * @return bool
     */
    public function get() : bool {
        try {
            return self::$metadata[$this->player->getId()];
        } catch (Exception $e) {
            return false;
        }
    }
}