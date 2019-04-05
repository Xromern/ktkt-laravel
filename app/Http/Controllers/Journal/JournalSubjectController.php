<?php

namespace App\Http\Controllers\Journal;

use App\Http\Controllers\Controller;
use App\Models\Journal\DateJournal;
use App\Models\Journal\GroupJournal;
use App\Models\Journal\MarksJournal;
use App\Models\Journal\SheetJournal;
use App\Models\Journal\Student;
use App\Models\Journal\Teacher;
use App\Repositories\SheetSubject;
use Illuminate\Http\Request;
use App\Repositories\SubjectGroup;


class JournalSubjectController extends Controller
{

    static public function showSubject($group_name, $subject_id)
    {

        $subject = SheetJournal::where('id',$subject_id)->select('id','group_id')->first();
        $group_id = GroupJournal::where('name',$group_name)->select('id')->first();
        $group_id = $group_id->id;
        if($subject==null){
        abort(404);
        }

        return view('Journal.journal_subject', compact('group_name', 'subject_id', 'group_id'));
    }

    static public function deleteSubject(Request $request){
        $subject_id = $request->subject_id;

        MarksJournal::where('journal_id', $subject_id)->delete();

        DateJournal::where('journal_id', $subject_id)->delete();

        SheetJournal::where('id', $subject_id)->delete();

        return "true";

    }

    static public function updateSubject(Request $request)
    {
        $subject_id = $request->subject_id;
        $subject_name = $request->subject_name;
        $subject_teacher = $request->subject_teacher;

        SheetJournal::where('id', $subject_id)->update(
            array(
                'name' => $subject_name,
                'teacher_id' => $subject_teacher
            )
        );
        return "true";
    }

    static public function addSubject(Request $request,SubjectGroup $subject)
    {
        $list_students = $request->list_student;
        $list_students = json_decode($list_students, true);
        $name_subject = $request->name_subject;
        $group_id = $request->group_id;

        $teacher_id = $request->teacher_id == "null" ? null : $request->teacher_id;
        if (mb_strlen($name_subject) < 5) return "Довжина імені групи повинна бути більше 5";


        $subject_id = $subject->insertSubject($name_subject,$teacher_id,$group_id);

        $subject->insertDate($subject_id);
        $subject->insertMarks($subject_id,$list_students);

        return "true";
    }


    static public function addStudentToSubject(Request $request,SubjectGroup $subjectGroup)
    {
        $student_id = $request->student_id;
        $subject_id = $request->subject_id;

        $check = MarksJournal::where('journal_id', $subject_id)->where('student_id', $student_id)->select('id')->get();

        if (count($check) > 0) {
            return "Такий студент вже існує.";
        }

        $subjectGroup->insertStudentToSubject($student_id,$subject_id);

        return "true";

    }

    static public function deleteStudentFromSubject(Request $request)
    {
        $student_id = $request->student_id;
        $subject_id = $request->subject_id;
        MarksJournal::where('journal_id', $subject_id)->where('student_id', $student_id)->delete();
        return "true";
    }

    static public function getSubjectStudent(Request $request)
    {
        $subject_id = $request->subject_id;
        $group_id = $request->group_id;

        $students_id = MarksJournal::where('journal_id', $subject_id)->select('student_id')->distinct('student_id')->get()->toArray();
        $students = Student::where('group_id', $group_id)->select('id', 'full_name')->orderBy('full_name', 'ASC')->get()->toArray();

        $array_id = array();

        for ($i = 0; $i < count($students_id); $i++) {
            $array_id[$i] = $students_id[$i]['student_id'];
        }

        $block = "";
        $i = 0;
        $block .= "<table class='student_ student_yes'>";
        foreach ($students as $student) {
            if (in_array($student['id'], $array_id)) {
                $block .= "<tr data-id-student='" . $student['id'] . "'>
            <td style='padding:0px; text-align:center;'>" . (++$i) . "</td>
            <td>" . $student['full_name'] . "</td>
            <td class='rewmove-student'>⁣</td>
            </tr>";
            }
        }
        $block .= "</table>";
        $block .= "<table class='student_ student_no'>";
        foreach ($students as $student) {
            if (!in_array($student['id'], $array_id)) {
                $block .= "<tr data-id-student='" . $student['id'] . "'>
            <td style='padding:0px; text-align:center;'>" . (++$i) . "</td>
            <td>" . $student['full_name'] . "</td>
            <td class='rewmove-student'>⁣</td>
            </tr>";
            }
        }
        $block .= "</table>";


        return $block;

    }

    static public function getSubjectName(Request $request)
    {
        $subject_id = $request->subject_id;
        $name = SheetJournal::where('id', $subject_id)->select('name')->first();
        return $name->name;
    }

    static public function showSubjectTeacherList(Request $request)
    {
        $subject_id = $request->subject_id;
        $teacher_id = SheetJournal::where('id', $subject_id)->select('teacher_id')->first();
        $teachers = Teacher::select('id', 'full_name')->get();

        return self::buildSubjectTeacherList($teachers, $teacher_id->teacher_id);
    }

    static public function buildSubjectTeacherList($teachers, $teacher_id)
    {

        $block = "";
        foreach ($teachers as $item) {
            $selected = ($teacher_id == $item->id) ? 'selected' : '';
            $block .= "<option $selected value='" . $item->id . "'>" . $item->full_name . "</option>";
        }
        return $block;
    }

    static public function showSubjectsGroup(Request $request)
    {
        $group_id = $request->group_id;
        $group_name = $request->group_name;
        $subjects = SheetJournal::getSubjects($group_id);

        return self::buildSubjects($subjects, $group_name);
    }

    static public function buildSubjects($subjects, $group_name)
    {
        $block = "";

        foreach ($subjects as $subject) {
            $block .= "
            <a href='/journal/groups/$group_name/subject-$subject->subject_id/'class='block-group'>
                <div class='block-subject-name'>" . $subject->subject_name . "</div>
                <div class='block-group-curator'><div>Викладач:</div> " . $subject->teacher_name . "</div>
                <div class='block-group-count-student'><div>Середній бал:</div> 4.3 </div>
   
            </a>";
            $block .= "</div>";
        }

        return $block;
    }
}
