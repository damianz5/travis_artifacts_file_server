<?php

namespace App\Controller;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\JsonResponse;

class IndexController extends Controller
{
    use CredentialsCheckTrait;

    /**
     * @Route("/api/list/{name}", requirements={"name" = "^[a-f0-9]{32}$"})
     * @Method({"GET"})
     *
     * @param $name string
     *
     * @return JsonResponse
     */
    public function listAction($name)
    {
        try {
            return $this->listFiles($name);
        } catch (\Exception $e) {
            return new JsonResponse([
                'status'  => 'error',
                'message' => $e->getMessage(),
            ]);
        }
    }

    private function listFiles($name)
    {
        $this->checkCredentials();
        $files = $this->get('app.file_collection_manager')->listFiles($name);

        return new JsonResponse([
            'status'          => 'ok',
            'collection_name' => $name,
            'files'           => $files,
        ]);
    }
}
