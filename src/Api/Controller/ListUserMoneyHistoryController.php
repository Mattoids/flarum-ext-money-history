<?php

namespace Mattoid\MoneyHistory\Api\Controller;

use Flarum\Locale\Translator;
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
        'user',
        'actor'
    ];

    protected $translator;

    public function __construct(UrlGenerator $url, Translator $translator)
    {
        $this->url = $url;
        $this->translator = $translator;
    }

    protected function data(ServerRequestInterface $request, Document $document)
    {
        $params = $request->getQueryParams();
        $actor = $request->getAttribute('actor');
        $limit = $this->extractLimit($request);
        $offset = $this->extractOffset($request);

        $userId = Arr::get($request->getQueryParams(), 'id');
        if (! $userId) {
            $actor->assertRegistered();
            $userId = $actor->id;
        } else {
            if ($actor->id != $userId) {
                $actor->assertCan('money-history.queryOthersMoneyHistory');
            }
        }
        $moneyHistoryQuery = UserMoneyHistory::query()->where(["user_id" => $userId]);
        $historyRecords = $moneyHistoryQuery
            ->skip($offset)
            ->take($limit + 1)
            ->orderBy('id', 'desc')
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

        foreach ($historyRecords as $historyRecord) {
            if ($historyRecord->source_key) {
                $historyRecord->source_desc = $this->translator->trans($historyRecord->source_key);
            }
        }

        return $historyRecords;
    }
}
