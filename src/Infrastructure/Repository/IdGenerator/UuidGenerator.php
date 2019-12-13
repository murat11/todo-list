<?php declare(strict_types=1);

namespace App\Infrastructure\Repository\IdGenerator;

use App\Infrastructure\Repository\IdGeneratorInterface;

class UuidGenerator implements IdGeneratorInterface
{
    /**
     * @return string
     */
    public function generateId(): string
    {
        $data = random_bytes(16);
        $data[6] = chr(ord($data[6]) & 0x0f | 0x40);
        $data[8] = chr(ord($data[8]) & 0x3f | 0x80);

        return vsprintf('%s%s-%s-%s-%s-%s%s%s', str_split(bin2hex($data), 4));
    }
}
