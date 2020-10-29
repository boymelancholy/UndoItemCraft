<?php

declare(strict_types=1);

namespace boymelancholy\undoitemcraft\events;

use boymelancholy\kitchen\Tray;
use boymelancholy\kitchen\Kitchen;
use boymelancholy\undoitemcraft\UndoItemCraft;
use boymelancholy\undoitemcraft\exceptions\Cases;
use boymelancholy\undoitemcraft\exceptions\Exceptioner;
use boymelancholy\undoitemcraft\utils\PlayerFlag;
use boymelancholy\undoitemcraft\utils\UicProcess;
use pocketmine\block\CraftingTable;
use pocketmine\event\Listener;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\item\Item;

class ExceptionListener implements Listener {

    /**
     * 重複タップの回避
     *
     * @param PlayerInteractEvent $event
     * @return void
     * @priority LOWEST
     */
    public function onAvoidMultipleTap(PlayerInteractEvent $event) : void {
        $playerFlag = new PlayerFlag($event->getPlayer(), UndoItemCraft::getInstance());
        if ($playerFlag->get()) {
            $event->setCancelled();
            return;
        }
        if ($event->getAction() == PlayerInteractEvent::LEFT_CLICK_BLOCK) {
            $playerFlag->set();
        }
    }

    /**
     * @param PlayerInteractEvent $event
     * @return void
     * @priority LOW
     * @ignoreCancelled true
     */
    public function onSetAvoidCases(PlayerInteractEvent $event) : void {
        if ($event->getAction() != PlayerInteractEvent::LEFT_CLICK_BLOCK) {
            return;
        }
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if (!UicProcess::fetch($player)) {
            Exceptioner::set($player, Cases::PROCESS_NOT_TRUE);
            return;
        }
        if (!$block instanceof CraftingTable) {
            Exceptioner::set($player, Cases::INTERACT_NOT_WORKBENCH);
            return;
        }
        $item = $event->getItem();
        if (!$player->isSneaking()) {
            Exceptioner::set($player, Cases::INTERACT_NOT_SNEAKING);
            return;
        }

        Tray::cancel($player);
        $kitchen = new Kitchen(Item::get($item->getId(), 0, $item->getCount()));
        $kitchen->cooking();
        $ingredients = $kitchen->getValidIngredient();
        $result = $kitchen->getValidResult();

        if ($result == null) {
            Exceptioner::set($player, Cases::INTERACT_WITH_NATURE_ITEM);
            return;
        }
        if ($item->getDamage() != $result->getDamage()) {
            Exceptioner::set($player, Cases::INTERACT_WITH_USED_ITEM);
            return;
        }
        if ($item->getCount() < $result->getCount()) {
            Exceptioner::set($player, Cases::NOT_HAS_ENOUGH_COUNT);
        }
        if (empty($ingredients)) {
            Exceptioner::set($player, Cases::CANT_REVERT_IT);
            return;
        }

        foreach ($ingredients as $material) {
            if (!$player->getInventory()->canAddItem($material)) {
                Exceptioner::set($player, Cases::INVENTORY_NOT_ENOUGH_SPACE);
                return;
            }
        }

        $fleshIngredients = $kitchen->convertFleshIngredients($ingredients);
        Tray::setOrder($player, $result, $fleshIngredients);
    }
}