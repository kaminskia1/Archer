<?php
namespace App\Admin\Field;

use EasyCorp\Bundle\EasyAdminBundle\Contracts\Field\FieldInterface;
use EasyCorp\Bundle\EasyAdminBundle\Field\FieldTrait;
use Symfony\Component\Form\Extension\Core\Type\DateIntervalType;

/**
 * Class DateIntervalField
 * @package App\Admin\Field
 */
final class DateIntervalField implements FieldInterface
{
    /**
     * Import FieldTrait
     */
    use FieldTrait;

    /**
     * Field constructor
     *
     * @param string $propertyName
     * @param string|null $label
     * @return static
     */
    public static function new(string $propertyName, ?string $label = null): self
    {
        return (new self())
            ->setProperty($propertyName)
            ->setLabel($label)
            // this template is used in 'index' and 'detail' pages
            ->setTemplatePath('admin/dateinterval.html.twig')
            // this is used in 'edit' and 'new' pages to edit the field contents
            // you can use your own form types too
            //            ->setFormType(DateIntervalType::class)
            //            ->addCssClass('field-datetime')
            //            // these methods allow to define the web assets loaded when the
            //            // field is displayed in any CRUD page (index/detail/edit/new)
            ->addCssFiles('js/admin/field-datetime.css')
            ->addJsFiles('js/admin/field-datetime.js')
            ;
    }
}