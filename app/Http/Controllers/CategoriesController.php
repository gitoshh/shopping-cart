<?php

namespace App\Http\Controllers;

use App\Domains\Category as CategoryDomain;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Validation\ValidationException;

class CategoriesController extends Controller
{
    private $request;
    private $categoryDomain;

    /**
     * CategoriesController constructor.
     *
     * @param Request        $request
     * @param CategoryDomain $categoryDomain
     */
    public function __construct(Request $request, CategoryDomain $categoryDomain)
    {
        $this->request = $request;
        $this->categoryDomain = $categoryDomain;
    }

    public function FetchCategories()
    {
        $categories = $this->categoryDomain->getAllCategories();

        return response()->json([
            'message' => 'success',
            'data'    => $categories->toArray(),
        ], 200);
    }

    /**
     * Retrieve category given its id.
     *
     * @param $categoryId
     *
     * @return JsonResponse
     */
    public function FetchCategoryById($categoryId)
    {
        $category = $this->categoryDomain->getCategoryById($categoryId);
        if ($category) {
            return response()->json([
                'message' => 'success',
                'data'    => $category->toArray(),
            ], 200);
        }

        return response()->json([
            'message' => 'Category not found',
        ], 404);
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
                'parentId'     => 'required',
                'categoryName' => 'required|unique:categories',
            ]);
        } catch (ValidationException $e) {
            return response()->json([
                'message' => $e->getMessage(),
            ], 400);
        }
        $parentId = $this->request->get('parentId');
        $categoryName = $this->request->get('categoryName');
        $categoryObject = $this->categoryDomain->addCategory($parentId, $categoryName);

        return response()->json([
            'message' => 'success',
            'data'    => $categoryObject->toArray(),
        ]);
    }

    /**
     * Update an existing category.
     *
     * @param int $categoryId
     *
     * @return JsonResponse
     */
    public function updateCategory(int $categoryId): JsonResponse
    {
        if (!$this->request->get('parentId') and !$this->request->get('categoryName')) {
            return response()->json([
                    'message' => 'bad request. Either update the parent, the category name or both',
                ],
            400);
        }
        $categoryName = $this->request->get('name') ?? null;
        $parentId = $this->request->get('parentId') ?? null;
        $categoryObject = $this->categoryDomain->updateCategory($categoryId, $categoryName, $parentId);

        return response()->json([
            'message' => 'success',
            'data'    => $categoryObject->toArray(),
        ]);
    }

    /**
     * Deletes a category given id.
     *
     * @param int $categoryId
     *
     * @return JsonResponse
     */
    public function removeCategory(int $categoryId): JsonResponse
    {
        $this->categoryDomain->deleteCategory((int) $categoryId);
        $category = $this->categoryDomain->getCategoryById((int) $categoryId);

        if (!$category) {
            return response()->json([
                'message' => 'deleted',
            ], 200);
        }

        return response()->json([
            'message' => 'An error occurred while trying to delete a category',
        ], 500);
    }
}
