<?php

namespace App\Http\Controllers;

use App\Category;
use App\Node;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class CategoriesController extends Controller
{
    private $request;

    /**
     * CategoriesController constructor.
     *
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    /**
     * Create a new category.
     *
     * @return JsonResponse
     */
    public function createNewCategory(): JsonResponse
    {
        try {
            $this->validate($this->request, [
                'parentId' => 'required',
                'name'     => 'required',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }

        $parentId = $this->request->get('parentId');
        DB::statement('call tree_traversal(?, ?, ?)', ['insert', 0, $parentId]);
        $lastNode = Node::latest()->first(['nodeID'])->toArray();
        $category = Category::firstOrCreate([
            'categoryName' => $this->request->get('name'),
            'nodeID'       => $lastNode['nodeID'],
        ]);

        return response()->json($category->toArray());
    }

    /**
     * Update an existing category.
     *
     * @param int $categoryId
     */
    public function updateCategory(int $categoryId)
    {
        $category = Category::where('categoryID', $categoryId)->first();
        if (isset($this->request->parentId)) {
            DB::statement('call tree_traversal(?, ?, ?)', ['move', $category->nodeID, $this->request->parentId]);
        }
    }
}
