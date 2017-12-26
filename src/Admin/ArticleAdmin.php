<?php

namespace Afsy\Admin;

use Afsy\Tag\TagManager;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\CoreBundle\Validator\ErrorElement;

class ArticleAdmin extends AbstractAdmin
{
    /**
     * @var TagManager
     */
    protected $tagManager;
    protected $tagsData;

    protected $datagridValues = array(
        '_sort_by' => 'publishedAt', // field name
        '_sort_order' => 'DESC',
        '_page' => 1
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->with('General')
                ->add('title')
                ->add('markdownBody', 'textarea', array('required' => true, 'attr' => array('rows' => '12')))
            ->end()
            ->with('Options')
                ->add('language', 'choice', array('choices' => array('French' => 'fr', 'English' => 'en')))
                ->add('publishedAt', 'datetime')
                ->add('isPublished', null, array('required' => false))
                ->add('authors')
                ->add('tags_list', 'text', array('required' => true, 'attr' => array('class' => 'jquery-tag-it')))
            ->end()
            ->with('Location')
                ->add('city')
                ->add('eventUrl')
                ->add('eventDate', 'datetime')
                ->add('address')
                ->add('map')
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('isPublished')
            ->add('city')
            ->add('authors')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('isPublished')
            ->add('publishedAt')
            ->add('city')
            ->add('authors')
            ->add('_action', 'actions', array(
            'actions' => array(
                'preview' => array('template' => 'admin/article/preview.html.twig'),
            )
        ))
        ;
    }

    public function validate(ErrorElement $errorElement, $object)
    {
        $errorElement
            ->with('title')
                ->assertLength(array('max' => 255))
            ->end()
        ;
    }

    public function postPersist($article)
    {
        $this->saveTagging($article);
    }

    public function postUpdate($article)
    {
        $this->saveTagging($article);
    }

    /**
     * Custom method to translate the tags_list string to real tags
     * via the TagManager.
     *
     * @param $article
     */
    private function saveTagging($article)
    {
        $tagManager = $this->getTagManager();

        $tags = $tagManager->splitTagNames($article->getTagsList());
        $article->setTags($tagManager->loadOrCreateTags($tags));

        $tagManager->saveTagging($article);
    }

    public function setTagManager(TagManager $tagManager)
    {
        $this->tagManager = $tagManager;
    }

    public function getTagManager()
    {
        return $this->tagManager;
    }

    /**
     * {@inheritdoc}
     */
    public function getObject($id)
    {
        $object = $this->getModelManager()->find($this->getClass(), $id);
        $this->getTagManager()->loadTagging($object);

        return $object;
    }
}
