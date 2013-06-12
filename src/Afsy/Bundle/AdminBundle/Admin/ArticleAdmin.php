<?php

namespace Afsy\Bundle\AdminBundle\Admin;

use Sonata\AdminBundle\Admin\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Validator\ErrorElement;
use Sonata\AdminBundle\Form\FormMapper;

class ArticleAdmin extends Admin
{
    protected $tagManager;
    protected $tagsData;

    protected $datagridValues = array(
        '_sort_order' => 'desc', // sort direction
        '_sort_by' => 'published_at' // field name
    );

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
            ->add('title')
            ->add('body', null, array('required' => false, 'attr' => array('class' => 'hidden html-body')))
            ->add('markdown_body', null, array('required' => false, 'attr' => array('class' => 'hidden markdown-body')))
            ->with('Options', array('collapsed' => true))
                ->add('language', 'choice', array('choices' => array('fr' => 'French', 'en' => 'English')))
                ->add('published_at')
                ->add('is_published', null, array('required' => false))
                ->add('authors')
                ->add('city')
                ->add('event_url')
                ->add('map')
                ->add('tags_list', 'text', array('required' => true, 'attr' => array('class' => 'jquery-tag-it')))
            ->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('title')
            ->add('is_published')
            ->add('city')
            ->add('authors')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->addIdentifier('title')
            ->add('is_published')
            ->add('published_at')
            ->add('city')
            ->add('authors')
            ->add('_action', 'actions', array(
            'actions' => array(
                'preview' => array('template' => 'AfsyAdminBundle:Article:preview.html.twig'),
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

    /**
     * set the tag manager
     *
     * @param string $tagManager the tag manager
     *
     * @return void
     */
    public function setTagManager($tagManager)
    {
        $this->tagManager = $tagManager;
    }

    /**
     * Returns the tag manager
     *
     * @return string the tag manager
     */
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

    public function getTemplate($name)
    {
        switch ($name) {
            case 'edit':
                return 'AfsyAdminBundle:Article:edit.html.twig';
                break;
            default:
                return parent::getTemplate($name);
                break;
        }
    }
}
