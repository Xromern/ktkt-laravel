@extends('layouts.layouts_journal')

@section('content')
@section('title', 'Вчителя')
    <div class="container-journal-center">
        <div class="container-journal-block">
            <input type="text" class="edit-add-teacher" placeholder="П.І.Б" required="required"/>

            <div class="button journal-button-teacher-add" >Додати</div>
        </div>
        <div class="container-journal-show-teachers">
            @include('MainSite.preloader')
        <table class="table-show-teachers"></table>
    </div>
    </div>

    <script>
        $('.journal-button-teacher-add').on('click', function(e){
            if(check_timeout_button($(this)))
            addTeacher($('.edit-add-teacher').val());
        });
        function addTeacher(TeacherName) {
            $('.table-show-teachers').html(" ");
            $('.preLoader').show();
            var token = "{{ csrf_token() }}";
            $.ajax({
                type: 'POST',
                url: '/journal/teachersAdd',
                data: ({
                    "_token": token,
                    'TeacherName':TeacherName
                }),
                error:function(){
                    note({
                        content: "Сталась помилка при додаванні вчителя",
                        type: "error",
                        time: 3
                    });
                    showTeachers();
                },
                success: function (result) {
                    if(result=="true") {
                        note({
                            content: "Вчителя успішно додано.",
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
                    $('.edit-add-teacher').val("");
                    showTeachers();
                }
            });
        }

        showTeachers();
        function showTeachers() {

            var token = "{{ csrf_token() }}";
            $.ajax({
                type: 'POST',
                url: '/journal/showTeachers',
                data: ({
                    "_token": token
                }),
                beforeSend: function() {
                    $('.preLoader').show();
                },
                complete: function() {
                    $('.preLoader').hide();
                },
                error:function(){
                    showTeachers()
                    note({
                        content: "Сталась помилка",
                        type: "error",
                        time: 1
                    });
                },
                success: function (result) {
                    $('.table-show-teachers').html(result);
                    //console.log(result)

                }
            });
        }

        $('html').on('click','.button-journal-delete',function () {
            deleteTeacher($(this));
        })

        function deleteTeacher(e){
            var id_teacher =e.parent().parent().attr('data-id-teacher');
            var id_key =e.parent().parent().attr('data-id-key');
            console.log(id_teacher);
           $('.table-show-teachers').html(" ");
           $('.preLoader').show();
            var token = "{{ csrf_token() }}";
            $.ajax({
                type: 'DELETE',
                url: '/journal/teacherDelete',
                data: ({
                    "_token": token,
                    'id_teacher':id_teacher,
                    'id_key':id_key,
                }),
                error:function(){
                    note({
                        content: "Сталась помилка при видаленні вчителя",
                        type: "error",
                        time: 3
                    });
                },
                success: function (result) {
                    if(result=="true") {
                        note({
                            content: "Вчителя успішно видалено.",
                            type: "info",
                            time: 5
                        });
                    }else{
                        note({
                            content: result,
                            type: "error",
                            time: 3
                        });
                    }
                    showTeachers();

                }
            });
        }

        $('html').on('click','.button-journal-update',function () {
            updateTeacher($(this));
        })

        function updateTeacher(e){
            var id_teacher =e.parent().parent().attr('data-id-teacher');
            var id_key =e.parent().parent().attr('data-id-key');//show-teacher-name
            var teacher_name = e.parent().parent().parent().find('.show-teacher-name input').val();
            var key = e.parent().parent().parent().find('.show-teacher-key input').val();
            console.log(key);
            $('.table-show-teachers').html(" ");
            $('.preLoader').show();
            var token = "{{ csrf_token() }}";
            $.ajax({
                type: 'PUT',
                url: '/journal/updateTeacher',
                data: ({
                    "_token": token,
                    'id_teacher':id_teacher,
                    'id_key':id_key,
                    'teacher_name':teacher_name,
                    'key':key
                }),
                error:function(){
                    note({
                        content: "Сталася помилка при зміненні вчителя",
                        type: "error",
                        time: 3
                    });
                },
                success: function (result) {
                    if(result=="true") {
                        note({
                            content: "Вчителя успішно змінено.",
                            type: "info",
                            time: 5
                        });
                    }else{
                        note({
                            content: result,
                            type: "error",
                            time: 3
                        });
                    }
                    showTeachers();

                }
            });
        }
    </script>
@endsection