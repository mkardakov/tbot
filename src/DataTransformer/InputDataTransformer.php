<?php
declare(strict_types=1);


namespace App\DataTransformer;

use ApiPlatform\Core\DataTransformer\DataTransformerInterface;
use App\Command\CommandCollection;
use App\Command\Telegram\HelpCommand;
use Borsaco\TelegramBotApiBundle\Service\Bot;
use Psr\Log\LoggerInterface;
use Telegram\Bot\Api;

/**
 * Class InputDataTransformer
 * @package App\DataTransformer
 */
final class InputDataTransformer implements DataTransformerInterface
{

    private $logger;

    private $bot;

    private $commands;

    /**
     * InputDataTransformer constructor.
     * @param LoggerInterface $logger
     * @param Api $bot
     * @param iterable $commands
     */
    public function __construct(iterable $commands, LoggerInterface $logger, Api $bot)
    {

        $this->logger = $logger;
        $this->bot = $bot;
        $this->commands = $commands;
    }

    public function transform($object, string $to, array $context = [])
    {
        foreach ($this->commands as $command) {
            $this->bot->addCommand($command);
        }
        $shelterBot = $this->bot->commandsHandler(true);
//        $this->bot->add
//        $shelterBot->getWebhookUpdate()->getMessage()->hasCommand()
//        file_put_contents('/var/www/html/telegram.log', $shelterBot->getWebhookUpdate()->getMessage()->detectType(), FILE_APPEND);
//        if (!$shelterBot->getWebhookUpdate()->getMessage()->isType('photo')) {
//            $text = $shelterBot->getWebhookUpdate()->getMessage()->text ?? 'no message';
//            $chatId = $shelterBot->getWebhookUpdate()->getChat()->id;
//            $shelterBot->sendMessage([
//                'text' => $text,
//                'chat_id' => $chatId
//            ]);
//        }
        die('success');
    }

    public function supportsTransformation($data, string $to, array $context = []): bool
    {
        $this->logger->debug("TO::::::::::" . $to);
        return true;
    }
}