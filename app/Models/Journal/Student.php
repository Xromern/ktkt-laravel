<?php

namespace App\Models\Journal;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{

    static public function getAllStudents($id_group){
        $field = array(
            'students.id as student_id',
            'students.full_name as full_name',
            'keys.id as key_id',
            'keys.key as key',
            'keys.date_use as date_use',
            'users.id as users_id',
            'users.name as user_name'
        );

        $student = \DB::table('students')
            ->rightJoin('keys', 'keys.student_id', '=', 'students.id')
            ->leftJoin('users','keys.user_use','=','user_id')
            ->where('students.group_id',$id_group)
            ->get($field);
        return $student;
    }

    static public function deleteStudents($id_key,$id_student){



        \DB::table('keys')->where('id',$id_key)->delete();
        \DB::table('students')->where('id',$id_student)->delete();
        return true;
    }
}
