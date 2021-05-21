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

class CommerceCheckoutFormType extends AbstractType
{

    use CommerceTraitModel;

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
                            'data'=>$data->getCommercePackage()
                        ])
                        ->add('commercePackageDurationToPriceID', ChoiceType::class, [
                            'choices' => $data->getCommercePackage()->getFormattedDurationToPrice(),
                            'expanded'=>true,
                            'choice_attr' => function($s){
                                return ['class'=>'col-1'];
                            }
                        ]);
                });
        }

        // form stage 2 (checkout before gateway instance chosen)
        elseif ($options['form_stage'] == 1)
        {
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
                            'placeholder' => "Payment Method"
                        ]);
                });

        }
        elseif ($options['form_stage'] == 2)
        {
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
                            'placeholder' => "Payment Method"
                        ]);

                    $fields = $data
                        ->getCommerceGatewayInstance()
                        ->getCommerceGatewayType()
                        ->getClassInstance()
                        ->getFormFields();

                    foreach ($fields as $element)
                    {
                       $form->add("gateway__" . $element->title, $element->type, array_merge($element->options, [
                           'mapped'=>false
                       ]));
                    }
                    $form
                        ->add('confirm', HiddenType::class, [
                            'data'=>true,
                            'mapped' => false
                        ])
                        ->add('submit', SubmitType::class);
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
