<?php

declare(strict_types=1);

namespace boymelancholy\undoitemcraft\exceptions;

use pocketmine\Player;

class Exceptioner {

    private static $exceptioners = [];

    /**
     * 例外ケースを取得
     *
     * @param Player $player
     * @param int $default
     * @return Cases
     */
    public static function get(Player $player, int $default = -1) : Cases {
        $id = $player->getUniqueId()->__toString();
        if (isset(self::$exceptioners[$id])) {
            return self::$exceptioners[$id];
        }
        return new Cases($default);
    }

    /**
     * 例外対象に追加
     *
     * @param Player $player
     * @param int $case
     */
    public static function set(Player $player, int $case) {
        $cases = new Cases($case);
        $id = $player->getUniqueId()->__toString();
        if (empty(self::$exceptioners[$id])) {
            self::$exceptioners[$id] = $cases;
        }
    }

    /**
     * 削除
     *
     * @param Player $player
     */
    public static function remove(Player $player) {
        unset(self::$exceptioners[$player->getUniqueId()->__toString()]);
    }
}