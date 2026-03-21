<?php

namespace Mattoid\MoneyHistory\Attributes;

use Flarum\Api\Serializer\BasicUserSerializer;
use Flarum\User\User;

class UserAttributes
{
    public function __invoke(BasicUserSerializer $serializer, User $user): array
    {
        if ($serializer->getActor()->cannot('money-history.queryOthersMoneyHistory')) {
            return [];
        }

        return [
            'canQueryOthersMoneyHistory' => true,
        ];
    }
}
