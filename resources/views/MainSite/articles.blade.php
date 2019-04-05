
@extends('layouts.layouts_article')

@section('content')
    <link href="{{ asset('css/articles.css') }}" rel="stylesheet">
    <div class="articles">
<div class="articles-content">

    @if(!(count($articles)==0 || count($articles)==-1))
    @foreach($articles as $article)
        <a class="article-container" href="/article/{{$article->id}}">

            <div class="article-container-image">
                <img class="image-article" src="{{ asset("storage/ktkt.png") }}" alt="image">
            </div>

            <div class="article-container-text">

                <div class="article-container-text-data">
                 {{$article->created_at}}
                </div>

                <div class="article-container-title">
                    {!!  $article->title!!}
                </div>

                <div class="article-container-excerpt">
                    {!!  $article->excerpt!!}
                </div>

            </div>

        </a>
    @endforeach

    @else
        <div style="margin: auto">Сторінка пуста</div>
    @endif

</div>
        <div class="paginate paginate-full">
            {{$articles->links()}}
        </div>
        <div class="paginate paginate-simple">
            {{$articlesSimple->links()}}
        </div>
</div>
    @include('MainSite.advertisement')
@endsection
