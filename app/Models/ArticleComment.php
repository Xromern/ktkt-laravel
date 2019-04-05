<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;
use SoftDeletes;

class ArticleComment extends Model
{
    protected $table = 'articles_comment';
    protected $softDelete = true;

    static public function addComment($id_article,$content_comment){

        ArticleComment::insert(
            array('article_id'=>$id_article,'users_id'=>Auth::id(),'content'=>$content_comment,'created_at'=>now(),'updated_at'=>now())
        );
    }

    static public function update_comment($id_comment,$content_comment){

        ArticleComment::where('id',$id_comment)->update(
            array('content'=>$content_comment,'updated_at'=>now())
        );
    }

    static public function getCommentContent($id_comment){
       return ArticleComment::where('id',$id_comment)->first()->content;
    }

    static public function deleteComment($id_comment){

        ArticleComment::where('id',$id_comment)->delete();
    }

    static public function show_comment($id_article){

      return ArticleComment::where('article_id',$id_article)->orderBy('id', 'DESC')->get();
    }


    static public function checkAuth($id_comment){
        $check  = ArticleComment::where('id',$id_comment)->where('users_id',Auth::id())->get();
        return count($check)>0 ? true:false;
    }

}
