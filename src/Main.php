<?php

declare(strict_types=1);

namespace AmitxD\ItemFram_Blocker;

use pocketmine\event\inventory\CraftItemEvent;
use pocketmine\inventory\ShapedRecipe;
use pocketmine\event\player\PlayerInteractEvent;
use pocketmine\block\BlockIds;
use pocketmine\event\block\BlockBreakEvent;
use pocketmine\event\block\BlockPlaceEvent;
use pocketmine\item\VanillaItems;
use pocketmine\block\BlockTypeIds;
use pocketmine\event\Listener;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\TextFormat as TF;

class Main extends PluginBase implements Listener{

    public function onEnable(): void {
        $this->getServer()->getPluginManager()->registerEvents($this, $this);
    }
    
    public function onCraftItem(CraftItemEvent $event): void {
        foreach($event->getRecipe()->getResults() as $results) {
            if($results->getTypeId() === BlockTypeIds::ITEM_FRAME) {
                $event->cancel();
            }
        }
    }
    
    public function onInteract(PlayerInteractEvent $event)
    {
        $player = $event->getPlayer();
        $block = $event->getBlock();
        if ($block->getTypeId() === BlockTypeIds::FRAME_BLOCK) {
        $player->sendMessage(TF::RED . "You can't use item frames here!");
        $event->cancel();
        }
    }
    
    public function onPlace(BlockPlaceEvent $event): void {
        $player = $event->getPlayer();
        foreach($event->getTransaction()->getBlocks() as [$x, $y, $z, $block]){
        $block = $event->getBlock();
        if($block->getTypeId() === BlockTypeIds::FRAME_BLOCK) {
            $event->cancel();
            $player->sendMessage(TF::RED . "Placing item frames is disabled!");
        }
      }
    }
    
    public function onBreak(BlockBreakEvent $event): void {
        $block = $event->getBlock();
        $blockPos = $block->getPosition();
        if ($block->getTypeId() === BlockTypeIds::FRAME_BLOCK) {
            $event->cancel();
            $Air = VanillaItems::AIR()->getBlock();
            $blockPos->getWorld()->setBlock($blockPos, $Air);
        }
    }
}
