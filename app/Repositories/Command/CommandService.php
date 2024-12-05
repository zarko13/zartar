<?php

namespace App\Repositories\Command;

use App\Enum\Command;
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

            $fedUpResponse = self::checkIsFedUp()->returnOrFail();
            $speakMessage = $fedUpResponse->data['speak_message'];
            $textMessage = $fedUpResponse->data['text_message'];
            if(!$speakMessage || !$textMessage){

                $mostSimilar = self::calculateSimilarities($command)->returnOrFail()->data['most_similar'];
                $speakMessage = $mostSimilar->value;
                $textMessage = $mostSimilar->value;
                // switch ($command) {
                //     case 'inspire':
                //     case 'inspire.':
                //     case 'inspired.':
                //     case 'inspired':
                //     case 'inspire me':
                //     case 'inspire me.':
                //     case 'inspire me please':
                //     case 'inspire me please.':
                //         $inspireResponse  = self::inspire()->returnOrFail();
                //         $speakMessage = $inspireResponse->data['speak_message'];
                //         $textMessage = $inspireResponse->data['text_message'];
                //         break;
                //     case 'what is your name':
                //         $inspireResponse  = self::inspire()->returnOrFail();
                //         $speakMessage = $inspireResponse->data['speak_message'];
                //         $textMessage = $inspireResponse->data['text_message'];
                //         break;

                //     default:
                //         $message  = self::getRandomMessage('unknown_command')->returnOrFail()->data['message'];
                //         $speakMessage = $message;
                //         $textMessage = $message;
                //         break;
                // }
            }



            $data['speak_message'] = $speakMessage;
            $data['text_message'] = $textMessage;

        } catch (Exception $error){
            Log::error('Failed to execute command.Error:'.$error);
            $errors[] = self::getRandomMessage('fail')->returnOrFail()->data['message'];
        }

        return new ServiceResponse($errors, $data);

    }


    public static function inspire(){

        $errors = null;
        $data = [];
        try {

            $message = shell_exec('cd ' . base_path() . ' && php artisan inspire');

            $data['speak_message'] = $message;
            $data['text_message'] = $message;

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

            $message = rand(1, 10) == 1 ? self::getRandomMessage('fed_up')->data['message'] : null;

            $data['speak_message'] = $message;
            $data['text_message'] = $message;

        } catch (Exception $error){
            Log::error('Failed to check is fed up.Error:'.$error);
            $errors[] = 'Failed to check is fed up';
        }

        return new ServiceResponse($errors, $data);

    }



    public static function getRandomMessage($type){

        $errors = null;
        $data = [];
        try {


            $data['message'] = config('messages')[$type][rand(0, count(config('messages')[$type]) - 1)];

        } catch (Exception $error){
            Log::error('Failed to get random message.Error:'.$error);
            $errors[] = 'Failed to get random message';
        }

        return new ServiceResponse($errors, $data);

    }


    public static function calculateSimilarities($userCommand){

        $errors = null;
        $data = [];
        $mostSimilar = null;
        $mostSimilarPercentage = 0;


        try {

            $userCommandParts = explode(' ', $userCommand);
            $similarities = [];

            foreach (Command::cases() as $command) {
                $commandParts = explode(' ', $command->value);
                $intersects = array_intersect($userCommandParts, $commandParts);
                $similarity = bcdiv(bcmul(strlen(implode('', $intersects)) , 100), strlen(implode('', $commandParts)), 2);
                $similarities[] = [
                    'command' => $command,
                    'similarity' => $similarity
                ];


                if($similarity >= $mostSimilarPercentage){
                    $mostSimilar = $command;
                    $mostSimilarPercentage = $similarity;
                }
            }


            $data['similarities'] = $similarities;
            $data['most_similar'] = $mostSimilar;
            $data['most_similar_percentage'] = $mostSimilarPercentage;

        } catch (Exception $error){
            Log::error('Failed to calculate similarities.Error:'.$error);
            $errors[] = 'Failed to calculate similarities';
        }

        return new ServiceResponse($errors, $data);

    }


}
