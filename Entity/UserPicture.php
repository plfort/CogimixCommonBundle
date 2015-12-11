<?php
namespace Cogipix\CogimixCommonBundle\Entity;

use Cogipix\CogimixBundle\Services\Images\ImageHelper;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use JMS\Serializer\Annotation as JMSSerializer;
/**
 * @ORM\Entity
 *@JMSSerializer\ExclusionPolicy("all")
 *
 */
class UserPicture
{
    private static $DEFAULT_IMG = '/bundles/cogimix/images/mini_logo.png';
    /**
     * @ORM\Id
     * @ORM\Column(type="integer")
     * @ORM\GeneratedValue(strategy="AUTO")
     * @JMSSerializer\ReadOnly()
     * @JMSSerializer\Exclude()
     */
    protected $id;

    /**
     * @var string $picFilename
     *
     * @ORM\Column(type="string", length=255, nullable=true)
     */
    protected $picFilename;

    /**
     * @ORM\OneToOne(targetEntity="User",mappedBy="picture")
     * @var User $user
     */
    protected $user;

    /**
     *  @JMSSerializer\Expose()
     *  @JMSSerializer\Accessor(getter="getWebPath")
     *  @JMSSerializer\Groups({"user_info","user_picture","suggestion"})
     *  */
    protected $webPath;

    public static function getDefaultImage(){
        return UserPicture::$DEFAULT_IMG;
    }

    public function getAbsolutePath()
    {
        return empty($this->picFilename) ? UserPicture::$DEFAULT_IMG
                : $this->getUploadRootDir() . '/' . $this->picFilename;
    }


    /**
     *
     * @var unknown_type
     */
    public function getWebPath()
    {

       return empty($this->picFilename) ? $this->user->getWebPicture(true) : ImageHelper::getThumbsnailWebPath($this->user->getId()).$this->picFilename;
       /* return null === $this->path ? UserPicture::$DEFAULT_IMG
                : $this->getUploadDir() . '/' . $this->path;*/
    }

    protected function getUploadRootDir()
    {

        return __DIR__ . '/../../../../web/' . $this->getUploadDir();
    }

    protected function getUploadDir()
    {

        return 'uploads/user_pictures';
    }

    public function getUser()
    {
        return $this->user;
    }

    public function setUser(User $user)
    {
        $this->user = $user;
    }

    public function getPicFilename()
    {
        return $this->picFilename;
    }

    public function setPicFilename($picFilename)
    {
        $this->picFilename = $picFilename;
    }

}
