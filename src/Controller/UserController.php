<?php

namespace App\Controller;

use App\Helper\RequestHelper;
use App\Helper\ResponseHelper;
use App\Repository\UserRepository;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class UserController extends AbstractController
{
    private $userRepository;

    /**
     * @param UserRepository $userRepo
     */
    public function __construct(UserRepository $userRepo)
    {
        $this->userRepository = $userRepo;
    }

    /**
     * @Route("/api/register", name="register", methods={"POST"})
     */
    public function add(Request $req)
    {

        $request = RequestHelper::parseJson($req->getContent());

        $validator = Validation::createValidator();
        $constraint = new Assert\Collection([
            "username" => new Assert\Required(),
            "password" => new Assert\Required(),
            "full_name" => new Assert\Required(),
            "phone" => new Assert\Optional(),
        ]);

        $violations = $validator->validate((array) $request, $constraint);

        if ($violations->count() > 0) {
            return ResponseHelper::create(400, ResponseHelper::validateMsgArrayToString($violations));
        }

        // Aynı kullanıcı adında başka müşteri var mı kontrolü.
        $user = $this->userRepository->findByUsername($request->username);

        if($user != null) {
            return ResponseHelper::create(400, "Bu kullanıcı adı ile zaten hesap oluşturulmuş.");
        }

        $this->userRepository->createUser(
            $request->username,
            $request->password,
            $request->full_name,
            $request->phone ?? null,
        );

        return ResponseHelper::create(200, "Müşteri başarıyla oluşturuldu.");
    }

   
}
