<?php

namespace App\Http\Controllers\Journal;

use App\Models\Journal\DateJournal;
use App\Models\Journal\GroupJournal;
use App\Models\Journal\Key;
use App\Models\Journal\MarksJournal;
use App\Models\Journal\SheetJournal;
use App\Models\Journal\Specialty;
use App\Models\Journal\Student;
use App\Models\Journal\Teacher;
use App\User;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Symfony\Component\Console\Helper\Table;

class JournalGroupsController extends Controller
{
    static public function showGroups()
    {
        return view('Journal.journal_groups');
    }

    static public function showEditGroup($group_name)
    {
        $group = GroupJournal::getGroup($group_name);//это id
        if (count($group) == 0) abort(404);
        $group = $group[0];
        return view('Journal.journal_groups_edit', compact('group'));
    }

    static public function addGroup(Request $request)
    {
        $id_teacher = $request->id_teacher;
        $id_specialty = $request->id_specialty;
        $group_name = trim($request->group_name);

        if (mb_strlen($group_name) != 7) {
            return "Невірна довжина імені групи.";
        }

        if ($id_teacher != "null") {
            $check = count(Teacher::where('id', $id_teacher)->get());
            if ($check == 0) return "Такого вчителя не існує";

            $check = GroupJournal::where('teacher_id', $id_teacher)->get();
            if (count($check) > 0) return "Вчитель вже є куратором групи " . $check[0]->name;
        } else {
            $id_teacher = null;
        }

        $check = count(Specialty::where('id', $id_specialty)->get());
        if ($check == 0) return "Такої спеціальності не існує";


        $check = count(GroupJournal::where('name', $group_name)->get());
        if ($check > 0) return "Така група вже існує";


        GroupJournal::insert(
            array(
                'name' => $group_name,
                'specialty_id' => $id_specialty,
                'teacher_id' => $id_teacher,
                'created_at' => now(),
                'updated_at' => now()

            )
        );

        return "true";
    }

    static public function updateGroup(Request $request)
    {
        $id_teacher = $request->id_teacher;
        $id_group = $request->id_group;
        $id_specialty = $request->id_specialty;
        $group_name = $request->group_name;
        //  dd($id_specialty);
        if (mb_strlen($group_name) != 7) {
            return "Невірна довжина імені групи.";
        }
        if ($id_teacher != "null") {
            $check = count(Teacher::where('id', $id_teacher)->get());
            if ($check == 0) return "Такого вчителя не існує";

            $check = GroupJournal::where('teacher_id', $id_teacher)->where('id', '!=', $id_group)->get();
            if (count($check) > 0) return "Вчитель вже є куратором групи " . $check[0]->name;
        } else {
            $id_teacher = null;
        }
        $check = count(Specialty::where('id', $id_specialty)->get());
        if ($check == 0) return "Такої спеціальності не існує";

        $check = count(GroupJournal::where('name', $group_name)->where('id', '!=', $id_group)->get());
        if ($check > 0) return "Така група вже існує";
        // dd($id_specialty);
        GroupJournal::where('id', $id_group)->update(
            array(
                'name' => $group_name,
                'specialty_id' => $id_specialty,
                'teacher_id' => $id_teacher,
                'updated_at' => now()
            )
        );

        return "true";
    }

    static public function deleteGroup(Request $request)
    {
        $id_group = $request->id_group;

        $check = GroupJournal::where('id', $id_group)->select('id')->get();
        if (count($check) == 0) return "Такої групи не існує";

        $sheet_journal = SheetJournal::where('id', $id_group)->select('id')->get();

        foreach ($sheet_journal as $journal) {
            MarksJournal::where('journal_id', $journal->id)->delete();
            DateJournal::where('journal_id', $journal->id)->delete();
        }

        $journal_student = Student::where('group_id', $id_group)->select('id', 'user_id')->orderBy('full_name', 'ASC')->get();
        //dd($id_group);
        foreach ($journal_student as $student) {
            Key::where('student_id', $student->id)->delete();

            Student::where('id', $student->id)->delete();
            User::where('id', $student->user_id)
                ->update(array('privilege' => 0));

        }
        SheetJournal::where('group_id', $id_group)->delete();
        GroupJournal::where('id', $id_group)->delete();
        return "true";
    }


    static public function buildGroup()
    {
        $block = "";
        $specialties = Specialty::select('id', 'specialty_name')->get();

        foreach ($specialties as $specialty) {
            $groups = GroupJournal::getGroupsSpecialties($specialty->id);
            if (count($groups) == 0) continue;
            $block .= "
            <div class='block-specialty'>
            <div class='line-specialty'>" . $specialty->specialty_name . "</div>";
            foreach ($groups as $group) {
                $count_sutdent = Student::where('group_id', $group->group_id)->select('id')->get();
                $block .= "
            <a href='/journal/groups/" . $group->group_name . "'class='block-group'>
                <div class='block-group-name'>" . $group->group_name . "</div>
                <div class='block-group-curator'><div>Куратор:</div> " . $group->teacher_name . "</div>
                <div class='block-group-count-student'><div>Кількість студентів:</div> " . count($count_sutdent) . "</div>
   
            </a>";
            }
            $block .= "</div>";
        }

        return $block;
    }
}

