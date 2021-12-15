<?php

namespace App\Command;

use App\Entity\City;
use App\Repository\CountryRepository;
use App\Service\CallApiService;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class CityBddCommand extends Command
{
    protected static $defaultName = 'city:bdd';
    protected static $defaultDescription = 'Add a short description for your command';

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
            ->addOption('option1', null, InputOption::VALUE_NONE, 'Option description')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);
        
        // Retrieving the country list from the database.
        $countriesList = $this->countryRepository->findAll();

        // we loop on the countries list to retrieve each country.
        foreach ($countriesList as $country) {

            // the name of each country in the list
            $countryName = $country->getName();
            
            // we call the cities list by country code from the Spott Api.
            $citiesList = $this->callApiService->getCitiesData($country->getCountryCode());

            // we loop on the cities list (from Api) to retrieve each city (belong to the country with the same country code)
            foreach ($citiesList as $city) {
                
                // Retrieving the country code and all city name
                $countryCode = $city['country']['id'];
                $cityName = $city['name'];

                // we create a new city object
                $newCity = new City;

                // we add the city properties to the new city object.
                $newCity->setName($cityName);
                $newCity->setCountryCode($countryCode);
                $newCity->setCountry($country);

                // we save it 
                $this->manager->persist($newCity);

            }

            // success message for each country
            $io->text('Cities belongs to ' . $countryName . " added with success");

        }

        // 3) We add all the news countries to the database.
        $this->manager->flush();

        // success message
        $io->success('Ajout des Villes effectuées avec succès !');

        return Command::SUCCESS;
    }
}