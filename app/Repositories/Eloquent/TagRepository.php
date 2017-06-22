<?php

namespace App\Repositories\Eloquent;

use App\Models\Tag;
use App\Repositories\Contracts\TagInterface;

class TagRepository extends BaseRepository implements TagInterface
{
    public function model()
    {
        return Tag::class;
    }

    public function getOrCreate($tags)
    {
        if (!is_array($tags)) {
            return false;
        }

        $newTags = [];
        $oldTags = [];

        foreach ($tags as $tag) {
            if (empty($tag['id'])) {
                $newTags[] = ['name' => strtolower($tag['name'])];
            } else {
                $oldTags[] = $tag['id'];
            }
        }

        return [
            'old' => $oldTags,
            'new' => $newTags,
        ];
    }
}
