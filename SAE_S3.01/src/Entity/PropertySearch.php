<?php

namespace App\Entity;

class PropertySearch
{

    private $nom;
    private $anneeDepart;
    private $anneeFin;
    private $genre;
    private $avis;
   
    public function getNom(): ?string
    {
        return $this->nom;
    }
 
    public function setNom(string $nom): self
    {
        $this->nom = $nom    ;
 
        return $this;
    }

    public function getAnneeDepart(): ?int
    {
        return $this->anneeDepart;
    }
 
    public function setAnneeDepart(int $anneeDepart): self
    {
        $this->anneeDepart = $anneeDepart    ;
 
        return $this;
    }

    public function getAnneeFin(): ?int
    {
        return $this->anneeFin;
    }
 
    public function setAnneeFin(int $anneeFin): self
    {
        $this->anneeFin = $anneeFin    ;
 
        return $this;
    }

    public function getGenre(): ?string
    {
        return $this->genre;
    }
 
    public function setGenre(string $genre): self
    {
        $this->genre = $genre;
 
        return $this;
    }
    public function getAvis(): ?string
    {
         return $this->avis;
    }

    public function setAvis(string $avis): self
    {
        $this->avis = $avis;

        return $this;
    }


    public function triGenre($entityManager, $genreFromForm, $toutesLesSeries): array
    {
        if (strlen($genreFromForm) > 0) {
            $genre = $entityManager->getRepository(Genre::class)->findBy(['name' => $genreFromForm])[0];
            $seriesByGenre = $genre->getSeries();

            $arrayGenre = array();
            foreach ($seriesByGenre as $serie){
                array_push($arrayGenre, $serie);
            }
        } else {
            $arrayGenre = $toutesLesSeries;
        }
        return $arrayGenre;
    }

    public function triName($nameFromForm, $toutesLesSeries): array
    {
        if (strlen($nameFromForm) > 0) {
            $arrayName = array();
            foreach ($toutesLesSeries as $serie){
                if (str_contains($serie->getTitle(), $nameFromForm)) {
                    array_push($arrayName, $serie);
                }
            }
        } else {
            $arrayName = $toutesLesSeries;
        }
        return $arrayName;
    }

    public function triAnneeDepart($entityManager, $anneeDepartFromForm, $toutesLesSeries):array
    {
        $queryBuilder = $entityManager->getRepository(Series::class)->createQueryBuilder('s');

        if (strlen($anneeDepartFromForm) > 0) {
            $queryBuilder->where('s.yearStart >= :date')
                ->setParameter('date', $anneeDepartFromForm);
            $seriesByAnneeDebut = $queryBuilder->getQuery()->getResult();
            $arrayAnneeDebut = array();
            foreach ($seriesByAnneeDebut as $serie){
                array_push($arrayAnneeDebut, $serie);
            }
        } else {
            $arrayAnneeDebut = $toutesLesSeries;
        }
        return $arrayAnneeDebut;
    }

    public function triAnneeFin($entityManager, $anneeFinFromForm, $toutesLesSeries):array
    {
        $queryBuilder = $entityManager->getRepository(Series::class)->createQueryBuilder('s');
        if (strlen($anneeFinFromForm) > 0) {
            $queryBuilder->where('s.yearStart <= :date')
                ->setParameter('date', $anneeFinFromForm);

            $seriesByAnneeFin = $queryBuilder->getQuery()->getResult();

            $seriesAnneeFin = array();
            foreach ($seriesByAnneeFin as $serie){
                array_push($seriesAnneeFin, $serie);
            }
        } else {
            $seriesAnneeFin = $toutesLesSeries;
        }
        return $seriesAnneeFin;
    }

    public function triAvis($entityManager, $avisFromForm, $toutesLesSeries):array
    {
        $queryBuilder = $entityManager->getRepository(Series::class)->createQueryBuilder('s');

        if (strlen($avisFromForm) > 0) {
            switch ($avisFromForm) {
                case 1:
                case 2:
                case 3:
                case 4:
                case 5:
                    $queryBuilder->where('s.rating BETWEEN :rating-1 AND :rating+1')
                        ->setParameter('rating', $avisFromForm);
                    break;
                case 'ASC':
                    $queryBuilder->orderBy('s.rating', 'ASC');
                    break;
                case 'DESC':
                    $queryBuilder->orderBy('s.rating', 'DESC');
                    break;
            }
            $seriesByAvis = $queryBuilder->getQuery()->getResult();

            $arrayAvis = array();
            foreach($seriesByAvis as $serie){
                array_push($arrayAvis, $serie);
            }
        }
        else {
            $arrayAvis = $toutesLesSeries;
        }
        return $arrayAvis;
    }
    public function triCroissantDecroissant($entityManager, $avisFromForm, $arrayIntersect):array
    {
        if (strlen($avisFromForm) > 0) {
            if ($avisFromForm == 'ASC' || $avisFromForm == 'DESC') {
                $arrayRating = array();
                foreach($arrayIntersect as $serie){
                    $arrayRating[$serie->getId()] = $serie->getRating();
                }

                if ($avisFromForm == 'ASC') {
                    asort($arrayRating);
                }
                else {
                    arsort($arrayRating);
                }

                $arrayIntersect = array();
                foreach($arrayRating as $x=>$x_value) {
                    array_push($arrayIntersect, $entityManager->getRepository(Series::class)->findBy(['id' => $x])[0]);
                }
            }
        }
        return $arrayIntersect;
    }
}