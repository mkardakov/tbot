<?php
declare(strict_types=1);

namespace App\Command\Telegram;

use App\Entity\Announcement;
use App\Entity\User;
use Doctrine\ORM\EntityManagerInterface;
use Psr\Log\LoggerInterface;
use Telegram\Bot\Actions;
use Telegram\Bot\Api;
use Telegram\Bot\Commands\Command;
use Telegram\Bot\Keyboard\Keyboard;

/**
 * Class StartCommand
 * @package App\Command\Telegram
 */
class StartCommand  extends Command implements OptionsAwareInterface
{

    /**
     * @var string Command Name
     */
    protected $name = "start";

    /**
     * @var string Command Description
     */
    protected $description = "Создайте обЪявление о поиске нового дома для животного";

    private $logger;

    private $entityManager;

    protected $telegram;

    protected $api;

    public function __construct(
        LoggerInterface  $logger,
        EntityManagerInterface $entityManager,
        Api $api
    )
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->api = $api;
    }

    public function handle()
    {
        // This will send a message using `sendMessage` method behind the scenes to
        // the user/chat id who triggered this command.
        // `replyWith<Message|Photo|Audio|Video|Voice|Document|Sticker|Location|ChatAction>()` all the available methods are dynamically
        // handled when you replace `send<Method>` with `replyWith` and use the same parameters - except chat_id does NOT need to be included in the array.
        $this->replyWithMessage(['text' => 'Давайте создадим новое объявление']);
//        $announcement->
        // This will update the chat status to typing...
        $this->replyWithChatAction(['action' => Actions::TYPING]);

        $commands = $this->telegram->getCommands();

        $text = '';
        foreach ($commands as $name => $handler) {
            if (in_array($name, $this->getOptions()))
            /* @var Command $handler */
            $text .= sprintf('/%s - <b>%s</b>'.PHP_EOL, $name, $handler->getDescription());
        }

        $this->replyWithMessage([
            'chat_id' => $this->getUpdate()->getChat()->id,
            'text' => $text,
            'parse_mode' => 'HTML'
        ]);
//        $keyboard = [
//            ['Я хочу отдать животное'],
//            ['Я хочу взять животное'],
//        ];

//        $reply_markup = Keyboard::make([
//            'keyboard' => $keyboard,
//            'resize_keyboard' => true,
//            'one_time_keyboard' => true
//        ]);
//        $response = $this->replyWithMessage([
//            'text' => 'Hello World',
//            'reply_markup' => $reply_markup
//        ]);
        // Reply with the commands list
//        $this->replyWith(['text' => $response]);

        // Trigger another command dynamically from within this command
        // When you want to chain multiple commands within one or process the request further.
        // The method supports second parameter arguments which you can optionally pass, By default
        // it'll pass the same arguments that are received for this command originally.
//        $this->triggerCommand('subscribe');
    }

    public function getOptions(): array
    {
        return ['take', 'give'];
    }
}