<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\Fixtures\Factory;

use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Channel\Factory\ChannelFactoryInterface;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiftyDegSyliusRobotsPluginChannelsFactory extends AbstractExampleFactory
{
    /** @var \Faker\Generator */
    private $faker;

    /** @var OptionsResolver */
    private $optionsResolver;

    /** @var ChannelFactoryInterface */
    private $channelFactory;

    public function __construct(
        ChannelFactoryInterface $channelFactory,
        private RepositoryInterface $localeRepository,
        private RepositoryInterface $currencyRepository,
    ) {
        $this->channelFactory = $channelFactory;

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
            ->setDefault('hostname', function (Options $options): string {
                /** @var string $optHostName */
                $optHostName = $options['hostname'];

                return $optHostName;
            })
            ->setDefault('default_locale', function (Options $options): ResourceInterface {
                /** @var ResourceInterface $localeRepo */
                $localeRepo = $this->localeRepository->findOneBy(['code' => $this->faker->randomElement($options['locales'])]);
                if(empty($localeRepo)) {
                    $allLocales = $this->localeRepository->findAll();
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
                /** @var CurrencyInterface $randomCurrencies */
                $randomCurrencies = $this->currencyRepository->findOneBy(['code' => $this->faker->randomElement($options['currencies'])]);
                if(empty($randomCurrencies)) {
                    $allCurrencies = $this->currencyRepository->findAll();
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
        if(!isset($options['name']) || empty($options['name'] ||
            !isset($options['code']) || empty($options['code']) ||
            !isset($options['hostname']) || empty($options['hostname']))) {
            throw new \Exception('Please chek you yaml configuration file, it seems some channel you can try to add, is missing some fundamental data');
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

        /** @var array<string> $localesOpt */
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

    /** @param array<array-key, mixed> $options */
    public function update($channel, array $options = []): ChannelInterface
    {
        if (isset($options['name']) && !empty($options['name']) && is_string($options['name'])) {
            $channel->setName($options['name']);
        }
        if (isset($options['hostname']) && !empty($options['hostname']) && is_string($options['hostname'])) {
            $channel->setHostname($options['hostname']);
        }

        if (isset($options['default_locale']) && !empty($options['default_locale']) && is_string($options['default_locale'])) {
            /** @var LocaleInterface $defaultLocaleOpt */
            $defaultLocaleOpt = $options['default_locale'];
            $channel->setDefaultLocale($defaultLocaleOpt);
        }

        if (isset($options['locales']) && !empty($options['locales'])) {
            /** @var array<string> $localesOpt */
            $localesOpt = $options['locales'];
            foreach ($localesOpt as $locale) {
                /** @var LocaleInterface $localeObj */
                $localeObj = $this->localeRepository->findOneBy(['code' => $locale]);
                $channel->addLocale($localeObj);
            }
        }

        if (isset($options['base_currency']) && !empty($options['base_currency'])) {
            /** @var CurrencyInterface $baseCurrencyOpt */
            $baseCurrencyOpt = $options['base_currency'];
            $channel->setBaseCurrency($baseCurrencyOpt);
        }

        if (isset($options['currencies']) && !empty($options['currencies'])) {
            /** @var array<CurrencyInterface> $currenciesOpt */
            $currenciesOpt = $options['currencies'];
            foreach ($currenciesOpt as $currency) {
                $channel->addCurrency($currency);
            }
        }

        return $channel;
    }

    /** @param array<array-key, mixed> $options */
    public function checkOptionsFormats(array $options = []): string
    {
        if(isset($options['locales'])) {
            foreach($options['locales'] as $optionLocale) {
                $localeRepo = $this->localeRepository->findOneBy(['code' => $optionLocale]);
                if(is_null($localeRepo) || empty($localeRepo)) {
                    return 'Please, check the format of the locales array data, in your configuration - ' . $optionLocale;
                }
            }
        }
        if(isset($options['currencies'])) {
            foreach($options['currencies'] as $optionCurrency) {
                $randomCurrencies = $this->currencyRepository->findOneBy(['code' => $optionCurrency]);
                if(is_null($randomCurrencies) || empty($randomCurrencies)) {
                    return 'Please, check the format of the currencies array data, in your configuration - ' . $optionCurrency;
                }
            }
            
        }
        if(isset($options['default_locale'])) {
            $localeRepo = $this->localeRepository->findOneBy(['code' => $this->faker->randomElement($options['default_locale'])]);
            if(is_null($localeRepo) || empty($localeRepo)) {
                return 'Please, check the format of the default_locale array data, in your configuration - ' . $options['default_locale'];
            }
        }
        if(isset($options['base_currency'])) {
            $randomCurrencies = $this->currencyRepository->findOneBy(['code' => $this->faker->randomElement($options['base_currency'])]);
            if(is_null($randomCurrencies) || empty($randomCurrencies)) {
                return 'Please, check the format of the base_currency data, in your configuration - ' . $options['base_currency'];
            }
        }

        return '-1';
    }
}
