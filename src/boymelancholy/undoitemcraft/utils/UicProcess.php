<?php

declare(strict_types=1);

namespace boymelancholy\undoitemcraft\utils;

use pocketmine\Player;

class UicProcess {
    public static $metadata = [];

    /**
     * Process登録
     *
     * @param Player $player
     * @param bool $val
     */
    public static function set(Player $player, bool $val = true) : void {
        self::$metadata[$player->getName()] = $val;
    }

    /**
     * Process確認
     *
     * @param Player $player
     * @return bool
     */
    public static function fetch(Player $player) : bool {
        return self::$metadata[$player->getName()] ?? false;
    }

    /**
     * Process削除
     *
     * @param Player $player
     */
    public static function remove(Player $player) : void {
        unset(self::$metadata[$player->getName()]);
    }
}