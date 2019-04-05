<?php

namespace App\Http\Controllers\Journal;

use App\Models\Journal\GroupJournal;
use App\Models\Journal\Key;

use App\Models\Journal\SheetJournal;
use App\Models\Journal\Teacher;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repositories\Teachers;
use phpDocumentor\Reflection\Types\Nullable;
use Psy\Util\Str;

class JournalTeachersController extends Controller
{

    static public function showTeachers(){

        return view('Journal.journal_teachers');
    }

    static public function getAllTeachers(){

        $teachers =  self::buildTeacher(Teacher::getAllTeachers());
       return $teachers;

    }

    static public function updateTeacher(Request $request){
        $id_teacher = $request->id_teacher;
        $id_key = $request->id_key;
        $teacher_name = $request->teacher_name;
        $key = $request->key;
        try{

        Teacher::where('id',$id_teacher)->update(
            array('full_name'=>$teacher_name,'updated_at'=>now())
        );

        Key::where('id',$id_key)->update(
            array('key'=>$key,'updated_at'=>now())
        );
        }catch (\Exception $e){
            return $e;
        }
        return "true";
    }


    static public function getTeacher($id){
        $teacher = Teacher::where('id',$id)->first();
        return $teacher;
    }

    static public function deleteTeacher(Request $request){
        $id_teacher = $request->id_teacher;
        $id_key = $request->id_key;

        GroupJournal::where('teacher_id',$id_teacher)->update(
            array('teacher_id'=>null,'updated_at'=>now())
        );

        SheetJournal::where('teacher_id',$id_teacher)->update(
            array('teacher_id'=>null,'updated_at'=>now())
        );


        if( Teacher::deleteTeacher($id_key,$id_teacher)){
        return "true";
       }else{
           return "Сталася помилка при видаленні вчителя";
       }
    }

     public function addTeacher(Request $request){
        $name = trim($request->TeacherName);
        $check = Teacher::where('full_name',$name)->get();
        if(count($check)>0){return "Такий вчитель вже існує.";}
        $key =str_slug($name, '_').str_random(20);

        $teacher_id = Teacher::insertGetId(
            array('full_name'=>$name)
        );

        Key::insert(
            array('key'=>$key,'privilege'=>2,'teacher_id'=>$teacher_id,'created_at'=>now(),'updated_at'=>now())
        );
        return "true";

    }

    static public function showTeacherList(Request $request){

        $teachers = Teacher::all();

        $selected =GroupJournal::where('id',$request->id_group)->select('teacher_id')->get();
        $selected = count($selected)==1? $selected[0]->teacher_id:0;
        return self::buildTeacherList($teachers,$selected);
    }

    static public function buildTeacherList($teachers,$selected = 0){
        $block="";
        $block.="<option value='null'>Без куратора</option>";
        foreach ($teachers as $item) {
            if($selected == $item->id) {
                $block .= "<option selected value='" . $item->id . "'>" . $item->full_name . "</option>";
            }else{
                $block .= "<option value='" . $item->id . "'>" . $item->full_name . "</option>";
            }
        }
        return $block;
    }

    /*Вывод всех учителей для редактирования

    */
    static private function buildTeacher($result){

        $str = "";

       foreach ($result as $teacher){

       $str .= "<tr class='tr-teacher-leavel-one'>
           <td class='show-teacher-name'><input value='".$teacher->full_name."'></td>';
           <th style='text-align:right;'>Код: </th><td class='show-teacher-key'><input value='".$teacher->key."'></td>';
           </tr>
           <tr class='tr-teacher-leavel-two' data-id-key='".$teacher->key_id."' data-id-teacher='".$teacher->teacher_id."'>';
           <td><br><div class='button button-journal-update' >Змінити</div><div class='button button-journal-delete' style='margin-left:10px;'>Видалити</div></td>';
           <td class='show-teacher-use' colspan='2'>Дата реєестрації:".( ($teacher->date_use!=null)?$teacher->date_use:'-')."</td>
           </tr>
       <tr><td colspan='3'><br><hr style='border:1px solid #999'><br></td></tr>";
        }

        return $str;

    }

}
