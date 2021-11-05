<?php

namespace App\Security;

use App\Entity\Task;
use App\Entity\User;
use Symfony\Component\Security\Core\Security;
use Symfony\Component\Security\Core\Authorization\Voter\Voter;
use Symfony\Component\Security\Core\Authentication\Token\TokenInterface;

class TaskVoter extends Voter
{
    const READ = 'read';
    const UPDATE = 'update';
    const DELETE = 'delete';
    const CREATE = 'create';

    private $user;

    public function __construct(Security $security)
    {
        $this->user = $security->getUser();
    }

    protected function supports($attribute, $subject)
    {
        // if the attribute isn't one we support, return false
        if (!in_array($attribute, [self::READ, self::UPDATE, self::DELETE, self::CREATE])) {
            return false;
        }

        // only vote on Post objects inside this voter
        if ($attribute != self::CREATE) {
            if (null !== $subject && !$subject instanceof Task) {
                return false;
            }
        }

        return true;
    }

    protected function voteOnAttribute($attribute, $subject, TokenInterface $token)
    {

        if (!$this->user instanceof User) {
            return false;
        }

        /** @var Task $task */
        $task = $subject;
        switch ($attribute) {
            case self::READ:
                return $this->canRead($task, $this->user);
            case self::UPDATE:
                return $this->canUpdate($task, $this->user);
            case self::DELETE:
                return $this->canDelete($task, $this->user);
            case self::CREATE:
                return $this->canCreate($this->user);
        }

        throw new \LogicException('This code should not be reached!');
    }

    public function canRead(Task $task, User $user)
    {
        return true;
    }

    public function canUpdate(Task $task, User $user)
    {
        return $this->canCreate($user);
    }

    public function canDelete(Task $task, User $user)
    {
        if (in_array('ROLE_ADMIN', $user->getRoles())) {
            return true;
        }

        if (in_array('ROLE_USER', $user->getRoles()) && $task->getCreatedBy() == $user) {

            return true;
        }
        return false;
    }

    public function canCreate(User $user)
    {
        if (in_array('ROLE_USER', $user->getRoles())) {
            return true;
        }
        return false;
    }
}
