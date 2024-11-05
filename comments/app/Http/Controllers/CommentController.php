<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use Illuminate\Http\Request;


class CommentController extends Controller
{
    public function store(Request $request){

        $comment = Comment::create([
            'post_id' => $request->input('post_id'),
            'text' => $request->input('text')
        ]);
        if(rand(1,10)<=9){
        $req = \Illuminate\Support\Facades\Http::post("http://localhost:8000/api/posts/{$comment->post_id}/comments",[
            'text' => $comment->text
        ]);

        if($req->failed()){
            echo 'Request failed';
        }
    }
        return $comment;
    }
}
