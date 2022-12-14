<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Doctrine\DBAL\Connection;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;

class APIController extends AbstractController
{
    public function getAll(Connection $connection): JsonResponse
    {
        try{
            $users = $connection->fetchAllAssociative('SELECT * FROM user');

            return new JsonResponse($users);
        }catch(\Exception $e){
            return $this->getError($e, 400);
        }
    }

    public function getOne(int $idUser, Connection $connection): JsonResponse
    {
        try{
            $users = $connection->fetchAllAssociative('SELECT * FROM user where id_user = ?', [$idUser]);

            if(isset($users[0]) ){
                return new JsonResponse( $users[0] );
            }
            return new JsonResponse(null , 404);
        }catch(\Exception $e){
            return $this->getError($e, 400);
        }
    }
    public function post(Request $request,Connection $connection): JsonResponse
    {
        try{
            $input = json_decode($request->getContent(), true);
            unset($input['id_user']);
            $connection->insert('user',$input);
            return $this->getOne($connection->lastInsertId(),$connection);
        }catch(\Exception $e){
            return $this->getError($e, 400);
        }
    }
    public function put(int $idUser, Request $request,Connection $connection): JsonResponse
    {
        try{
            $input = json_decode($request->getContent(), true);
            unset($input['id_user']);
            $connection->update("user",  $input,["id_user"=> $idUser]);
            return $this->getOne($idUser,$connection);
        }catch(\Exception $e){
            return $this->getError($e, 400);
        }
    }
    public function delete(int $idUser,Connection $connection): Response
    {
        try{
            $connection->delete("user",["id_user"=> $idUser]);
            return new Response();
        }catch(\Exception $e){
            return $this->getError($e, 400);
        }
    }


    public function getError(\Exception $e, int $httpCode){
        return new JsonResponse((object)[
            'message' => $e->getMessage()
        ] , $httpCode);
    }


    public function getAllProducts(Request $request):JsonResponse{

        $host = $request->getSchemeAndHttpHost();

        $json =  [
            (object) [
               "id_product" => 1,
               "name" => "PC PORTABLE ASUS CHROMEBOOK C204",
               "price" => 545.00,
               "description" => "??cran 11.6\" HD - Processeur: Intel Celeron N4020 (1,10 GHz up to 2.80 GHz, 4 Mo de m??moire cache, Dual-Core) - Syst??me d'exploitation: Chrome OS - M??moire RAM: 4 Go LPDDR4 - Disque Dur: 32 Go eMMC - Carte Graphique: Intel UHD 600 Graphics avec Wi-Fi, Bluetooth, 3x USB 3.2 Gen 1 Type-A, 3x USB 3.2 Gen 1 Type-C, 1 x combo audio jack de 3,5 mm et lecteur de cartes Micro SD - Clavier Chiclet - Couleur: Gris fonc?? - Garantie: 1 an",
               "image" => $host."/images/c204ma-gj0203-10.jpg"
             ],
             (object) [
               "id_product" => 2,
               "name" => "APPLE MACBOOK AIR M2 (2022) 8GO 256GO SSD - MINUIT",
               "price" => 5059 ,
               "image" => $host."/images/mly33fn-a.jpg",
               "description" => "??cran 13.6\"  Liquid Retina LED IPS (2 560 x 1 664 pixels) - Processeur: Apple M2 (CPU 8 coeurs / GPU 8 coeurs / Neural Engine 16 coeurs) - Syst??me d'exploitation: MacOS - M??moire RAM: 8 Go - Disque Dur: 256 Go SSD - Carte Graphique: Intel HD Graphics avec Wi-Fi, Bluetooth, 2x Thunderbolt 4/USB-C, Prise Casque 3.5mm, Port de charge MagSafe 3 - Capteur Touch ID, Magic Keyboard r??tro??clair?? - Couleur: Minuit - Garantie: 1 an"
             ],
             (object) [
               "id_product" => 3,
               "name" => "SMARTPHONE EVERTEK M20S MINI 1GO 16GO - ROUGE",
               "price" =>  219 ,
               "description" => "??cran 5\" FWVGA 16:9 2.5D - Processeur: SC7731E 1.3G Quad Core - Syst??me d???exploitation: Android 10 (Go edition) - M??moire RAM: 1Go - Stockage: 16Go - Appareil Photo Arri??re : 5MP FF /Frontale: 2MP - WiFi 3G, Bluetooth - Batterie: 2000 mAH - Couleur: Rouge - Garantie: 1 an",
               "image" => $host."/images/010-02159-14_1723.jpg"
             ],
             (object) [
                 "id_product" => 4,
                 "name" => "IPHONE 11 64GO BLANC - APPLE",
                 "price" => 2389 ,
                 "description" => "Ecran 6,1\" LCD Retina IPS - R??solution: 828 x 1792 pixels - Processeur: Apple A13 Bionic Hexa-core (2x2.65 GHz Lightning + 4x1.8 GHz Thunder) - Syst??me d'exploitation: iOS - M??moire RAM: 4 Go - Stockage: 64Go - Appareil photo Arriere: Double: 12 M??gaPixel (Ouverture f/1.8, 26mm) + 12 M??gaPixels - Appareil Avant 12M??gaPixels Retina Flash avec Wifi, 4G et Bluetooth 5.0 - Batterie: lithium???ion - Enregistrement vid??o 4K - Temps de conversation Sans Fil: jusqu????? 17 heures - Class?? IP68 (profondeur maximale de 4 m??tres jusqu'?? 30 minutes) - Couleur: Blanc - Garantie: 1 an",
               "image" => $host."/images/010-02159-14_336.jpg"
             ],
             (object) [
                 "id_product" => 5,
                 "name" => "MONTRE CONNECT??E REZMAY Y20 - GOLD (REZMAY-Y20-GOLD)",
                 "price" => 159 ,
                 "description" => "Montre Connect??e REZMAY-Y20 - ??cran: 1.7\" - Syst??me d???exploitation: Android 4.4 et iOS 8.0- Connectivit??: Bluetooth - Batterie: 190 mAh - Autonomie en veille: jusqu'?? 7 jours - Dur??e d'utilisation: environ 3 - 4 jours - Temps de charge : environ 2 - 3 heures - Mati??re bracelet: Silicone - R??sistant ?? l'eau IP67 - Le REZMAY Y20  fournit un suivi sportif : suivi d'activit?? de toute la journ??e, chronom??tre, rapport de donn??es sportives - Sant??: moniteur de fr??quence cardiaque, moniteur de tension art??rielle, suivi du sommeil, respiration - Recevez des notifications de Smartphone, r??veil, m??t??o, contr??le de la cam??ra, contr??le de la musique, langue de police compl??te - Couleur: Gold - Garantie: 1 an",
               "image" => $host."/images/rezmay-y20-gold.jpg"
             ],
             (object) [
                 "id_product" => 6,
                 "name" => "IMPRIMANTE ?? R??SERVOIR INT??GR?? 3EN1 HP INK TANK 415 COULEUR WI-FI (Z4B53A)",
               "price" => 449 ,
               "description" => "Imprimante ?? R??servoir int??gr?? HP Ink Tank 415 - Fonctions: Impression, copier, Num??risation - Format Papier: A4 - Technologie d'impression: jet d'encre - Vitesse d'impression: Jusqu'?? 8 ppm (Noir), Jusqu'?? 5 ppm (couleur) - R??solution d'impression: 4800 x 1200 dpi(couleur), 1200 x 1200 dpi(noir) - R??solution de copie: Jusqu'?? 600 x 300 ppp - R??solution de num??risation: Jusqu'?? 1200 x 1200 ppp - Impression recto verso Manuelle - Connectivit??: Wi-Fi, USB - Couleur: Noir - Garantie: 1 an",
               "image" => $host."/images/hk12-frozen_507.jpg"
             ],
         ];
        
        return new JsonResponse( $json);
    }
}