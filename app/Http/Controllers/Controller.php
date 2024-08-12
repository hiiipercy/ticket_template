<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    public function setPageTitle($title,$siteTitle){
        return view()->share(['title'=>$title,'siteTitle'=>$siteTitle]);
    }

    protected function response_json($status='success',$message=null,$data=null,$response_code=200)
    {
        return response()->json([
            'status'        => $status,
            'message'       => $message,
            'data'          => $data,
            'response_code' => $response_code
        ]);
    }
}
