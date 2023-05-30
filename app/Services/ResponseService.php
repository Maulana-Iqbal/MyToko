<?php

namespace App\Services;

class ResponseService{
    private $success=true;
    private $message='Success';
    private $data='No Data';


    public function response($success=null,$message=null,$data=null){
        if($success==false){
            $this->success=$success;
        }

        if($message){
            $this->message=$message;
        }

        if($data){
            $this->data=$data;
        }
        return response()->json(['success'=>$this->success,'message'=>$this->message,'data'=>$this->data],200);
    }
}
