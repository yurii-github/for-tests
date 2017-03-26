<?php
namespace Yurii\OroBundle\Translation;

use Symfony\Component\Translation\TranslatorBagInterface;
use Symfony\Component\Translation\TranslatorInterface;

/**
 * Decorates Translator
 * Replaces default domain 'messages'
 *
 * @author Yurii K.
 */
class DecoratedTranslator implements TranslatorInterface, TranslatorBagInterface
{

    /** @var TranslatorBagInterface|TranslatorInterface */
    protected $translator;

    /**
     * replaces 'messages' default domain to 'example'
     *
     * @param string $domain            
     * @return string default domain
     */
    protected function defaultDomain($domain)
    {
        // TODO: make as config from current domain
        return $domain == 'messages' ? 'example' : $domain;
    }

    /**
     *
     * @param TranslatorInterface|TranslatorBagInterface $translator            
     */
    public function __construct($translator)
    {
        $this->translator = $translator;
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Translation\TranslatorInterface::trans()
     */
    public function trans($id, array $parameters = [], $domain = null, $locale = null)
    {
        return $this->translator->trans($id, $parameters, $this->defaultDomain($domain), $locale);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Translation\TranslatorInterface::transChoice()
     */
    public function transChoice($id, $number, array $parameters = [], $domain = null, $locale = null)
    {
        return $this->translator->transChoice($id, $number, $parameters, $this->defaultDomain($domain), $locale);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Translation\TranslatorInterface::setLocale()
     */
    public function setLocale($locale)
    {
        $this->translator->setLocale($locale);
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Translation\TranslatorInterface::getLocale()
     */
    public function getLocale()
    {
        return $this->translator->getLocale();
    }

    /**
     *
     * {@inheritdoc}
     *
     * @see \Symfony\Component\Translation\TranslatorBagInterface::getCatalogue()
     */
    public function getCatalogue($locale = null)
    {
        return $this->translator->getCatalogue($locale);
    }
}