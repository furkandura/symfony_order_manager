<?php

namespace App\Controller;

use DateTime;
use App\Helper\RequestHelper;
use App\Helper\ResponseHelper;
use App\Repository\UserRepository;
use App\Repository\OrderRepository;
use Symfony\Component\Validator\Validation;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class OrderController extends AbstractController
{
    private $orderRepository;

    /**
     * @param OrderRepository $orderRepo
     */
    public function __construct(OrderRepository $orderRepo)
    {
        $this->orderRepository = $orderRepo;
    }

    /**
     * @Route("/api/order/new", name="order_new", methods={"POST"})
     */
    public function new(Request $req)
    {

        $request = RequestHelper::parseJson($req->getContent());

        $validator = Validation::createValidator();
        $constraint = new Assert\Collection([
            "product_id" => new Assert\Required(),
            "quantity" => new Assert\Required(),
            "address" => new Assert\Required(),
            "shipping_date" => new Assert\Optional()
        ]);

        $violations = $validator->validate((array) $request, $constraint);

        if ($violations->count() > 0) {
            return ResponseHelper::create(400, ResponseHelper::validateMsgArrayToString($violations));
        }

        // Product tablosu olsaydı eğerki burada gelen product_id li ürün o müşteriye mi ait konrolü yapılacakti.

        $this->orderRepository->createOrder(
            $this->getUser()->getId(),
            $request->product_id,
            $request->quantity,
            $request->address,
            $request->shippingDate ?? null,
        );

        return ResponseHelper::create(200, "Sipariş başarıyla oluşturuldu.");
    }



    /**
     * @Route("/api/order/update/{order_id}", name="order_update", methods={"PUT"})
     */
    public function update(Request $req, $order_id)
    {

        $request = RequestHelper::parseJson($req->getContent());

        $validator = Validation::createValidator();
        $constraint = new Assert\Collection([
            "product_id" => new Assert\Required(),
            "quantity" => new Assert\Required(),
            "address" => new Assert\Required(),
            "shipping_date" => new Assert\Optional()
        ]);

        $violations = $validator->validate((array) $request, $constraint);

        if ($violations->count() > 0) {
            return ResponseHelper::create(400, ResponseHelper::validateMsgArrayToString($violations));
        }

        // Güncellemek istediği sipariş auth olmuş kullanıcının değilse hata döndürüyoruz.
        if (!$this->orderRepository->chechIsAuthOrder($order_id, $this->getUser()->getId())) {
            return ResponseHelper::create(400, "Sipariş bulunamadı.");
        }

        // Sipariş teslimat aşamasına geçmişse engelliyoruz.
        if (!$this->orderRepository->checkShippingDate($order_id)) {
            return ResponseHelper::create(400, "Sipariş artık teslimat aşamasına geçti bu yüzden sipariş güncellenemez.");
        }

        // Product içinde api istenseydi burada gelen product_id li ürün o müşteriye mi ait konrolü yapılacakti.
        // Şuanlık es geçtim o kısmı

        // Tarih datetime objesine dönüştürülüyor.
        if(strlen($request->shipping_date) > 10) {
            $formatShippingDate = DateTime::createFromFormat("Y-m-d H:i:s", $request->shipping_date);
        }

        $this->orderRepository->updateOrder(
            $this->getUser()->getId(),
            $order_id,
            $request->product_id,
            $request->quantity,
            $request->address,
            $formatShippingDate ?? null,
        );

        return ResponseHelper::create(200, "Sipariş başarıyla güncellendi.");
    }

    /**
     * @Route("/api/order/detail/{order_id}", name="order_detail", methods={"GET"})
     */
    public function detail($order_id)
    {
        // Sipariş auth olmuş kullanıcının mı diye kontrol ediyoruz değilse hata döndürüyoruz.
        if (!$this->orderRepository->chechIsAuthOrder($order_id, $this->getUser()->getId())) {
            return ResponseHelper::create(400, "Sipariş bulunamadı.");
        }

        $order = $this->orderRepository->getAuthSingleOrder($order_id);

        return ResponseHelper::create(200, "", $order);
    }

    /**
     * @Route("/api/order/all", name="order_all", methods={"GET"})
     */
    public function all()
    {
        $orders = $this->orderRepository->getAuthAllOrders($this->getUser()->getId());
        
        return ResponseHelper::create(200, "", ["orders" => $orders]);
    }
}
