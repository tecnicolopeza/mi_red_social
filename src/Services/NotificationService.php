<?php
namespace App\Services;

 use App\Entity\Notifications;
 use Doctrine\Persistence\ManagerRegistry as PersistenceManagerRegistry;

 class NotificationService {
     public $doctrine;

     public function __construct(PersistenceManagerRegistry $doctrine) {
        $this->doctrine = $doctrine;
     }

     public function set($user, $type, $typeId, $extra = null){

        $em = $this->doctrine->getManager();

        $notification = new Notifications();
        $notification->setUser($user);
        $notification->setTypeNot($type);
        $notification->setTypeId($typeId);
        $notification->setReaded(0);
        $notification->setCreated(new \DateTime("now"));
        $notification->setExtra($extra);

        $em->persist($notification);
        $flush = $em->flush();

        if($flush == null){
            $status = true;
        }else{
            $status = false;
        }

        return $status;

     }
 }