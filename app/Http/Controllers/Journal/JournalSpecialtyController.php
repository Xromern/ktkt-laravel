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

class JournalSpecialtyController extends Controller
{
    static public function showSpecialty(Request $request)
    {
        $specialty = Specialty::all();
        return self::buildSpecialty($specialty);
    }

    static public function addSpecialty(Request $request)
    {
        $name_specialty = trim($request->name);
        if (!Specialty::addSpecialty($name_specialty)) {
            return "Така спеціальність вже існує.";
        }
        return "true";
    }

    static public function deleteSpecialty(Request $request)
    {
        $id = $request->id;
        try {
            Specialty::where('id', $id)->delete();
        } catch (QueryException $e) {
            return "Спочатку выдаліть всі групи з цієї спеціальності.";
        }
        return "true";
    }

    static public function updateSpecialty(Request $request)
    {
        $id = $request->id;
        $name = trim($request->name);

        if (Specialty::updateSpecialty($id, $name) != true) {
            return "Сталася помилка при зміненні вчителя ";
        }
        return "true";
    }

    static public function showSpecialtyList(Request $request)
    {

        $specialty = Specialty::all();
        // dd($request->specialty_id);
        return self::buildSpecialtyList($specialty,$request->specialty_id);
    }

    static public function buildSpecialtyList($specialty,$selected=0)
    {
        $block="";
        foreach ($specialty as $item) {;
            if($selected == $item->id) {
                $block .= "<option selected value='" . $item->id . "'>" .$item->specialty_name . "</option>";
            }else{
                $block .= "<option value='" . $item->id . "'>" . $item->specialty_name  . "</option>";
            }

        }
        return $block;
    }

    static public function buildSpecialty($specialty)
    {
        $block = "";
        foreach ($specialty as $item) {
            $block .= "<tr>
                <td> <input value='" . $item->specialty_name . "' type='text' class='input-name-specialty' placeholder='ПП' required='required'/></td>
                <td data-id-specialty='" . $item->id . "'> <div class='button button-update-specialty'>✔</div><div class='button button-delete-specialty'>✘</div><td/>
                <tr><td colspan='2'><br><hr style='border:1px solid #999'><br></td></tr>
            </tr>";
        }
        return $block;
    }
}
