<?php

namespace App\Http\Controllers;

use App\Models\Hrd_employee_question;
use App\Models\Hrd_employee_question_point;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class KjkRandomQuestion extends Controller
{
    function index(){
        $questions = Hrd_employee_question::where("company_id", Session::get("company_id"))
            ->where("test_id", -1)
            ->get();
        return view("employee.random.index", compact("questions"));
    }

    function detail($id){
        $question = Hrd_employee_question::findOrFail($id);
        $points = $question->points()
            ->orderBy("order_num")->get();

        return view("employee.random.detail", compact("question", "points"));
    }

    function add_question(Request $request){
        $question = Hrd_employee_question::findOrNew($request->id);
        if(empty($request->id)){
            $question = new Hrd_employee_question();
            $question->company_id = Session::get('company_id');
            $question->test_id = -1;
            $question->question_type = 1;
        }
        $question->label = $request->question;
        $question->save();

        return redirect()->back();
    }

    function add_answer(Request $request){
        $question = Hrd_employee_question_point::findOrNew($request->id);

        $istrue = $request->is_true ?? 0;

        if($istrue != 0){
            Hrd_employee_question_point::where("question_id", $request->qid)
                ->update([
                    "is_true" => 0
                ]);
        }

        if(empty($request->id)){
            $question->company_id = Session::get('company_id');
            $question->question_id = $request->qid;
        }
        $question->label = $request->question;
        $question->is_true = $request->is_true ?? 0;
        $question->save();

        return redirect()->back();
    }
}
