<?php

namespace App\Models\Journal;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

class Specialty extends Model
{
   static public function addSpecialty($name){
       $name = trim($name);
       $check = DB::table('specialties')->where('specialty_name',$name)->get();
       if(count($check)>0){
           return false;
       }
       DB::table('specialties')->insert(
           array('specialty_name'=>$name)
       );
       return true;
   }

   static public function updateSpecialty($id,$name){
       Specialty::where('id',$id)->update(
           array('specialty_name'=>$name,'updated_at'=>now())
       );

       return true;
   }


}
