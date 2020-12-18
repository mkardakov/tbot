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
use Twig\Environment;

class TakeCommand extends Command implements OptionsAwareInterface
{

    /**
     * @var string Command Name
     */
    protected $name = "take";

    /**
     * @var string Command Description
     */
    protected $description = "Я хочу взять животное";

    private $logger;

    private $entityManager;

    protected $telegram;

    protected $api;

    protected $twig;

    public function __construct(
        LoggerInterface  $logger,
        EntityManagerInterface $entityManager,
        Api $api,
        Environment $twig
    )
    {
        $this->logger = $logger;
        $this->entityManager = $entityManager;
        $this->api = $api;
        $this->twig = $twig;
    }

    public function handle()
    {
        $chat = $this->getUpdate()->getChat();
        $users = $this->entityManager->getRepository(User::class)->findBy([
            'username' => $chat->username
        ]);
        if (empty($users)) {
            $user = new User();
            $user->setName($chat->firstName)
                ->setLastName($chat->lastName)
                ->setTelegramId($chat->id)
                ->setUsername($chat->username);
        } else {
            $user = end($users);
        }
        $incompleteAnn = $user->getAnnouncements()->filter(function(Announcement $announcement) {
            return !$announcement->isFinished();
        });
        if ($incompleteAnn->isEmpty()) {
            $announcement = new Announcement();
            $announcement->setUser($user);
        } else {
            $announcement = $incompleteAnn->first();
        }
        $announcement->setAction(Announcement::ACTION_TAKE);
        $announcement->setStep(self::getName());
        $this->entityManager->persist($announcement);
        $this->entityManager->flush();
        $this->api->sendMessage([
            'chat_id' => $this->api->getWebhookUpdate()->getChat()->id,
            'text' => $this->twig->render('form/take.html.twig'),
            'parse_mode' => 'HTML'
        ]);
    }

    public function getOptions(): array
    {
        return ['take', 'give'];
    }
}