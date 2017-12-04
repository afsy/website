<?php

namespace Afsy\Bundle\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Form\Type\BooleanType;
use Sonata\CoreBundle\Validator\ErrorElement;

class AuthorAdmin extends AbstractAdmin
{
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('name')
                   ->add('city', 'text', array('required' => false))
                   ->add('email')
                   ->add('isEnabled', BooleanType::class, array('required' => false, 'transform' => true));
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('name')
                       ->add('email');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('name')
                   ->add('email')
                   ->add('isEnabled');
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement->with('name')
                     ->assertLength(array('max' => 255))
                     ->end();
    }
}
