<?php

namespace App\Models\Journal;

use Illuminate\Database\Eloquent\Model;

class GroupJournal extends Model
{
    static public function addGroup($group_name,$id_specialties,$id_curator){
        $group_name = trim($group_name);

        $check_specialties = DB::table('specialties')->where('id',$id_specialties)->get();

        $check_teacher = Teacher::where('id',$id_curator)->get();

        if(count($check_specialties)==0 || count($check_teacher)==0){
            return false;
        }
        $id_curator = ($id_curator!=0)?$id_curator:NULL;
        DB::table('group_journals')->insert(
            array('name'=>$group_name,'specialty_id'=>$id_specialties,'teacher_id'=>$id_curator)
        );
        return true;
    }
       static  $field_group = array(
        'group_journals.id as group_id',
        'group_journals.name as group_name',
        'group_journals.specialty_id as specialty_id',
        'teachers.full_name as teacher_name',
        'teachers.id as teacher_id',
        );

    static public function getGroupsSpecialties($id)
    {
        $groups = GroupJournal
            ::leftJoin('teachers', 'teachers.id', '=', 'group_journals.teacher_id')
            ->where('group_journals.specialty_id',$id)
            ->get(self::$field_group);

        return $groups;
    }

    static public function getGroup($name)
    {
        $group = GroupJournal
            ::leftJoin('teachers', 'teachers.id', '=', 'group_journals.teacher_id')
            ->where('group_journals.name',$name)
            ->get(self::$field_group);

        return $group;
    }
}
