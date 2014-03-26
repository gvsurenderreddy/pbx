<?php

namespace VoIP\PBX\RealTimeBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

/**
 * Conf
 *
 * @ORM\Table(name="ast_conf")
 * @ORM\Entity
 */
class Conf
{
    /**
     * @var integer
     *
     * @ORM\Column(name="id", type="integer")
     * @ORM\Id
     * @ORM\GeneratedValue(strategy="AUTO")
     */
    private $id;

    /**
     * @var integer
     *
     * @ORM\Column(name="cat_metric", type="integer")
     */
    private $catMetric = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="var_metric", type="integer")
     */
    private $varMetric = 0;

    /**
     * @var integer
     *
     * @ORM\Column(name="commented", type="integer")
     */
    private $commented = 0;

    /**
     * @var string
     *
     * @ORM\Column(name="filename", type="string", length=128)
     */
    private $filename = '';

    /**
     * @var string
     *
     * @ORM\Column(name="category", type="string", length=128)
     */
    private $category = 'default';

    /**
     * @var string
     *
     * @ORM\Column(name="var_name", type="string", length=128)
     */
    private $varName = '';

    /**
     * @var string
     *
     * @ORM\Column(name="var_val", type="string", length=128)
     */
    private $varVal = '';


    /**
     * Get id
     *
     * @return integer 
     */
    public function getId()
    {
        return $this->id;
    }

    /**
     * Set catMetric
     *
     * @param integer $catMetric
     * @return Conf
     */
    public function setCatMetric($catMetric)
    {
        $this->catMetric = $catMetric;

        return $this;
    }

    /**
     * Get catMetric
     *
     * @return integer 
     */
    public function getCatMetric()
    {
        return $this->catMetric;
    }

    /**
     * Set varMetric
     *
     * @param integer $varMetric
     * @return Conf
     */
    public function setVarMetric($varMetric)
    {
        $this->varMetric = $varMetric;

        return $this;
    }

    /**
     * Get varMetric
     *
     * @return integer 
     */
    public function getVarMetric()
    {
        return $this->varMetric;
    }

    /**
     * Set commented
     *
     * @param integer $commented
     * @return Conf
     */
    public function setCommented($commented)
    {
        $this->commented = $commented;

        return $this;
    }

    /**
     * Get commented
     *
     * @return integer 
     */
    public function getCommented()
    {
        return $this->commented;
    }

    /**
     * Set filename
     *
     * @param string $filename
     * @return Conf
     */
    public function setFilename($filename)
    {
        $this->filename = $filename;

        return $this;
    }

    /**
     * Get filename
     *
     * @return string 
     */
    public function getFilename()
    {
        return $this->filename;
    }

    /**
     * Set category
     *
     * @param string $category
     * @return Conf
     */
    public function setCategory($category)
    {
        $this->category = $category;

        return $this;
    }

    /**
     * Get category
     *
     * @return string 
     */
    public function getCategory()
    {
        return $this->category;
    }

    /**
     * Set varName
     *
     * @param string $varName
     * @return Conf
     */
    public function setVarName($varName)
    {
        $this->varName = $varName;

        return $this;
    }

    /**
     * Get varName
     *
     * @return string 
     */
    public function getVarName()
    {
        return $this->varName;
    }

    /**
     * Set varVal
     *
     * @param string $varVal
     * @return Conf
     */
    public function setVarVal($varVal)
    {
        $this->varVal = $varVal;

        return $this;
    }

    /**
     * Get varVal
     *
     * @return string 
     */
    public function getVarVal()
    {
        return $this->varVal;
    }
}
