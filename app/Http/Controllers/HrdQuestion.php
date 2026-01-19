<?php

namespace App\Http\Controllers;

use App\Models\Hrd_employee_question;
use App\Models\Hrd_employee_question_point;
use App\Models\Hrd_employee_test;
use App\Models\Hrd_employee_test_category;
use App\Models\Hrd_employee_test_materials;
use App\Models\Hrd_employee_test_result;
use App\Models\Kjk_disc_desc_line;
use App\Models\Kjk_disc_line1;
use App\Models\Kjk_disc_line2;
use App\Models\Kjk_disc_line3;
use App\Models\Kjk_disc_psikogram;
use App\Models\Kjk_mbti_key;
use App\Models\Kjk_mbti_psikogram;
use App\Models\Kjk_mbti_analysis;
use App\Models\Kjk_mbti_analysis_identifier;
use App\Models\Kjk_wpt_age;
use App\Models\Kjk_wpt_result;
use App\Models\Kjk_wpt_score_iq;
use App\Models\Kjk_wpt_interpretasi;
use App\Models\Papikostik_parameter;
use App\Models\Papikostik_psikogram;
use App\Models\User_profile;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class HrdQuestion extends Controller
{
    function index(){
        $categories = Hrd_employee_test_category::where("company_id", Session::get('company_id'))
            ->orderBy('name')
            ->get();
        $tests = Hrd_employee_test::where("company_id", Session::get("company_id"))
            ->get();
        return view("employee.test.index", compact("tests", "categories"));
    }

    function add(Request $request){
        // $question = Hrd_employee_test::find($request->id);
        $question = Hrd_employee_test::findOrNew($request->id);
        // if(empty($question)){
        //     $question = new Hrd_employee_test();
        //     $question->company_id = Session::get('company_id');
        // }
        $question->company_id = Session::get("company_id");
        $question->descriptions = $request->descriptions;
        $question->instructions = $request->instructions;
        $question->label = $request->label;
        $question->question_per_quiz = $request->total;
        // $question->treshold = $request->treshold;
        $question->is_active = $request->is_active ?? 0;
        $question->time_limit = $request->time_limit;
        $question->category_id = $request->category;
        $question->take_limit = $request->take_limit;
        $question->random_order = $request->random_order;
        $question->save();

        return redirect()->back();
    }

    function activate($id){
        $test = Hrd_employee_test::find($id);
        $test->is_active = !$test->is_active;
        $test->save();

        return redirect()->back();
    }

    function question($id){
        $test = Hrd_employee_test::find($id);
        $questions = $test->questions()->orderBy('order_num')->get();
        return view("employee.test.questions", compact("questions", "test"));
    }

    function materials($id){
        $test = Hrd_employee_test::find($id);
        $materials = $test->materials()->orderBy('order_num')->get();
        return view("employee.test.materials", compact("materials", "test"));
    }

    function materials_add(Request $request){
        $num = 1;
        $question = Hrd_employee_test_materials::find($request->id);
        $last = Hrd_employee_test_materials::where("company_id", Session::get("company_id"))
            ->orderBy("order_num", "desc")
            ->first();
        if(empty($question)){
            if(!empty($last)){
                $num = $last->order_num + 1;
            }
            $question = new Hrd_employee_test_materials();
            $question->company_id = Session::get('company_id');
            $question->order_num = $num;
            $question->test_id = $request->test_id;
        }
        $act = $request->act;
        if(empty($act)){
            $question->title = $request->title;
        }
        $question->save();
        $file = $request->file("file");
        if(!empty($file)){
            $dir = str_replace("prototype/public_html", "public_html/kerjaku/assets", public_path("media/exams"));
            $newName = $question->id."_".date("YmdHis")."_".$file->getClientOriginalName();
            $target_dir = str_replace("\\", "/", public_path($dir));
            if($file->move($target_dir, $newName)){
                $question->file = "$dir/$newName";
                $question->save();
            }
        }

        return redirect()->back();
    }

    function detail($id){
        $question = Hrd_employee_question::find($id);
        $points = $question->points()
            ->orderBy("order_num")->get();
        $test = Hrd_employee_test::find($question->test_id);

        return view("employee.test.detail", compact("question", "points", "test"));
    }

    function qview($id){
        $question = Hrd_employee_question::find($id);
        $points = $question->points()
            ->orderBy("order_num")->get();
        $test = Hrd_employee_test::find($question->test_id);

        return view("employee.test.question_view", compact("question", "points", "test"));
    }

    function take_exam($id){
        $test = Hrd_employee_test::find(base64_decode($id));
        $questions = [];

        $start = Hrd_employee_test_result::where('test_id', $test->id)
            ->where("user_id", Auth::id())
            ->whereNull("result_detail")
            ->where('company_id', $test->company_id)
            ->first();
        $res = [];
        $takeCount = Hrd_employee_test_result::where('test_id', $test->id)
            ->where("user_id", Auth::id())
            ->whereNotNull("result_detail")
            ->where('company_id', $test->company_id)
            ->orderby("id", "desc")
            ->get();
        $prev = $takeCount->first();
        $qids = [];
        $old = [];
        $old_step = 0;
        $isTimeLimit = false;

        $cf = \App\Models\Preference_config::where("id_company", $test->company_id)->first();

        return view("employee.test.take_exam", compact("test", "questions", "start", "res", "takeCount", "qids", "prev", "old", "old_step", "isTimeLimit", "cf"));
    }

    function test_guest(){
        $test = Hrd_employee_test::orderBy("order_num")->get();
        return view("employee.test.guest", compact("test"));
    }

    function exam($id){
        $start = Hrd_employee_test_result::findOrFail(base64_decode($id));

        $test = Hrd_employee_test::findOrFail($start->test_id);
        $questions = [];
        $res = [];
        $takeCount = Hrd_employee_test_result::where('test_id', $test->id)
            ->where("user_id", Auth::id())
            ->whereNotNull("result_detail")
            ->where('company_id', $test->company_id)
            ->orderby("id", "desc")
            ->get();
        $prev = $takeCount->first();
        $qids = [];
        $old = [];
        $old_step = 0;
        $isTimeLimit = false;
        if(!empty($start)){
            $res = json_decode($start->result_detail, true);
            $old = json_decode($start->step_progres, true);
            $old_step = $start->current_step;
            $qids = json_decode($start->question_ids, true);
            $questions = Hrd_employee_question::where("test_id", $test->id)
                ->whereIn("id", $qids)
                ->get();
            $d1 = date("Y-m-d H:i:s");
            $d2 = date("Y-m-d H:i:s", strtotime($start->created_at." +$test->time_limit minutes"));
            if($d1 > $d2){
                $isTimeLimit = true;
            }

            if($isTimeLimit){
                $points = json_decode($start->step_progres ?? "[]", true);
                $qid = array_keys($points);
                $questions = $test->questions()->get();

                $result_point = 0;
                $rp = [];
                foreach($questions as $item){
                    if(in_array($item->id, $qid)){
                        $pq = $points[$item->id];
                        $pid = array_keys($pq);
                        $ptrue = $item->points()
                            ->where('is_true', 1)
                            ->count();
                        $psel = $item->points()->whereIn("id", $pid)
                            ->where('is_true', 1)
                            ->count();
                        $tp = $ptrue == count($pid) ? 1 : 0;
                        $rp[] = $tp;
                        $result_point += $tp;
                    }
                }

                $rppctg = ($result_point / $test->question_per_quiz) * 100;

                $start->result_detail = $start->step_progres;
                $start->result_point = $rppctg;
                $start->pass = $rppctg > $test->treshold ? 1 : 0;
                $start->save();
            }
        }

        return view("employee.test.exam", compact("test", "questions", "start", "res", "takeCount", "qids", "prev", "old", "old_step", "isTimeLimit"));
    }

    function exam_review($id){
        $result = Hrd_employee_test_result::find($id);
        $test = Hrd_employee_test::find($result->test_id);
        $qids = json_decode($result->question_ids, true);
        $questions = Hrd_employee_question::where("test_id", $test->id)
                ->whereIn("id", $qids)
                ->get();
        $res = json_decode($result->result_detail, true);
        $qwrong = [];
        foreach($questions as $item){
            if(in_array($item->id, $qids)){
                if(!isset($res[$item->id])){
                    $col['id'] = $item->id;
                    $col['answer'] = [];
                    $qwrong[] = $col;
                } else {
                    $pq = $res[$item->id];
                    $pid = array_keys($pq);
                    $ptrue = $item->points()
                        ->where('is_true', 1)
                        ->count();
                    $psel = $item->points()->whereIn("id", $pid)
                        ->where('is_true', 1)
                        ->count();
                    if($ptrue != count($pid)){
                        $col['id'] = $item->id;
                        $col['answer'] = $pq;
                        $qwrong[] = $col;
                    }
                }
            }
        }

        return view("employee.test.review", compact("test", 'qids', 'questions', 'res', 'qwrong'));
    }

    function exam_result(Request $request){
        $result = Hrd_employee_test_result::find($request->id);
        $test = Hrd_employee_test::find($result->test_id);

        $points = $request->point;
        $qid = array_keys($request->point);
        $questions = $test->questions()->get();

        $result_point = 0;
        $rp = [];
        if($test->category_id == 1){
            foreach($questions as $item){
                if(in_array($item->id, $qid)){
                    $pq = $points[$item->id];
                    $tp = 0;
                    if($item->question_type == 2){
                        $ptrue = $item->points()->where("label", $pq)->get();
                        if(count($ptrue) > 0){
                            $tp = 1;
                        }
                    } else {
                        $pid = $pq;
                        $ptrue = $item->points()
                            ->where("id", $pid)->first();
                        // dd($ptrue);
                        // $psel = $item->points()->whereIn("id", $pid)
                        //     ->where('is_true', 1)
                        //     ->count();
                        $tp = $ptrue->is_true == 1 ? 1 : 0;
                        $rp[] = $tp;
                    }
                    $result_point += $tp;
                }
            }
        } elseif($test->category_id == 3){
            $papi_param = Papikostik_parameter::where("company_id", Session::get("company_id"))->get();
            $qpoint = Hrd_employee_question_point::whereIn("question_id", $questions->pluck("id"))->get();
            $qp = [];
            foreach($qpoint as $item){
                $qp[$item->question_id][$item->id] = $item->order_num;
            }
            $q_no = $questions->pluck("id", "order_num");
            foreach($papi_param as $item){
                $p_key = json_decode($item->p_key, true);
                $papi_point = 0;
                $col = [];
                $_i = 1;
                foreach($p_key as $no => $va){
                    $qid = $q_no[$no];
                    $_p = 0;
                    if(isset($points[$qid])){
                        $_qp = $qp[$qid];
                        $_pq = $points[$qid];
                        if(isset($_qp[$_pq])){
                            $_va = $_qp[$_pq];
                            if($_va == $va){
                                $_p = 1;
                            }
                        }
                    }
                    $col[$_i++] = $_p;
                    $papi_point += $_p;
                }

                $p_desc = json_decode($item->p_desc, true);
                $papi_desc = "";
                foreach($p_desc as $va){
                    $ppoint = $papi_point == 0 ? 1 : $papi_point;
                    if($ppoint >= $va[0] && $ppoint <= $va[1]){
                        $papi_desc = $va[2];
                        break;
                    }
                }

                $papi_cat = 1;

                if($papi_point > 1 && $papi_point <= 3){
                    $papi_cat = 2;
                } elseif($papi_point >= 4 && $papi_point <= 6){
                    $papi_cat = 3;
                } elseif($papi_point >= 7 && $papi_point <= 8){
                    $papi_cat = 4;
                }  elseif($papi_point > 8){
                    $papi_cat = 5;
                }

                $psikogram = new Papikostik_psikogram();
                $psikogram->test_result_id = $result->id;
                $psikogram->user_id = Auth::id();
                $psikogram->p_id = $item->id;
                $psikogram->point = $papi_point;
                $psikogram->category = $papi_cat;
                $psikogram->descriptions = $papi_desc;
                $psikogram->result = json_encode($col);
                $psikogram->company_id = $item->company_id;
                $psikogram->save();
            }
        } elseif($test->category_id == 5){
            $qpoint = Hrd_employee_question_point::whereIn("question_id", $questions->pluck("id"))->get();
            $qp = [];
            foreach($qpoint as $item){
                $qp[$item->question_id][$item->id] = $item->order_num;
            }
            $pLine = [
                "D" => 0,
                "I" => 0,
                "S" => 0,
                "C" => 0,
                "*" => 0,
            ];
            $kLine = [
                "D" => 0,
                "I" => 0,
                "S" => 0,
                "C" => 0,
                "*" => 0,
            ];
            $sLine = [
                "D" => 0,
                "I" => 0,
                "S" => 0,
                "C" => 0,
            ];
            foreach($questions as $item){
                $label = json_decode($item->label, true);
                $discP = $label['P'];
                $discK = $label['K'];
                $_p = $points[$item->id];
                $_qp = $qp[$item->id];
                $_orP = $_qp[$_p["P"]];
                $_orK = $_qp[$_p["K"]];

                $_discP = $discP[$_orP];
                $_discK = $discK[$_orK];
                $pLine[$_discP] += 1;
                $kLine[$_discK] += 1;
            }

            $sLine["D"] = $pLine["D"] - $kLine["D"];
            $sLine["I"] = $pLine["I"] - $kLine["I"];
            $sLine["S"] = $pLine["S"] - $kLine["S"];
            $sLine["C"] = $pLine["C"] - $kLine["C"];

            $disc_line1 = Kjk_disc_line1::where("company_id", Session::get("company_id"))->get();
            $disc_line2 = Kjk_disc_line2::where("company_id", Session::get("company_id"))->get();
            $disc_line3 = Kjk_disc_line3::where("company_id", Session::get("company_id"))->get();
            $line1 = [];
            $line2 = [];
            $line3 = [];
            foreach($disc_line1 as $item){
                $line1[$item->value] = $item;
            }

            foreach($disc_line2 as $item){
                $line2[$item->value] = $item;
            }

            foreach($disc_line3 as $item){
                $line3[$item->value] = $item;
            }

            $psiline1 = [];
            $psiline2 = [];
            $psiline3 = [];

            foreach($pLine as $key => $item){
                if($key != "*"){
                    $_psi = $line1[$item];
                    $psiline1[$key] = $_psi[$key];
                }
            }

            foreach($kLine as $key => $item){
                if($key != "*"){
                    $_psi = $line2[$item];
                    $psiline2[$key] = $_psi[$key];
                }
            }

            foreach($sLine as $key => $item){
                if($key != "*"){
                    $_psi = $line3[$item];
                    $psiline3[$key] = $_psi[$key];
                }
            }

            $psicode1 = [];
            $psicode2 = [];
            $psicode3 = [];

            foreach($psiline1 as $key => $item){
                if($item > 0){
                    $psicode1[] = $key;
                }
            }

            foreach($psiline2 as $key => $item){
                if($item > 0){
                    $psicode2[] = $key;
                }
            }

            foreach($psiline3 as $key => $item){
                if($item > 0){
                    $psicode3[] = $key;
                }
            }

            // $_line1 = Kjk_disc_desc_line::where("")

            $_arr = [
                "1" => $pLine,
                "2" => $kLine,
                "3" => $sLine,
            ];

            $psikogram = new Kjk_disc_psikogram();
            $psikogram->test_result_id = $result->id;
            $psikogram->user_id = Auth::id();
            $psikogram->psikogram = json_encode($_arr);
            $psikogram->line1 = json_encode($psiline1);
            $psikogram->line2 = json_encode($psiline2);
            $psikogram->line3 = json_encode($psiline3);
            $psikogram->company_id = $test->company_id;
            $psikogram->save();
        } elseif($test->category_id == 2){
            $qpoint = Hrd_employee_question_point::whereIn("question_id", $questions->pluck("id"))->get();
            $qp = [];
            foreach($qpoint as $item){
                $qp[$item->question_id][$item->id] = $item->is_true;
            }
            $mbti_key = Kjk_mbti_key::where("company_id", Session::get('company_id'))->get();

            $mbti_dimension = [
                [1, 5, 10],
                [2, 6, 20],
                [3, 7, 20],
                [4, 8, 20],
            ];

            $mkey = [];
            foreach($mbti_key as $item){
                $col = [];
                $col['label'] = $item->label;
                $col['identifier'] = $item->identifier;
                $col['value'] = 0;
                $col['%'] = 0;
                $mkey[$item->code] = $col;
            }
            foreach($questions as $item){
                $pid = $qp[$item->id];
                $_sel = $points[$item->id] ?? [];
                if(!empty($_sel)){
                    $_code = $pid[$_sel];
                    if(isset($mkey[$_code])){
                        $mkey[$_code]['value'] += 1;
                    }
                }
            }

            $mbti_res = [];
            $sumIdentifier = 0;

            foreach($mbti_dimension as $item){
                $el1 = $mkey[$item[0]];
                $el2 = $mkey[$item[1]];
                $sum = $item[2];
                $pctg1 = ($el1['value'] / $sum) * 100;
                $el1['%'] = $pctg1;
                $pctg2 = ($el2['value'] / $sum) * 100;
                $el2['%'] = $pctg2;
                if($pctg1 > $pctg2){
                    $identifier = $el1['identifier'];
                } else {
                    $identifier = $el2['identifier'];
                }
                $col = [];
                $col['left'] = $el1;
                $col['right'] = $el2;
                $col['identifier'] = $identifier;
                $sumIdentifier += $identifier;
                $mbti_res[] = $col;
            }

            $mbti_result = new Kjk_mbti_psikogram();
            $mbti_result->test_result_id = $result->id;
            $mbti_result->user_id = Auth::id();
            $mbti_result->identifier = $sumIdentifier;
            $mbti_result->psikogram_result = json_encode($mbti_res);
            $mbti_result->save();
        } elseif($test->category_id == 4){
            $score = [];
            foreach($questions as $item){
                $tp = 0;
                if(in_array($item->id, $qid)){
                    $pq = $points[$item->id];
                    if($item->question_type == 2){
                        $_pq = explode("\r\n", $pq);
                        $_tp = 0;
                        foreach($_pq as $pqval){
                            $ptrue = $item->points()->where("label", $pqval)->get();
                            if(count($ptrue) > 0){
                                $_tp += 1;
                            }
                        }

                        if($item->point != 0){
                            if($_tp == $item->point){
                                $tp = 1;
                            }
                        } else {
                            if($_tp >=1){
                                $tp = 1;
                            }
                        }
                    } else {
                        $pid = array_keys($pq);
                        $ptrue = $item->points()
                            ->where('is_true', 1)
                            ->count();
                        $psel = $item->points()->whereIn("id", $pid)
                            ->where('is_true', 1)
                            ->count();
                        $tp = $ptrue == $psel ? 1 : 0;
                        $rp[] = $tp;
                    }
                    $result_point += $tp;
                }
                $score[$item->id] = $tp;
            }
            $profile = User_profile::where("user_id", Auth::id())->first();
            $umur = "-";
            if(!empty($profile)){
                $d1 = date_create($profile->birth_date);
                $d2 = date_create(date("Y-m-d"));
                $diff = date_diff($d1, $d2);
                $umur = $diff->format("%y");
            }

            $age_point = Kjk_wpt_age::where("min_age", ">=", $umur)
                ->where("max_age", "<=", $umur)
                ->first();
            $apoint = $age_point->point ?? 0;
            $test_score = $result_point + $apoint;
            $wpt_result = new Kjk_wpt_result();
            $wpt_result->test_result_id = $result->id;
            $wpt_result->user_id = Auth::id();
            $wpt_result->true = $result_point;
            $wpt_result->wrong = $test->question_per_quiz - $result_point;
            $wpt_result->age_point = $apoint;
            $wpt_result->score = $test_score;
            $wpt_result->company_id = $test->company_id;
            $wpt_result->test_result = json_encode($score);
            $wpt_result->save();
        } elseif($test->category_id == 6){
            // dd($request->all());
            $answered = $request->point;
            $totalQ = $request->pid;

            $answeredPctg = (count($answered) / count($totalQ)) * 100;
            $result->att_detail_result = $answeredPctg;
            foreach($questions as $item){
                if(in_array($item->id, $qid)){
                    $pq = $points[$item->id];
                    $tp = 0;
                    if($item->question_type == 2){
                        $ptrue = $item->points()->where("label", $pq)->get();
                        if(count($ptrue) > 0){
                            $tp = 1;
                        }
                    } else {
                        $pid = $pq;
                        $ptrue = $item->points()
                            ->where("id", $pid)->first();
                        // dd($ptrue);`
                        // $psel = $item->points()->whereIn("id", $pid)
                        //     ->where('is_true', 1)
                        //     ->count();
                        $tp = $ptrue->is_true == 1 ? 1 : 0;
                        $rp[] = $tp;
                    }
                    $result_point += $tp;
                }
            }
        } elseif($test->category_id == 7){
            $rEye = 0;
            $gEye = 0;
            foreach($questions as $item){
                if(in_array($item->id, $qid)){
                    $pq = $points[$item->id];
                    $tp = 0;
                    $pid = $pq;
                    $ptrue = $item->points()
                        ->where("id", $pid)->first();
                    // dd($ptrue);`
                    // $psel = $item->points()->whereIn("id", $pid)
                    //     ->where('is_true', 1)
                    //     ->count();
                    $tp = $ptrue->is_true == 1 ? 1 : 0;
                    $result_point += $tp;
                    if($ptrue->is_true == 2){
                        $rEye += 1;
                    } elseif($ptrue->is_true == 3){
                        $gEye += 1;
                    }
                }
            }

            $dff = $questions->count() - $result_point;
            if($result_point > 20){
                $cb = "Normal";
            } elseif($result_point >= 5 && $result_point <= 20){
                if($rEye == 3){
                    $cb = "Protanopia";
                } elseif($gEye == 3){
                    $cb = "Deutranopia";
                } else {
                    $cb = "Partial";
                }
            } else {
                $cb = "Total Color Blind";
            }

            $cb_res = [
                "normal" => $result_point,
                "red" => $rEye,
                "green" => $gEye,
                "false" => $dff,
                "text" => $cb
            ];

            $result->color_blind_result = json_encode($cb_res);
        }

        $rppctg = ($result_point / $test->question_per_quiz) * 100;

        $result->result_detail = json_encode($points);
        $result->result_point = $rppctg > 100 ? 100 : $rppctg;
        $result->pass = $rppctg > $test->treshold ? 1 : 0;
        $result->result_end = date("Y-m-d H:i:s");
        $result->save();

        return redirect()->route("test.result.page", $result->id);
    }

    function result_page($id){
        $last = Hrd_employee_test_result::find($id);
        $test = Hrd_employee_test::find($last->test_id);
        $wpt = Kjk_wpt_result::where("test_result_id", $last->id)->first();

        $wpt_iq = \App\Models\Kjk_wpt_score_iq::pluck("iq", "score");
        $wpt_interpretasi = \App\Models\Kjk_wpt_interpretasi::pluck("label", "score");

        return view("employee.test.result", compact("id", "test", "last", "wpt", "wpt_iq", "wpt_interpretasi"));
    }

    function exam_start(Request $request){
        $test = Hrd_employee_test::find($request->id);
        $start = Hrd_employee_test_result::where("test_id", $request->id)
            ->where("user_id", Auth::id())
            ->where('company_id', $test->company_id)
            ->first();

        if($test->random_order){
            $qs = Hrd_employee_question::where("test_id", $test->id)
                ->inRandomOrder()
                ->take($test->question_per_quiz)
                ->get();
        } else {
            $qs = Hrd_employee_question::where("test_id", $test->id)
                ->orderBy("order_num")
                ->take($test->question_per_quiz)
                ->get();
        }

        $start = new Hrd_employee_test_result();
        $start->question_ids = json_encode($qs->pluck("id")->toArray());
        $start->test_id = $request->id;
        $start->user_id = Auth::id();
        $start->company_id = $test->company_id;
        $start->save();

        return redirect()->route("hrd.test.exam", base64_encode($start->id));
    }

    function exam_step($id, $step, Request $request){
        $test = Hrd_employee_test_result::find($id);
        $_test = Hrd_employee_test::find($test->test_id);
        $points = $request->point ?? [];
        $stp = [];
        if($_test->category_id == 5){
            foreach($points as $pid => $p){
                foreach($p as $pk => $val){
                    $stp[$pid][$pk] = $val;
                }
            }
        } else {
            $pid = $request->pid ?? [];
            foreach($pid as $p){
                if(isset($points[$p])){
                    $stp[$p] = $points[$p];
                }
            }
        }

        $test->step_progres = json_encode($stp);
        $test->current_step = $step;
        $test->save();

        return json_encode($step);
    }

    function category_add(Request $request){
        $category = new Hrd_employee_test_category();
        $category->name = $request->name;
        $category->company_id = Session::get("company_id");
        $category->save();

        return redirect()->back();
    }

    function category_update($id, Request $request){
        $category = Hrd_employee_test_category::find($id);
        $category->name = $request->name;
        $category->save();

        return 1;
    }

    function category_get(Request $request){
        $category = Hrd_employee_test_category::where("company_id", Session::get("company_id"))
            ->orderBy("name")
            ->get();
        $data = [];
        foreach($category as $i =>$item){
            $col['i'] = $i+1;
            $col['name'] = "<input class='form-control' value='$item->name' name='name'>";
            $col['action'] = "<button type='button' onclick='edit_category(this,$item->id)' class='btn btn-primary btn-icon btn-xs'><i class='fa fa-edit'></i></button>";
            $col['action'] .= "<a href='".route("hrd.test.category.delete", $item->id)."' class='btn btn-danger btn-icon btn-xs'><i class='fa fa-trash'></i></a>";
            $data[] = $col;
        }
        $res = [
            "data" => $data
        ];
        return json_encode($res);
    }

    function category_delete($id){
        $category = Hrd_employee_test_category::find($id)->delete();

        return redirect()->back();
    }

    function delete($type, $id){
        if($type == "point"){
            $t = Hrd_employee_question_point::class;
        } elseif($type == "question") {
            $t = Hrd_employee_question::class;
        } elseif($type == "test"){
            $t = Hrd_employee_test::class;
        } else {
            $t = Hrd_employee_test_materials::class;
        }

        $ob = $t::find($id);
        $ob->delete();

        return redirect()->back();
    }

    function order_change($type, $order, $id){
        if($type == "point"){
            $t = Hrd_employee_question_point::class;
        } elseif($type == "question") {
            $t = Hrd_employee_question::class;
        } else {
            $t = Hrd_employee_test_materials::class;
        }

        $ob = $t::find($id);
        $op = ">";
        $or = "asc";
        if($order == "up"){
            $op = "<";
            $or = "desc";
        }
        $tg = $t::where("order_num", $op, $ob->order_num)
            ->where(function($q) use($type, $ob){
                if($type == "point"){
                    $q->where("question_id", $ob->question_id);
                }
            })->orderBy("order_num", "$or")->first();
        $n1 = $ob->order_num;
        $n2 = $tg->order_num;
        $ob->order_num = $n2;
        $tg->order_num = $n1;
        $ob->save();
        $tg->save();
        return redirect()->back();
    }

    function question_add(Request $request){
        $num = 1;
        $question = Hrd_employee_question::find($request->id);
        $last = Hrd_employee_question::where("company_id", Session::get("company_id"))
            ->where("test_id", $request->test_id)
            ->orderBy("order_num", "desc")
            ->first();
        if(empty($question)){
            if(!empty($last)){
                $num = $last->order_num + 1;
            }
            $question = new Hrd_employee_question();
            $question->company_id = Session::get('company_id');
            $question->order_num = $num;
            $question->test_id = $request->test_id;
        }
        $act = $request->act;
        if(empty($act)){
            $question->label = $request->question;
            $question->label2 = $request->label2 ?? null;
            $question->point = $request->point;
            $question->question_type = $request->q_type ?? 1;
        }
        $file = $request->file("image");
        if(!empty($file)){
            $_dir = str_replace("\\", "/", public_path("media/exams"));
            $dir = str_replace("prototype/public_html", "public_html/assets", $_dir);
            $newName = $question->id."_".date("YmdHis")."_".$file->getClientOriginalName();
            if($file->move($dir, $newName)){
                $question->img = "media/exams/$newName";
            }
        }
        $question->save();

        return redirect()->back();
    }

    function point_add(Request $request){
        $num = 1;
        $question = Hrd_employee_question_point::find($request->id);
        $last = Hrd_employee_question_point::where("question_id", $request->qid)
            ->where("company_id", Session::get("company_id"))
            ->orderBy("order_num", "desc")
            ->first();

        if(empty($question)){
            if(!empty($last)){
                $num = $last->order_num + 1;
            }
            $question = new Hrd_employee_question_point();
            $question->company_id = Session::get('company_id');
            $question->order_num = $num;
            $question->question_id = $request->qid;
            $question->className = $request->className ?? null;
        }
        $act = $request->act;
        if(empty($act)){
            $question->label = $request->question;
            $question->is_true = $request->is_true ?? 0;
        }
        $question->save();
        $file = $request->file("image");
        if(!empty($file)){
            $dir = str_replace("prototype/public_html", "public_html/kerjaku/assets", public_path("media/exams"));
            $newName = $question->id."_".date("YmdHis")."_".$file->getClientOriginalName();
            $target_dir = str_replace("\\", "/", public_path($dir));
            if($file->move($target_dir, $newName)){
                $question->img = "$dir/$newName";
                $question->save();
            }
        }

        return redirect()->back();
    }

    function generate_papikostick(){
        $soal = 90;
        for ($i=1; $i <= $soal; $i++) {
            $s = new Hrd_employee_question();
            $s->test_id = 12;
            $s->label = "Pilih salah satu yang paling menggambarkan diri Anda";
            $s->order_num = $i;
            $s->company_id = 1;
            $s->save();
        }
    }

    function papikostik_psikogram($id, Request $request){
        $last_test = Hrd_employee_test_result::find($id);
        $test = Hrd_employee_test::find($last_test->test_id);

        $profile = User_profile::where("user_id", $last_test->user_id)->first();
        $param = Papikostik_parameter::where("company_id", $test->company_id)->get();
        $result = Papikostik_psikogram::whereIn("p_id", $param->pluck('id'))
            ->where("test_result_id", $last_test->id)
            // ->where("user_id", $request->uid ?? Auth::id())
            ->get();
        $psikogram = [];
        foreach($result as $item){
            $psikogram[$item->p_id] = $item;
        }
        $cat = [1=>"R", "K", "C", "B", "T"];
        $back = $request->server('HTTP_REFERER');
        return view("employee.test.papikostik_psikogram", compact("back", "param", "psikogram", "cat", "last_test"));
    }

    function disc_psikogram($id, Request $request){
        $back = $request->server('HTTP_REFERER');
        $last_test = Hrd_employee_test_result::find($id);
        $test = Hrd_employee_test::find($last_test->test_id);

        $user = User::find($last_test->user_id);

        $profile = User_profile::where("user_id", $last_test->user_id)->first();
        $umur = "-";
        if(!empty($profile)){
            $d1 = date_create($profile->birth_date);
            $d2 = date_create(date("Y-m-d"));
            $diff = date_diff($d1, $d2);
            $umur = $diff->format("%y");
        }

        $psikogram_data = Kjk_disc_psikogram::where("test_result_id", $last_test->id)
            ->first();

        $psikogram = json_decode($psikogram_data->psikogram, true);

        $line1 = json_decode($psikogram_data->line1, true);
        $line2 = json_decode($psikogram_data->line2, true);
        $line3 = json_decode($psikogram_data->line3, true);
        arsort($line1);
        arsort($line2);
        arsort($line3);

        $code_line1 = [];
        $code_line2 = [];
        $code_line3 = [];
        foreach($line1 as $key => $item){
            if($item > 0 && count($code_line1) < 3){
                $code_line1[] = $key;
            }
        }

        foreach($line2 as $key => $item){
            if($item > 0 && count($code_line2) < 3){
                $code_line2[] = $key;
            }
        }

        foreach($line3 as $key => $item){
            if($item > 0 && count($code_line3) < 3){
                $code_line3[] = $key;
            }
        }

        $desc_line[1] = Kjk_disc_desc_line::line1()->where("code", implode("-", $code_line1))->first();
        $desc_line[2] = Kjk_disc_desc_line::line1()->where("code", implode("-", $code_line2))->first();
        $desc_line[3] = Kjk_disc_desc_line::line1()->where("code", implode("-", $code_line3))->first();

        $desc_kepribadian = Kjk_disc_desc_line::line2()->where("code", implode("-", $code_line3))->first();
        $desc_job = Kjk_disc_desc_line::line3()->where("code", implode("-", $code_line3))->first();

        $disc = ["D", "I", "S", "C", "*"];

        if($request->a){
            if($request->a == "chart"){
                $response = $psikogram[$request->l];
                if(isset($response["*"])){
                    unset($response["*"]);
                }
                return json_encode($response);
            }
        }

        return view("employee.test.disc_psikogram", compact("user","test", "back", "last_test", "umur", "profile", "psikogram", "psikogram_data", "disc", "desc_line", "desc_kepribadian", "desc_job"));
    }

    function mbti_psikogram($id, Request $request){
        $last_test = Hrd_employee_test_result::find($id);
        $test = Hrd_employee_test::find($last_test->test_id);

        $user = User::find($last_test->user_id);

        $profile = User_profile::where("user_id", $last_test->user_id)->first();
        $umur = "-";
        if(!empty($profile)){
            $d1 = date_create($profile->birth_date);
            $d2 = date_create(date("Y-m-d"));
            $diff = date_diff($d1, $d2);
            $umur = $diff->format("%y");
        }

        $mbti_result = Kjk_mbti_psikogram::where("test_result_id", $last_test->id)
            ->first();

        $identifier = Kjk_mbti_analysis_identifier::where("code", $mbti_result->identifier)->first();

        $_desc = ["A. DESKRIPSI KEPRIBADIAN", "B. SARAN DAN PENGEMBANGAN", "C. SARAN PROFESI"];

        $analysis[0] = Kjk_mbti_analysis::where("identifier", $identifier->identifier)
            ->where("descriptions", "!=", "")
            ->where("code", "like", "d%")
            ->orderBy("code")
            ->get();
        $analysis[1] = Kjk_mbti_analysis::where("identifier", $identifier->identifier)
            ->where("descriptions", "!=", "")
            ->where("code", "like", "s%")
            ->orderBy("code")
            ->get();
        $analysis[2] = Kjk_mbti_analysis::where("identifier", $identifier->identifier)
            ->where("descriptions", "!=", "")
            ->where("code", "like", "p%")
            ->orderBy("code")
            ->get();

        $back = $request->server('HTTP_REFERER');

        $tag = Kjk_mbti_key::pluck("tag", "identifier");

        return view("employee.test.mbti_psikogram", compact("back", "test", "last_test",'user', "profile", "umur", 'analysis', '_desc', "mbti_result", "tag"));
    }

    function wpt_psikogram($id, Request $request){
        $last_test = Hrd_employee_test_result::findOrFail($id);
        $test = Hrd_employee_test::findOrFail($last_test->test_id);

        $user = User::find($last_test->user_id);

        $profile = User_profile::where("user_id", $last_test->user_id)->first();
        $umur = "-";
        if(!empty($profile)){
            $d1 = date_create($profile->birth_date);
            $d2 = date_create(date("Y-m-d"));
            $diff = date_diff($d1, $d2);
            $umur = $diff->format("%y");
        }

        $jawaban = json_decode($last_test->result_detail, true);
        $point = Hrd_employee_question_point::whereIn("question_id", $test->questions->pluck("id"))->pluck("order_num", "id");
        $wpt = Kjk_wpt_result::where("test_result_id", $last_test->id)
            ->firstOrFail();
        $skor = json_decode($wpt->test_result, true);
        $iq = Kjk_wpt_score_iq::where("score", $wpt->score)->first();
        $interpretasi = Kjk_wpt_interpretasi::where("score", ">=", $wpt->score)
            ->orderBy("score")
            ->first();

        $back = $request->server('HTTP_REFERER');

        return view("employee.test.wpt_psikogram", compact("back","test", "user", "last_test", "profile", "umur", "jawaban", "point", "skor", "wpt", 'iq', "interpretasi"));
    }
}
