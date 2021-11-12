<?php

namespace App\Twig;

use App\Entity\Task;
use App\Entity\User;
use Twig\TwigFilter;
use App\Security\TaskVoter;
use Twig\Extension\AbstractExtension;
use Symfony\Component\Security\Core\Security;

class TaskVoterExtension extends AbstractExtension
{
    /**
     * @var TaskVoter
     */
    private $taskvoter;

    /**
     * @var User
     */
    protected $user;


    /**
     * TaskExtension constructor.
     * @param CurrentUser $user
     * @param TaskDtoRepository $taskDtoRepository
     * @param TaskVoter $taskvoter
     */
    public function __construct(
        Security $security,
        TaskVoter $taskvoter

    ) {
        $this->user = $security->getUser();

        $this->taskvoter = $taskvoter;
    }

    public function getFilters()
    {
        return [
            new TwigFilter('taskCanRead', [$this, 'taskCanRead']),
            new TwigFilter('taskCanUpdate', [$this, 'taskCanUpdate']),
            new TwigFilter('taskCanDelete', [$this, 'taskCanDelete']),
        ];
    }



    public function taskCanRead(Task $item)
    {
        return $this->taskvoter->canRead($item, $this->user);
    }

    public function taskCanUpdate(Task $item)
    {
        return $this->taskvoter->canUpdate($item, $this->user);
    }

    public function taskCanDelete(Task $item)
    {
        return $this->taskvoter->canDelete($item, $this->user);
    }
}
