<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\Fixtures\Factory;

use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Sylius\Component\Resource\Model\ResourceInterface;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;

class FiftyDegSyliusRobotsPluginChannelsFactory extends AbstractExampleFactory
{
    /** @var FactoryInterface */
    private $channelFactory;

    /** @var \Faker\Generator */
    private $faker;

    /** @var OptionsResolver */
    private $optionsResolver;

    public function __construct(
        FactoryInterface $channelFactory,
        private RepositoryInterface $localeRepository,
        private RepositoryInterface $currencyRepository,
        private RepositoryInterface $zoneRepository,
    ) {
        $this->channelFactory = $channelFactory;

        $this->faker = \Faker\Factory::create();

        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        $resolver
            ->setDefault('name', function (): string {
                return $this->faker->sentence;
            })
            ->setDefault('code', function (Options $options): string {
                /** @var string $optName */
                $optName = $options['name'];

                return StringInflector::nameToCode($optName);
            })
            ->setDefault('hostname', function (Options $options): string {
                /** @var string $optHostName */
                $optHostName = $options['hostname'];

                return $optHostName;
            })
            ->setDefault('default_locale', function (Options $options): ResourceInterface {
                /** @var ResourceInterface $localeRepo */
                $localeRepo = $this->localeRepository->findOneBy(['code' => $this->faker->randomElement($options['locales'])]);

                return $localeRepo;
            })

            ->setAllowedTypes('default_locale', ['string', LocaleInterface::class])
            ->setNormalizer('default_locale', LazyOption::getOneBy($this->localeRepository, 'code'))
            ->setDefault('locales', function (Options $options): string {
                /** @var string $optLocales */
                $optLocales = $options['locales'];

                return $optLocales;
            })
            ->setAllowedTypes('locales', 'array')
            ->setDefault('base_currency', function (Options $options): CurrencyInterface {
                /** @var CurrencyInterface $randomCurrencies */
                $randomCurrencies = $this->faker->randomElement($options['currencies']);

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
            /** @var LocaleInterface $localeObj */
            $localeObj = $this->localeRepository->findOneBy(['code' => $locale]);
            $channel->addLocale($localeObj);
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
}
