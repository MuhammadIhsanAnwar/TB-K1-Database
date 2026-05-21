<?php
namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Category;
use App\Models\Product;
use App\Support\MarketplaceCategoryCatalog;
use Illuminate\Support\Facades\Schema;

class CategorySeeder extends Seeder
{
    public function run(): void
    {
        $hasIconColumn = Schema::hasColumn('categories', 'icon');
        $hasImageColumn = Schema::hasColumn('categories', 'image');
        $keepIds = [];

        foreach (MarketplaceCategoryCatalog::tree() as $i => $entry) {
            $parent = $this->upsertCategory($entry, null, $i, $hasIconColumn, $hasImageColumn);
            $keepIds[] = $parent->id;

            foreach ($entry['children'] as $j => $child) {
                $child = $this->upsertCategory($child, $parent, $j, $hasIconColumn, $hasImageColumn);
                $keepIds[] = $child->id;
            }
        }

        $fallbackCategoryId = Category::query()->where('slug', 'top-up-game')->value('id');
        $obsoleteCategoryIds = Category::query()->whereNotIn('id', $keepIds)->pluck('id');

        if ($fallbackCategoryId && $obsoleteCategoryIds->isNotEmpty() && Schema::hasTable('products')) {
            Product::query()
                ->whereIn('category_id', $obsoleteCategoryIds)
                ->update(['category_id' => $fallbackCategoryId]);
        }

        if ($obsoleteCategoryIds->isNotEmpty()) {
            Category::query()->whereIn('id', $obsoleteCategoryIds)->delete();
        }
    }

    private function upsertCategory(array $entry, ?Category $parent, int $sortOrder, bool $hasIconColumn, bool $hasImageColumn): Category
    {
        $payload = [
            'name' => $entry['name'],
            'sort_order' => $sortOrder,
            'is_active' => true,
            'parent_id' => $parent?->id,
        ];

        if ($hasIconColumn && array_key_exists('icon', $entry)) {
            $payload['icon'] = $entry['icon'];
        }

        if ($hasImageColumn && array_key_exists('image', $entry)) {
            $payload['image'] = $entry['image'];
        }

        return Category::updateOrCreate(
            ['slug' => $entry['slug']],
            $payload
        );
    }
}
