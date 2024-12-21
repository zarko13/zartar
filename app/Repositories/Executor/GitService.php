<?php

namespace App\Repositories\Executor;

use App\Repositories\ServiceResponse;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Process;
use Illuminate\Support\Str;

class GitService
{
    public static function add(){

        $errors = null;
        $data = [];

        try {
            
            $result = Process::run('git add .');
            $data['message'] = $result->successful() ? $result->errorOutput() : $result->output();
        
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

            
            $result = Process::run('git commit -m "' . $message . '"');
            $data['message'] = $result->successful() ? $result->errorOutput() : $result->output();
        
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

            $result = Process::run('git push');
            $data['message'] = $result->successful() ? $result->errorOutput() : $result->output();
        
        } catch (Exception $error){
            $errors[] = 'Failed to detect branch';
            Log::error('Failed to detect branch.Error:'.$error);
            
        }

        return new ServiceResponse($errors, $data);

    }

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

    public static function checkout($branch){

        $errors = null;
        $data = [];

        try {

            $result = Process::run('git checkout ' . $branch);
            $data['message'] = $result->successful() ? $result->errorOutput() : $result->output();
        
        } catch (Exception $error){
            $errors[] = 'Failed to detect branch';
            Log::error('Failed to detect branch.Error:'.$error);
            
        }

        return new ServiceResponse($errors, $data);

    }
    
    public static function reset(){

        $errors = null;
        $data = [];

        try {

            $result = Process::run('git reset --hard');
            $data['message'] = $result->successful() ? $result->errorOutput() : $result->output();
        
        } catch (Exception $error){
            $errors[] = 'Failed to detect branch';
            Log::error('Failed to detect branch.Error:'.$error);
            
        }

        return new ServiceResponse($errors, $data);

    }

    public static function stash(){

        $errors = null;
        $data = [];

        try {

            $result = Process::run('git stash');
            $data['message'] = $result->successful() ? $result->errorOutput() : $result->output();
        
        } catch (Exception $error){
            $errors[] = 'Failed to detect branch';
            Log::error('Failed to detect branch.Error:'.$error);
            
        }

        return new ServiceResponse($errors, $data);

    }

    public static function apply(){

        $errors = null;
        $data = [];

        try {

            $result = Process::run('git stash apply');
            $data['message'] = $result->successful() ? $result->errorOutput() : $result->output();
        
        } catch (Exception $error){
            $errors[] = 'Failed to detect branch';
            Log::error('Failed to detect branch.Error:'.$error);
            
        }

        return new ServiceResponse($errors, $data);

    }


    public static function pull(){

        $errors = null;
        $data = [];

        try {

            $result = Process::run('git pull');
            $data['message'] = $result->successful() ? $result->errorOutput() : $result->output();
        
        } catch (Exception $error){
            $errors[] = 'Failed to detect branch';
            Log::error('Failed to detect branch.Error:'.$error);
            
        }

        return new ServiceResponse($errors, $data);

    }


    public static function createBranch($branch){

        $errors = null;
        $data = [];

        try {

            $result = Process::run('git pull');
            $data['message'] = $result->successful() ? $result->errorOutput() : $result->output();
        
        } catch (Exception $error){
            $errors[] = 'Failed to detect branch';
            Log::error('Failed to detect branch.Error:'.$error);
            
        }

        return new ServiceResponse($errors, $data);

    }


    public static function dummyPush(){

        $errors = null;
        $data = [];

        try {

            self::add()->returnOrFail();
            self::commit(fake()->sentence())->returnOrFail();
            self::push()->returnOrFail();
        
        } catch (Exception $error){
            $errors[] = 'Failed to detect branch';
            Log::error('Failed to detect branch.Error:'.$error);
            
        }

        return new ServiceResponse($errors, $data);

    }
   
}
