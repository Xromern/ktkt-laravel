@extends('layouts.layouts_journal')

@section('content')
@section('title', 'Групи')

<div class="container-journal-list container-journal-center">
<div class="container-journal-block">
    <div class="add-group-level-1">
        <input type="text" class="edit-add-group-name" placeholder="ПП-0000" required="required"/>

        <select class="select-teacher">

        </select><a href='/journal/teachers' class="button">Вчителя</a>
        <select class="select-specialty">

        </select><div class="button button-add-specialty-show">Спеціальність</div>
    </div>
    <div class="add-group-level-2"><div class="button button-add-group">Додати групу</div></div>
</div>
    <a href='/journal/teachers' class='block-group'>
        <div class="block-group-caption">Вчителя</div>

    </a>
    <div class="container-show-journal-list">
    </div>

<div id="myModal" class="modal">
<!-- Modal content -->
<div class="modal-content">
    <span class="modal-close">✘</span>
    <div class="container-add-specialty">
        <input type="text" class="input-add-specialty" placeholder="ПП-0000" required="required"/>
        <div class="button button-add-specialty">Додати</div>
    </div>
    <div class="container-table-speciality">
        @include('MainSite.preloader')
        <table class="table-show-speciality">

        </table>
    </div>
</div>


</div>

</div>
</div>
<script>
$(document).ready(function() {

    $('.button-add-specialty').on('click', function(){

            addSpecialty();
    });


    function addSpecialty() {
        $('.table-show-speciality').html(" ");

        var name = $('.input-add-specialty').val();
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            url: '/journal/specialtyAdd',
            data: ({
                "_token":token,
                'name':name
            }),
            error:function(){
                note({
                    content: "Сталась помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function(result){
            if(result=="true"){

                note({
                    content: "Спеціальність успішно додана.",
                    type: "info",
                    time: 5
                });
            }else{
                note({
                    content: result,
                    type: "error",
                    time: 5
                });
            }
                showSpecialty()
                showSpecialtyList();
            }
        });
    }

    function showSpecialty() {

        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            url: '/journal/specialtyShow',
            data: ({
                "_token":token
            }),
            beforeSend: function() {
                $('.preLoader').show();
            },
            complete: function() {
                $('.preLoader').hide();
            },
            error:function(){
                note({
                    content: "Сталась помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function(result){
                $('.table-show-speciality').html(result);

            }
        });
    }

    $('html').on('click','.button-delete-specialty', function(){
    var id = $(this).parent().attr('data-id-specialty');
        deleteSpecialty(id);
    });

    function deleteSpecialty(id) {
        $('.table-show-speciality').html(" ");
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'DELETE',
            url: '/journal/specialtyDelete',
            data: ({
                "_token":token,
                'id':id
            }),
            error:function(){
                note({
                    content: "Сталась помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function(result){
                if(result=="true"){

                    note({
                        content: "Спеціальність успішно видалена.",
                        type: "info",
                        time: 5
                    });
                }else{
                    note({
                        content: result,
                        type: "error",
                        time: 5
                    });
                }
                showSpecialty();
                showSpecialtyList();
            }
        });
    }

    $('html').on('click','.button-update-specialty', function(){
        var obj = $(this).parent();
        var name = obj.parent().find('.input-name-specialty').val()
        var id = obj.attr('data-id-specialty');

       updateSpecialty(id,name);
    });

    function updateSpecialty(id,name) {
        $('.table-show-speciality').html(" ");
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'PUT',
            url: '/journal/specialtyUpdate',
            data: ({
                "_token":token,
                'id':id,
                'name':name
            }),
            error:function(){
                note({
                    content: "Сталась помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function(result){
                if(result=="true"){
                    note({
                        content: "Спеціальність успішно змінена.",
                        type: "info",
                        time: 3
                    });
                }else{
                    note({
                        content: "result",
                        type: "error",
                        time: 3
                    });
                }
                showSpecialtyList();
                showSpecialty();
                showGroups();
            }
        });
    }
    showSpecialtyList();
    function showSpecialtyList() {
        $('.select-specialty').html(" ");
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            url: '/journal/specialtyShowList',
            data: ({
                "_token":token
            }),
            error:function(){
                note({
                    content: "Сталась помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function(result){

            $('.select-specialty').html(result);
            }
        });
    }

    showTeacherList();
    function showTeacherList() {
        $('.select-specialty').html(" ");
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            url: '/journal/teachersShowList',
            data: ({
                "_token":token
            }),
            error:function(){
                note({
                    content: "Сталась помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function(result){

                $('.select-teacher').html(result);
            }
        });
    }

    $('html').on('click','.button-add-group', function(){

        var group_name = $('.edit-add-group-name').val();
        var id_teacher = $('.select-teacher').val();
        var id_specialty = $('.select-specialty').val();
        addGroup(id_teacher,id_specialty,group_name);
    });

    function addGroup(id_teacher,id_specialty,group_name) {

        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            url: '/journal/groupAdd',
            data: ({
                "_token":token,
                "id_teacher":id_teacher,
                "id_specialty":id_specialty,
                "group_name":group_name
            }),
            error:function(){
                note({
                    content: "Сталась помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function(result){
                if(result=="true"){
                    note({
                        content: "Група успішно додана.",
                        type: "info",
                        time: 3
                    });
                }else{
                    note({
                        content: result,
                        type: "error",
                        time: 3
                    });
                }
                showGroups();
            }
        });
    }

    showGroups();
    function showGroups() {
        $('.container-show-journal-list').html(" ");
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            url: '/journal/groupsShow',
            data: ({
                "_token":token
            }),
            error:function(){
                note({
                    content: "Сталась помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function(result){

               $('.container-show-journal-list').html(result);
            }
        });
    }

    var modal = document.getElementById('myModal');

    var span = document.getElementsByClassName("modal-close")[0];

    //если кликаю на крестик, добавлюя id коммента в модальное окно для редактирование комменатрия
    //
    $('html').on('click','.button-add-specialty-show', function(){

        modal.style.display = "block";
        showSpecialty();
    });

    span.onclick = function() {
        $('.table-show-speciality').html(" ");
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {
            $('.table-show-speciality').html(" ");
            modal.style.display = "none";
        }
    }
})
</script>
@endsection