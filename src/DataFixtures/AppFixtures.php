<?php

namespace App\DataFixtures;

use App\Entity\Categorie;
use App\Entity\Formation;
use App\Entity\Inscription;
use App\Entity\User;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\String\Slugger\SluggerInterface;

class AppFixtures extends Fixture
{

    //10 utilisateurs (5 formateurs et 5 apprenants)
    //5 catégories de formation (Développement web, Data Science, Design, Marketing, Gestion de projet)
    //15 formations variées avec différentes modalités et niveaux
    //25 inscriptions avec différents statuts

    public function __construct(
        private readonly UserPasswordHasherInterface $hasher,
        private readonly SluggerInterface $slugger,
    )
    {
    }

    public function load(ObjectManager $manager): void
    {
        $faker = Factory::create('fr_FR');
        $this->loadUsers($manager, $faker);
        $this->loadCategories($manager,$faker);
        $this->loadFormations($manager,$faker);
        $this->loadInscriptions($manager,$faker);

    }

    private function loadUsers(ObjectManager $manager, Generator $faker): void
    {
        for ($i = 0; $i < 10; $i++) {
            $user = new \App\Entity\User();
            $user->setEmail($faker->unique()->email());
            $user->setPrenom($faker->firstName());
            $user->setNom($faker->lastName());
            $user->setTelephone($faker->phoneNumber());
            $user->setDateNaissance($faker->dateTimeBetween('-60 years', '-18 years'));
            $user->setEntreprise($faker->company());
            $user->setPoste($faker->jobTitle());
            $user->setBio($faker->paragraph());
            $user->setDateCreation(new \DateTime());
            $user->setEstActif(true);

            // Définir les rôles
            if ($i < 5) {
                $user->setRoles(['ROLE_FORMATEUR']);
            } else {
                $user->setRoles(['ROLE_APPRENANT']);
            }

            // Hasher le mot de passe
            $hashedPassword = $this->hasher->hashPassword($user, 'password123');
            $user->setPassword($hashedPassword);

            $manager->persist($user);
        }

        $manager->flush();
    }

    private function loadCategories(ObjectManager $manager, Generator $faker): void
    {
        $categories = [
            ['Développement web','bi bi-browser-chrome'],
            ['Data Science', 'bi bi-graph-down'],
            ['Design', 'bi bi-brush'],
            ['Marketing', 'bi bi-cash-stack'],
            ['Gestion de projet', 'bi bi-calendar2-range']
        ];

        foreach ($categories as $c) {
            $category = new Categorie();
            $category->setNom($c[0]);
            $category->setSlug($this->slugger->slug($c[0])->lower());
            $category->setDescription($faker->paragraph());
            $category->setCouleur($faker->hexColor());
            $category->setIcone($c[1]);
            $manager->persist($category);
        }

        $manager->flush();
    }

    private function loadFormations(ObjectManager $manager, Generator $faker): void
    {

        $categories = $manager->getRepository(Categorie::class)->findAll();
        $formateurs = $manager->getRepository(User::class)->findAll();

        for ($i = 0; $i < 15; $i++) {
            $formation = new Formation();
            $formation->setDateCreation(new \DateTime());
            $formation->setTitre($faker->sentence(3));
            $formation->setSlug($this->slugger->slug($formation->getTitre())->lower());
            $formation->setDescription($faker->paragraph());
            $formation->setModalite($faker->randomElement(['En ligne', 'Présentiel', 'Hybride']));
            $formation->setNiveau($faker->randomElement(['Débutant', 'Intermédiaire', 'Avancé']));
            $formation->setDuree($faker->numberBetween(1, 12));
            $formation->setDateCreation(new \DateTime());
            $formation->setCapaciteMax($faker->numberBetween(4, 12));
            $formation->setPrix($faker->randomFloat(2, 10, 500));
            $formation->setCategorie($faker->randomElement($categories));
            $formation->setFormateur($faker->randomElement($formateurs));
            $formation->setEstPublie($faker->boolean());
            $formation->setDateDebut($faker->dateTimeBetween('+3 jours', '+3 mois'));
            $formation->setDateFin((clone $formation->getDateDebut())->modify('+' . $formation->getDuree() . ' weeks'));

            $manager->persist($formation);
        }
        $manager->flush();
    }

    private function loadInscriptions(ObjectManager $manager, Generator $faker): void
    {
        $formations = $manager->getRepository(Formation::class)->findAll();
        $apprenants = $manager->getRepository(User::class)->findByRole('ROLE_APPRENANT');

        for ($i = 0; $i < 25; $i++) {
            $inscription = new Inscription();
            $inscription->setDateInscription($faker->dateTimeBetween('-6 months', 'now'));
            $inscription->setStatut($faker->randomElement(['En attente', 'Confirmée', 'Annulée']));
            $inscription->setFormation($faker->randomElement($formations));
            $inscription->setApprenant($faker->randomElement($apprenants));
            $inscription->setModePaiement($faker->randomElement(['carte', 'Virement','Chèque']));

            $manager->persist($inscription);
        }

        $manager->flush();
    }

}
