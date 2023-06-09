<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\Fixtures\Factory;

use Sylius\Bundle\CoreBundle\Fixture\Factory\ChannelExampleFactory;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Channel\Factory\ChannelFactoryInterface;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Core\Model\TaxonInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

/**
 * This class implements a Channel Factory,
 * it is used by FiftyDegSyliusRobotsPluginChannelsFixture to create the Channel pbject to store.
 *
 * @author Marco Pistorello <marco.pistorello@fiftydeg.com>
 */
class FiftyDegSyliusRobotsPluginChannelsFactory extends ChannelExampleFactory
{
    /** @var \Faker\Generator */
    private $faker;

    /** @var OptionsResolver */
    private $optionsResolver;

    /** @var ChannelFactoryInterface */
    private $channelFactory;

    private RepositoryInterface $localeRepository;

    private RepositoryInterface $currencyRepository;

    private RepositoryInterface $zoneRepository;

    private ?TaxonRepositoryInterface $taxonRepository;

    private ?FactoryInterface $shopBillingDataFactory;

    public function __construct(
        ChannelFactoryInterface $channelFactory,
        RepositoryInterface $localeRepository,
        RepositoryInterface $currencyRepository,
        RepositoryInterface $zoneRepository,
        ?TaxonRepositoryInterface $taxonRepository = null,
        ?FactoryInterface $shopBillingDataFactory = null
    ) {
        $this->channelFactory = $channelFactory;
        $this->localeRepository = $localeRepository;
        $this->currencyRepository = $currencyRepository;
        $this->zoneRepository = $zoneRepository;
        $this->taxonRepository = $taxonRepository;
        $this->shopBillingDataFactory = $shopBillingDataFactory;

        parent::__construct(
            $channelFactory,
            $localeRepository,
            $currencyRepository,
            $zoneRepository,
            $taxonRepository,
            $shopBillingDataFactory
        );



        $this->faker = \Faker\Factory::create();

        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('name', function (Options $options): string {
                /** @var string $optName */
                $optName = $options['name'];

                return $optName;
            })
            ->setDefault('code', function (Options $options): string {
                /** @var string $optCode */
                $optCode = $options['code'];

                return $optCode;
            })
            ->setDefault('hostname', function (): string {
                return 'localhost';
            })
            ->setDefault('default_locale', function (Options $options): ResourceInterface {
                /** @var ResourceInterface|null $localeRepo */
                $localeRepo = $this->localeRepository->findOneBy(['code' => $this->faker->randomElement($options['locales'])]);
                if (null === $localeRepo) {
                    /** @var array<array-key, ResourceInterface> $allLocales */
                    $allLocales = $this->localeRepository->findAll();

                    /** @var ResourceInterface $localeRepo */
                    $localeRepo = reset($allLocales);
                }

                return $localeRepo;
            })

            ->setAllowedTypes('default_locale', ['string', LocaleInterface::class])
            ->setNormalizer('default_locale', LazyOption::getOneBy($this->localeRepository, 'code'))
            ->setDefault('locales', LazyOption::all($this->localeRepository))
            ->setAllowedTypes('locales', 'array')
            ->setNormalizer('locales', LazyOption::findBy($this->localeRepository, 'code'))
            ->setDefault('base_currency', function (Options $options): CurrencyInterface {
                /** @var CurrencyInterface|null $randomCurrencies */
                $randomCurrencies = $this->currencyRepository->findOneBy(['code' => $this->faker->randomElement($options['currencies'])]);
                if (null === $randomCurrencies) {
                    $allCurrencies = $this->currencyRepository->findAll();

                    /** @var CurrencyInterface $randomCurrencies */
                    $randomCurrencies = reset($allCurrencies);
                }

                return $randomCurrencies;
            })
            ->setAllowedTypes('base_currency', ['string', CurrencyInterface::class])
            ->setNormalizer('base_currency', LazyOption::getOneBy($this->currencyRepository, 'code'))
            ->setDefault('currencies', LazyOption::all($this->currencyRepository))
            ->setAllowedTypes('currencies', 'array')
            ->setNormalizer('currencies', LazyOption::findBy($this->currencyRepository, 'code'))
        ;
    }

    /** @param array<array-key, mixed> $options */
    public function create(array $options = []): ChannelInterface
    {
        if (!isset($options['name']) ||
            !isset($options['code'])) {
            throw new \Exception('Please check you yaml configuration file, it seems some channel you can try to add, is missing some fundamental data');
        }

        /** @var array<string, string|CurrencyInterface|LocaleInterface> $options */
        $options = $this->optionsResolver->resolve($options);

        /** @var ChannelInterface $channel */
        $channel = $this->channelFactory->createNew();

        if (is_string($options['code'])) {
            $channel->setCode($options['code']);
        }
        if (is_string($options['name'])) {
            $channel->setName($options['name']);
        }
        if (is_string($options['hostname'])) {
            $channel->setHostname($options['hostname']);
        }

        /** @var LocaleInterface $defaultLocaleOpt */
        $defaultLocaleOpt = $options['default_locale'];
        $channel->setDefaultLocale($defaultLocaleOpt);

        /** @var array<array-key, LocaleInterface> $localesOpt */
        $localesOpt = $options['locales'];
        foreach ($localesOpt as $locale) {
            $channel->addLocale($locale);
        }

        /** @var CurrencyInterface $baseCurrencyOpt */
        $baseCurrencyOpt = $options['base_currency'];
        $channel->setBaseCurrency($baseCurrencyOpt);

        /** @var array<CurrencyInterface> $currenciesOpt */
        $currenciesOpt = $options['currencies'];
        foreach ($currenciesOpt as $currency) {
            $channel->addCurrency($currency);
        }

        return $channel;
    }

    /**
     * @param ChannelInterface $channel
     * @param array<array-key, mixed> $options
     * */
    public function update($channel, array $options = []): ChannelInterface
    {
        if (isset($options['name']) && is_string($options['name']) && $options['name'] !== '') {
            $channel->setName($options['name']);
        }
        if (isset($options['hostname']) && is_string($options['hostname']) && $options['hostname'] !== '') {
            $channel->setHostname($options['hostname']);
        }

        if (isset($options['default_locale']) && is_string($options['default_locale']) && $options['default_locale'] !== '') {
            /** @var LocaleInterface $defaultLocaleOpt */
            $defaultLocaleOpt = $options['default_locale'];
            $channel->setDefaultLocale($defaultLocaleOpt);
        }

        if (isset($options['locales'])) {
            /** @var array<string> $localesOpt */
            $localesOpt = $options['locales'];
            foreach ($localesOpt as $locale) {
                /** @var LocaleInterface $localeObj */
                $localeObj = $this->localeRepository->findOneBy(['code' => $locale]);
                $channel->addLocale($localeObj);
            }
        }

        if (isset($options['base_currency'])) {
            /** @var CurrencyInterface $baseCurrencyOpt */
            $baseCurrencyOpt = $options['base_currency'];
            $channel->setBaseCurrency($baseCurrencyOpt);
        }

        if (isset($options['currencies'])) {
            /** @var array<CurrencyInterface> $currenciesOpt */
            $currenciesOpt = $options['currencies'];
            foreach ($currenciesOpt as $currency) {
                $channel->addCurrency($currency);
            }
        }

        return $channel;
    }

    /**
     * Checks that the values ​​of the variables in the configuration file coincide with those already in DB
     *
     * @param array<array-key, mixed> $options
     * @return string|null
     */
    public function checkOptionsFormats(array $options = []): ?string
    {
        if (isset($options['locales'])) {
            /** @var array<array-key, string> $optionsLocales */
            $optionsLocales = $options['locales'];
            foreach ($optionsLocales as $optionLocale) {
                $localeRepo = $this->localeRepository->findOneBy(['code' => $optionLocale]);
                if (null === $localeRepo) {
                    $result = 'Please, check the format of the locales array data, in your configuration - ' . $optionLocale;

                    return $result;
                }
            }
        }
        if (isset($options['currencies'])) {
            /** @var array<array-key, string> $optionsCurrencies */
            $optionsCurrencies = $options['currencies'];
            foreach ($optionsCurrencies as $optionCurrency) {
                $randomCurrencies = $this->currencyRepository->findOneBy(['code' => $optionCurrency]);
                if (null === $randomCurrencies) {
                    $result = 'Please, check the format of the currencies array data, in your configuration - ' . $optionCurrency;

                    return $result;
                }
            }
        }
        if (isset($options['default_locale'])) {
            $localeRepo = $this->localeRepository->findOneBy(['code' => $this->faker->randomElement($options['default_locale'])]);
            if (null === $localeRepo) {
                /** @var string $defaultLocale */
                $defaultLocale = $options['default_locale'];
                $result = 'Please, check the format of the default_locale array data, in your configuration - ' . $defaultLocale;

                return $result;
            }
        }
        if (isset($options['base_currency'])) {
            $randomCurrencies = $this->currencyRepository->findOneBy(['code' => $this->faker->randomElement($options['base_currency'])]);
            if (null === $randomCurrencies) {
                /** @var string $baseCurrency */
                $baseCurrency = $options['base_currency'];
                $result = 'Please, check the format of the base_currency data, in your configuration - ' . $baseCurrency;

                return $result;
            }
        }

        return null;
    }
}
