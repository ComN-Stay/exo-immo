<?php

namespace App\Controller\Admin;

use App\Entity\Photos;
use App\Repository\PhotosRepository;
use App\Repository\PropertyRepository;
use Symfony\Component\HttpFoundation\Request;
use EasyCorp\Bundle\EasyAdminBundle\Context\AdminContext;
use EasyCorp\Bundle\EasyAdminBundle\Router\AdminUrlGenerator;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractCrudController;

class PhotosCrudController extends AbstractCrudController
{
    private $adminUrlGenerator;

    public function __construct(AdminUrlGenerator $adminUrlGenerator)
    {
        $this->adminUrlGenerator = $adminUrlGenerator;
    }

    public static function getEntityFqcn(): string
    {
        return Photos::class;
    }

    public function delete_photo(Request $request, PropertyRepository $propertyRepository, PhotosRepository $photosRepository)
    {
        $id = $request->query->get('entityId');
        $photo = $photosRepository->find($id);
        $property = $propertyRepository->find($photo->getProperty());
        $link = $this->getParameter('upload_photos_path');
        unlink($link . '/' . $photo->getFilename());
        $photosRepository->remove($photo, true);
        $url = $this->generateUrl('property_photos', ['uuid' => $property->getId()]);
        $this->addFlash('success', 'Photo supprimée !');
        return $this->redirect($url);
    }
}
