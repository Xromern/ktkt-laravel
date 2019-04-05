<?php

namespace App\Http\Controllers\Journal;

use App\Models\Journal\GroupJournal;
use App\Models\Journal\Key;

use App\Models\Journal\MarksJournal;
use App\Models\Journal\SheetJournal;
use App\Models\Journal\Student;
use App\Models\Journal\Teacher;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JournalStudentsController extends Controller
{
    static public function showStudents($name_group)
    {

        $check = GroupJournal::where('name', $name_group)->select('id')->get();

        if (count($check) == 0) abort(404);
        $group_id = $check[0]->id;
        return view('Journal.journal_students', compact('name_group', 'group_id'));
    }

    static public function getAllStudents(Request $request)
    {
        $group_id = $request->group_id;
        $students = self::buildStudent(Student::getAllStudents($group_id));
        return $students;

    }

    static public function updateStudent(Request $request)
    {
        $id_student = $request->id_student;
        $id_key = $request->id_key;
        $student_name = $request->student_name;
        $key = $request->key;
        try {

            Student::where('id', $id_student)->update(
                array('full_name' => $student_name, 'updated_at' => now())
            );

            Key::where('id', $id_key)->update(
                array('key' => $key, 'updated_at' => now())
            );
        } catch (\Exception $e) {
            return $e;
        }
        return "true";
    }


    static public function getStudent($id)
    {
        $student = Student::where('id', $id)->first();
        return $student;
    }

    static public function deleteStudent(Request $request)
    {
        $id_student = $request->id_student;

        MarksJournal::where('student_id', $id_student)->delete();

        Key::where('student_id', $id_student)->delete();
        Student::where('id', $id_student)->delete();
        User::where('id', $id_student)
            ->update(array('privilege' => 0));

        return "true";
    }

    public function addStudent(Request $request)
    {
        $name = trim($request->StudentName);
        $check = Student::where('full_name', $name)->get();
        $group_id = $request->group_id;
        if (count($check) > 0) {
            return "Такий студент вже існує.";
        }
        $key = str_slug($name, '_') . str_random(20);

        $student_id = Student::insertGetId(
            array('full_name' => $name, 'group_id' => $group_id)
        );

        Key::insert(
            array('key' => $key, 'privilege' => 1, 'student_id' => $student_id, 'created_at' => now(), 'updated_at' => now())
        );
        return "true";

    }


    static public function showStudentsForAddSubject(Request $request)
    {
        $group_id = $request->group_id;

        $students = Student::where('group_id', $group_id)->get();
        //dd($students);
        $block = "";
        $i = 0;
        foreach ($students as $student) {
            $block .= "<tr data-id-student='" . $student->id . "'>
            <td style='padding:0px; text-align:center;'>" . (++$i) . "</td>
            <td>" . $student->full_name . "</td>
            <td class='rewmove-student'>⁣</td>
            </tr>";
        }
        return $block;
    }

    static private function buildStudent($result)
    {/*Вывод всех учителей*/
        $str = "";
        // dd($result);
        foreach ($result as $student) {

            $str .= "<tr class='tr-student-leavel-one'>
           <td class='show-student-name'><input value='" . $student->full_name . "'></td>';
           <th style='text-align:right;'>Код: </th><td class='show-student-key'><input value='" . $student->key . "'></td>';
           </tr>
           <tr class='tr-student-leavel-two' data-id-key='" . $student->key_id . "' data-id-student='" . $student->student_id . "'>';
           <td><br><div class='button button-journal-update' >Змінити</div><div class='button button-journal-delete' style='margin-left:10px;'>Видалити</div></td>';
           <td class='show-student-use' colspan='2'>Дата реєестрації:" . (($student->date_use != null) ? $student->date_use : '-') . "</td>
           </tr>
       <tr><td colspan='3'><br><hr style='border:1px solid #999'><br></td></tr>";
        }

        return $str;

    }
}
