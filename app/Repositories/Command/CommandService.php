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


            $percentage = rand(1,100);
            $message = $percentage === 1 ? 'ne handri me više' : null;
            Log::info($command);
            if(!$message){
                switch ($command) {
                    case 'inspire':
                    case 'inspire.':
                    case 'inspired.':
                    case 'inspired':
                    case 'inspire me':
                    case 'inspire me please':
                        $message = self::inspire()->returnOrFail()->data['message'];
                        break;

                    default:
                        # code...
                        break;
                }
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

            $data['message'] = shell_exec('cd ' . base_path() . ' && php artisan inspire');

        } catch (Exception $error){
            Log::error('Failed to execute inspire.Error:'.$error);
            $errors[] = 'Failed to execute inspire';
        }

        return new ServiceResponse($errors, $data);

    }



    public static function checkIsFedUp(){

        $errors = null;
        $data = [];
        try {


            


            $isFedUp = rand(1, 10) == 1;


            $data['speak_message'] = $isFedUp;
            $data['text_message'] = $isFedUp;

        } catch (Exception $error){
            Log::error('Failed to check is fed up.Error:'.$error);
            $errors[] = 'Failed to check is fed up';
        }

        return new ServiceResponse($errors, $data);

    }
}
