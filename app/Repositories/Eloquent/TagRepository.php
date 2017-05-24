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
        if (!$tags) {
            return false;
        }

        $tags = is_array($tags) ? $tags : [$tags];
        $tags = array_map('strtolower', $tags);
        // get all tag in array with key => id and value => name
        $oldTags = $this->whereIn('name', $tags)->lists('name', 'id')->toArray();
        $newTags = $oldTags ? array_diff($tags, $oldTags) : $tags;

        if (!$newTags) {
            return false;
        }

        $tagsName = [];

        foreach ($newTags as $name) {
            $tagsName[] = [
                'name' => $name,
            ];
        }

        return [
            'old' => array_keys($oldTags),
            'new' => $tagsName, 
        ];
    }
}
