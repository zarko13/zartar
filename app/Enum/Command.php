<?php

namespace App\Enum;

enum Command : string
{
    case MAKE_MODEL = 'make model';
    case MAKE_MIGRATION = 'make migration';
    case MAKE_SEEDER = 'make seeder';
    case MAKE_FACTORY = 'make factory';
    case MAKE_CONTROLLER = 'make controller';
    case MAKE_REQUEST = 'make request';
    case MAKE_MIDDLEWARE = 'make middleware';
    case MAKE_JOB = 'make job';
    case MAKE_EVENT = 'make event';
    case MAKE_LISTENER = 'make listener';
    case MAKE_COMMAND = 'make command';


    case GIT_PUSH = 'git push';
    case GIT_ADD = 'git add';
    case GIT_COMMIT = 'git commit';
    case GIT_BRANCH= 'git branch';
    case GIT_CHECKOUT= 'git checkout';
    case GIT_RESET = 'git reset';
    case GIT_STASH = 'git stash';
    case GIT_APPLY = 'git apply';
    case GIT_PULL = 'git pull';

    public static function commandsRequiredArgument() : array {
        return [
            self::MAKE_MODEL->value => [
                [
                    'key' => 'model_name',
                    'label' => 'Please specify the model name'
                ],
            ],
            self::MAKE_MIGRATION->value => [
                [
                    'key' => 'migration_name',
                    'label' => 'Please specify the migration name'
                ]
            ],
            self::MAKE_SEEDER->value => [
                [
                    'key' => 'seeder_name',
                    'label' => 'Please specify the seeder name'
                ],
            ],
            self::MAKE_FACTORY->value => [
                [
                    'key' => 'factory_name',
                    'label' => 'Please specify the factory name'
                ]
            ],
            self::MAKE_CONTROLLER->value => [
                [
                    'key' => 'controller_name',
                    'label' => 'Please specify the controller name'
                ],
            ],
            self::MAKE_REQUEST->value => [
                [
                    'key' => 'request_name',
                    'label' => 'Please specify the request name'
                ]
            ],
            self::MAKE_MIDDLEWARE->value => [
                [
                    'key' => 'middleware_name',
                    'label' => 'Please specify the middleware name'
                ],
            ],
            self::MAKE_JOB->value => [
                [
                    'key' => 'job_name',
                    'label' => 'Please specify the job name'
                ]
            ],
            self::MAKE_EVENT->value => [
                [
                    'key' => 'event_name',
                    'label' => 'Please specify the event name'
                ],
            ],
            self::MAKE_LISTENER->value => [
                [
                    'key' => 'listener_name',
                    'label' => 'Please specify the listener name'
                ]
            ],
            self::MAKE_COMMAND->value => [
                [
                    'key' => 'command_name',
                    'label' => 'Please specify the command name'
                ],
            ],
        ];
    }

    public static function commandHasRequiredArguments($enum): bool {
        $required = [
            self::MAKE_MODEL,
            self::MAKE_MIGRATION,
            self::MAKE_SEEDER,
            self::MAKE_FACTORY,
            self::MAKE_COMMAND,
            self::MAKE_REQUEST,
            self::MAKE_MIDDLEWARE,
            self::MAKE_JOB,
            self::MAKE_EVENT,
            self::MAKE_LISTENER,
            self::MAKE_COMMAND
        ];

        return in_array($enum, $required);
    }

}
