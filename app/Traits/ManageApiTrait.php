<?php

namespace App\Traits ;

trait ManageApiTrait
{
    public function createApi($data , $msg = 'Data is created Sucessfully')
    {
        return response()->json(['msg' =>$msg , 'data' => $data] , 201);
    }

    public function successApi($data = null , $msg = '')
    {
        return response()->json([ 'msg' => $msg , 'data' => $data] , 200);
    }

    public function faildApi($msg = '' , $status )
    {
        return response()->json(['msg' => $msg ] , $status);
    }

}

