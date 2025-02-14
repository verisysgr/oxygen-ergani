<?php

namespace OxygenSuite\OxygenErgani\Http\Documents;

use OxygenSuite\OxygenErgani\Exceptions\ErganiException;
use OxygenSuite\OxygenErgani\Models\Card;
use OxygenSuite\OxygenErgani\Responses\WorkCardResponse;

class WorkCard extends Documents
{
    private const ACTION = 'WRKCardSE';

    /**
     * @param  Card|Card[]  $cards
     * @return WorkCardResponse[]
     *
     * @throws ErganiException
     */
    public function handle(Card|array $cards): array
    {
        if ($cards instanceof Card) {
            $cards = [$cards];
        }

        $body = array_map(fn (Card $card) => $card->toArray(), $cards);

        return $this->submit($body)->morphToArray(WorkCardResponse::class);
    }

    protected function action(): string
    {
        return self::ACTION;
    }
}