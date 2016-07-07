<?php
namespace DAG\JIRA\BuildsTable\Command;

use DAG\JIRA\BuildsTable\Client;
use DAG\JIRA\BuildsTable\HTMLTableBuilder;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;

/**
 * Class TableUpdateCommand
 */
final class TableUpdateCommand extends Command
{
    protected function configure()
    {
        $this
            ->setName('update')
            ->addArgument('jira_user', InputArgument::REQUIRED, '')
            ->addArgument('jira_password', InputArgument::REQUIRED, '')
            ->addArgument('jira_url', InputArgument::REQUIRED, '')
            ->addArgument('cells', InputArgument::REQUIRED, 'A list of values separated by line')
            ->addArgument('page_id', InputArgument::REQUIRED, '');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $cells = explode("\n", $input->getArgument('cells'));

        $client = new Client(
            $input->getArgument('jira_user'),
            $input->getArgument('jira_password'),
            $input->getArgument('jira_url')
        );

        $page = $client->getPage($input->getArgument('page_id'));

        $builder = new HTMLTableBuilder();
        $html = $builder->build($page['body']['view']['value'], $cells);
        $html = str_replace("\n", "", $html);
        $html = str_replace("<col>", "<col/>", $html);

        $newPage = [
            'title' => $page['title'],
            'type' => $page['type'],
            'version' => [
                'number' => $page['version']['number'] + 1,
            ],
            'body' => [
                'storage' => [
                    'value' => $html,
                    "representation" => "storage",
                ],
            ],
        ];

        if (isset($page['ancestors'])) {
            $newPage['ancestors'] = [];
            foreach ($page['ancestors'] as $ancestor) {
                $newPage['ancestors'][] = ['id' => $ancestor['id']];
            }
        }

        $client->sendPage($input->getArgument('page_id'), $newPage);
    }
}
