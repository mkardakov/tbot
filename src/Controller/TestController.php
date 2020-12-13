<?php

namespace App\Controller;

use Borsaco\TelegramBotApiBundle\Service\Bot;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Telegram\Bot\Objects\Message;

class TestController extends AbstractController
{
//    /**
//     * @Route("/", name="test")
//     */
    public function index(Request $request, Bot $bot): Response
    {
//        file_put_contents('/var/www/html/telegram.log', print_r($bot->getBot('ShelterFinderBot')->getWebhookUpdate()->getMessage()->toArray(), 1), FILE_APPEND);
        $shelterBot = $bot->getBot('ShelterFinderBot');
        file_put_contents('/var/www/html/telegram.log', $shelterBot->getWebhookUpdate()->getMessage()->detectType(), FILE_APPEND);
        $text = $shelterBot->getWebhookUpdate()->getMessage()->text;
        $chatId = $shelterBot->getWebhookUpdate()->getChat()->id;
        $shelterBot->sendMessage([
            'text' => $text,
            'chat_id' => $chatId
        ]);
        return $this->render('test/index.html.twig', [
            'controller_name' => 'TestController',
        ]);
    }
}
