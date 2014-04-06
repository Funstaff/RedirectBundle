<?php

namespace Funstaff\Bundle\RedirectBundle\Form\Type;

use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\OptionsResolver\OptionsResolverInterface;

/**
 * RedirectType.
 *
 * @author Bertrand Zuchuat <bertrand.zuchuat@gmail.com>
 */
class RedirectType extends AbstractType
{
    /**
     * @var string
     */
    private $class;

    /**
     * @var Symfony\Component\Translation\Translator
     */
    private $trans;

    /**
     * Constructor
     *
     * @param string classname
     * @param Symfony\Component\Translation\Translator $trans
     */
    public function __construct($class, $trans)
    {
        $this->class = $class;
        $this->trans = $trans;
    }

    /**
     * {@inheritdoc}
     */
    public function buildForm(FormBuilderInterface $builder, array $options)
    {
        $builder
            ->add('source', 'text')
            ->add('destination', 'text')
            ->add('statusCode', 'choice', array(
                'choices' => array(
                    301 => $this->trans->trans('status.301', array(), 'Redirect'),
                    307 => $this->trans->trans('status.307', array(), 'Redirect'),
                    308 => $this->trans->trans('status.308', array(), 'Redirect')
                )
            ))
            ->add('enabled', 'checkbox');
    }

    /**
     * {@inheritdoc}
     */
    public function setDefaultOptions(OptionsResolverInterface $resolver)
    {
        $resolver->setDefaults(array(
            'data_class' => $this->class,
            'intention'  => 'redirect'
        ));
    }

    /**
     * {@inheritdoc}
     */
    public function getName()
    {
        return 'funstaff_redirect';
    }
}