@extends('layouts.layouts_journal')

@section('content')
@section('title', 'Група ')

<div class="container-table-subject-journal">
    <table class="table-subject-journal table ver">

    </table>
</div>

<div class="container-journal-center">
    <div class="container-journal-block">
        <div class="button button-show-modal-update-subject"> Редагувати предмет.</div>
        <div class="button button-delete-subject"> Видалити предмет.</div>

    </div>
</div>

<div id="myModal" class="modal modal-update-subject">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="modal-close">✘</span>
        <div class="modal-container-journal-update-subject">
            <input type="text" class="input-update-subject" placeholder="Предмет" required="required"/>
            <select class="select-teacher">
            </select>
            <div class="button button-update-subject">Зберегти</div>
        </div>
        <div class="container-table-students-subject">
            @include('MainSite.preloader')
            <div class="field_block_2 student_select">
                <table class="student_ student_yes">
                </table>
                <table class="student_ student_no">
                </table>
            </div>
        </div>
    </div>

</div>

<div id="modal-date" class="modal modal-date-subject">
    <!-- Modal content -->
    <div class="modal-content">
        <span class="modal-close">✘</span>

        <div class="modal-container-date-subject">
            <input type="date" class="date-subject-modal" >
            <div class="subject-modal-block-attestation">
                Атестація  <input type="checkbox" class="checkbox-subject-modal" ></div>
            <textarea class="textarea-subject-modal"></textarea>

            <div class="button button-update-subject-date">Зберегти</div>
        </div>

    </div>

</div>
<script>
$(document).ready(function () {

    showSubjectJournal()

    function showSubjectJournal() {
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'post',
            url: '/journal/showSubjectTable',
            data: ({
                "_token": token,
                "subject_id": '{{$subject_id}}'
            }),
            error: function () {
                note({
                    content: "Сталася помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function (result) {
                $('.table-subject-journal').html(result)
            }
        });
    }

    $('html').on('click', '.button-delete-subject', function () {
        if (confirm("Ви дійсно хочете видалити предмет?"))
            deleteSubject();
    })

    function deleteSubject() {
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'delete',
            url: '/journal/deleteSubject',
            data: ({
                "_token": token,
                "subject_id": '{{$subject_id}}'
            }),
            error: function () {
                note({
                    content: "Сталася помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function (result) {
                if (result == "true") {
                    note({
                        content: "Предмет видалений.",
                        type: "info",
                        time: 5
                    });
                    setTimeout(function () {
                        location.href = "/journal/groups/{{$group_name}}";
                    }, 0)

                } else {
                    note({
                        content: result,
                        type: "error",
                        time: 3
                    });
                }
            }
        });
    }

    $('html').on('click', '.button-update-subject', function () {
        updateSubject();
    })

    function updateSubject() {
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'put',
            url: '/journal/updateSubject',
            data: ({
                "_token": token,
                "subject_id": '{{$subject_id}}',
                'subject_name': $('.input-update-subject').val(),
                'subject_teacher': $('.select-teacher').val()
            }),
            error: function () {
                note({
                    content: "Сталася помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function (result) {
                if (result == "true") {
                    note({
                        content: "Предмет змінений.",
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
                getSubjectName()
                showSubjectTeacherList();

            }
        });
    }

    $('html').on('change', '.table-subject-select',function () {
        var student_id = $(this).parent().parent().find('.table-subject-name').attr('data-student-id');

        updateMark($(this).val(), $(this).attr('data-id-mark'),student_id);
    })

    function updateMark(mark, mark_id,student_id) {
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'put',
            url: '/journal/updateMark',
            data: ({
                "_token": token,
                'mark': mark,
                'mark_id': mark_id,
                'student_id':student_id

            }),
            error: function () {
                note({
                    content: "Сталася помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function (result) {

                showSubjectJournal();
            }
        });
    }

    function deleteStudentFromSubject(student_id) {
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'delete',
            url: '/journal/deleteStudentFromSubject',
            data: ({
                "_token": token,
                "subject_id": '{{$subject_id}}',
                "student_id": student_id
            }),
            beforeSend: function () {
                $('.student_select').html("");
                $('.preLoader').show();
            },
            complete: function () {
                $('.preLoader').hide();
                getSubjectStudent();
            },
            error: function () {
                note({
                    content: "Сталася помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function (result) {
                if (result == "true") {
                    note({
                        content: "Студента успішно выдалено.",
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


            }
        });
    }

    function addStudentToSubject(student_id) {
        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            url: '/journal/addStudentToSubject',
            data: ({
                "_token": token,
                "subject_id": '{{$subject_id}}',
                "student_id": student_id
            }),
            beforeSend: function () {
                $('.student_select').html("");
                $('.preLoader').show();
            },
            complete: function () {
                $('.preLoader').hide();
                getSubjectStudent();
            },
            error: function () {
                note({
                    content: "Сталася помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function (result) {
                if (result == "true") {
                    note({
                        content: "Студента успішно додано.",
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


            }
        });
    }

    function getSubjectStudent() {

        var token = "{{ csrf_token() }}";


        $.ajax({
            type: 'POST',
            url: '/journal/getSubjectStudent',
            data: ({
                "_token": token,
                "subject_id": '{{$subject_id}}',
                "group_id": "{{$group_id}}"
            }),
            beforeSend: function () {
                $('.preLoader').show();
            },
            complete: function () {
                $('.preLoader').hide();
            },
            error: function () {
                note({
                    content: "Сталася помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function (result) {

                $('.student_select').html(result);

            }
        });
    }

    getSubjectName()

    function getSubjectName() {

        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            url: '/journal/getSubjectName',
            data: ({
                "_token": token,
                "subject_id": '{{$subject_id}}'
            }),
            error: function () {
                note({
                    content: "Сталася помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function (result) {
                $('.input-update-subject').val(result);

            }
        });
    }

    function showSubjectTeacherList() {

        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            url: '/journal/showSubjectTeacherList',
            data: ({
                "_token": token,
                "subject_id": '{{$subject_id}}'
            }),
            error: function () {
                note({
                    content: "Сталася помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function (result) {
                $('.select-teacher').html(result);

            }
        });
    }

    remove_student();

    function remove_student() {
        $('html').on('click', '.student_yes tr', function (e) {
            var student_id = $(this).attr('data-id-student');
            deleteStudentFromSubject(student_id);

            $(this).prependTo($('.student_no'));
            calculation_height();
            showSubjectJournal()
        });
    }

    add_student();

    function add_student() {
        $('html').on('click', '.student_no tr', function (e) {
            var student_id = $(this).attr('data-id-student');
            addStudentToSubject(student_id);

            $(this).prependTo($('.student_yes'));
            calculation_height();
            showSubjectJournal()
        });
    }

    function calculation_height() {
        var length = $('.student_no tr').length;
        var min_height = length * 35.3;
        $('.student_select').css('min-height', min_height + 'px');
    }

    var modal = document.getElementById('myModal');

    var span = document.getElementsByClassName("modal-close")[0];

    var modal_content = document.getElementsByClassName("modal-close")[0];
    //если кликаю на крестик, добавлюя id коммента в модальное окно для редактирование комменатрия
    $('html').on('click', '.button-show-modal-update-subject', function () {
        showSubjectTeacherList()
        getSubjectStudent()
        getSubjectName()
        modal.style.display = "block";

    });

    span.onclick = function () {
        $('.student_select').html("");
        modal.style.display = "none";
    }

    window.onclick = function (event) {
        if (event.target == modal) {
            $('.student_select').html("");

            modal.style.display = "none";
        }
    }


    var modal_date = document.getElementById('modal-date');

    $('html').on('click', '.button-update-subject-date', function () {

        var date_id = $('.modal-update-subject').attr('data-date-id');
        var date = $('.date-subject-modal').val();
        var description = $('.textarea-subject-modal').val();
        var attestation = $('.checkbox-subject-modal').is(':checked');
        attestation = attestation?1:0;
        updateDate(date_id,date,description,attestation);

        showSubjectJournal();

    });

    function updateDate(date_id,date,description,attestation) {

        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'PUT',
            url: '/journal/updateDate',
            data: ({
                "_token": token,
                "date_id": date_id,
                "date":date,
                "description":description,
                "attestation":attestation
            }),
            error: function () {
                note({
                    content: "Сталася помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function (result) {
 //console.log(result)

            }
        });
    }

    function getDate(date_id) {

        var token = "{{ csrf_token() }}";
        $.ajax({
            type: 'POST',
            url: '/journal/getDate',
            data: ({
                "_token": token,
                "date_id": date_id
            }),
            error: function () {
                note({
                    content: "Сталася помилка, перезавантажте сторінку.",
                    type: "error",
                    time: 15
                });
            },
            success: function (result) {
                $('.date-subject-modal').val(result['date']);
                $('.textarea-subject-modal').val(result['description']);
                $('.checkbox-subject-modal').prop('checked', result['attestation']);
            }
        });
    }


    var span = document.getElementsByClassName("modal-close")[1];

    var modal_content = document.getElementsByClassName("modal-close")[1];
    //если кликаю на крестик, добавлюя id коммента в модальное окно для редактирование комменатрия
    $('html').on('click', '.journal-table-date', function () {
       var date_id =  $(this).attr('data-date-id');
       $('.modal-update-subject').attr('data-date-id',date_id);
        getDate(date_id);
        modal_date.style.display = "block";

    });

    span.onclick = function () {
        $('.date-subject-modal').val('');
        $('.textarea-subject-modal').val('');
        $('.checkbox-subject-modal').val('')

        modal_date.style.display = "none";
    }

    window.onclick = function (event) {
        if (event.target == modal_date) {


            modal_date.style.display = "none";
        }
    }
    $('html').on('mouseover', '.column', function() {

        var table2 = $(this).parent().parent();
        var column = $(this).data('column') + "";
        $(table2).find("." + column).addClass('hov-column');

    });
    $('html').on('mouseout', '.column', function() {

        var table2 = $(this).parent().parent();
        var column = $(this).data('column') + "";
        $(table2).find("." + column).removeClass('hov-column');

    });
});
</script>
@endsection