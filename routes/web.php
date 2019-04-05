<?php


Route::get('/' ,'ArticleController@showPage')->name('home');

Route::get('/page/{page_id}'  ,'ArticleController@showPage');

Route::get('/article/{article_id}'  ,'ArticleController@showArticle');



    Route::post('/addComment', 'CommentController@addComment');

    Route::post('/showComments', 'CommentController@showComments');

    Route::delete('/deleteComment', 'CommentController@deleteComment');

    Route::post('/getCommentContent', 'CommentController@getCommentContent');

    Route::post('/updateComment', 'CommentController@updateComment');


    Route::group(['prefix' => 'journal', 'middleware' => 'auth'], function () {

        Route::middleware(['journal_admin'])->group(function () {//только для админов

            Route::group(['prefix' => '/'], function () {//routs teacher

                Route::get('/teachers', 'Journal\JournalTeachersController@showTeachers');

                Route::post('/teachersAdd', 'Journal\JournalTeachersController@addTeacher');

                Route::delete('/teacherDelete', 'Journal\JournalTeachersController@deleteTeacher');

                Route::post('/showTeachers', 'Journal\JournalTeachersController@getAllTeachers');

                Route::put('/updateTeacher', 'Journal\JournalTeachersController@updateTeacher');

                Route::post('/teachersShowList', 'Journal\JournalTeachersController@showTeacherList');
            });

            Route::group(['prefix' => '/'], function () {//routs students

                Route::get('/groups/{name_group}/students', 'Journal\JournalStudentsController@showStudents');


                Route::post('/studentAdd', 'Journal\JournalStudentsController@addStudent');

                Route::delete('/studentDelete', 'Journal\JournalStudentsController@deleteStudent');

                Route::post('/showStudents', 'Journal\JournalStudentsController@getAllStudents');

                Route::put('/updateStudent', 'Journal\JournalStudentsController@updateStudent');

                Route::post('/showStudentsForAddSubject', 'Journal\JournalStudentsController@showStudentsForAddSubject');


            });//showStudentsForAddSubject

            Route::group(['prefix' => '/'], function () {//routs Subject

                Route::post('/showSubjectsGroup', 'Journal\JournalSubjectController@showSubjectsGroup');

                Route::post('/addSubject', 'Journal\JournalSubjectController@addSubject');

                Route::post('/showSubjectTeacherList', 'Journal\JournalSubjectController@showSubjectTeacherList');

                Route::get('/groups/{group_name}/subject-{subject_id}', 'Journal\JournalSubjectController@showSubject');

                Route::post('/showSubjectTable', 'Journal\JournalSheetController@showSubjectTable');//updateMark

                Route::put('/updateMark', 'Journal\JournalSheetController@updateMark');

                Route::put('/updateDate', 'Journal\JournalSheetController@updateDate');

                Route::post('/getDate', 'Journal\JournalSheetController@getDate');

                Route::middleware(['journal_admin'])->group(function () {

                    Route::post('/getSubjectName', 'Journal\JournalSubjectController@getSubjectName');

                    Route::post('/getSubjectStudent', 'Journal\JournalSubjectController@getSubjectStudent');

                    Route::delete('/deleteStudentFromSubject', 'Journal\JournalSubjectController@deleteStudentFromSubject');

                    Route::post('/addStudentToSubject', 'Journal\JournalSubjectController@addStudentToSubject');

                    Route::put('/updateSubject', 'Journal\JournalSubjectController@updateSubject');

                    Route::delete('/deleteSubject', 'Journal\JournalSubjectController@deleteSubject');



                });

            });

        });

        Route::middleware(['journal_teacher'])->group(function () {//только для админов и учителей

            Route::group(['prefix' => '/'], function () {//routs group

                Route::get('/groups', 'Journal\JournalGroupsController@showGroups');

                Route::post('/groupAdd', 'Journal\JournalGroupsController@addGroup');

                Route::post('/groupsShow', 'Journal\JournalGroupsController@buildGroup');

                Route::get('/groups/{id_group}', 'Journal\JournalGroupsController@showEditGroup');

                Route::put('/updateGroup', 'Journal\JournalGroupsController@updateGroup');

                Route::delete('/deleteGroup', 'Journal\JournalGroupsController@deleteGroup');


                Route::post('/specialtyAdd', 'Journal\JournalSpecialtyController@addSpecialty');

                Route::post('/specialtyShow', 'Journal\JournalSpecialtyController@showSpecialty');

                Route::post('/specialtyShowList', 'Journal\JournalSpecialtyController@showSpecialtyList');

                Route::delete('/specialtyDelete', 'Journal\JournalSpecialtyController@deleteSpecialty');

                Route::put('/specialtyUpdate', 'Journal\JournalSpecialtyController@updateSpecialty');

            });

        });

    });

Auth::routes();

Route::get('logout', '\App\Http\Controllers\Auth\LoginController@logout');

Route::get('/home', 'HomeController@index')->name('home');

