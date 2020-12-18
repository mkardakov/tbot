<?php
declare(strict_types=1);


namespace App\Service;

use App\Entity\Announcement;
use http\Exception\InvalidArgumentException;

class Parser
{

    private $properties = [
        'animal',
        'breed',
        'location',
        'contacts'
    ];

    public function populate(string $input, Announcement $announcement)
    {
        foreach (explode(PHP_EOL, $input) as $key => $value)
        {
            if (!isset($this->properties[$key])) {
                throw new \InvalidArgumentException('Incorrectly populate template');
            }
            $setter = 'set' . ucfirst($this->properties[$key]);
            $announcement->$setter($value);

        }
    }
}