<?php

declare(strict_types=1);

namespace boymelancholy\undoitemcraft\exceptions;

use boymelancholy\undoitemcraft\utils\TextContainer;
use ReflectionClass;

class Cases {

    const UNDEFINED = -1;
    const UNKNOWN = 0;
    const INTERACT_NOT_WORKBENCH = 1;
    const INTERACT_NOT_SNEAKING = 2;
    const INVENTORY_NOT_ENOUGH_SPACE = 3;
    const PROCESS_NOT_TRUE = 4;
    const INTERACT_WITH_USED_ITEM = 5;
    const NOT_HAS_ENOUGH_COUNT = 6;
    const CANT_REVERT_IT = 7;
    const INTERACT_WITH_NATURE_ITEM = 8;

    /** @var int */
    private $case;

    /** @var string */
    private $errorMessage;

    public function __construct(int $case = 0) {
        $this->case = $case;

        $oClass = new ReflectionClass( __CLASS__ );
        $const = (array_flip($oClass->getConstants()))[$case];
        $this->errorMessage = TextContainer::get(strtolower($const));
    }

    /**
     * ケースの確認
     *
     * @return int
     */
    public function get() : int {
        return $this->case;
    }

    /**
     * エラーが定義済みか確認
     *
     * @return bool
     */
    public function isDefined() : bool {
        return $this->case !== self::UNDEFINED;
    }

    /**
     * エラーメッセージの返却
     *
     * @return string|null
     */
    public function getMessage() : ?string {
        return $this->errorMessage;
    }
}