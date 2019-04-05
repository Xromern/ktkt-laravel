@extends('layouts.layouts_journal')

@section('content')
    @section('title', 'Група '.$group->group_name)
    <div class="change-group container-journal-center">
        <div class="container-journal-block">
            <div class="add-group-level-1">
                <input type="text" value="{{$group->group_name}}" class="edit-add-group-name" placeholder="ПП-0000" required="required"/>

                <select class="select-teacher-curator">
                </select><a href='/journal/teachers' class="button">Вчителя</a>
                <select class="select-specialty">
                </select><a href="/journal/groups" class="button button-add-specialty-show">Спеціальність</a>
            </div>
            <div class="add-group-level-2">
                <div class="button button-update-group">Зберегти</div>
                <div class="button button-delete-group">Видалити</div>
            </div>


        </div>
        <a href='/journal/groups/{{$group->group_name}}/students' class='block-group'>
            <div class="block-group-caption">Студенти</div>

        </a>
        <a href='/journal/groups/{{$group->group_name}}/form6' class='block-group'>
            <div class="block-group-caption">Форма 6</div>

        </a>

        <div class="container-journal-block">
            <div class="button button-show-subject-add">Створит новий журнал</div>
        </div>
        @include('MainSite.preloader')
        <div class="container-subjects">


        </div>

    </div>

<div id="myModal" class="modal modal-add-subject">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="modal-close">✘</span>
        <div class="modal-container-journal-add-subject">
            <input type="text" class="input-add-subject" placeholder="Предмет" required="required"/>
            <select class="select-teacher">
            </select>
            <div class="button button-add-subject">Додати</div>
        </div>
        <div class="container-table-students-subject">
            @include('MainSite.preloader')
            <div class="field_block_2 student_select">
                <ul class="student_ student_yes">
                </ul>
                <ul class="student_ student_no">
                </ul>
            </div>
        </div>
    </div>
</div> <script src="https://code.jquery.com/jquery-1.12.4.js"></script>
<script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>
<script>
    $( function() {
        $( ".student_yes , .student_no " ).sortable({
            connectWith: ".student_"
        }).disableSelection();
    } );

    show_subjects();
    function show_subjects(){
        var token = "{{ csrf_token() }}";
        $.ajax({
            url:"/journal/showSubjectsGroup",
            type:"POST",
            data:({
                "_token":token,
                "group_id":'{{$group->group_id}}',
                "group_name":'{{$group->group_name}}'
            }),
            beforeSend: function() {
                $('.preLoader').show();
            },
            complete: function() {
                $('.preLoader').hide();
            },
            error: function() {
                note({
                    content: "Сталася помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 30
                });
            },success:function(result){

                $('.container-subjects').html(result);

            }
        });
    }

    $('html').on('click','.button-update-group', function(){
        updateGroup();
    });

    function updateGroup() {

        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'PUT',
            url: '/journal/updateGroup',
            data: ({
                "_token":token,
                "id_teacher" : $('.select-teacher-curator').val(),
                "id_specialty": $('.select-specialty').val(),
                "id_group": '{{$group->group_id}}',
                "group_name":$('.edit-add-group-name').val()
            }),
            error:function(){
                note({
                    content: "Сталася помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function(result){

                if(result=="true"){

                    note({
                        content: "Група успішно змінена.",
                        type: "info",
                        time: 5
                    });
                    history.pushState("Group_name","Group_name",$('.edit-add-group-name').val());
                }else{
                    note({
                        content: result,
                        type: "error",
                        time: 5
                    });
                }
                setTimeout(function () {
                   location.reload();
                },1500)

            }

        });
    }

    $('html').on('click','.button-delete-group', function(){
        if(confirm("Всі журнали, студенти цієї групи будуть видалені. Продовжии?"))
            deleteGroup()
    });

    function deleteGroup(){
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'DELETE',
            url: '/journal/deleteGroup',
            data: ({
                "_token":token,
                "id_group" :'{{$group->group_id}}'
            }),
            error:function(){
                note({
                    content: "Сталась помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function(result){
                if(result=="true") {
                    note({
                        content: "Група була успішно видалена.",
                        type: "info",
                        time: 15
                    });
                    setTimeout(function () {
                        location.href="/journal/groups/";
                    },2000)
                }

            }
        })
    }

    showTeacherList('{{$group->group_id}}','.select-teacher-curator');
    function showTeacherList(id_group,select_teacher) {
        {{$group->group_id}}
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            url: '/journal/teachersShowList',
            data: ({
                "_token":token,
                "id_group" :id_group
            }),
            error:function(){
                note({
                    content: "Сталась помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function(result){

                $(select_teacher).html(result);
            }
        });
    }

    showSpecialtyList();
    function showSpecialtyList() {
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            url: '/journal/specialtyShowList',
            data: ({
                "_token":token,
                'specialty_id': '{{$group->specialty_id}}',
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

    $('html').on('click','.button-add-subject', function(){
        addSubject();
    });//addSubject


    function addSubject(){
        var token = "{{ csrf_token() }}";
        $.ajax({
            url:"/journal/addSubject",
            type:"POST",

            data:({
                "_token":token,
                'name_subject':$('.input-add-subject').val(),
                'list_student':create_list_student(),
                'group_id':'{{$group->group_id}}',
                'teacher_id':$('.select-teacher').val()
            }),
            beforeSend: function() {
                $('.preLoader').show();
                $('.student_').html("");
            },
            complete: function() {

            },
            error: function() {
                note({
                    content: "Сталася помилка.",
                    type: "error",
                    time: 15
                });
            },success:function(data){

                if(data=="true") {
                    setTimeout(function () {
                        note({
                            content: "Предмет створеный",
                            type: "info",
                            time: 5
                        });
                    },400)

                    $('.input-add-subject').val("");
                    $('.preLoader').hide();
                }else{
                    note({
                        content: data,
                        type: "error",
                        time: 5
                    });
                }
                show_student();
                show_subjects();
            }
        });
    }

    function create_list_student(){
        var length =  $('.student_yes tr').length;
        var array = [];
        for(var i = 1;i<=length;i++){
            array.push($('.student_yes tr:nth-child('+[i]+')').attr('data-id-student'));
        }
        return JSON.stringify(array);
    }


    show_student();
    function show_student(){
        var token = "{{ csrf_token() }}";
        $.ajax({
            url:"/journal/showStudentsForAddSubject",
            type:"POST",
            data:({
                "_token":token,
                "group_id":'{{$group->group_id}}'
            }),
            beforeSend: function() {
                $('.preLoader').show();
                $('.student_').html("");
            },
            complete: function() {
                $('.preLoader').hide();
            },
            error: function() {

            },success:function(data){
                $('.student_yes').html(data);

            }
        });
    }
    function calculation_height(){
        var length = $('.student_no tr').length;
        var min_height = length*35.3;
        $('.student_select').css('min-height',min_height+'px');
    }
//

    var modal = document.getElementById('myModal');

    var span = document.getElementsByClassName("modal-close")[0];

    //если кликаю на крестик, добавлюя id коммента в модальное окно для редактирование комменатрия
    //
    $('html').on('click','.button-show-subject-add', function(){
        showTeacherList('{{$group->group_id}}','.select-teacher');
        show_student();
        modal.style.display = "block";
    });

    span.onclick = function() {

        showTeacherList('{{$group->group_id}}','.select-teacher');
        show_student();
        modal.style.display = "none";
    }

    window.onclick = function(event) {
        if (event.target == modal) {

            modal.style.display = "none";
        }
    }

</script>
@endsection