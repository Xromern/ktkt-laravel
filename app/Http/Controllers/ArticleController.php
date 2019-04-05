<?php

namespace App\Http\Controllers;

use App\Models\ArticleComment;
use Illuminate\Http\Request;
use App\Models\Article;
use Illuminate\Support\Facades\Auth;

class ArticleController extends Controller
{
  static public function showArticle($article_id){

        $article = Article::where('id',$article_id)->first();

        return view('MainSite.article',compact('article'));
    }

    static public function showPage(){
        $article =  Article::orderBy('id', 'DESC');
        $articles = $article->paginate(9);
        $articlesSimple = $article->simplePaginate(9);

        return view('MainSite.articles',compact('articles','articlesSimple'));


    }


}
