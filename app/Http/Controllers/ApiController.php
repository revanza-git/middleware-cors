<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use GuzzleHttp\Client;

class ApiController extends Controller{

    public function forward(Request $param){
        \Log::debug($param);
        //Param:
        //method
        //url
        //params
        //headers
        try{
            if(isset($param['method']) || isset($param['url']) || isset($param['params'])|| isset($param['headers'])){

                $arr = $param['params'];

                if($arr!==null){
                    foreach($arr as $key => $field){
                        if($field === null){
                            $arr[$key]="";
                        }
                    }
                }          

                $client = new Client();
                $response = $client->request($param['method'],$param['url'],[
                    'json' => $arr,
                    'headers'=>$param['headers'],
                    'verify'=>false
                ]);
                
                $statusCode = $response->getStatusCode();
                $body = $response->getBody()->getContents();
                \Log::debug($body);
        
                return response($body)->header('Content-Type','application/json');
            }else{
                $stat['status'] = 'ERROR';
                $stat['desc'] = 'method,url,params,and headers parameter Must exists and Cannot be NULL';
                return response(json_encode($stat),400)->header('Content-Type','application/json');
            }          
        }catch(\Exception $e){
            \Log::debug($e);
            return response($e->getResponse()->getBody(true),400)->header('Content-Type','application/json');
        }     
    }
}
