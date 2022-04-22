<?php

namespace App\Service;

use App\Repository\PictureRepository;
use Doctrine\ORM\EntityManagerInterface;

class PictureConverter
{

    //todo il faut que je récupère le fichier base 64 en bdd
    //todo il faut que je le convertisse
    //todo il faut que je déplace le résulatt de la convertion dans un dossier
    //todo il faut que je persiste le nom du fichier en BDD (dans le bon user)
    //todo il faut que je retourne en GET un groupe avec tout sauf le fichier BASE64
    //todo il faut que je crée ensuite le bon lien côté js


    public function index(PictureRepository $pictureRepository,EntityManagerInterface $doctrine)
    {
        // je récupère tous mes objets
        $imgs = $pictureRepository->findAll();
                
        //je boucle dessus et converti les images.
        foreach ($imgs as $obj) {

            $pictureFile = $obj->getPictureFile();

            if ($pictureFile) {
                //je ne fait rien sil y a déjà une valeur dans pictureFile
            } else {
                $id = $obj->getId();
                $img = $obj->getPicture();
                    
                $img = str_replace('data:image/jpeg;base64,', '', $img);
                $img = str_replace(' ', '+', $img);
                $data = base64_decode($img);
                $newFileName = uniqid() . '.jpeg';
                $file = "./assets/upload/pictures/" . $newFileName;
                $success = file_put_contents($file, $data);
                    
                $img = $obj->setPictureFile($newFileName);
    
                $doctrine->persist($img);
                $doctrine->flush();
            }
        }
    }
}