<?php

namespace Mattoid\MoneyHistory\Api\Controller;

use Flarum\Api\Controller\AbstractListController;
use Flarum\User\UserRepository;
use Mattoid\MoneyHistory\Api\Serializer\MoneyHistorySerializer;
use Mattoid\MoneyHistory\model\UserMoneyHistory;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Support\Arr;
use Tobscure\JsonApi\Document;
use Flarum\Http\UrlGenerator;

class ListUserMoneyHistoryController extends AbstractListController
{
    protected $url;
    public $serializer = MoneyHistorySerializer::class;

    public $include = [
        'user',
        'createUser'
    ];

    protected $repository;

    public function __construct(UserRepository $repository, UrlGenerator $url)
    {
        $this->url = $url;
        $this->repository = $repository;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $params = $request->getQueryParams();
        $actor = $request->getAttribute('actor');
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);

        $userId = Arr::get($request->getQueryParams(), 'id');
        if (!$userId) {
            $userId = $actor->id;
        }
        $moneyHistoryQuery = UserMoneyHistory::query()->where(["user_id"=>$userId]);
        $MoneyHistoryResult = $moneyHistoryQuery
            ->skip($offset)
            ->take($limit + 1)
            ->orderBy('id', 'desc')
            ->get();

        $hasMoreResults = $limit > 0 && $MoneyHistoryResult->count() > $limit;

        if($hasMoreResults){
            $MoneyHistoryResult->pop();
        }

        $document->addPaginationLinks(
            $this->url->to('api')->route('user.money.history', ['id' => $userId]),
            $params,
            $offset,
            $limit,
            $hasMoreResults ? null : 0
        );

        return $MoneyHistoryResult;
    }
}
