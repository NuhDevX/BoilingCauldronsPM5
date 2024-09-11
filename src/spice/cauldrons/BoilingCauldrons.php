<?php
/**
 * Copyright 2021-2022 Spice
 *
 * Licensed under the Apache License, Version 2.0 (the "License");
 * you may not use this file except in compliance with the License.
 * You may obtain a copy of the License at
 *
 *     http://www.apache.org/licenses/LICENSE-2.0
 *
 * Unless required by applicable law or agreed to in writing, software
 * distributed under the License is distributed on an "AS IS" BASIS,
 * WITHOUT WARRANTIES OR CONDITIONS OF ANY KIND, either express or implied.
 * See the License for the specific language governing permissions and
 * limitations under the License.
 */
declare(strict_types=1);

namespace spice\cauldrons;

use pocketmine\block\BlockTypeIds;
use pocketmine\item\Item;
use pocketmine\item\ItemTypeIds;
use pocketmine\world\World;
use pocketmine\plugin\PluginBase;
use pocketmine\utils\SingletonTrait;
use spice\cauldrons\block\BlockManager;

class BoilingCauldrons extends PluginBase
{
    use SingletonTrait;

    /** @var array */
    public array $settings = [];
    /** @var BlockManager */
    private BlockManager $blockManager;

    public function onEnable(): void
    {
        self::setInstance($this);
        $this->saveDefaultConfig();
        $this->settings = $this->getConfig()->getAll();
        $this->blockManager = new BlockManager($this);
    }

    /**
     * @param Item $item
     * @return bool
     */
    public static function canBeCooked(Item $item): bool
    {
        switch ($item->getTypeId()) {
            case ItemTypeIds::RAW_BEEF:
            case ItemTypeIds::RAW_CHICKEN:
            case ItemTypeIds::RAW_FISH:
            case ItemTypeIds::RAW_MUTTON:
            case ItemTypeIds::RAW_PORKCHOP:
            case ItemTypeIds::RAW_SALMON:
            case ItemTypeIds::RAW_RABBIT:
            case ItemTypeIds::POTATO:
                return true;
            default:
                return false;
        }

    }

    /**
     * @param Item $item
     * @return Item
     */
    public static function getCookedItem(Item $item): Item
    {
        switch ($item->getTypeId()) {
            case ItemTypeIds::RAW_BEEF:
                $id = ItemTypeIds::COOKED_BEEF;
                break;
            case ItemTypeIds::RAW_CHICKEN:
                $id = ItemTypeIds::COOKED_CHICKEN;
                break;
            case ItemTypeIds::RAW_FISH:
                $id = ItemTypeIds::COOKED_FISH;
                break;
            case ItemTypeIds::RAW_MUTTON:
                $id = ItemTypeIds::MUTTON_COOKED;
                break;
            case ItemTypeIds::RAW_PORKCHOP:
                $id = ItemTypeIds::COOKED_PORKCHOP;
                break;
            case ItemTypeIds::RAW_SALMON:
                $id = ItemTypeIds::COOKED_SALMON;
                break;
            case ItemTypeIds::RAW_RABBIT:
                $id = ItemTypeIds::COOKED_RABBIT;
                break;
            case ItemTypeIds::POTATO:
                $id = ItemTypeIds::BAKED_POTATO;
                break;
            default:
                $id = BlockTypeIds::AIR;
        }
        return $id;
    }

    /**
     * @param Level $level
     * @return bool
     */
    public function canBeUsedInWorld(World $world): bool
    {
        return in_array($world->getFolderName(), $this->settings["enabled-worlds"] ?? [], true);
    }

    /**
     * @return bool
     */
    public function isCookingEnabled(): bool
    {
        return (bool)$this->settings["cooking"] ?? true;
    }

    /**
     * @return int
     */
    public function getCookingTime(): int
    {
        return (int)$this->settings["cook-time"] ?? 10;
    }

    /**
     * @return int
     */
    public function getMaxStack(): int
    {
        return (int)$this->settings["max-stack"] ?? 5;
    }

    /**
     * @return bool
     */
    public function canDamagePlayers(): bool
    {
        return $this->settings["damage-players"] ?? true;
    }

    /**
     * @return float
     */
    public function getPlayerDamage(): float
    {
        return (float)$this->settings["damage"] ?? 1.0;
    }

    /**
     * @return BlockManager
     */
    public function getBlockManager(): BlockManager
    {
        return $this->blockManager;
    }

}
