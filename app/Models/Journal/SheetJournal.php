<?php

namespace App\Models\Journal;

use Illuminate\Database\Eloquent\Model;

class SheetJournal extends Model
{
    static public function getSubjects($group_id){

        $field = array(
            'teachers.id as teacher_id',
            'teachers.full_name as teacher_name',
            'teachers.full_name as teacher_name',
            'sheet_journals.name as subject_name',
            'sheet_journals.id as subject_id'

        );

       $subjects =  \DB::table('sheet_journals')
            ->leftJoin('teachers','sheet_journals.teacher_id','=','teachers.id')
            ->where('sheet_journals.group_id',$group_id)
            ->get($field);

       return $subjects;
    }


}
