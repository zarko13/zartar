<?php

namespace App\Repositories\Command;

use App\Repositories\ServiceResponse;
use Exception;
use Illuminate\Support\Facades\Log;

class CommandService
{
    public static function executeCommand($command){

        $errors = null;
        $data = [];
        try {


            $data['message'] = 'Dave I understand your concern';

        } catch (Exception $error){
            Log::error('FFailed to execute command.Error:'.$error);
            $errors[] = 'Dave I understand your concern';
        }

        return new ServiceResponse($errors, $data);

    }
}
