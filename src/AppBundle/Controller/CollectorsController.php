<?php

namespace AppBundle\Controller;

use AppBundle\Entity\Collectors;
use AppBundle\Entity\Details;
use AppBundle\Entity\Movements;
use AppBundle\Entity\Products;
use AppBundle\Entity\Record;
use AppBundle\Entity\Sellers;
use AppBundle\Form\PaymentType;
use AppBundle\Twig\AppExtension;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;
use Symfony\Component\HttpFoundation\Request;

class CollectorsController extends Controller
{

    /**
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function indexAction()
    {
        $details = $this->getDoctrine()
            ->getRepository('AppBundle:Details')->findAll();

        $records = $this->getDoctrine()
            ->getRepository('AppBundle:Record')->findAll();

        // dump($records); exit;

        return $this->render('collectors/index.html.twig', [
            'details' => $details,
            'records' => $records
        ]);
    }

    /**
     * @param Request $request
     * @param Details $detail
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function collectorSaleAction(Details $detail, Request $request)
    {

        $em = $this->getDoctrine()->getEntityManager();
        $appExtension = new AppExtension($em);

        $movements = $em->getRepository('AppBundle:Movements')->findBy([
            'documentNumber' => $detail->getId(),
        ], [
            'id' => 'DESC'
        ]);

        $form = $this->createForm(PaymentType::class);
        $form->handleRequest($request);

        if ($form->isValid()) {

            $movement = new Movements();

            $data = $form->getData();
            $deposit = $data['deposit'];

            $metadata = $detail->getMetadata();

            /** @var Products $product */
            $product = $metadata['product'];
            $price = $product->getPrice() * $detail->getQuantity();

            if ($price < $deposit) {

                $this->get('session')->getFlashBag()->add(
                    'deposito',
                    'El monto ingresado es mayor a la cuenta'
                );

                return $this->render('collectors/sale/index.html.twig', [
                    'form' => $form->createView(),
                    'movements' => $movements,
                    'detail' => $detail
                ]);
            }

            $movement->setDocumentNumber($detail);
            $movement->setClient($metadata['customer']);

            if (is_null($seller = $em->getRepository('AppBundle:Sellers')->findOneBy(['id' => $metadata['seller']['id']]))) {

                $seller = new Sellers();

                $seller->setUser($em->getReference('AppBundle:Users', $metadata['seller']['id']));
                $em->persist($seller);
            }

            $movement->setSeller($seller);
            $movement->setPaidForm($data['paidForm']);
            $movement->setRode($data['deposit']);
            $movement->setMovementType((int)$data['movementType']);

            if (is_null($collector = $em->getRepository('AppBundle:Collectors')->findOneBy(['id' => $this->getUser()]))) {

                $collector = new Collectors();

                $collector->setUser($this->getUser());
                $em->persist($collector);
            }

            $movement->setCollector($collector);

            if (($total = $appExtension->calculateMovements($detail) - $movement->getRode()) >= 1) {

                $record = new Record();
                $record->setDocumentPendingPayment($detail);
                $record->setAmountTotalDebt($total);
                $record->setSeller($seller);
                $record->setCustomer($metadata['customer']);
                $em->persist($record);
            }

            $em->persist($movement);
            $em->flush();

            $this->get('session')->getFlashBag()->add(
                'pago',
                'El pago se realizo exitosamente'
            );

            return $this->redirectToRoute('collectors_index');
        }

        return $this->render('collectors/sale/index.html.twig', [
            'form' => $form->createView(),
            'movements' => $movements,
            'detail' => $detail
        ]);
    }

    /**
     * @param Movements $movement
     * @return \Symfony\Component\HttpFoundation\Response
     */
    public function movementsDetailAction(Movements $movement)
    {
        return $this->render('collectors/movements/index.html.twig', [
            'movement' => $movement
        ]);
    }
}
