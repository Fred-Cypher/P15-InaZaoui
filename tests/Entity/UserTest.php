<?php

namespace App\Tests\Entity;

use App\Entity\User;
use PHPUnit\Framework\TestCase;

class UserTest extends TestCase
{
    public function testUserGettersAndSetters(): void
    {
        $user = new User();

        $user->setEmail('test@test.com');
        $this->assertSame('test@test.com', $user->getEmail());
        $this->assertSame('test@test.com', $user->getUserIdentifier());

        $user->setName('Camille');
        $this->assertSame('Camille', $user->getName());

        $user->setDescription('Description de l\'utilisateur');
        $this->assertSame('Description de l\'utilisateur', $user->getDescription());

        $user->setPassword('password123');
        $this->assertSame('password123', $user->getPassword());

        $user->setAuthorised(true);
        $this->assertTrue($user->isAuthorised());
    }

    public function testIsAdmin(): void
    {
        $user = new User();

        $user->setAdmin(true);
        $this->assertTrue($user->isAdmin());

        $user->setAdmin(false);
        $this->assertFalse($user->isAdmin());
    }
}
