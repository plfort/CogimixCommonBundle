<?php
/**
 * Created by PhpStorm.
 * User: pilou
 * Date: 21/10/15
 * Time: 21:51
 */

namespace Cogipix\CogimixCommonBundle\Entity;

use Doctrine\ORM\Mapping as ORM;

use JMS\Serializer\Annotation as JMSSerializer;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;


/**
 * Class Tag
 * @package Cogipix\CogimixCommonBundle\Entity
 * @ORM\Entity(repositoryClass="Cogipix\CogimixCommonBundle\Repository\TagRepository",readOnly=true)
 */
class Tag {


    /**
     * @ORM\Id()
     * @ORM\GeneratedValue(strategy="AUTO")
     * @ORM\Column(type="integer")
     * @JMSSerializer\ReadOnly()
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"playlist_list","playlist_detail"})
     * @var integer
     */
    protected $id;

    /**
     * @ORM\Column(type="string",length=20,unique=true)
     * @JMSSerializer\ReadOnly()
     * @JMSSerializer\Expose()
     * @JMSSerializer\Groups({"playlist_list","playlist_detail"})
     * @var string
     */
    protected $label;

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
    public function getLabel()
    {
        return $this->label;
    }

    /**
     * @param string $label
     */
    public function setLabel($label)
    {
        $this->label = $label;
    }

    public function __toString()
    {
        return $this->label;
    }




}