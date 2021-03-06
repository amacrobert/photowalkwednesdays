<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Datagrid\{ListMapper, DatagridMapper};
use Symfony\Component\Form\Extension\Core\Type\{TextType, FileType};
use DateTime;

class ImageAdmin extends AbstractAdmin {

    // Form fields
    protected function configureFormFields(FormMapper $formMapper) {

        $file_options = ['required' => false];
        if ($filename = $this->getSubject()->getFilename()) {
            $file_options['help'] = '
                <img src="' . $filename . '" style="width: 250px;" />
            ';
        }

        $formMapper
            ->add('file', FileType::class, $file_options)
        ;
    }

    public function upload($image) {
        if (!$image->getFile()) {
            return;
        }

        ///////////////////////////////
        // @TODO: Sanitize file here //
        ///////////////////////////////

        // Move image to image dir
        $image->getFile()->move(
            './images/uploads/',
            $image->getFile()->getClientOriginalName()
        );

        // set the path property to the filename where you've saved the file
        $image->setFilename('/images/uploads/' . $image->getFile()->getClientOriginalName());
    }

    public function getTemplate($name) {
        switch ($name) {
            case 'short_object_description':
                return 'Event/short_object_description.html.twig';
            default:
                return parent::getTemplate($name);
        }
    }


    public function prePersist($image) {
        $this->upload($image);
    }

    public function preUpdate($image) {
        $this->upload($image);
    }

    // Filter fields
    protected function configureDatagridFilters(DatagridMapper $datagridMapper) {
        $datagridMapper
        ;
    }

    // List view fields
    protected function configureListFields(ListMapper $listMapper) {
        $listMapper
            ->add('filename', 'string', ['template' => 'list_image.html.twig'])
        ;
    }
}
