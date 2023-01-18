<?php

namespace App\Entity;

use Doctrine\ORM\EntityManagerInterface;

class PropertySearch
{

    private $nom;
    private $anneeDepart;
    private $anneeFin;
    private $genre;
    private $avis;
    private $suivi;

    /**
     * Permet d'obtenir le nom servant comme paramètre à la recherche par nom
     *
     * @return ?string
     */
    public function getNom(): ?string
    {
        return $this->nom;
    }

    /**
     * Permet de définir la valeure du nom passé en paramètre
     *
     * @param string $nom le nom passé dans le filtre de la barre de recherche
     *
     * @return self
     */
    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    /**
     * Permet d'obtenir la valeure du filtre de l'année de départ
     *
     * @return ?int
     */
    public function getAnneeDepart(): ?int
    {
        return $this->anneeDepart;
    }

    /**
     * Permet de définir la valeure du filtre par année de départ
     *
     * @param int $anneeDepart la valeure du filtre de l'année de départ
     *
     * @return self
     */
    public function setAnneeDepart(int $anneeDepart): self
    {
        $this->anneeDepart = $anneeDepart;

        return $this;
    }

    /**
     * Permet d'obtenir la valeure du filtre par année de fin
     *
     * @return ?int
     */
    public function getAnneeFin(): ?int
    {
        return $this->anneeFin;
    }

    /**
     * Permet de définir l'année de fin servant de filtre
     *
     * @param int $anneeFin l'annee de fin passé en filtre
     *
     * @return self
     */
    public function setAnneeFin(int $anneeFin): self
    {
        $this->anneeFin = $anneeFin;

        return $this;
    }

    /**
     * Permet d'obtenir le genre passé en filtre
     *
     * @return ?string
     */
    public function getGenre(): ?string
    {
        return $this->genre;
    }

    /**
     * Permet de définir l'état du filtre genre de la barre de recherche
     *
     * @param string $genre le genre selectionner comme filtre pour la recherche
     *
     * @return self
     */
    public function setGenre(string $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    /**
     * Permet d'obtenir l'état du filtre avis de la barre de recherche
     *
     * @return ?string
     */
    public function getAvis(): ?string
    {
        return $this->avis;
    }

    /**
     * Permet de définir l'état du filtre des séries par rapport aux avis
     *
     * @param string $avis l'etat du filtre avis sélectionné
     *
     * @return self
     */
    public function setAvis(string $avis): self
    {
        $this->avis = $avis;

        return $this;
    }

    /**
     * Permet d'obtenir l'état du filtre suivi
     *
     * @return ?bool
     */
    public function getSuivi(): ?bool
    {
        return $this->suivi;
    }

    /**
     * Permet de changer l'état du filtre suivi de la barre de recherche
     *
     * @param bool $suivi vrai pour afficher les séries suivis par un utilisateur sinon faux
     *
     * @return self
     */
    public function setSuivi(bool $suivi): self
    {
        $this->suivi = $suivi;

        return $this;
    }

    /**
     * Permet de trier les séries par le genre sélectionner comme filtre dans la barre de recherche
     *
     * @param EntityManagerInterface $entityManager ll'accés à la base de données
     * @param ?string $genreFromForm le genre avec lequel on tri
     * @param array $toutesLesSeries la liste des séries
     *
     * @return array
     */
    public function triGenre(
        EntityManagerInterface $entityManager,
        ?string $genreFromForm,
        array $toutesLesSeries
        ): array
    {
        // si on a rentré un filtre de genre
        if (strlen($genreFromForm) > 0) {
            // tous les genres qui ont pour nom de genre $genreFromForm
            $genre = $entityManager
                ->getRepository(Genre::class)
                ->findBy(['name' => $genreFromForm])[0];
            // toutes les séries qui ont le bon genre
            $seriesByGenre = $genre->getSeries();

            // on ajoute toutes les séries qui ont le bon genre dans l'array
            $arrayGenre = array();
            foreach ($seriesByGenre as $serie) {
                array_push($arrayGenre, $serie);
            }
        } else {
            // sinon on sélectionne toutes les séries de la base
            $arrayGenre = $toutesLesSeries;
        }
        return $arrayGenre;
    }

    /**
     * Permet de trier la liste des séries en fonction du nom de la série
     *
     * @param ?string $nameFromForm le nom avec lequel on tri la liste des séries
     * @param array $toutesLesSeries la liste des séries
     *
     * @return array
     */
    public function triName(?string $nameFromForm, array $toutesLesSeries): array
    {
        // si on a rentré un filtre de nom
        if (strlen($nameFromForm) > 0) {
            $arrayName = array();
            // pour toutes les séries
            foreach ($toutesLesSeries as $serie) {
                // si le nom de la série commence par le nom $nameFromForm
                if (str_starts_with(strtolower($serie->getTitle()), strtolower($nameFromForm))) {
                    array_push($arrayName, $serie);
                }
            }
        } else {
            // sinon on sélectionne toutes les séries de la base
            $arrayName = $toutesLesSeries;
        }
        return $arrayName;
    }

    /**
     * Permet de trier la liste des séries par rapport à l'année de départ renseigné dans le filtre
     *
     * @param EntityManagerInterface $entityManager vrai pour afficher les séries suivis par un utilisateur sinon faux
     * @param ?int $anneeDepartFromForm l'année de départ avec laquelle on tri la liste des séries
     * @param array $toutesLesSeries la liste des séries
     *
     * @return array
     */
    public function triAnneeDepart(
        EntityManagerInterface $entityManager,
        ?int $anneeDepartFromForm,
        array $toutesLesSeries
        ): array
    {
        // si on a rentré un filtre "année après"
        if (strlen($anneeDepartFromForm) > 0) {
            // on crée un queryBuilder
            $queryBuilder = $entityManager
                ->getRepository(Series::class)
                ->createQueryBuilder('s');
            // on sélectionne toutes les séries qui ont une date de sortie après l'année $anneeDepartFromForm
            $queryBuilder
                ->where('s.yearStart >= :date')
                ->setParameter('date', $anneeDepartFromForm);
            $seriesByAnneeDebut = $queryBuilder->getQuery()->getResult();

            // on ajoute toutes les séries trouvées dans l'array
            $arrayAnneeDebut = array();
            foreach ($seriesByAnneeDebut as $serie) {
                array_push($arrayAnneeDebut, $serie);
            }
        } else {
            // sinon on sélectionne toutes les séries de la base
            $arrayAnneeDebut = $toutesLesSeries;
        }
        return $arrayAnneeDebut;
    }

    /**
     * Permet de trier la liste des films par rapport à l'année de fin renseigné dans le filtre
     *
     * @param EntityManagerInterface $entityManager vrai pour afficher les séries suivis par un utilisateur sinon faux
     * @param ?int $anneeFinFromForm l'année de fin avec laquelle on tri la liste des séries
     * @param array $toutesLesSeries la liste des séries
     *
     * @return array
     */
    public function triAnneeFin(
        EntityManagerInterface $entityManager,
        ?int $anneeFinFromForm,
        array $toutesLesSeries
        ): array
    {
        // si on a rentré un filtre "année avant"
        if (strlen($anneeFinFromForm) > 0) {
            // on crée un queryBuilder
            $queryBuilder = $entityManager
                ->getRepository(Series::class)
                ->createQueryBuilder('s');
            // on sélectionne toutes les séries qui ont une date de sortie avant l'année $anneeFinFromForm
            $queryBuilder
                ->where('s.yearStart <= :date')
                ->setParameter('date', $anneeFinFromForm);
            $seriesByAnneeFin = $queryBuilder->getQuery()->getResult();

            // on ajoute toutes les séries trouvées dans l'array
            $seriesAnneeFin = array();
            foreach ($seriesByAnneeFin as $serie) {
                array_push($seriesAnneeFin, $serie);
            }
        } else {
            // sinon on sélectionne toutes les séries de la base
            $seriesAnneeFin = $toutesLesSeries;
        }
        return $seriesAnneeFin;
    }

     /**
     * Permet de trier la liste des films par rapport à l'année de fin renseigné dans le filtre
     *
     * @param EntityManagerInterface $entityManager vrai pour afficher les séries suivis par un utilisateur sinon faux
     * @param ?string $avisFromForm l'avis de avec lequel on tri la liste des séries
     * @param array $toutesLesSeries la liste des séries
     *
     * @return array
     */
    public function triAvis(?string $avisFromForm, array $toutesLesSeries): array
    {
        $arrayAvis = array();
        // si on a rentré un filtre d'avis
        if (strlen($avisFromForm) > 0) {
            switch ($avisFromForm) {
                // si on cherche à trier dans une plage de note
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                    // pour toutes les séries de la base
                    foreach ($toutesLesSeries as $serie) {
                        // on calcule la moyenne des notes validées de cette série
                        $sum = 0;
                        $nbNotes = 0;
                        foreach ($serie->getRating() as $rate) {
                            $sum += $rate->getValue();
                            $nbNotes++;
                        }
                        if ($nbNotes != 0) {
                            $avg = $sum / $nbNotes;
                            // si on est dans la bonne plage, on ajoute la série à l'array
                            if ($avg >= $avisFromForm-1 && $avg <= $avisFromForm) {
                                array_push($arrayAvis, $serie);
                            }
                        }
                    }
                    break;
                // sinon on sélectionne toutes les séries de la base
                default:
                    $arrayAvis = $toutesLesSeries;
            }
        } else {
            // sinon on sélectionne toutes les séries de la base
            $arrayAvis = $toutesLesSeries;
        }
        return $arrayAvis;
    }

    /**
     * Permet de trier la liste des films par rapport aux avis croissant ou décroissant
     *
     * @param EntityManagerInterface $entityManager vrai pour afficher les séries suivis par un utilisateur sinon faux
     * @param ?string $avisFromForm l'avis de avec lequel on tri la liste des séries
     * @param array $arrayIntersect la liste de toutes les séries de tous les tris
     *
     * @return array
     */
    public function triCroissantDecroissant(
        EntityManagerInterface $entityManager,
        ?string $avisFromForm,
        array $arrayIntersect
        ): array
    {
        // si on a rentré un filtre d'avis
        if (strlen($avisFromForm) > 0) {
            // si on cherche à trier par ordre croissant ou décroissant
            if ($avisFromForm == 'ASC' || $avisFromForm == 'DESC') {
                $arrayRating = array();
                // pour toutes les séries de l'array finale
                foreach ($arrayIntersect as $serie) {
                    // on calcule la moyenne des notes validées de cette série
                    $sum = 0;
                    $nbNotes = 0;
                    foreach ($serie->getRating() as $rate) {
                        $sum += $rate->getValue();
                        $nbNotes++;
                    }
                    if ($nbNotes != 0) {
                        $avg = $sum / $nbNotes;
                        // on ajoute à l'array de tri la série associée à sa moyenne
                        $arrayRating[$serie->getId()] = $avg;
                    }
                }
                if ($avisFromForm == 'ASC') {
                    // si on cherche à trier par ordre croissant
                    asort($arrayRating);
                } else {
                    // si on cherche à trier par ordre décroissant
                    arsort($arrayRating);
                }
                // on ajoute à l'array de retour les séries
                $arrayIntersect = array();
                foreach ($arrayRating as $x => $x_value) {
                    array_push($arrayIntersect, $entityManager->getRepository(Series::class)->findBy(['id' => $x])[0]);
                }
            }
        }
        return $arrayIntersect;
    }

     /**
     * Permet de trier la liste des films par rapport au série suivi par l'utilisateur
     *
     * @param EntityManagerInterface $entityManager vrai pour afficher les séries suivis par un utilisateur sinon faux
     * @param ?bool $suiviFromForm l'avis de avec lequel on tri la liste des séries
     * @param array $arrayIntersect la liste de toutes les séries de tous les tris
     * @param ?int $id l'id de l'utilisateur
     *
     * @return array
     */
    public function triSuivi(
        EntityManagerInterface $entityManager,
        ?bool $suiviFromForm,
        array $arrayIntersect,
        ?int $id
        ): array
    {
        // si on a rentré un filtre de série suivie
        if ($suiviFromForm) {
            // l'utilisateur actuel
            $user = $entityManager->getRepository(User::class)->findBy(['id' => $id])[0];
            // les séries suivies de l'utilisateur actuel
            $seriesBySuivi = $user->getSeries();

            // pour toutes les séries suivies par l'utilisateur
            $arrayIntersect = array();
            foreach ($seriesBySuivi as $serie) {
                // on ajoute la série à l'array
                array_push($arrayIntersect, $serie);
            }
        }
        return $arrayIntersect;
    }
}
