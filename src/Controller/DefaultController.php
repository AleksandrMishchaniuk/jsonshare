<?php

namespace App\Controller;

use App\Entity\Hash;
use App\Entity\Json;
use App\Repository\JsonRepository;
use DateTime;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class DefaultController extends AbstractController
{
    /**
     * @Route("/{id<\d+>?}/{hash<\w+>?}", name="main", methods={"GET"})
     * @param int|null $id
     * @param null|string $hash
     * @param JsonRepository $repository
     * @return Response
     */
    public function index(?int $id, ?string $hash, JsonRepository $repository)
    {
        $json = $hashItem = null;
        if ($id && $hash) {
            $json = $repository->findByIdAndHash($id, $hash);
        }
        if ($id && !$json) {
            return $this->redirectToRoute('main');
        }

        if ($json) {
            foreach ($json->getHashes() as $hashObj) {
                if ($hashObj->getHash() == $hash) {
                    $hashItem = $hashObj;
                    break;
                }
            }
        }
        return $this->render('default/index.html.twig', [
            'json' => $json,
            'hash' => $hashItem
        ]);
    }

    /**
     * @Route("/create", name="create", methods={"POST"})
     * @param Request $request
     * @return Response
     */
    public function createJson(Request $request)
    {
        $json = new Json();
        $json->setText(json_decode($request->request->get('text'), true));
        $json->setCreatedAt(new DateTime());

        $hash1 = new Hash();
        $hash1->setAccessLevel(Hash::ACCESS_LEVEL_READ);
        $hash1->setHash(Hash::generateHash());
        $json->addHash($hash1);

        $hash2 = new Hash();
        $hash2->setAccessLevel(Hash::ACCESS_LEVEL_EDIT);
        $hash2->setHash(Hash::generateHash());
        $json->addHash($hash2);

        $em = $this->getDoctrine()->getManager();
        $em->persist($json);
        $em->persist($hash1);
        $em->persist($hash2);
        $em->flush();

        return new Response($this->generateUrl('main', [
            'id' => $json->getId(),
            'hash' => $hash2->getHash(),
        ]), 201);
    }
}