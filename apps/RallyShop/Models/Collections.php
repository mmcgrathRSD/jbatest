<?php
namespace RallyShop\Models;

class Collections extends \Shop\Models\Collections
{
    public static function updateNewItems()
    {
        $newProducts = \Shop\Models\Products::collection()->find([
            'publication.status' => 'published',
            'display_as_newitem' => true
        ], [
            'sort' => [
                'first_publication_time' => -1
            ],
            'limit' => 50,
            'projection' => ['_id' => true]
        ]);

        $ids = [];
        foreach ($newProducts as $newProduct) {
            $ids[] = (string) $newProduct['_id'];
        }

        $collection = (new \Shop\Models\Collections)
            ->setCondition('slug', 'new-items')
            ->getItem();

        if (!empty($collection->id)) {
            $collection->set('products', $ids);
            $collection->save();
        }
    }
}
