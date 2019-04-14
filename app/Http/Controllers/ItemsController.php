<?php

namespace App\Http\Controllers;

use App\Domains\Item;
use Dotenv\Exception\ValidationException;
use Exception;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class ItemsController extends Controller
{
    private $itemDomain;

    /**
     * ItemsController constructor.
     *
     * @param Request $request
     * @param Item    $itemDomain
     */
    public function __construct(Request $request, Item $itemDomain)
    {
        parent::__construct($request);
        $this->itemDomain = $itemDomain;
    }

    public function fetchItems()
    {
        $response = $this->itemDomain->allItems();

        return response()->json([
            'message' => 'success',
            'data'    => $response->toArray(),
        ], 200);
    }

    public function fetchItemById(int $itemId)
    {
    }

    /**
     * Creates a new item.
     *
     * @throws \Illuminate\Validation\ValidationException
     *
     * @return JsonResponse
     */
    public function createNewItem()
    {
        try {
            $this->validate($this->request, [
                'itemName'    => 'required|string|unique:items',
                'imageUrl'    => 'required',
                'cost'        => 'required',
                'quantity'    => 'required',
                'description' => 'required',
                'categoryID'  => 'required',
            ]);
        } catch (ValidationException $exception) {
            throw new Exception($exception->getMessage());
        }

        $itemPayload = [
            'itemName'    => $this->getPayload('itemName'),
            'imageUrl'    => $this->getPayload('imageUrl'),
            'cost'        => $this->getPayload('cost'),
            'quantity'    => $this->getPayload('quantity'),
            'description' => $this->getPayload('description'),
            'categoryID'  => $this->getPayload('categoryID'),
        ];

        $response = $this->itemDomain->addNewItem($itemPayload);

        return response()->json([
            'message' => 'success',
            'data'    => $response->toArray(),
        ], 201);
    }

    /**
     * Updates an item given id.
     *
     * @param int $itemId
     */
    public function updateItem(int $itemId)
    {
    }

    /**
     * Deletes an item given an id.
     *
     * @param int $itemId
     *
     * @throws Exception
     *
     * @return JsonResponse
     */
    public function deleteItem(int $itemId)
    {
        $this->itemDomain->deleteItem($itemId);

        return response()->json([
            'message' => 'success',
        ], 200);
    }
}
