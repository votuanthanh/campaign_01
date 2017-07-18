<?php

use Illuminate\Database\Seeder;
use App\Models\Action;

class ActionTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Action::truncate();
        factory(Action::class, 10)->create()->each(function ($action) {
            foreach (range(1, 2) as $key) {
                $comments[] = factory(App\Models\Comment::class)->make();
                $like[] = factory(App\Models\Like::class)->make()->toArray();
                $media[] = factory(App\Models\Media::class)->make();
            }

            $action->comments()->saveMany($comments);
            $action->likes()->createMany($like);
            $action->media()->saveMany($media);
        });
    }
}
