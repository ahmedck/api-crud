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
               "description" => "Écran 11.6\" HD - Processeur: Intel Celeron N4020 (1,10 GHz up to 2.80 GHz, 4 Mo de mémoire cache, Dual-Core) - Système d'exploitation: Chrome OS - Mémoire RAM: 4 Go LPDDR4 - Disque Dur: 32 Go eMMC - Carte Graphique: Intel UHD 600 Graphics avec Wi-Fi, Bluetooth, 3x USB 3.2 Gen 1 Type-A, 3x USB 3.2 Gen 1 Type-C, 1 x combo audio jack de 3,5 mm et lecteur de cartes Micro SD - Clavier Chiclet - Couleur: Gris foncé - Garantie: 1 an",
               "image" => $host."/images/c204ma-gj0203-10.jpg"
             ],
             (object) [
               "id_product" => 2,
               "name" => "APPLE MACBOOK AIR M2 (2022) 8GO 256GO SSD - MINUIT",
               "price" => 5059 ,
               "image" => $host."/images/mly33fn-a.jpg",
               "description" => "Écran 13.6\"  Liquid Retina LED IPS (2 560 x 1 664 pixels) - Processeur: Apple M2 (CPU 8 coeurs / GPU 8 coeurs / Neural Engine 16 coeurs) - Système d'exploitation: MacOS - Mémoire RAM: 8 Go - Disque Dur: 256 Go SSD - Carte Graphique: Intel HD Graphics avec Wi-Fi, Bluetooth, 2x Thunderbolt 4/USB-C, Prise Casque 3.5mm, Port de charge MagSafe 3 - Capteur Touch ID, Magic Keyboard rétroéclairé - Couleur: Minuit - Garantie: 1 an"
             ],
             (object) [
               "id_product" => 3,
               "name" => "SMARTPHONE EVERTEK M20S MINI 1GO 16GO - ROUGE",
               "price" =>  219 ,
               "description" => "Écran 5\" FWVGA 16:9 2.5D - Processeur: SC7731E 1.3G Quad Core - Systéme d’exploitation: Android 10 (Go edition) - Mémoire RAM: 1Go - Stockage: 16Go - Appareil Photo Arriére : 5MP FF /Frontale: 2MP - WiFi 3G, Bluetooth - Batterie: 2000 mAH - Couleur: Rouge - Garantie: 1 an",
               "image" => $host."/images/010-02159-14_1723.jpg"
             ],
             (object) [
                 "id_product" => 4,
                 "name" => "IPHONE 11 64GO BLANC - APPLE",
                 "price" => 2389 ,
                 "description" => "Ecran 6,1\" LCD Retina IPS - Résolution: 828 x 1792 pixels - Processeur: Apple A13 Bionic Hexa-core (2x2.65 GHz Lightning + 4x1.8 GHz Thunder) - Systéme d'exploitation: iOS - Mémoire RAM: 4 Go - Stockage: 64Go - Appareil photo Arriere: Double: 12 MégaPixel (Ouverture f/1.8, 26mm) + 12 MégaPixels - Appareil Avant 12MégaPixels Retina Flash avec Wifi, 4G et Bluetooth 5.0 - Batterie: lithium‑ion - Enregistrement vidéo 4K - Temps de conversation Sans Fil: jusqu’à 17 heures - Classé IP68 (profondeur maximale de 4 mètres jusqu'à 30 minutes) - Couleur: Blanc - Garantie: 1 an",
               "image" => $host."/images/010-02159-14_336.jpg"
             ],
             (object) [
                 "id_product" => 5,
                 "name" => "MONTRE CONNECTÉE REZMAY Y20 - GOLD (REZMAY-Y20-GOLD)",
                 "price" => 159 ,
                 "description" => "Montre Connectée REZMAY-Y20 - Écran: 1.7\" - Système d’exploitation: Android 4.4 et iOS 8.0- Connectivité: Bluetooth - Batterie: 190 mAh - Autonomie en veille: jusqu'à 7 jours - Durée d'utilisation: environ 3 - 4 jours - Temps de charge : environ 2 - 3 heures - Matière bracelet: Silicone - Résistant à l'eau IP67 - Le REZMAY Y20  fournit un suivi sportif : suivi d'activité de toute la journée, chronomètre, rapport de données sportives - Santé: moniteur de fréquence cardiaque, moniteur de tension artérielle, suivi du sommeil, respiration - Recevez des notifications de Smartphone, réveil, météo, contrôle de la caméra, contrôle de la musique, langue de police complète - Couleur: Gold - Garantie: 1 an",
               "image" => $host."/images/rezmay-y20-gold.jpg"
             ],
             (object) [
                 "id_product" => 6,
                 "name" => "IMPRIMANTE À RÉSERVOIR INTÉGRÉ 3EN1 HP INK TANK 415 COULEUR WI-FI (Z4B53A)",
               "price" => 449 ,
               "description" => "Imprimante à Réservoir intégré HP Ink Tank 415 - Fonctions: Impression, copier, Numérisation - Format Papier: A4 - Technologie d'impression: jet d'encre - Vitesse d'impression: Jusqu'à 8 ppm (Noir), Jusqu'à 5 ppm (couleur) - Résolution d'impression: 4800 x 1200 dpi(couleur), 1200 x 1200 dpi(noir) - Résolution de copie: Jusqu'à 600 x 300 ppp - Résolution de numérisation: Jusqu'à 1200 x 1200 ppp - Impression recto verso Manuelle - Connectivité: Wi-Fi, USB - Couleur: Noir - Garantie: 1 an",
               "image" => $host."/images/hk12-frozen_507.jpg"
             ],
         ];
        
        return new JsonResponse( $json);
    }
}