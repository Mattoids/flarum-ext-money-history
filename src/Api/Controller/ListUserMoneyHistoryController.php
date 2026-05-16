<?php

namespace Mattoid\MoneyHistory\Api\Controller;

use Flarum\Api\Controller\AbstractListController;
use Mattoid\MoneyHistory\Api\Serializer\MoneyHistorySerializer;
use Mattoid\MoneyHistory\Model\UserMoneyHistory;
use Psr\Http\Message\ServerRequestInterface;
use Illuminate\Support\Arr;
use Tobscure\JsonApi\Document;
use Flarum\Http\UrlGenerator;

class ListUserMoneyHistoryController extends AbstractListController
{
    protected $url;
    public $serializer = MoneyHistorySerializer::class;

    public $include = [
        'actor'
    ];

    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $params = $request->getQueryParams();
        $actor = $request->getAttribute('actor');
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);
        $filters = $this->extractFilter($request);

        $userId = Arr::get($request->getAttribute('routeParameters', []), 'id')
            ?: Arr::get($filters, 'user')
            ?: Arr::get($params, 'id');
        if (! $userId) {
            $actor->assertRegistered();
            $userId = $actor->id;
        } else {
            if ($actor->id != $userId) {
                $actor->assertCan('money-history.queryOthersMoneyHistory');
            }
        }
        $moneyHistoryQuery = UserMoneyHistory::query()->where(['user_id' => $userId]);
        $historyRecords = $moneyHistoryQuery
            ->orderBy('id', 'desc')
            ->skip($offset)
            ->take($limit + 1)
            ->get();

        $hasMoreResults = $limit > 0 && $historyRecords->count() > $limit;

        if ($hasMoreResults) {
            $historyRecords->pop();
        }

        $document->addPaginationLinks(
            $this->url->to('api')->route('user.money.history', ['id' => $userId]),
            $params,
            $offset,
            $limit,
            $hasMoreResults ? null : 0
        );

        return $historyRecords;
    }
}
