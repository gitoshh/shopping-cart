<?php

namespace App\Domains;

use App\Item as ItemModel;
use Exception;
use Illuminate\Database\Eloquent\Collection;

class Item
{
    /**
     * Retrieves all items filtered if needed.
     *
     * @param array|null $filters
     *
     * @return ItemModel[]|Collection
     */
    public function allItems(?array $filters = null)
    {
        $items = ItemModel::all();

        if ($filters) {
        }

        return $items;
    }

    /**
     * Retrieves item given its id.
     *
     * @param int $itemId
     *
     * @return ItemModel[]|Collection|null
     */
    public function getItemById(int $itemId)
    {
        $item = ItemModel::where('itemID', $itemId);
        if ($item) {
            return $item;
        }
    }

    /**
     * Creates new item.
     *
     * @param array $item
     *
     * @return array|void
     */
    public function addNewItem(array $item)
    {
        $item = ItemModel::firstorCreate($item);

        if (!$item->first()) {
            return;
        }

        return $item;
    }

    /**
     * Deletes an item.
     *
     * @param int $itemId
     *
     * @throws Exception
     *
     * @return void
     */
    public function deleteItem(int $itemId)
    {
        $item = ItemModel::where('itemID', $itemId);
        if (!$item->first()) {
            throw new Exception('Item not found');
        }
        $item->delete();
    }
}
