<?php

namespace App\Http\Controllers\Journal;

use App\Models\Journal\DateJournal;
use App\Models\Journal\MarksJournal;
use App\Repositories\SheetSubject;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class JournalSheetController extends Controller
{
    public function showSubjectTable(Request $request, SheetSubject $sheetSubject)
    {
        $subject_id = $request->subject_id;

        $sheetSubject->getSubjectTable($subject_id);

        return $sheetSubject->getSubjectTable($subject_id);

    }

    public function updateMark(Request $request, SheetSubject $sheetSubject)
    {
        $mark_id = $request->mark_id;
        $mark = $request->mark;
        $student_id = $request->student_id;


        return $sheetSubject->updateMark($mark_id, $mark, $student_id);
    }

    public function updateDate(Request $request, SheetSubject $sheetSubject)
    {
        $date_id = $request->date_id;
        $date = $request->date;
        $description = $request->description;
        $attestation = $request->attestation;

        return $sheetSubject->updateDate($date_id, $date, $description, $attestation);
    }

    public function getDate(Request $request)
    {
        $date_id = $request->date_id;

        $date = DateJournal::where('id', $date_id)->select('date', 'description', 'attestation')->first()->toArray();

        return $date;
    }


}
