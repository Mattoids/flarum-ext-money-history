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
            $historyRecord->source_desc = $this->buildSourceDesc($historyRecord);
        }

        return $historyRecords;
    }

    private function buildSourceDesc(UserMoneyHistory $historyRecord): string
    {
        if ($historyRecord->source_key) {
            return $this->translator->trans(
                $historyRecord->source_key,
                $this->buildTranslationParameters($historyRecord->source_params ?? [])
            );
        }

        return $historyRecord->source ?? '';
    }

    private function buildTranslationParameters(array $sourceParams): array
    {
        $parameters = [];

        foreach ($sourceParams as $key => $value) {
            if (! is_scalar($value) && $value !== null) {
                continue;
            }

            if (substr($key, -3) === 'Key' && is_string($value)) {
                $parameters['{'.substr($key, 0, -3).'}'] = $this->translator->trans($value);
                continue;
            }

            $parameters['{'.$key.'}'] = $value;
        }

        return $parameters;
    }
}
