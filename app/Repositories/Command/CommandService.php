<?php

namespace App\Repositories\Command;

use App\Repositories\ServiceResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class CommandService
{
    public static function executeCommand($command){

        $errors = null;
        $data = [];
        try {


            $message = null;

            switch ($command) {
                case 'inspire':
                    $message = self::inspire()->returnOrFail()->data['message'];
                    break;

                default:
                    # code...
                    break;
            }

            $data['message'] = $message ? $message : 'Dave odvadi malo';

        } catch (Exception $error){
            Log::error('FFailed to execute command.Error:'.$error);
            $errors[] = 'Dave I understand your concern';
        }

        return new ServiceResponse($errors, $data);

    }


    public static function inspire(){

        $errors = null;
        $data = [];
        try {

            $data['message'] = Process::run('php artisan inspire')->output();

        } catch (Exception $error){
            Log::error('Failed to execute inspire.Error:'.$error);
            $errors[] = 'Failed to execute inspire';
        }

        return new ServiceResponse($errors, $data);

    }
}
