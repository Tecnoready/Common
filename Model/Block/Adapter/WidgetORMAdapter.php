<?php

/*
 * This file is part of the TecnoCreaciones package.
 * 
 * (c) www.tecnocreaciones.com
 * 
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Tecnoready\Common\Model\Block\Adapter;

use Doctrine\Bundle\DoctrineBundle\Registry;
use Doctrine\ORM\EntityRepository;
use LogicException;
use Symfony\Component\DependencyInjection\ContainerAwareInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;
use Tecnoready\Common\Model\Block\BlockInterface;

/**
 * Manejador de widgets ORM
 *
 * @author Carlos Mendoza <inhack20@tecnocreaciones.com>
 */
class WidgetORMAdapter extends BaseAdapter implements ContainerAwareInterface
{

    protected $container;
    
    function find($id)
    {
        $blockWidgetBox = $this->getRepository()->find($id);
        return $blockWidgetBox;
    }

    function findByIds(array $ids)
    {
        $qb = $this->getRepository()->createQueryBuilder('b');
        $qb
                ->andWhere($qb->expr()->in('b.id', $ids))
        ;
        return $qb->getQuery()->getResult();
    }

    public function findAllPublishedByEvent($event)
    {
        $user = $this->getUser();
        return $this->getRepository()->findBy(array(
                    'event' => $event,
                    'user' => $user,
                    'enabled' => true
        ));
    }

    public function remove(BlockInterface $blockWidgetBox)
    {
        $success = false;
        $em = $this->getDoctrine()->getManager();
        $user = $this->getUser();
        if ($blockWidgetBox->getUser() === $user) {
            $em->remove($blockWidgetBox);
            $em->flush();
            $success = true;
        }
        return $success;
    }

    public function save(BlockInterface $blockWidgetBox, $andFlush = true)
    {
        $em = $this->getDoctrine()->getManager();

        $em->persist($blockWidgetBox);
        if ($andFlush) {
            $em->flush();
        }

        return $blockWidgetBox;
    }

    public function buildBlockWidget(array $parameters = array())
    {
        $user = $this->getUser();

        $blockWidget = parent::buildBlockWidget($parameters);
        $blockWidget->setUser($user);

        return $blockWidget;
    }

    /**
     * @return EntityRepository
     */
    private function getRepository()
    {
        $em = $this->getDoctrine()->getManager();
        $repository = $em->getRepository($this->classBox);
        return $repository;
    }

    public function countPublishedByEvent($event)
    {
        $user = $this->getUser();
        $qb = $this->getRepository()->createQueryBuilder("w");

        $qb->select("COUNT(w.id) total")
                ->andWhere("w.event = :event")
                ->andWhere("w.user = :user")
                ->andWhere("w.enabled = :enabled")
                ->setParameter("event", $event)
                ->setParameter("user", $user)
                ->setParameter("enabled", true)
        ;
        $result = $qb->getQuery()->getOneOrNullResult();
        return (int) $result["total"];
    }

    public function findPublishedByTypeAndName($type, $name)
    {
        $user = $this->getUser();
        return $this->getRepository()->findOneBy(array(
                    'type' => $type,
                    'name' => $name,
                    'user' => $user,
                    'enabled' => true
        ));
    }

    public function clearAllByEvent($eventName)
    {
        $user = $this->getUser();
        $qb = $this->getRepository()->createQueryBuilder("w");
        $qb
                ->delete()
                ->andWhere("w.event = :event")
                ->andWhere("w.user = :user")
                ->setParameter("event", $eventName)
                ->setParameter("user", $user)
        ;
        $r = $qb->getQuery()->getResult();
        return $r;
    }

    /**
     * Shortcut to return the Doctrine Registry service.
     *
     * @return Registry
     *
     * @throws LogicException If DoctrineBundle is not available
     */
    public function getDoctrine()
    {
        if (!$this->container->has('doctrine')) {
            throw new LogicException('The DoctrineBundle is not registered in your application.');
        }

        return $this->container->get('doctrine');
    }

    /**
     * Get a user from the Security Context
     *
     * @return mixed
     *
     * @throws LogicException If SecurityBundle is not available
     *
     * @see TokenInterface::getUser()
     */
    public function getUser()
    {
        if (!$this->container->has('security.context')) {
            throw new LogicException('The SecurityBundle is not registered in your application.');
        }

        if (null === $token = $this->container->get('security.context')->getToken()) {
            return;
        }

        if (!is_object($user = $token->getUser())) {
            return;
        }

        return $user;
    }

    public function setContainer(ContainerInterface $container = null)
    {
        $this->container = $container;
    }

}
