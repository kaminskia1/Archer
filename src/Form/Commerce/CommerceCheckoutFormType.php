<?php

namespace App\Form\Commerce;

use App\Entity\Commerce\CommerceGatewayInstance;
use App\Entity\Commerce\CommerceInvoice;
use App\Entity\Commerce\CommercePackage;
use App\Form\Type\EntityHiddenType;
use App\Model\CommerceTraitModel;
use Symfony\Bridge\Doctrine\Form\Type\EntityType;
use Symfony\Component\Form\AbstractType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Symfony\Component\Form\Extension\Core\Type\SubmitType;
use Symfony\Component\Form\FormBuilderInterface;
use Symfony\Component\Form\FormEvent;
use Symfony\Component\Form\FormEvents;
use Symfony\Component\OptionsResolver\OptionsResolver;
use Symfony\Component\Security\Core\Security;

class CommerceCheckoutFormType extends AbstractType
{

    use CommerceTraitModel;

    private $security;

    public function __construct(Security $security)
    {
        $this->security = $security;
    }

    public function buildForm(FormBuilderInterface $builder, array $options)
    {

        // form stage 1 (product page)
        if ($options['form_stage'] == 0) {
            $builder
                ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $form
                        ->add('commercePackage', EntityHiddenType::class, [
                            'class' => CommercePackage::class,
                            'data' => $data->getCommercePackage()
                        ])
                        ->add('commercePackageDurationToPriceID', ChoiceType::class, [
                            'choices' => ($this->security->getUser()->hasRole('ROLE_SELLER')) ? array_merge(
                                $data->getCommercePackage()->getFormattedSubscriptionPrices(),
                                $data->getCommercePackage()->getFilteredFormattedLicensePrices()
                            ) : $data->getCommercePackage()->getFormattedSubscriptionPrices(),
                            'expanded' => true,
                            'choice_attr' => function ($s) {
                                return ['class' => 'col-1'];
                            }
                        ]);
                });
        }

        // form stage 2 (checkout before gateway instance chosen)
        elseif ($options['form_stage'] == 1) {
            $builder
                ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $form
                        ->add('commercePackage', EntityHiddenType::class, [
                            'class' => CommercePackage::class,
                            'data' => $data->getCommercePackage()
                        ])
                        ->add('commercePackageDurationToPriceID', HiddenType::class)
                        ->add('commerceGatewayInstance', EntityType::class, [
                            'class' => CommerceGatewayInstance::class,
                            'placeholder' => "Payment Method",
                            'expanded' => true,
                            'choice_attr' => function ($s) {
                                return ['class' => 'col-1'];
                            }
                        ]);
                });

        }

        // form stage 3 (gateway instance chosen)
        elseif ($options['form_stage'] == 2) {
            $builder
                ->addEventListener(FormEvents::PRE_SET_DATA, function (FormEvent $event) {
                    $form = $event->getForm();
                    $data = $event->getData();
                    $form
                        ->add('commercePackage', EntityHiddenType::class, [
                            'class' => CommercePackage::class,
                            'data' => $data->getCommercePackage()
                        ])
                        ->add('commercePackageDurationToPriceID', HiddenType::class)
                        ->add('commerceGatewayInstance', EntityType::class, [
                            'class' => CommerceGatewayInstance::class,
                            'placeholder' => "Payment Method",
                            'expanded' => true,
                            'choice_attr' => function ($s) {
                                return ['class' => 'col-1'];
                            }
                        ]);

                    $fields = $data
                        ->getCommerceGatewayInstance()
                        ->getCommerceGatewayType()
                        ->getClassInstance()
                        ->getFormFields();

                    foreach ($fields as $element) {
                        $form->add("gateway__" . $element->title, $element->type, array_merge($element->options, [
                            'mapped' => false,
                            'row_attr' => ['class' => 'gateway_field_row']
                        ]));
                    }
                    $form
                        ->add('confirm', HiddenType::class, [
                            'data' => true,
                            'mapped' => false
                        ])
                        ->add('submit', SubmitType::class, [
                            'label' => 'Continue',
                            'validate' => true,
                            'attr' => ['class' => 'btn btn-primary']
                        ]);
                });
        }
    }

    public function configureOptions(OptionsResolver $resolver)
    {
        $resolver->setDefaults([
            'form_stage' => 0,
            'data_class' => CommerceInvoice::class,
        ]);

        $resolver->setAllowedTypes('form_stage', 'integer');
    }
}
