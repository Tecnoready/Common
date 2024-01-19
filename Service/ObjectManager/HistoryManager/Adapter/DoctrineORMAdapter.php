<?php

namespace Tecnoready\Common\Service\ObjectManager\HistoryManager\Adapter;

use Tecnoready\Common\Model\ObjectManager\HistoryManager\HistoryInterface;
use Doctrine\ORM\EntityManager;
// use Pagerfanta\Pagerfanta as Paginator;
use Tecnocreaciones\Bundle\ToolsBundle\Model\Paginator\Paginator;
use Pagerfanta\Doctrine\ORM\QueryAdapter;
use Symfony\Component\OptionsResolver\OptionsResolver;
use DateTime;

/**
 * Adaptador de doctrine2
 *
 * @author Carlos Mendoza <inhack20@gmail.com>
 */
class DoctrineORMAdapter implements HistoryAdapterInterface
{

    use \Tecnoready\Common\Service\ObjectManager\TraitConfigure;

    /**
     * @var string
     */
    private $className;

    /**
     * @var EntityManager
     */
    private $em;

    public function __construct($className, EntityManager $em)
    {
        $this->className = $className;
        $this->em = $em;
    }

    public function create(HistoryInterface $entity)
    {
        $this->em->persist($entity);
        return $this->em->flush();
    }

    public function delete(HistoryInterface $entity)
    {
        $this->em->remove($entity);
        return $this->em->flush();
    }

    public function find($id)
    {
        return $this->em->find($id);
    }

    /**
     * @param array $criteria
     * @param array $sortBy
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function getPaginator(array $criteria = [], array $sortBy = [])
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "sort" => null,
            "direction" => null,
            "user" => null
        ]);
        $criteria = $resolver->resolve($criteria);

        $repository = $this->em->getRepository($this->className);
        $qb = $repository->createQueryBuilder("e");
        $qb->orderBy("e.createdAt", "DESC");

        // Busca por id de objeto
        if (($objectId = $this->objectId) != null) {
            $qb
                ->andWhere("e.objectId = :objectId")
                ->setParameter("objectId", $objectId)
            ;
        }

        // Busca por tipo en especifico
        if (($objectType = $this->objectType) != null) {
            $qb
                ->andWhere("e.objectType = :objectType")
                ->setParameter("objectType", $objectType)
            ;
        }

        // Busca por usuario que registro el historial
        if (($user = $criteria["user"]) != null) {
            $qb
                ->andWhere("e.user = :user")
                ->setParameter("user", $user)
            ;   
        }

        //Doctrine\ORM\Query
        $sort = $criteria["sort"];
        $direction = $criteria["direction"];
        if (!empty($sort) && !empty($direction)) {
            $sort = explode("+", $sort);
            foreach ($sort as $field) {
                $qb->orderBy($field, $direction);
            }
        }
        
        $pagerfanta = new Paginator(new QueryAdapter($qb));
        $pagerfanta->setDefaultFormat(Paginator::FORMAT_ARRAY_STANDARD);
        return $pagerfanta;
    }

    public function createNew(array $options = array())
    {
        $resolver = new OptionsResolver();
        $resolver->setDefaults([
            "and_flush" => true,
            "event_name" => "DEFAULT",
        ]);
        $resolver->setRequired([
            "description","event_name"
        ]);

        $options = $resolver->resolve($options);
        
        $entity = new $this->className();
        if (false) {
            $entity = new HistoryInterface();
        }
        $ip = isset($SERVER["HTTP_X_FORWARDED_FOR"]) ? $SERVER["HTTP_X_FORWARDED_FOR"] : null;
        if($ip === null && isset($SERVER["REMOTE_ADDR"])){
            $ip = $SERVER["REMOTE_ADDR"];
        }
        if(empty($ip)){
            $ip = "127.0.0.1";
        }
        $entity
                ->setCreatedFromIp($ip)
                ->setCreatedAt(new DateTime())
                ->setEventName($options["event_name"])
                ->setDescription($options["description"])
                ->setObjectType($this->objectType)
                ->setObjectId($this->objectId)
                ->setUserAgent(isset($_SERVER["HTTP_USER_AGENT"]) ? $_SERVER["HTTP_USER_AGENT"] : "cli")
        ;
        if($options["and_flush"] === true){
            $this->create($entity);
        }
        return $entity;
    }

}
