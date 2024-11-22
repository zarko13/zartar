<?php

namespace App\Http\Controllers;

use App\Http\Controllers\BaseController;
use App\Http\Requests\ExecuteCommandRequest;
use App\Repositories\Command\CommandService;

class PublicController extends BaseController
{
    public function index(){
        return view('index');
    }

    public function asyncExecuteCommand(ExecuteCommandRequest $request){
        $input = $request->input();
        $command = $input['command'];

        $response = CommandService::executeCommand($command);

        return $response->toAsyncResponse();

    }

}
