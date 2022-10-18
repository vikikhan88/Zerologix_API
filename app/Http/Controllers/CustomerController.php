<?php

namespace App\Http\Controllers;

use App\Models\Customer;
use App\Models\Templates;
use App\Http\Requests\StoreCustomerRequest;
use App\Http\Requests\UpdateCustomerRequest;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Mail\ZerologixEmail;
use App\Jobs\Emails;
use Mail;

class CustomerController extends Controller
{

    public function sendingEmails(StoreCustomerRequest $request){
        $response = $emailData= array();



        if($request){

            $details = [];
            $details['fullname'] = $request->fullname;
            $details['email'] = $request->email;
            $details['body'] = $request->body;

            //$details['email'] = view('templates.email', $emailData)->render();
            $temp_no = $request->template_no;


             $details['template'] = Templates::find($temp_no)->get()->toarray();
            dispatch(new \App\Jobs\Emails($details));
            //return response()->json(['message'=>'Mail Send Successfully!!']);

            $response =array(
                "status" => 200,
                "messages" => "Successfull! Thank you for using the service, you email is in the queues will be send shortly.",
                );
        }else{
            $response =array(
                "status" => 503,
                "msg" => "Request type not valid.",
                );
        }

        return response()->json($response);
    }
}
