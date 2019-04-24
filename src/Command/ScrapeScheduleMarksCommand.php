<?php

namespace App\Command;

use Goutte\Client;
use InvalidArgumentException;
use Symfony\Bundle\FrameworkBundle\Command\ContainerAwareCommand;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Dotenv\Dotenv;
use Symfony\Component\Filesystem\Filesystem;

class ScrapeScheduleMarksCommand extends ContainerAwareCommand
{
    private $baseURL;
    private $marksPath;
    private $CUUsername;
    private $CUPassword;

    protected static $defaultName = 'scrape:schedule:marks';

    protected function configure()
    {
        //Get values from .env

        $dotEnv = new Dotenv();
        $dotEnv->load(__DIR__ . '/../../.env');

        $this->baseURL = getenv('SCRAPPER_BASE_URL');
        $this->marksPath = getenv('MARKS_PATH');
        $this->CUUsername = getenv('SCRAPPER_USERNAME');
        $this->CUPassword = getenv('SCRAPPER_PASSWORD');

        $this->setDescription('Scrape marks from CU Schedule')
            ->addArgument('scheduleIds', InputArgument::IS_ARRAY, 'Schedule IDs');
    }

    protected function execute(InputInterface $input, OutputInterface $output)
    {
        $io = new SymfonyStyle($input, $output);
        $scheduleIds = $input->getArgument('scheduleIds');

        if (!$scheduleIds) {
            throw new InvalidArgumentException('Please, pass scheduleId!');
        }

        //Create new User-Agent
        $client = new Client();

        $filesystem = new Filesystem();

        //Log In in profile

        $loginPost = $client->request('POST', $this->baseURL . '/cu/login?');

        $loginForm = $loginPost->selectButton('შესვლა')->form();

        $loginForm['username'] = $this->CUUsername;
        $loginForm['password'] = $this->CUPassword;
        $loginForm['token'] = null;
        $loginForm['submit'] = 'Login';

        //Submit form
        $client->submit($loginForm);

        //Create marks folder if don't exists

        $marksPath = $this->getContainer()->get('kernel')->getRootDir().'/..'.$this->marksPath;

        if (!$filesystem->exists($marksPath)) {
            $filesystem->mkdir($marksPath, 0700);
        }

        foreach ($scheduleIds as $scheduleId) {
            $io->success("Starting scrapping schedule No: {$scheduleId}");

            //Get marks page HTML
            $marks = $client->request('POST', $this->baseURL . '/cu/controllers/GradesList', ['iddd' => $scheduleId]);

            //Write HTML to file
            if (strpos($content = $marks->html(), 'გამოცდის ქულები არ არის სრულად შეყვანილი') === false) {
                //Write to file
                file_put_contents("{$marksPath}/schedule_{$scheduleId}.html", $content);

                $io->success("Schedule with No: {$scheduleId} successfully scrapped!");
            } else {
                $io->warning("Schedule with No: {$scheduleId} not found!");
            }
        }
    }
}
