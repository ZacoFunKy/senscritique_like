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
    public function triGenre(EntityManagerInterface $entityManager, ?string $genreFromForm, array $toutesLesSeries): array
    {
        if (strlen($genreFromForm) > 0) {
            $genre = $entityManager->getRepository(Genre::class)->findBy(['name' => $genreFromForm])[0];
            $seriesByGenre = $genre->getSeries();

            $arrayGenre = array();
            foreach ($seriesByGenre as $serie) {
                array_push($arrayGenre, $serie);
            }
        } else {
            $arrayGenre = $toutesLesSeries;
        }
        return $arrayGenre;
    }

    /**
     * Permet de trier la liste des séries en fonction du nom de la série
     *
     * @param ?string $nameFromForm le nom avec lequel on tri la liste des séries
     * @param array $toutesLesSeries
     *
     * @return array
     */
    public function triName(?string $nameFromForm, array $toutesLesSeries): array
    {
        if (strlen($nameFromForm) > 0) {
            $arrayName = array();
            foreach ($toutesLesSeries as $serie) {
                if (str_starts_with($serie->getTitle(), $nameFromForm)) {
                    array_push($arrayName, $serie);
                }
            }
        } else {
            $arrayName = $toutesLesSeries;
        }
        return $arrayName;
    }

    /**
     * Permet de trier la liste des séries par rapport à l'année de départ renseigné dans le filtre
     *
     * @param bool $suivi vrai pour afficher les séries suivis par un utilisateur sinon faux
     *
     * @return array
     */
    public function triAnneeDepart($entityManager, $anneeDepartFromForm, $toutesLesSeries): array
    {
        $queryBuilder = $entityManager->getRepository(Series::class)->createQueryBuilder('s');

        if (strlen($anneeDepartFromForm) > 0) {
            $queryBuilder->where('s.yearStart >= :date')
                ->setParameter('date', $anneeDepartFromForm);
            $seriesByAnneeDebut = $queryBuilder->getQuery()->getResult();
            $arrayAnneeDebut = array();
            foreach ($seriesByAnneeDebut as $serie) {
                array_push($arrayAnneeDebut, $serie);
            }
        } else {
            $arrayAnneeDebut = $toutesLesSeries;
        }
        return $arrayAnneeDebut;
    }

    /**
     * Permet de trier la liste des films par rapport à l'année de fin renseigné dans le filtre
     *
     * @param bool $suivi vrai pour afficher les séries suivis par un utilisateur sinon faux
     *
     * @return array
     */
    public function triAnneeFin($entityManager, $anneeFinFromForm, $toutesLesSeries): array
    {
        $queryBuilder = $entityManager->getRepository(Series::class)->createQueryBuilder('s');
        if (strlen($anneeFinFromForm) > 0) {
            $queryBuilder->where('s.yearStart <= :date')
                ->setParameter('date', $anneeFinFromForm);

            $seriesByAnneeFin = $queryBuilder->getQuery()->getResult();

            $seriesAnneeFin = array();
            foreach ($seriesByAnneeFin as $serie) {
                array_push($seriesAnneeFin, $serie);
            }
        } else {
            $seriesAnneeFin = $toutesLesSeries;
        }
        return $seriesAnneeFin;
    }

     /**
     * Permet de trier la liste des films par rapport à l'année de fin renseigné dans le filtre
     *
     * @param bool $suivi vrai pour afficher les séries suivis par un utilisateur sinon faux
     *
     * @return array
     */
    public function triAvis($entityManager, $avisFromForm, $toutesLesSeries): array
    {
        $queryBuilder = $entityManager->getRepository(Rating::class)->createQueryBuilder('r');

        if (strlen($avisFromForm) > 0) {
            switch ($avisFromForm) {
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                    $queryBuilder->where('r.value BETWEEN :rating-1 AND :rating')
                        ->setParameter('rating', $avisFromForm);
                    break;
                case 'ASC':
                    $queryBuilder->orderBy('r.value', 'ASC');
                    break;
                case 'DESC':
                    $queryBuilder->orderBy('r.value', 'DESC');
                    break;
                default:
                    break;
            }
            $ratingByvalue = $queryBuilder->getQuery()->getResult();

            $seriesByAvis = array();
            foreach ($ratingByvalue as $serie) {
                array_push($seriesByAvis, $serie->getSeries());
            }

            $arrayAvis = array();
            foreach ($seriesByAvis as $serie) {
                array_push($arrayAvis, $serie);
            }
        } else {
            $arrayAvis = $toutesLesSeries;
        }
        return $arrayAvis;
    }

    public function triCroissantDecroissant($entityManager, $avisFromForm, $arrayIntersect): array
    {
        if (strlen($avisFromForm) > 0) {
            if ($avisFromForm == 'ASC' || $avisFromForm == 'DESC') {
                $arrayRating = array();
                foreach ($arrayIntersect as $serie) {
                    $rating = $entityManager->getRepository(Rating::class)->findBy(['series' => $serie])[0];
                    $arrayRating[$serie->getId()] = $rating->getValue();
                }

                if ($avisFromForm == 'ASC') {
                    asort($arrayRating);
                } else {
                    arsort($arrayRating);
                }

                $arrayIntersect = array();
                foreach ($arrayRating as $x => $x_value) {
                    array_push($arrayIntersect, $entityManager->getRepository(Series::class)->findBy(['id' => $x])[0]);
                }
            }
        }
        return $arrayIntersect;
    }

    public function triSuivi($entityManager, $suiviFromForm, $arrayIntersect, $id): array
    {
        if ($suiviFromForm) {
            $user = $entityManager->getRepository(User::class)->findBy(['id' => $id])[0];
            $seriesBySuivi = $user->getSeries();

            $arrayIntersect = array();
            foreach ($seriesBySuivi as $serie) {
                array_push($arrayIntersect, $serie);
            }
        }
        return $arrayIntersect;
    }
}
