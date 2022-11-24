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
}