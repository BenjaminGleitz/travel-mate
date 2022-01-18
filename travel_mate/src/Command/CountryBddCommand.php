<?php

namespace App\Command;

use App\Entity\Country;
use App\Repository\CountryRepository;
use App\Service\CallApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CountryBddCommand extends Command
{
    protected static $defaultName = 'country:bdd';
    protected static $defaultDescription = 'add country to the database from SpottAPI';

    private $callApiService;
    private $countryRepository;
    private $manager;

    public function __construct(CallApiService $callApiService, CountryRepository $countryRepository, EntityManagerInterface $manager) 
    {
        // retrieving the country list.
        $this->countryRepository = $countryRepository;

        // we need to call the API's datas.
        $this->callApiService = $callApiService;

        // to save the datas to the database.
        $this->manager = $manager;

        parent::__construct();
    }

    protected function configure(): void
    {   
        $this
            ->addArgument('arg1', InputArgument::OPTIONAL, 'Argument description')
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description');
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);


        // 1) we take back the countries list from the API.
        $countryList = $this->callApiService->getCountriesData();

        // 2) to each country, we make an new object, with the country property, and we add it to the database.
        foreach ($countryList as $country) {

            // retrieving the country code, and the name of each country.
            $countryCode = $country['id'];
            $name = $country['name'];

            // we make an object of each country.
            $newCountry = new Country;

            // we add the country properties to the new country.
            $newCountry->setName($name);
            $newCountry->setCountryCode($countryCode);

            // we save it 
            $this->manager->persist($newCountry);

            // success message for each country
            $io->text('the country ' . $name . " added with success");
            
        }

        // 3) We add all the news countries to the database.
        $this->manager->flush();

        // success message
        $io->success('Ajout des pays effectués avec succès !');

        return Command::FAILURE;
    }
}
