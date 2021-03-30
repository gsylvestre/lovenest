<?php

namespace App\Command;

use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\String\ByteString;
use Symfony\Contracts\HttpClient\HttpClientInterface;

class DownloadDummyImagesCommand extends Command
{
    protected static $defaultName = 'app:download-dummy-images';
    protected static $defaultDescription = 'Download dummy images from unsplash for use in fixtures';

    /**
     * @var HttpClientInterface
     */
    private HttpClientInterface $httpClient;

    public function __construct(string $name = null, HttpClientInterface $httpClient)
    {
        parent::__construct($name);
        $this->httpClient = $httpClient;
    }

    protected function configure()
    {
        $this
            ->setDescription(self::$defaultDescription)
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $keywords = ["face+woman", "face+man"];
        for($i=1; $i<=1; $i++){
            foreach($keywords as $kw){
                $url = "https://api.unsplash.com/search/photos?page=$i&per_page=30&query=$kw&client_id=f4b599bb478f23fe8c8fb588020abce521b3b7d93388627bd2d98980ac07e0ba";

                $response = $this->httpClient->request('GET', $url);
                $content = $response->toArray();

                foreach($content['results'] as $pic){
                    $io->writeln($pic['urls']['small']);
                    $newFilename = ByteString::fromRandom(30) . ".jpg";
                    file_put_contents("src/DataFixtures/pics/" . $newFilename, file_get_contents($pic['urls']['small']));
                }
            }
        }


        $io->success('You have a new command! Now make it your own! Pass --help to see your options.');

        return Command::SUCCESS;
    }
}
