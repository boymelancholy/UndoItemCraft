<?php

declare(strict_types=1);

namespace boymelancholy\undoitemcraft;

use boymelancholy\undoitemcraft\commands\RevertCommand;
use boymelancholy\undoitemcraft\events\BaseListener;
use boymelancholy\undoitemcraft\events\ExceptionListener;
use boymelancholy\undoitemcraft\utils\TextContainer;
use pocketmine\plugin\PluginBase;

class UndoItemCraft extends PluginBase {

    /** @var UndoItemCraft */
    private static $instance;

    public function onEnable() {
        self::$instance = $this;
        $this->getServer()->getPluginManager()->registerEvents(new BaseListener(), $this);
        $this->getServer()->getPluginManager()->registerEvents(new ExceptionListener(), $this);
        $this->getServer()->getCommandMap()->register('revert', new RevertCommand());
        $this->registerMessages();
    }

    /**
     * messages.iniのパース
     *
     * @return void
     */
    public function registerMessages() : void {
        if (!file_exists($this->getDataFolder() . 'textcontainer.ini')) {
            $resources = $this->getResources();
            foreach ($resources as $resource) {
                if ($resource->getFilename() === 'textcontainer.ini') {
                    copy($resource->getPathname(), $this->getDataFolder() . 'textcontainer.ini');
                }
            }
        }
        new TextContainer($this->getDataFolder() . 'textcontainer.ini');
    }

    /**
     * インスタンス取得
     *
     * @return UndoItemCraft
     */
    public static function getInstance() : self {
        return self::$instance;
    }
}