<?php

namespace App\Controller;

use App\Entity\Announcement;
use App\Entity\User;
use App\Service\Parser;
use Borsaco\TelegramBotApiBundle\Service\Bot;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Telegram\Bot\Api;
use Telegram\Bot\Objects\Message;

class MessageController extends AbstractController
{

    private $commands;

    public function __construct(iterable $commands)
    {
        $this->commands = $commands;
    }

    /**
     * @Route("/", name="routing", methods={"POST"})
     */
    public function index(Api $bot, Parser $parser, EntityManagerInterface $em): Response
    {
        try {
            if ($bot->getWebhookUpdate()->hasCommand()) {
                foreach ($this->commands as $command) {
                    $bot->addCommand($command);
                }
                $bot->commandsHandler(true);
                return new Response('command accepted');
            }
            $user = $em->getRepository(User::class)->findOneBy([
                'username' => $bot->getWebhookUpdate()->getChat()->username
            ]);
            $announcement = $user->getAnnouncements()->filter(function(Announcement $announcement) {
                return !$announcement->isFinished();
            })->first();
            $parser->populate($bot->getWebhookUpdate()->getMessage()->text, $announcement);
            $announcement->setStep(Announcement::FINISHED);
            $em->persist($announcement);
            $em->flush();
            $bot->sendMessage([
                'chat_id' => $bot->getWebhookUpdate()->getChat()->id,
                'text' => "Вы успешно добавили обЪяву"
            ]);
        } catch(\Throwable $e) {
            $bot->sendMessage([
                'chat_id' => $bot->getWebhookUpdate()->getChat()->id,
                'text' => $e->getMessage()
            ]);
        }
        return new Response('not a command', 200);
    }
}
