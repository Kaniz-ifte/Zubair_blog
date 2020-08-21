<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

use Auth;
use Mail;
use Input;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Session;
use DB;
class CategoriesController extends Controller
{


   function Categories($quiz_id){

         // $this->quizinfo=Quiz::where('id',$quiz_id)->first();
         // $this->questions=Question::where('quiz_id',$quiz_id)->where('deleted_at',null)->get();
        $quizzes=Quiz::where('d',$quiz_id)->where('deleted_at',null)->first();
        return view('single-quiz')->with('quizzes',$quizzes);
   }

   function StartQuiz($quiz_id){


         $this->quizinfo=Quiz::where('id',$quiz_id)->first();
         $this->questions=Question::inRandomOrder()->where('quiz_id',$quiz_id)->where('deleted_at',null)->get();



        return view('start-quiz')->with('data',$this);

   }

   function QuizSubmit(Request $req){

     // return dd($req->all());

     $right_ans=0;
     $wrong_ans=0;
     $my_marks=0;
     $questions=array();
     $total_ques=count($req->question_id);

     foreach ($req->question_id as $question_id) {
       $right_answer='';
       $title='';


       $question_info=QuestionInfo($question_id);

       if(isset($question_info->right_answer)){
         $right_answer=$question_info->right_answer;
         $title=$question_info->title;

       if($req->input('option_'.$question_id)!=''){



         if($question_info->right_answer==$req->input('option_'.$question_id)){
           $right_ans++ ;

             if(isset($question_info->marks) && $question_info->marks>=0){
               $my_marks=$my_marks+$question_info->marks;
             }

         }else{
           $wrong_ans++ ;
         }
       }

       }

       $question = array(
         'question_id'=>$question_id,
         'my_option' => $req->input('option_'.$question_id),
         'right_answer' =>$right_answer,
         'title' =>$title
        );

       array_push($questions,$question);

     }

     // $result=new Result();
     $this->right_answer=$right_ans;
     $this->wrong_answer=$wrong_ans;
     $this->my_marks=$my_marks;
     $this->quiz_id=$req->quiz_id;
     $this->passing_mark=$req->passing_mark;
     $this->questions=$questions;
     //
     // if (Auth::User()) {
     //   $this->user_id=Auth::User()->id;
     //   $this->times=MyTimes($req->quiz_id,Auth::User()->id);
     // }
     $this->not_answer=$total_ques-($wrong_ans+$right_ans);
     // $result->save();

     Session::put('quiz_data',$this);

        return view('quiz-result')->with('data',$this);

   }

   function QuizResult(){
          return view('quiz-result');
   }


   function ResultLogin(Request $req){


    // $validatedData = $request->validate([
    //   'email' => 'required|email', // make sure the email is an actual email
    //   'password' => 'required|alphaNum|min:8'
    // ]);


      // create our user data for the authentication
      $userdata = array(
        'email' => $req->get('email') ,
        'password' => $req->get('password')
      );
      // attempt to do the login
      if (Auth::attempt($userdata))
        {
      return redirect()->back()->with('data',Session::get('quiz_data'));
        // do whatever you want on success
        }
        else
        {
        // validation not successful, send back to form
        return redirect()->back()->with('data',Session::get('quiz_data'));
        }
   }

   function ResultRegister(Request $req){
     // create our user data for the authentication

     if ($req->get('password')==$req->get('password_confirmation')) {


     $count=User::where('email',$req->get('email'))->count();

     if ($count<=0) {

          $data=User::create([
              'name' => $req->get('name'),
              'email' => $req->get('email'),
              'password' => Hash::make($req->get('password')),
          ]);




          $userdata = array(
            'email' => $req->get('email') ,
            'password' => $req->get('password')
          );
          // attempt to do the login
          if (Auth::attempt($userdata))
            {

                 Mail::send('email.TestMail', $data, function($message) use ($data){
                 $message->from('test@smartifier.org', 'Smartifier');
                      $message->subject("Welcome to Smartifier");
                      $message->to($data->email);
                 });

          return redirect()->back()->with('data',Session::get('quiz_data'));
            // do whatever you want on success
            }
            else
            {
            // validation not successful, send back to form
            return redirect()->back()->with('data',Session::get('quiz_data'));
            }

          }else{
             return redirect()->back()->with('data',Session::get('quiz_data'));
          }

     }else{
        return redirect()->back()->with('data',Session::get('quiz_data'));
     }

     }
}
