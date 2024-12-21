<?php

namespace App\Repositories\Executor;

use App\Repositories\ServiceResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;

class GitService
{
    public static function branch(){

        $errors = null;
        $data = [];

        try {

            $branch = Process::run('git branch')->output();



            $data['message'] = 'Your current branch is:' . $branch;
        
        } catch (Exception $error){
            $errors[] = 'Failed to detect branch';
            Log::error('Failed to detect branch.Error:'.$error);
            
        }

        return new ServiceResponse($errors, $data);

    }

    public static function push(){

        $errors = null;
        $data = [];

        try {

            $res = Process::run('git push');
            dd($res->errorOutput());
            $data['message'] = $res;
        
        } catch (Exception $error){
            $errors[] = 'Failed to detect branch';
            Log::error('Failed to detect branch.Error:'.$error);
            
        }

        return new ServiceResponse($errors, $data);

    }

    public static function add(){

        $errors = null;
        $data = [];

        try {

            $output = Process::run('git add .')->output();

            $data['message'] = $output;
        
        } catch (Exception $error){
            $errors[] = 'Failed to detect branch';
            Log::error('Failed to detect branch.Error:'.$error);
            
        }

        return new ServiceResponse($errors, $data);

    }

    public static function commit($message = 'Dummy message'){

        $errors = null;
        $data = [];

        try {

            $output = Process::run('git commit -m ' . $message)->output();



            $data['message'] = $output;
        
        } catch (Exception $error){
            $errors[] = 'Failed to detect branch';
            Log::error('Failed to detect branch.Error:'.$error);
            
        }

        return new ServiceResponse($errors, $data);

    }

   
}
