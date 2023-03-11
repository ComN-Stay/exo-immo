<?php

namespace App\EventSubscriber;

use App\Entity\Property;
use App\Repository\PhotosRepository;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use EasyCorp\Bundle\EasyAdminBundle\Event\BeforeEntityDeletedEvent;

class EasyAdminSubscriber implements EventSubscriberInterface
{
    private $photosRepository;

    public function __construct(PhotosRepository $photosRepository)
    {
        $this->photosRepository = $photosRepository;
    }

    public static function getSubscribedEvents()
    {
        return [
            BeforeEntityDeletedEvent::class => ['deletePhotos'],
        ];
    }

    public function deletePhotos(BeforeEntityDeletedEvent $event, $uploadPath)
    {
        $entity = $event->getEntityInstance();

        if (!($entity instanceof Property)) {
            return;
        }
        dd($entity);
        $id = $entity->getId();
        $photos = $this->photosRepository->findBy(['property' => $id]);
        foreach ($photos as $photo) {
            $path = $this->getParameter('upload_photos_path') . '/' . $photo->getFilename();
            dd($path);
            @unlink($path);
        }
    }
}
