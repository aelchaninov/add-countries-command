<?php

declare(strict_types=1);

namespace App\Command;

use App\Entity\Country;
use App\Enums\CountriesEnum;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\InvalidArgumentException;
use Symfony\Component\Console\Exception\LogicException;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

class AddCountriesCommand extends Command
{
    /** @var SymfonyStyle */
    private $io;

    /** @var EntityManagerInterface */
    private $entityManager;

    /**
     * AddCountries constructor.
     *
     * @param EntityManagerInterface $entityManager
     *
     * @throws LogicException
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        parent::__construct();
        $this->entityManager = $entityManager;
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    public function execute(InputInterface $input, OutputInterface $output): void
    {
        $this->addCountries();

        $this->io->success('Default countries have been imported successfully');
    }

    /**
     * Seeding countries to database.
     */
    public function addCountries(): void
    {
        $em = $this->entityManager;
        $countries = (new CountriesEnum())->getCountries();
        $existingCountries = $em->getRepository(Country::class)->findAll();

        foreach ($countries as $countryData) {
            $countryDataCode = $countryData['code'];
            $countryExists = \array_filter(
                $existingCountries,
                function (Country $c) use ($countryDataCode) {
                    return $c->getCode() === $countryDataCode;
                }
            );

            if (!$countryExists) {
                $country = new Country();
                $country
                    ->setCode($countryData['code'])
                    ->setTitle($countryData['title'])
                    ->setPicture($countryData['picture_id'])
                    ->setSort((int) $countryData['sort'])
                ;
                $em->persist($country);
            }
        }
        $em->flush();
    }

    /**
     * @param InputInterface  $input
     * @param OutputInterface $output
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * {@inheritdoc}
     *
     * @throws InvalidArgumentException
     */
    protected function configure(): void
    {
        $this
            ->setName('app:command:add-countries')
            ->setDescription('Insert default countries to database')
        ;
    }
}
