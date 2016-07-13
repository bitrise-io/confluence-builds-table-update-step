#!/usr/bin/env php
<?php

require __DIR__.'/vendor/autoload.php';

use DAG\JIRA\BuildsTable\Command\JSONUpdateCommand;
use DAG\JIRA\BuildsTable\Command\TableUpdateCommand;
use Symfony\Component\Console\Application;
use Symfony\Component\Console\Input\ArgvInput;

$inputArg = [
    basename(__FILE__),
    'update',
    isset($_SERVER['jira_user']) ? $_SERVER['jira_user'] : null,
    isset($_SERVER['jira_password']) ? $_SERVER['jira_password'] : null,
    isset($_SERVER['jira_url']) ? $_SERVER['jira_url'] : null,
    isset($_SERVER['page_id']) ? $_SERVER['page_id'] : null,
    isset($_SERVER['attachment_filename']) ? $_SERVER['attachment_filename'] : null,
    isset($_SERVER['content']) ? $_SERVER['content'] : null,
];

$input = new ArgvInput($inputArg);

$application = new Application(
    'Confluence builds table updates'
);
//$application->add(new TableUpdateCommand());
$application->add(new JSONUpdateCommand());
$application->run($input);
