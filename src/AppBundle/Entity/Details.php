<?php

namespace AppBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Doctrine\Common\Collections\ArrayCollection;

/**
 * Details
 *
 * @ORM\Table(name="details")
 * @ORM\Entity(repositoryClass="AppBundle\Repository\DetailsRepository")
 */
class Details
{
    CONST DETAILS_SALE = 'detail.sale';
    CONST DETAILS_PURCHASE = 'detail.purchase';

    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer", nullable=false)
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="IDENTITY")
     */
    protected $id;

    /**
     * @var string
     *
     * @ORM\Column(name="number", type="string", length=254, nullable=true)
     */
    protected $number;

    /**
     * @var Users
     * @ORM\ManyToOne(targetEntity="Users", inversedBy="detail", cascade={"persist", "remove"})
     */
    protected $user;

    /**
     * @var \DateTime
     *
     * @ORM\Column(name="dateOfIssue", type="datetime", nullable=true)
     */
    protected $dateOfIssue;

    /**
     * @ORM\Column(name="unit_value", type="decimal", precision=30, scale=0, nullable=true)
     */
    protected $unitValue;

    /**
     * @var string
     *
     * @ORM\Column(name="quantity", type="string", length=30, nullable=true)
     */
    protected $quantity;

    /**
     * @ORM\Column(name="iva", type="decimal", precision=30, scale=0, nullable=true)
     */
    protected $iva;

    /**
     * @ORM\Column(name="discount", type="decimal", precision=30, scale=0, nullable=true)
     */
    protected $discount;

    /**
     * @ORM\Column(name="value_total", type="decimal", precision=30, scale=0, nullable=true)
     */
    protected $valueTotal;

    /**
     * @var string
     * @ORM\Column(name="type", type="string", length=30, nullable=false)
     */
    protected $type = self::DETAILS_PURCHASE;

    /**
     * @var Supplier
     * @ORM\ManyToOne(targetEntity="Supplier", inversedBy="detail", cascade={"persist", "remove"})
     */
    protected $supplier;

    /**
     * @var Customers
     * @ORM\ManyToOne(targetEntity="Customers", inversedBy="detail", cascade={"persist", "remove"})
     */
    protected $customer;

    /**
     * @var Products
     * @ORM\ManyToOne(targetEntity="Products", inversedBy="detail", cascade={"persist", "remove"})
     */
    protected $product;

    /**
     * @var array
     *
     * @ORM\Column(name="meta_data", type="array", nullable=true)
     */
    protected $metaData;


    /**
     * Details constructor.
     */
    public function __construct()
    {
        $this->metaData = new ArrayCollection();
        $this->dateOfIssue = new \DateTime('now');
    }

    /**
     * @return array|ArrayCollection
     * @internal param $key
     */
    public function getMetaData()
    {
        return $this->metaData;
    }

    /**
     * @param $key
     * @param array $metaData
     * @return Details
     */
    public function setMetaData($key, array $metaData)
    {
        $this->metaData[$key] = $metaData;

        return $this;
    }

    /**
     * @param array $metaData
     * @return $this
     */
    public function removeMetaData(array $metaData)
    {
        $this->metaData->removeElement($metaData);

        return $this;
    }

    /**
     * @return int
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * @return string
     */
    public function getType()
    {
        return $this->type;
    }

    /**
     * @param string $type
     */
    public function setType(string $type)
    {
        $this->type = $type;
    }

    /**
     * @return string
     */
    public function getNumber()
    {
        return $this->number;
    }

    /**
     * @param string $number
     */
    public function setNumber(string $number)
    {
        $this->number = $number;
    }

    /**
     * @return \DateTime
     */
    public function getDateOfIssue()
    {
        return $this->dateOfIssue;
    }

    /**
     * @param \DateTime $dateOfIssue
     */
    public function setDateOfIssue(\DateTime $dateOfIssue)
    {
        $this->dateOfIssue = $dateOfIssue;
    }

    /**
     * @return string
     */
    public function getQuantity()
    {
        return $this->quantity;
    }

    /**
     * @param string $quantity
     */
    public function setQuantity(string $quantity)
    {
        $this->quantity = $quantity;
    }

    /**
     * @return mixed
     */
    public function getUnitValue()
    {
        return $this->unitValue;
    }

    /**
     * @param mixed $unitValue
     */
    public function setUnitValue($unitValue)
    {
        $this->unitValue = $unitValue;
    }

    /**
     * @return mixed
     */
    public function getIva()
    {
        return $this->iva;
    }

    /**
     * @param mixed $iva
     */
    public function setIva($iva)
    {
        $this->iva = $iva;
    }

    /**
     * @return mixed
     */
    public function getDiscount()
    {
        return $this->discount;
    }

    /**
     * @param mixed $discount
     */
    public function setDiscount($discount)
    {
        $this->discount = $discount;
    }

    /**
     * @return mixed
     */
    public function getValueTotal()
    {
        return $this->valueTotal;
    }

    /**
     * @param mixed $valueTotal
     */
    public function setValueTotal($valueTotal)
    {
        $this->valueTotal = $valueTotal;
    }

    /**
     * @return Users
     */
    public function getUser()
    {
        return $this->user;
    }

    /**
     * @param Users $user
     */
    public function setUser(Users $user)
    {
        $this->user = $user;
    }


    /**
     * Set supplier
     *
     * @param \AppBundle\Entity\Supplier $supplier
     *
     * @return Details
     */
    public function setSupplier(Supplier $supplier = null)
    {
        $this->supplier = $supplier;

        return $this;
    }

    /**
     * Get supplier
     *
     * @return \AppBundle\Entity\Supplier
     */
    public function getSupplier()
    {
        return $this->supplier;
    }

    /**
     * Set customer
     *
     * @param \AppBundle\Entity\Customers $customer
     *
     * @return Details
     */
    public function setCustomer(Customers $customer = null)
    {
        $this->customer = $customer;

        return $this;
    }

    /**
     * Get customer
     *
     * @return \AppBundle\Entity\Customers
     */
    public function getCustomer()
    {
        return $this->customer;
    }

    /**
     * Set product
     *
     * @param \AppBundle\Entity\Products $product
     *
     * @return Details
     */
    public function setProduct(Products $product = null)
    {
        $this->product = $product;

        return $this;
    }

    /**
     * Get product
     *
     * @return \AppBundle\Entity\Products
     */
    public function getProduct()
    {
        return $this->product;
    }
}
