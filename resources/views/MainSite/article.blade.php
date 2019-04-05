@extends('layouts.layouts_article')

@section('content')

<div class="container-article-sheet">
    <div class="article-sheet-caption">{!!  $article->title!!}</div>
    <div class="article-sheet">
        {!!($article->content_html)!!}

    </div>

    <form id="form-add-comment" action="#" method="post">
        {{ csrf_field() }}
        <div class="container-comment">
            <textarea id="editor-comment"></textarea>

            <button class="button comment-send" type="submit">Відправити</button>
        </div>
    </form>
    <div class="container-comments"></div>
@include('MainSite.preloader')

<!-- The Modal -->
<div id="myModal" class="modal">

    <!-- Modal content -->
    <div class="modal-content">
        <span class="modal-close">✘</span>
        <textarea id="editor-edit-comment"></textarea>

        <button class="button comment-edit" type="submit">Змінити</button>
    </div>

</div>


<script src="{{URL::to('js/vendor/editor/tinymce.min.js')}}"></script>
<script>

    var editor_comment = 'editor-comment';
    var editor_edit_comment = 'editor-edit-comment';

    $(document).ready(function () {
        tinymce.init({
            selector: '#' + editor_comment,
            height: 200,
            theme_advanced_resizing: false,
            language: "uk",
            theme: "modern",
            theme_advanced_resizing: false,
            theme_advanced_resize_horizontal: false,
            menubar: false,
            plugins: 'link',
            toolbar1: 'bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat'
        });

        tinymce.init({
            selector: '#' + editor_edit_comment,

            theme_advanced_resizing: false,
            theme_advanced_resize_horizontal: false,
            height: 200,
            language: "uk",
            theme: "modern",
            menubar: false,
            plugins: 'link',
            toolbar1: 'bold italic strikethrough forecolor backcolor | link | alignleft aligncenter alignright alignjustify  | numlist bullist outdent indent  | removeformat'
        });


        $('#form-add-comment').on('submit', function (e) {
            e.preventDefault();

            addComment();

        });

        function addComment() {
            var token = "{{ csrf_token() }}";
            $.ajax({
                type: 'POST',
                url: '/addComment',
                data: ({
                    "_token": token,
                    "id": "{{$article->id}}",
                    "text_comment": tinymce.get("editor-comment").getContent()
                }),
                error: function () {
                    note({
                        content: "Сталась помилка, перезавантажте сторінку.",
                        type: "error",
                        time: 15
                    });
                },
                success: function (result) {
                    //console.log(result);
                    setTimeout(ShowComments, 100);
                    note({
                        content: "Коментар успішно додано.",
                        type: "info",
                        time: 15
                    });
                }
            });
        }

        $('html').on('click', '.block-comment-button-remove', function () {
            if (check_timeout_button($(this))) {

                if (confirm('Ви дійсно хочете видалити коментар? ')) {
                    $('.preLoader').show();
                    var id_comment = $(this).parent().attr('data-id');
                    console.log(id_comment);
                    deleteComment(id_comment);
                }
            }
        });

        function deleteComment(id_comment) {
            $('.preLoader').show();
            $('.container-comments').html(" ");
            $.ajax({
                type: 'DELETE',
                url: '/deleteComment',
                data: ({
                    "_token": "{{ csrf_token() }}",
                    "id_comment": id_comment,

                }),
                error: function () {
                    note({
                        content: "Сталась помилка.",
                        type: "error",
                        time: 5
                    });
                    // setTimeout(deleteComment(id_comment),30);
                },
                success: function (result) {
                    console.log(result)
                    setTimeout(ShowComments, 100);
                    note({
                        content: "Коментар успішно видалено.",
                        type: "info",
                        time: 15
                    });
                }
            });
        }

        ShowComments();

        function ShowComments() {
            $('.preLoader').show();
            var token = "{{ csrf_token() }}";
            $.ajax({
                type: 'POST',
                url: '/showComments',
                data: ({
                    "_token": token,
                    "id_article": "{{$article->id}}"
                }),
                beforeSend: function () {
                    $('.preLoader').show();
                },
                complete: function () {
                    $('.preLoader').hide();
                },
                error: function () {
                    note({
                        content: "Сталась помилка, перезавантажте сторінку.",
                        type: "error",
                        time: 15
                    });
                    ShowComments();
                    console.log("error");
                },
                success: function (result) {
                    // console.log(result)
                    // result = result['comments'];

                    $('.container-comments').html(result);

                }
            });
        }


        function getCommentOnEdit(id_comment) {
            var token = "{{ csrf_token() }}";
            $.ajax({
                type: 'POST',
                url: '/getCommentContent',
                data: ({
                    "_token": token,
                    "id_comment": id_comment
                }),
                error: function () {
                    note({
                        content: "Сталась помилка, перезавантажте сторінку.",
                        type: "error",
                        time: 15
                    });
                },
                success: function (result) {

                    tinyMCE.get(editor_edit_comment).execCommand("mceInsertContent", false, result);
                }
            });

        }

        function setCommentOnEdit(id_comment, content_comment) {
            var token = "{{ csrf_token() }}";
            $.ajax({
                type: 'POST',
                url: '/updateComment',
                data: ({
                    "_token": token,
                    "id_comment": id_comment,
                    'content_comment': content_comment
                }),
                error: function () {
                    note({
                        content: "Сталась помилка при редагуванні коментаря",
                        type: "error",
                        time: 15
                    });
                },
                success: function (result) {
                    note({
                        content: "Коментар успішно змінено.",
                        type: "info",
                        time: 15
                    });
                }
            });

        }

        $('html').on('click', '.comment-edit', function (e) {


            e.preventDefault();

            setCommentOnEdit($(this).parent().attr('id_comment'), tinymce.get(editor_edit_comment).getContent());
            modal.style.display = "none";
            setTimeout(ShowComments, 200);

        })

        // Get the modal
        var modal = document.getElementById('myModal');


// Get the <span> element that closes the modal
        var span = document.getElementsByClassName("modal-close")[0];

        //если кликаю на крестик, добавлюя id коммента в модальное окно для редактирование комменатрия
        //
        $('html').on('click', '.block-comment-button-edit', function () {

            tinyMCE.get(editor_edit_comment).setContent('');
            modal.style.display = "block";
            var id_comment = $(this).parent().attr('data-id');
            $('.modal-content').attr('id_comment', id_comment);
            getCommentOnEdit(id_comment);
        });

// When the user clicks on <span> (x), close the modal
        span.onclick = function () {
            $('.modal-content').removeAttr();
            tinyMCE.get(editor_edit_comment).setContent('');
            modal.style.display = "none";
        }

// When the user clicks anywhere outside of the modal, close it
        window.onclick = function (event) {
            if (event.target == modal) {
                $('.modal-content').removeAttr('id_comment');
                tinyMCE.get(editor_edit_comment).setContent('');
                modal.style.display = "none";
            }
        }

    });
</script>
</div>
    @include('MainSite.advertisement')
@endsection

