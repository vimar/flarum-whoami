<?php

namespace Vimar\FlarumWhoAmI\Api\Controllers;

use Flarum\Api\Controller\AbstractShowController;
use Flarum\Api\Serializer\BasicUserSerializer;
use Flarum\Api\Serializer\CurrentUserSerializer;
use Flarum\Http\RequestUtil;
use Flarum\Http\SlugManager;
use Flarum\User\UserRepository;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class WhoamiController extends AbstractShowController
{
    /**
     * {@inheritdoc}
     */
    public $serializer = BasicUserSerializer::class;

    /**
     * {@inheritdoc}
     */
    public $include = ['groups'];

    /**
     * @var SlugManager
     */
    protected $slugManager;

    /**
     * @var UserRepository
     */
    protected $users;

    /**
     * @param SlugManager $slugManager
     * @param UserRepository $users
     */
    public function __construct(SlugManager $slugManager, UserRepository $users)
    {
        $this->slugManager = $slugManager;
        $this->users = $users;
    }

    /**
     * {@inheritdoc}
     */
    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);

        $user = null;
        if ($actor->id) {
            $user = $actor;
            $this->serializer = CurrentUserSerializer::class;
        }

        return $user;
    }
}
