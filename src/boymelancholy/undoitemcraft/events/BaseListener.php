<?php

declare(strict_types=1);

namespace boymelancholy\undoitemcraft\events;

use boymelancholy\kitchen\Tray;
use boymelancholy\undoitemcraft\exceptions\Exceptioner;
use boymelancholy\undoitemcraft\utils\TextContainer;
use boymelancholy\undoitemcraft\utils\UicProcess;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\event\player\PlayerQuitEvent;

class BaseListener implements Listener {

    /**
     * PlayerInteractEvent
     *
     * @param PlayerInteractEvent $event
     * @priority HIGHEST
     * @ignoreCancelled true
     */
    public function onInteract(PlayerInteractEvent $event) {
        $player = $event->getPlayer();
        $cases = Exceptioner::get($player);
        if ($cases->isDefined()) {
            Exceptioner::remove($player);
            $message = $cases->getMessage();
            if ($message == null) {
                return;
            }
            $player->sendMessage($message);
            return;
        }

        if (!Tray::isOrdered($player)) {
            return;
        }

        $payment = Tray::getPayment($player);
        $ingredients = Tray::getServing($player);

        $player->getInventory()->removeItem($payment);
        $player->getInventory()->addItem(...$ingredients);
        $player->getInventory()->sendContents($player);
        $player->sendMessage(TextContainer::get('completed_revert_item'));
        Tray::cancel($player);
    }

    /**
     * PlayerQuitEvent
     *
     * @param PlayerQuitEvent $event
     */
    public function onQuit(PlayerQuitEvent $event) {
        UicProcess::set($event->getPlayer());
        Exceptioner::remove($event->getPlayer());
        Tray::cancel($event->getPlayer());
    }
}