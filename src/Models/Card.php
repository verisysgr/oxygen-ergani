<?php

namespace OxygenSuite\OxygenErgani\Models;

class Card extends Model
{
    protected array $expectedOrder = [
        'f_afm_ergodoti',
        'f_aa',
        'f_comments',
        'Details',
    ];

    public function getEmployerTin(): ?string
    {
        return $this->get('f_afm_ergodoti');
    }

    public function setEmployerTin(string $employerTin): static
    {
        return $this->set('f_afm_ergodoti', $employerTin);
    }

    public function getBranchCode(): int|string|null
    {
        return $this->get('f_aa');
    }

    public function setBranchCode(int|string $branchCode): static
    {
        return $this->set('f_aa', $branchCode);
    }

    public function getComments(): ?string
    {
        return $this->get('f_comments');
    }

    public function setComments(string $comments): static
    {
        return $this->set('f_comments', $comments);
    }

    /**
     * If an index is provided, it will return the card detail at the given index.
     * Otherwise, it will return the list of card details.
     *
     * @param  int|null  $index  The index of the card detail to be returned.
     * @return CardDetail|array|null
     */
    public function getDetails(?int $index = null): CardDetail|array|null
    {
        if ($index === null) {
            return $this->get('Details')['CardDetails'] ?? null;
        }

        return $this->get('Details')['CardDetails'][$index] ?? null;
    }

    /**
     * Sets the list of card details.
     *
     * @param  CardDetail[]  $cardDetails
     * @return $this
     */
    public function setDetails(array $cardDetails): static
    {
        return $this->set('Details', ['CardDetails' => $cardDetails]);
    }

    /**
     * Adds a card detail to the current list of details.
     *
     * @param  CardDetail|CardDetail[]  $cardDetail  The card detail object to be added.
     * @return static
     */
    public function addDetails(CardDetail|array $cardDetail): static
    {
        $details = $this->getDetails() ?? [];
        if ($cardDetail instanceof CardDetail) {
            $details[] = $cardDetail;
        } else {
            $details = array_merge($details, $cardDetail);
        }

        return $this->setDetails($details);
    }
}
