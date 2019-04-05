<?php

namespace App\Repositories;

use App\Models\Journal\DateJournal;
use App\Models\Journal\MarksJournal;
use App\Models\Journal\SheetJournal;
use App\Models\Journal\Student;
use http\Env\Request;
use phpDocumentor\Reflection\Types\Integer;

class SheetSubject
{
    public function getSubjectTable($subject_id)
    {

        $students_id = MarksJournal::where('journal_id', $subject_id)->select('student_id')->distinct('student_id')->get();


        $table = $this->getHeadSubjectTable($subject_id);

        $i = 0;
        foreach ($students_id as $item) {
            $table .= "<tr class='table-subject-tr'>";
            $i++;
            $table .= "<th class='table-subject-number-student'>$i</th>";
            $table .= $this->getMarksStudent($subject_id, $item->student_id);
            $table .= "</tr>";

        }

        return $table;

    }

    public function getHeadSubjectTable($subject_id)
    { // Вывод шапки журнала

        $dates = DateJournal::where('journal_id', $subject_id)->get()->toArray();
        $block = "<tbody>";
        $block .= '<tr ><th rowspan="2">id</th><th class="name-student" rowspan="2">Прізвище та ініціали</th><th colspan="35">Місяць, число</th><th rowspan=2 width="20px;">с/б</th></tr>';
        $block .= '<tr>';
        $i = 0;
        foreach ($dates as $row) {
            $block .= '
            <th  data-column="column' . ++$i . '" class="journal-table-date column' . $i . ' column" data-date="' . $row['date'] . '" title="' . $row['date'] . '&#013' . $row['description'] . '" ' . 'data-date-description="' . $row['description'] . '" data-date-id=' . $row['id'] . ' data-date-id-journal="' . $row['journal_id'] . '"'
                . 'data-date-number = ' . $i . '>' . $this->convert_date($row['date']) . '&#160</th>';
        }
        return $block;
    }

    public function updateDate($date_id,$date,$description,$attestation){

        DateJournal::where('id', $date_id)->update(
            array(
                'date'=>$date,
                'description'=>$description,
                'attestation'=>$attestation
            )
        );
        return "true";
    }

    public function updateMark($id_mark,$mark,$student_id){

        \DB::beginTransaction();
        MarksJournal::where('id', $id_mark)->update(array('mark'=>$mark));

        \DB::commit();

        $mark_check = MarksJournal::where('id',$id_mark)->select('id','journal_id','mark')->first();

        return $this->getAverageMark($student_id,$mark_check->journal_id);
        //   return "Сталася помилка. Перезавантажте сторінку.";

    }

    public function getMarksStudent($subject_id, $student_id)
    {
        $student = Student::where('id', $student_id)->select('id', 'full_name')->first();

        $field = array(
            'marks_journals.id as id',
            'marks_journals.student_id as student_id',
            'marks_journals.journal_id as journal_id',
            'marks_journals.mark as mark',
            'date_journals.attestation as attestation',
        );

        $marks = MarksJournal::
        leftJoin('date_journals', 'date_journals.id', '=', 'marks_journals.date_id')->
        where('marks_journals.journal_id', $subject_id)
            ->where('marks_journals.student_id', $student_id)
            ->get($field);

        $name = $this->convertName($student->full_name);
        $td = "<td data-student-id='$student->id' class='table-subject-name'>$name</td>";

        $count = 0;
        $average = 0;$num=1;
        foreach ($marks as $mark) {
            $select = $this->selectMark($mark->id,$mark->mark,$num);


            if($mark->attestation==0){
                $td .= "<td data-column='column$num'  class='table-subject-marks column column$num' data-subject-mark-id='$mark->id'>$select</td>";
            }else{
                $style = "table-subject-attestation";
                $attestation = round(($average == 0)?0:$average/$count,1);
                $td .= "<td data-column='column$num'  class='table-subject-marks $style column column$num' data-subject-mark-id='$mark->id'>$attestation</td>";
            }

            if (ctype_digit($mark->mark)) {
                $average += $mark->mark;
                $count++;
            }
            $num++;
        }

        $average = ($average == 0)?0:$average/$count;
        $average = round($average, 1);
        $td .= "<td class='table-subject-average' width='26px'>$average</td>";
        return $td;
    }

    private function convertName($str){


        $m = explode(' ', $str);

        return  $m[0] . ' ' . substr($m[1],0,2) . '.' . substr($m[2],0,2) . '.' ;


    }

    private function selectMark($mark_id,$mark,$num)
    {

        $select = "<select data-column='column$num' data-id-mark='$mark_id' class='table-subject-select column column$num'>";
        $select .= "<option></option>";
        for ($i = 1; $i <= 5; $i++) {
            $selected = ($i == $mark) ? 'selected' : "";
            $select .= "<option $selected>$i</option>";
        }
        $selected = ($mark == 'Н') ? 'selected' : "";
        $select .= "<option $selected>Н</option>";
        $select .= "</select>";
        return $select;
    }

    public function convert_date($date)
    {// Преобразование даты с 0000-00-00 в 00 00
        $date_array = explode("-", $date);
        return @$date_array[1] . "\n" . @$date_array[2];
    }

    public function getAverageMark($student_id,$journal_id){
        $marks =  MarksJournal::where('mark','!=',null)
            ->where('mark','!=','')
            ->where('mark','!=','Н')
            ->where('journal_id',$journal_id)
            ->where('student_id',$student_id)
            ->get();

        if($marks == null){return 0;}
        $average = 0;
        foreach ($marks as $mark){
            if(ctype_digit($mark))
                $average+=$mark->mark;
        }

        if($average == 0) return 0;
        return round($average/count($marks), 1);;
    }
}
