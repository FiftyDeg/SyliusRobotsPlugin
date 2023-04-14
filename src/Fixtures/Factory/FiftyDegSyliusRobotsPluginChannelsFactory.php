<?php

declare(strict_types=1);

namespace FiftyDeg\SyliusRobotsPlugin\Fixtures\Factory;

use Sylius\Bundle\CoreBundle\Fixture\Factory\AbstractExampleFactory;
use Sylius\Component\Core\Formatter\StringInflector;
use Sylius\Component\Core\Model\ChannelInterface;
use Sylius\Component\Resource\Factory\FactoryInterface;
use Symfony\Component\OptionsResolver\Options;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Sylius\Component\Locale\Model\LocaleInterface;
use Sylius\Bundle\CoreBundle\Fixture\OptionsResolver\LazyOption;
use Sylius\Component\Resource\Repository\RepositoryInterface;
use Sylius\Component\Taxonomy\Repository\TaxonRepositoryInterface;
use Sylius\Component\Currency\Model\CurrencyInterface;
use Sylius\Component\Addressing\Model\ZoneInterface;

class FiftyDegSyliusRobotsPluginChannelsFactory extends AbstractExampleFactory
{
    /**
     * @var FactoryInterface
     */
    private $channelFactory;

    /**
     * @var \Faker\Generator
     */
    private $faker;

    /**
     * @var OptionsResolver
     */
    private $optionsResolver;

    public function __construct(FactoryInterface $channelFactory,
        private RepositoryInterface $localeRepository,
        private RepositoryInterface $currencyRepository,
        private RepositoryInterface $zoneRepository) {

        $this->channelFactory = $channelFactory;

        $this->faker = \Faker\Factory::create();

        $this->optionsResolver = new OptionsResolver();
        $this->configureOptions($this->optionsResolver);
    }

    protected function configureOptions(OptionsResolver $resolver): void
    {
        
        $resolver
            ->setDefault('name', function (Options $options): string {
                return $this->faker->sentence;
            })
            ->setDefault('code', function (Options $options): string {
                return StringInflector::nameToCode($options['name']);
            })
            ->setDefault('hostname', function (Options $options): string {
                return $options['hostname'];
            })
            ->setDefault('default_locale', function (Options $options): LocaleInterface {
                $x = $this->localeRepository->findOneBy(['code' => 'en_US']);
                return $this->localeRepository->findOneBy(['code' => $this->faker->randomElement($options['locales'])]);
            })

            ->setAllowedTypes('default_locale', ['string', LocaleInterface::class])
            ->setNormalizer('default_locale', LazyOption::getOneBy($this->localeRepository, 'code'))
            ->setDefault('locales', function (Options $options): string {
                return $options['locales'];
            })
            ->setAllowedTypes('locales', 'array')
            ->setDefault('base_currency', fn (Options $options): CurrencyInterface => $this->faker->randomElement($options['currencies']))
            ->setAllowedTypes('base_currency', ['string', CurrencyInterface::class])
            ->setNormalizer('base_currency', LazyOption::getOneBy($this->currencyRepository, 'code'))
            ->setDefault('currencies', LazyOption::all($this->currencyRepository))
            ->setAllowedTypes('currencies', 'array')
            ->setNormalizer('currencies', LazyOption::findBy($this->currencyRepository, 'code'))
            
        ;

    }

    public function create(array $options = []): ChannelInterface
    {
        $options = $this->optionsResolver->resolve($options);

        /** @var ChannelInterface $channel */
        $channel = $this->channelFactory->createNew();
        $channel->setCode($options['code']);
        $channel->setName($options['name']);
        $channel->setHostname($options['hostname']);
        $channel->setDefaultLocale($options['default_locale']);
        foreach ($options['locales'] as $locale) {
            $localeObj = $this->localeRepository->findOneBy(['code' => $locale]);
            $channel->addLocale($localeObj);
        }
        $channel->setBaseCurrency($options['base_currency']);
        foreach ($options['currencies'] as $currency) {
            $channel->addCurrency($currency);
        }

        return $channel;
    }
}