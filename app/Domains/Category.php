<?php

namespace App\Domains;

use App\Category as CategoryModel;
use App\Node;
use Illuminate\Support\Facades\DB;

class Category
{
    /**
     * Creates a new category in the categories tables and adds a new node.
     *
     * @param int    $parentId
     * @param string $categoryName
     *
     * @return mixed
     */
    public function addCategory(int $parentId, string $categoryName)
    {
        DB::statement('call tree_traversal(?, ?, ?)', ['insert', null, $parentId]);
        $lastNode = Node::latest()->first(['nodeID'])->toArray();
        $category = CategoryModel::firstOrCreate([
            'categoryName' => $categoryName,
            'nodeID'       => $lastNode['nodeID'],
        ]);

        return $category;
    }

    /**
     * Retrieves all categories applying filters where necessary.
     *
     * @param array|null $filters
     *
     * @return mixed
     */
    public function getAllCategories(?array $filters = null)
    {
        $categories = CategoryModel::all();

        // Filter data with given filters.
        if ($filters) {
        }

        return $categories;
    }

    /**
     * Retrieves a category given the category id.
     *
     * @param int $categoryId
     *
     * @return mixed|null
     */
    public function getCategoryById(int $categoryId)
    {
        $category = CategoryModel::where('categoryId', $categoryId)->first();

        if ($category) {
            return $category;
        }
    }

    /**
     * Updates a given category by modifying the name or the node it falls under.
     *
     * @param int         $categoryId
     * @param string|null $categoryName
     * @param int|null    $parentId
     *
     * @return array
     */
    public function updateCategory(int $categoryId, ?string $categoryName = null, ?int $parentId = null)
    {
        return [];
    }

    /**
     * Deletes a category.
     *
     * @param int $categoryId
     */
    public function deleteCategory(int $categoryId)
    {
        // Get the category to delete
        $category = CategoryModel::where('categoryID', $categoryId);

        // Separate variable to hold the category to get the node id from later
        // once the category is deleted
        $deletedCategory = $category->first()->toArray();
        $category->delete();

        // Delete the node using the stored procedure
        DB::statement('call tree_traversal(?, ?, ?)', ['delete', $deletedCategory['categoryID'], null]);
    }
}
