<?php

namespace App\Enum;

enum Command : string
{
    case MAKE_MODEL = 'make model';
    case MAKE_MIGRATION = 'make migration';
    case MAKE_SEEDER = 'make seeder';
    case MAKE_CEDAR = 'make cedar';
    case MAKE_CIDER = 'make cider';
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


    public static function commandsRequiredArguments() : array {
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

            self::GIT_PUSH->value => [],

            self::GIT_ADD->value => [],

            self::GIT_COMMIT->value => [
                [
                    'key' => 'commit_message',
                    'label' => 'Please specify the commit message'
                ],
            ],

            self::GIT_RESET->value => [],

            self::GIT_STASH->value => [],

            self::GIT_APPLY->value => [],

            self::GIT_PULL->value => [],
        ];
    }

    public function commandRequiredArguments() : array {
        return self::commandsRequiredArguments()[$this->value];
    }


    public static function commandsOptionalArguments() : array {
        return [
            self::MAKE_MODEL->value => [
            ],
            self::MAKE_MIGRATION->value => [
            ],
            self::MAKE_SEEDER->value => [
            ],
            self::MAKE_FACTORY->value => [
            ],
            self::MAKE_CONTROLLER->value => [
            ],
            self::MAKE_REQUEST->value => [
            ],
            self::MAKE_MIDDLEWARE->value => [
            ],
            self::MAKE_JOB->value => [
            ],
            self::MAKE_EVENT->value => [
            ],
            self::MAKE_LISTENER->value => [
            ],
            self::MAKE_COMMAND->value => [
            ],

            self::GIT_PUSH->value => [],

            self::GIT_ADD->value => [],

            self::GIT_COMMIT->value => [
            ],

            self::GIT_RESET->value => [],

            self::GIT_STASH->value => [],

            self::GIT_APPLY->value => [],

            self::GIT_PULL->value => [],
        ];
    }

    public function commandOptionalArguments() : array {
        return self::commandsOptionalArguments()[$this->value];
    }

    case SHOW_CURRENT_WORKING_DIRECTORY = 'current directory';
    case LIST_FILES = 'list files';
    case SHOW_FILES = 'show files';
    case RENAME_FILE = 'rename file';
    case REMOVE_FILES = 'remove files';
}
