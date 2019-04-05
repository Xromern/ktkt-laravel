<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\ArticleComment;
class CommentController extends Controller
{
    public function addComment(Request $request){

        if(Auth::check()) {
            $id_article = $request->id;
            $comment_content = $request->text_comment;
            ArticleComment::addComment($id_article, $comment_content);
            echo $id_article;
        }else{
            abort();
        }
    }

    public function deleteComment(Request $request){

        $id_comment = $request->id_comment;
        if(ArticleComment::checkAuth($id_comment) || User::is_admin()) {

            ArticleComment::deleteComment($id_comment);
        }
    }

    public function updateComment(Request $request){

        $id_comment = $request->id_comment;
        if(ArticleComment::checkAuth($id_comment) || User::is_admin()) {
            $comment_content = $request->content_comment;
            ArticleComment::update_comment($id_comment, $comment_content);
        }
    }


    public function getCommentContent(Request $request){
        if (!$request->ajax()) return abort(403);
        $id_comment = $request->id_comment;
        if(ArticleComment::checkAuth($id_comment) || User::is_admin()) {
            return ArticleComment::getCommentContent($id_comment);
        }
    }

    public function showComments(Request $request){
        if (!$request->ajax()) return abort(403);
        $id_article = $request->id_article;

        $comments = ArticleComment::show_comment($id_article);
        //dd(self::build_comment($comments));
     // return response()->json(array('comments'=> self::build_comment($comments)), 200);
        //dd(User::is_admin());
        return self::build_comment($comments);
    }

    static private function build_comment($comments){

        $format_str="";
        foreach ($comments as $comment){

            $block  = " <div name='comment' class='block-comment'>
            <div class='block-comment-container-head'>
            <div class='block-comment-container-img'></div>
            <div class='article-comment-container-data'>
            <div class='block-comment-container-nameAuthor'>%s </div>
            <div class='block-comment-container-date'>%s  </div>
            <div class='block-comment-container-status'>%s</div>
            </div><div class='block-comment-container-action' data-id='%s'>";
            if(ArticleComment::checkAuth($comment['id']) || User::is_admin()) {
                $block .= "
            <div class='block-comment-button-edit' >✎</div>
            <div class='block-comment-button-remove'>✘</div> ";
            }
            $block .="</div> </div><div class='block-comment-container-body'>
            <div class='block-comment-container-content'>%s </div>
            </div>
            </div>";

        $format_str .= sprintf($block, $comment['users_id'], $comment['created_at'],'Студент', $comment['id'],$comment['content']);


        }return $format_str;
    }
}
