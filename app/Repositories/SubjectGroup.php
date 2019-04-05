<?php

namespace App\Repositories;

use App\Models\Journal\DateJournal;
use App\Models\Journal\MarksJournal;
use App\Models\Journal\SheetJournal;

class SubjectGroup{

    public function validateSubject(){

    }

    public function insertSubject($name_subject,$teacher_id,$group_id){
        $journal_id = SheetJournal::insertGetId(
            array(
                "name" => $name_subject,
                "teacher_id" => $teacher_id,
                "group_id" => $group_id,
                "created_at" => now(),
                "updated_at" => now()
            )
        );

        return $journal_id;
    }

    public function insertDate($subject_id){

        $array_date = [];

        for ($i = 0; $i < 35; $i++) {
            $array_date[] = [
                "journal_id" => $subject_id,
                "created_at" => now(),
                "updated_at" => now()
            ];
        }
        DateJournal::insert($array_date);

    }

    public function insertMarks($subject_id,$list_students){

        foreach ($list_students as $student_id) {
            $this->insertStudentToSubject($student_id,$subject_id);
        }

    }

    public function insertStudentToSubject($student_id,$subject_id){

        $dates_id = DateJournal::where('journal_id', $subject_id)->select('id')->get();

        $array_marks=[];

        foreach ($dates_id as $date_id) {
            $array_marks[] = [
                "student_id" => $student_id,
                "journal_id" => $subject_id,
                "date_id" => $date_id->id,
                "created_at" => now(),
                "updated_at" => now()
            ];
        }
        MarksJournal::insert($array_marks);
    }
}