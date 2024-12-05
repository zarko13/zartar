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
}
