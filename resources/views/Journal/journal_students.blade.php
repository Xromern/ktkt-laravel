@extends('layouts.layouts_journal')

@section('content')
@section('title', 'Студенти групи - '.$name_group)
<div class="container-journal-center">
    <div class="container-journal-block">
        <input type="text" class="edit-add-student" placeholder="П.І.Б" required="required"/>

        <div class="button journal-button-student-add">Додати</div>
    </div>
    <div class="container-journal-show-students">
        @include('MainSite.preloader')
        <table class="table-show-students"></table>
    </div>
</div>
<script>
    $('.journal-button-student-add').on('click', function (e) {
        if (check_timeout_button($(this)))
            addStudent($('.edit-add-student').val());
    });

    function addStudent(TeacherName) {
        $('.table-show-students').html(" ");
        $('.preLoader').show();
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            url: '/journal/studentAdd',
            data: ({
                "_token": token,
                'StudentName': TeacherName,
                'group_id': '{{$group_id}}',
                'name_group': '{{$name_group}}'
            }),
            error: function () {
                note({
                    content: "Сталась помилка при додаванні студента",
                    type: "error",
                    time: 3
                });
                showStudents();
            },
            success: function (result) {
                if (result == "true") {
                    note({
                        content: "Студента успішно додано.",
                        type: "info",
                        time: 3
                    });
                } else {
                    note({
                        content: result,
                        type: "error",
                        time: 3
                    });
                }
                showStudents();
                $('.edit-add-student').val("");
            }
        });
    }

    showStudents();

    function showStudents() {

        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            url: '/journal/showStudents',
            data: ({
                "_token": token,
                'group_id': '{{$group_id}}'
            }),
            beforeSend: function () {
                $('.preLoader').show();
            },
            complete: function () {
                $('.preLoader').hide();
            },
            error: function () {
                showStudents()
                note({
                    content: "Сталася помилка",
                    type: "error",
                    time: 1
                });
            },
            success: function (result) {
                $('.table-show-students').html(result);
                //console.log(result)

            }
        });
    }

    $('html').on('click', '.button-journal-delete', function () {
        deleteTeacher($(this));
    })

    function deleteTeacher(e) {
        var id_student = e.parent().parent().attr('data-id-student');
        var id_key = e.parent().parent().attr('data-id-key');
        console.log(id_student);
        $('.table-show-students').html(" ");
        $('.preLoader').show();
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'DELETE',
            url: '/journal/studentDelete',
            data: ({
                "_token": token,
                'id_student': id_student,
                'id_key': id_key,
            }),
            error: function () {
                note({
                    content: "Сталась помилка при видаленні Студента",
                    type: "error",
                    time: 3
                });
            },
            success: function (result) {
                if (result == "true") {
                    note({
                        content: "Студента успішно видалено.",
                        type: "info",
                        time: 5
                    });
                } else {
                    note({
                        content: result,
                        type: "error",
                        time: 3
                    });
                }

                showStudents();

            }
        });
    }


    $('html').on('click', '.button-journal-update', function () {
        updateStudent($(this));
    })

    function updateStudent(e) {
        var id_student = e.parent().parent().attr('data-id-student');
        var id_key = e.parent().parent().attr('data-id-key');//show-teacher-name
        var student_name = e.parent().parent().parent().find('.show-student-name input').val();
        var key = e.parent().parent().parent().find('.show-student-key input').val();
        console.log(key);
        $('.table-show-students').html(" ");
        $('.preLoader').show();
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'PUT',
            url: '/journal/updateStudent',
            data: ({
                "_token": token,
                'id_student': id_student,
                'id_key': id_key,
                'student_name': student_name,
                'key': key

            }),
            error: function () {
                note({
                    content: "Сталася помилка при зміненні студента",
                    type: "error",
                    time: 15
                });
            },
            success: function (result) {
                if (result == "true") {
                    note({
                        content: "Студент успішно змінено.",
                        type: "info",
                        time: 15
                    });
                } else {
                    note({
                        content: result,
                        type: "error",
                        time: 15
                    });
                }
                showStudents();

            }
        });
    }
</script>
@endsection