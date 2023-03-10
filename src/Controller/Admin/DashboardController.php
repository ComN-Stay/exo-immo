<?php

namespace App\Controller\Admin;

use App\Entity\Team;
use App\Entity\User;
use App\Entity\Photos;
use App\Entity\Property;
use App\Entity\TypeBien;
use App\Entity\TypeTransaction;
use Doctrine\ORM\EntityManager;
use App\Repository\UserRepository;
use App\Repository\PhotosRepository;
use App\Repository\PropertyRepository;
use App\Repository\TypeBienRepository;
use Doctrine\ORM\EntityManagerInterface;
use App\Repository\TypeTransactionRepository;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use EasyCorp\Bundle\EasyAdminBundle\Config\Assets;
use EasyCorp\Bundle\EasyAdminBundle\Config\MenuItem;
use EasyCorp\Bundle\EasyAdminBundle\Config\UserMenu;
use EasyCorp\Bundle\EasyAdminBundle\Config\Dashboard;
use Symfony\Component\String\Slugger\SluggerInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use EasyCorp\Bundle\EasyAdminBundle\Controller\AbstractDashboardController;

#[Route('/admin')]
class DashboardController extends AbstractDashboardController
{
    private $typeTransactionRepository;
    private $typeBienRepository;
    private $userRepository;
    private $propertyRepository;
    private $em;

    public function __construct(
        TypeBienRepository $typeBienRepository,
        TypeTransactionRepository $typeTransactionRepository,
        UserRepository $userRepository,
        PropertyRepository $propertyRepository,
        EntityManagerInterface $em
    ) {
        $this->typeBienRepository = $typeBienRepository;
        $this->typeTransactionRepository = $typeTransactionRepository;
        $this->userRepository = $userRepository;
        $this->propertyRepository = $propertyRepository;
        $this->em = $em;
    }

    #[Route('/', name: 'admin_dashboard')]
    public function index(): Response
    {
        $transactions = $this->typeTransactionRepository->findAll();
        $types = $this->typeBienRepository->findAll();
        return $this->render('admin/dashboard.html.twig', [
            'transactions' => $transactions,
            'types' => $types
        ]);
    }

    #[Route('/property_photos', name: 'property_photos')]
    public function showPropertyPhotos(Request $request, PhotosRepository $photoRepository, PropertyRepository $propertyRepository, SluggerInterface $slugger): Response
    {
        $property_id = $request->get('uuid');
        $property = $propertyRepository->find($property_id);

        if ($request->files->get('photo') !== null) {
            $file = $request->files->get('photo');
            $originalFilename = pathinfo($file->getClientOriginalName(), PATHINFO_FILENAME);
            $safeFilename = $slugger->slug($originalFilename);
            $newFilename = $safeFilename . '-' . uniqid() . '.' . $file->guessExtension();
            $path = $this->getParameter('upload_photos_path');
            $file->move(
                $path,
                $newFilename
            );
            $newPhoto = new Photos();
            $newPhoto->setFilename($newFilename);
            $newPhoto->setTitle($request->request->get('title'));
            $newPhoto->setProperty($property);
            $this->em->persist($newPhoto);
            $this->em->flush();
        }

        $photos = $photoRepository->findBy(['property' => $property_id]);
        return $this->render('admin/photos.html.twig', [
            'photos' => $photos,
            'property' => $property
        ]);
    }

    public function configureDashboard(): Dashboard
    {
        return Dashboard::new()
            ->setTitle('Exo Symfony')
            ->renderContentMaximized()
            ->setLocales(['fr', 'en']);
    }

    public function configureMenuItems(): iterable
    {
        $nbBiens = $this->propertyRepository->findAll();
        $nbClients = $this->userRepository->findAll();
        yield MenuItem::linkToDashboard('Dashboard', 'fa fa-home');
        yield MenuItem::linkToCrud('Gestion clients', 'fas fa-users', User::class)
            ->setBadge(count($nbClients), 'success');
        yield MenuItem::linkToCrud('Gestion des biens', 'fas fa-house-user', Property::class)
            ->setBadge(count($nbBiens), 'success');
        yield MenuItem::linkToCrud('Gestion ??quipe', 'fas fa-user-friends', Team::class);
        yield MenuItem::linkToCrud('Types de biens', 'fas fa-list', TypeBien::class);
        yield MenuItem::linkToCrud('Types de transactions', 'fas fa-list', TypeTransaction::class);
    }

    public function configureUserMenu(UserInterface $user): UserMenu
    {
        return UserMenu::new()
            ->setName($user->getFirstname())
            ->displayUserName(true)
            ->displayUserAvatar(false)
            ->setGravatarEmail($user->getEmail())
            ->addMenuItems([
                MenuItem::section(),
                MenuItem::linkToLogout('Logout', 'fa fa-sign-out'),
            ]);;
    }

    public function configureAssets(): Assets
    {
        return parent::configureAssets()
            ->addWebpackEncoreEntry('app');
    }
}
