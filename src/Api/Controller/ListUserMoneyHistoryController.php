<?php

namespace Mattoid\MoneyHistory\Api\Controller;

use Flarum\Api\Controller\AbstractListController;
use Flarum\Http\RequestUtil;
use Flarum\User\UserRepository;
use Illuminate\Support\Arr;
use Mattoid\MoneyHistory\Api\Serializer\MoneyHistorySerializer;
use Mattoid\MoneyHistory\model\UserMoneyHistory;
use Psr\Http\Message\ServerRequestInterface;
use Tobscure\JsonApi\Document;

class ListUserMoneyHistoryController extends AbstractListController
{
    public $serializer = MoneyHistorySerializer::class;

    public $include = [
        'post.discussion',
        'giver',
        'receiver',
    ];

    protected $repository;

    public function __construct(UserRepository $repository)
    {
        $this->repository = $repository;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $actor = RequestUtil::getActor($request);

        $user = $this->repository->findOrFail(Arr::get($request->getQueryParams(), 'id'), $actor);

//        $actor->assertCan('seeMoneyRewardHistory', $user);

        return UserMoneyHistory::query()
            ->where('user_id', $user->id)
            ->orderBy('change_time', 'desc')
            ->get();
    }
}
