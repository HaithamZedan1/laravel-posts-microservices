<?php

namespace App\Console\Commands;

use App\Models\Post;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class SyncComments extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'sync:comments';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $allComments = DB::connection('comments_db')->table('comments')->get();

        DB::setDefaultConnection('mysql');

        $posts = Post::all();

        foreach ($posts as $post) {
            $comments = $post->comments;

            $filteredComments = $allComments->filter(function ($comment) use ($post){
                return $comment->post_id == $post->id;
            });

            if(count($comments) !== $filteredComments->count()){
                $post->comments =$filteredComments->map(function($comment){
                    return [
                        'text' => $comment->text
                    ];
                })->values();

                $post->save();
            }
        }

    }
}
