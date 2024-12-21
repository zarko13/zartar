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
            $isFedUp = $fedUpResponse->data['is_fed_up'];
            if($isFedUp){
            }
            $speakMessage = $fedUpResponse->data['speak_message'];
            $textMessage = $fedUpResponse->data['text_message'];
            if(!$speakMessage || !$textMessage){

                $similarityResponse = self::calculateSimilaritiesAlt($command)->returnOrFail();

                $exactCommand = $similarityResponse->data['exact_command'];
                $didYouMeanCommands = $similarityResponse->data['did_you_mean_commands'];

                if($didYouMeanCommands){
                    $speakMessage = $didYouMeanCommands;
                    $textMessage = $didYouMeanCommands;
                }

                if($exactCommand){

                    if(count($exactCommand->commandRequiredArguments())){
                        $speakMessage = $exactCommand->commandRequiredArguments()[0]['label'];
                        $textMessage = $exactCommand->commandRequiredArguments()[0]['label'];
                    }


                    if(!$speakMessage){
                        switch ($command) {
                            case 'value':
                                # code...
                                break;

                            default:
                                # code...
                                break;
                        }
                    }
                }

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
            $data['is_fed_up'] = $message ? true : false;

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

    public static function calculateSimilaritiesAlt($userCommand){

        $errors = null;
        $data = [];
        $didYouMeanCommands = [];
        $exactCommand = null;
        $currentMaxSimilarity = 0;


        try {
            $didYouMeanThreshold = config('similarity.did_you_mean_threshold');
            $exactThreshold = config('similarity.exact_threshold');

            foreach (Command::cases() as $command) {
                $similarity = self::calculateExpressionsSimilarity($userCommand, $command->value)->returnOrFail()->data['total_similarity'];
                if($similarity >= $didYouMeanThreshold){
                    $didYouMeanCommands[] = $command->value;


                    if($similarity >= $exactThreshold && $similarity > $currentMaxSimilarity){
                        $currentMaxSimilarity = $similarity;
                        $exactCommand = $command;
                    }
                }
            }

            $didYouMean = !$exactCommand && count($didYouMeanCommands) ? 'Did you mean one of these:' . implode(',', $didYouMeanCommands) : null;

            $data['exact_command'] = $exactCommand;
            $data['did_you_mean_commands'] = $didYouMean;

        } catch (Exception $error){
            Log::error('Failed to calculate similarities.Error:'.$error);
            $errors[] = 'Failed to calculate similarities';
        }

        return new ServiceResponse($errors, $data);

    }

    public static function removePunctuation($expressions){

        $errors = null;
        $data = [];


        try {

            $data['expression'] = preg_replace("#[[:punct:]]#", "", $expressions);

        } catch (Exception $error){
            Log::error('Failed to remove punctuation.Error:'.$error);
            $errors[] = 'Failed to remove punctuation';
        }

        return new ServiceResponse($errors, $data);

    }

    public static function chunkExpression($expression){

        $errors = null;
        $data = [];


        try {

            $data['chunks'] = explode(' ', $expression);

        } catch (Exception $error){
            Log::error('Failed to chunk expression.Error:'.$error);
            $errors[] = 'Failed to chunk expression';
        }

        return new ServiceResponse($errors, $data);

    }

    public static function calculateExpressionsSimilarity($case, $target){

        $errors = null;
        $data = [];


        try {

            $cleanedCase = self::removePunctuation($case)->returnOrFail()->data['expression'];
            $cleanedTarget = self::removePunctuation($target)->returnOrFail()->data['expression'];

            $caseChunks = self::chunkExpression($cleanedCase)->returnOrFail()->data['chunks'];
            $targetChunks = self::chunkExpression($cleanedTarget)->returnOrFail()->data['chunks'];

            $totalDistance = 0;
            $totalLength = 0;
            $targetAnalysis = [];
            foreach ($targetChunks as $targetChunk) {
                $targetLength = strlen($targetChunk);
                $totalLength = bcadd($totalLength, $targetLength);
                $minDistance = $targetLength;
                $closesChunk = null;
                foreach ($caseChunks as $caseChunk) {
                    $distance = levenshtein($caseChunk, $targetChunk);
                    if($distance < $minDistance){
                        $minDistance = $distance;
                        $closesChunk = $caseChunk;
                    }
                }

                $totalDistance = bcadd($totalDistance, $minDistance);
                $targetAnalysis[] = [
                    'target' => $targetChunk,
                    'distance' => $minDistance,
                    'case' => $closesChunk,
                    'similarity' => bcdiv(bcmul(bcsub($targetLength, $minDistance), 100), $targetLength, 2)
                ];
            }

            $data['target_analysis'] = $targetAnalysis;
            $data['total_length'] = $totalLength;
            $data['total_distance'] = $totalDistance;
            $data['total_similarity'] = bcdiv(bcmul(bcsub($totalLength, $totalDistance), 100), $totalLength, 2);

        } catch (Exception $error){
            Log::error('Failed to chunk expression.Error:'.$error);
            $errors[] = 'Failed to chunk expression';
        }

        return new ServiceResponse($errors, $data);

    }
}
