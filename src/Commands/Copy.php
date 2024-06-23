<?php

namespace App\Commands;

use App\Controller\InstagramController;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Helper\Table;

class Copy extends Command
{

    public function __construct(
        private InstagramController $cliDb,
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this   ->setName('copy')
                ->setDescription('Copy from db')
                ->setHelp('This command copies entries from prod. to dev. db.')
                ->addArgument('feedId', InputArgument::REQUIRED, 'What ID to copy.')
                ->addOption('only', null, InputOption::VALUE_REQUIRED, 'Copies feed with the given ID and the respective entry from this source.')
                ->addOption('include-posts', null, InputOption::VALUE_REQUIRED, 'Copies feed with the given ID and this many posts.');
    }

    protected function execute(InputInterface $input, OutputInterface $output) :int
    {
        $feedId = $input->getArgument('feedId');
        $desiredSource = $input->getOption('only');
        $numOfPosts = $input->getOption('include-posts');
        $resultTable = new Table($output);

        if (!$desiredSource and !$numOfPosts) {
            // 1. copy feed with ID 'feedId' (incl. all sources)
            $result = $this->cliDb->findIt($feedId);
            $resultTable->setHeaders(['name', 'instagramName', 'instagramFanCount', 'tiktokName', 'tiktokFanCount']);
            foreach($result as $row){
                $resultTable->setRows([[$row['name'], str_replace(',', "\n", $row['instagramName']), str_replace(',', "\n", $row['instagramFan']), str_replace(',', "\n", $row['tiktokName']), str_replace(',', "\n", $row['tiktokFan'])]]);
            }

        } else if ($desiredSource and !$numOfPosts) {
            // 2. copy feed with ID 'feedId' and the respective entry from 'only'
            $desiredSource = ($desiredSource === 'instagram') ? 'instagram_sources' : 'tiktok_sources';
            $headers = ($desiredSource === 'instagram_sources') ? ['name', 'instagramName', 'instagramFanCount'] : ['name', 'tiktokName', 'tiktokFanCount'];
            $result = $this->cliDb->findIt($feedId, $desiredSource);
            $resultTable->setHeaders($headers);
            foreach($result as $row){
                $resultTable->setRows([[$row['name'], str_replace(',', "\n", $row['media']), str_replace(',', "\n", $row['fan'])]]);
            }

        } else if (!$desiredSource and $numOfPosts) {
            // 3. copy feed with ID 'feedId' and 'include-posts' posts
            $result = $this->cliDb->findIt($feedId, null, $numOfPosts);
            $resultTable->setHeaders(['name', 'posts']);
            foreach($result as $row){
                $resultTable->setRows([[$row['name'], str_replace(',', "\n", $row['postUrl'])]]);
            }

        } else if ($desiredSource and $numOfPosts) {
            // 4. copy feed with ID 'feedId', the respective entry from 'only' and 'include-posts' posts
            $desiredSource = ($desiredSource === 'instagram') ? 'instagram_sources' : 'tiktok_sources';
            $headers = ($desiredSource === 'instagram_sources') ? ['name', 'instagramName', 'instagramFanCount'] : ['name', 'tiktokName', 'tiktokFanCount'];
            $result = $this->cliDb->findIt($feedId, $desiredSource, $numOfPosts);
            $resultTable->setHeaders($headers);
            foreach($result as $row){
                $resultTable->setRows([[$row['name'], str_replace(',', "\n", $row['media']), str_replace(',', "\n", $row['fan']), str_replace(',', "\n", $row['postUrl'])]]);
            }
        }

        $output->writeln("");
        $output->writeln("[ ". date("Y-m-d, h:i:s") ." ]");
        $output->writeln("Entries from feed ID '{$feedId}'");
        $resultTable->render();
        $output->writeln("");
        return Command::SUCCESS;
    }
}