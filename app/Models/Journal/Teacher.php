<?php

namespace App\Models\Journal;

use Illuminate\Database\Eloquent\Model;

class Teacher extends Model
{

    static public function getAllTeachers(){
        $field = array(
            'teachers.id as teacher_id',
            'teachers.full_name as full_name',
            'keys.id as key_id',
            'keys.key as key',
            'keys.date_use as date_use',
            'users.id as users_id',
            'users.name as user_name'
        );

        $teacher = \DB::table('teachers')
            ->rightJoin('keys', 'keys.teacher_id', '=', 'teachers.id')
            ->leftJoin('users','keys.user_use','=','user_id')
            ->where('teacher_id','!=',null)
            ->get($field);
        return $teacher;
    }

    static public function deleteTeacher($id_key,$id_teacher){
        \DB::table('keys')->where('id',$id_key)->delete();
        \DB::table('teachers')->where('id',$id_teacher)->delete();
        return true;
    }
}
