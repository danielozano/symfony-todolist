<?php
// src/Acme/TaskBundle/Entity/User.php
namespace Acme\TaskBundle\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
/**
 * @ORM\Entity
 * @ORM\Table(name="users")
 * @ORM\Entity(repositoryClass="Acme\TaskBundle\Repository\UserRepository")
 * @UniqueEntity(fields="username", message="This username is already in use")
 * @UniqueEntity(fields="email", message="This email is already in use")
 */
class User implements UserInterface, \Serializable
{
	/**
 	 * @ORM\Id
	 * @ORM\Column(type="integer")
	 * @ORM\GeneratedValue(strategy="AUTO")
	 */
	private $id;
	/**
	 * @ORM\Column(type="string", length=60, unique=true)
	 * @Assert\NotBlank()
	 * @Assert\Email()
	 */
	private $email;
	/**
	 * @ORM\Column(type="string", length=25, unique=true)
	 * @Assert\NotBlank
	 */
	private $username;
	/**
	 * @ORM\Column(type="string", length=64)
	 */
	private $password;
	/**
	 * No persiste en base de datos. Simplemente es un paso extra para poder
	 * tratar la contraseña antes de persistir la real en base de datos.
	 * @Assert\NotBlank()
	 * @Assert\Length(max = 4096)
	 */
	private $plainPassword;
	/**
	 * @ORM\Column(name="is_active", type="boolean")
	 */
	private $isActive;

	public function __construct()
	{
		$this->isActive = true;
	}

	public function getUsername()
	{
		return $this->username;
	}

	public function getPassword()
	{
		return $this->password;
	}

    public function getPlainPassword()
    {
        return $this->plainPassword;
    }

	public function getRoles()
	{
		return array('ROLE_USER');
	}

	public function getSalt()
	{
		return null;
	}

	public function eraseCredentials()
	{

	}
	/** @see \Serializable::seralize() */
	public function serialize()
	{
		return serialize(array(
			$this->id,
			$this->username,
			$this->password,
		));
	}
	/** @see  \Serializable::unserialize() */
	public function unserialize($serialized)
	{
		list(
			$this->id,
			$this->username,
			$this->password,
		) = unserialize($serialized);
	}

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
     * Set email
     *
     * @param string $email
     *
     * @return User
     */
    public function setEmail($email)
    {
        $this->email = $email;

        return $this;
    }

    /**
     * Get email
     *
     * @return string
     */
    public function getEmail()
    {
        return $this->email;
    }

    /**
     * Set username
     *
     * @param string $username
     *
     * @return User
     */
    public function setUsername($username)
    {
        $this->username = $username;

        return $this;
    }

    /**
     * Set password
     *
     * @param string $password
     *
     * @return User
     */
    public function setPassword($password)
    {
        $this->password = $password;

        return $this;
    }

    public function setPlainPassword($password)
    {
        $this->plainPassword = $password;
    }

    /**
     * Set isActive
     *
     * @param boolean $isActive
     *
     * @return User
     */
    public function setIsActive($isActive)
    {
        $this->isActive = $isActive;

        return $this;
    }

    /**
     * Get isActive
     *
     * @return boolean
     */
    public function getIsActive()
    {
        return $this->isActive;
    }
}
