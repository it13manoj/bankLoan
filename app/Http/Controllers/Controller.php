<?php

namespace App\Http\Controllers;

use Illuminate\Foundation\Auth\Access\AuthorizesRequests;
use Illuminate\Foundation\Bus\DispatchesJobs;
use Illuminate\Foundation\Validation\ValidatesRequests;
use Illuminate\Routing\Controller as BaseController;

class Controller extends BaseController
{
    use AuthorizesRequests, DispatchesJobs, ValidatesRequests;

    private function setResponse(array $response) {
        header('Content-Type: application/json');
        echo json_encode($response);
        exit;
}

public function success($msg = "Success", $data = array()) {
return $this->setResponse(array('code' => "200", 'success' => true, 'messages' => $msg, 'data' => $data ) );
}

public function error($messages = "Error", $data = null, $code = 400) {
return $this->setResponse(array('code' => $code, 'success' => false, 'messages' => $messages, 'data'=> $data));
}


public function validation($request='', $rules = [], $messages = [])
{
$validator = Validator::make($request, $rules, $messages);

if ($validator->fails()) {
    $this->error(@$validator->errors()->all()[0]);
}
}
}
