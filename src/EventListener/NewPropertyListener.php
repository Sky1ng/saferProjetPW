<?php

namespace App\EventListener;

use App\Entity\Property;
use Swift_Mailer;
use Symfony\Bundle\TwigBundle\TwigEngine;
use Symfony\Component\EventDispatcher\Event;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;

class NewPropertyListener implements EventSubscriberInterface
{
    private $mailer;

    public function __construct(Swift_Mailer $mailer)
    {
        dump('NewPropertyListener');
        $this->mailer = $mailer;

    }

    public static function getSubscribedEvents()
    {

        return [
            'easyadmin.pre_persist' => ('onNewProperty')
        ];
    }

    public function onNewProperty(Event $event)
    {
        dump($event);
        $property = $event->getSubject();
        if (!$property instanceof Property) {
            return;
        }

        $message = (new \Swift_Message('Nouveau bien ajouté'))
            ->setFrom('noreply@example.com')
            ->setTo('admin@example.com')
            ->setBody(
                $this->twig->render(
                    'emails/new_property.html.twig',
                    ['property' => $property]
                ),
                'text/html'
            )
        ;
        $this->mailer->send($message);
    }
}
