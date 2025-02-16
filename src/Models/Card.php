<?php

namespace OxygenSuite\OxygenErgani\Models;

class Card extends Model
{
    public function getEmployerTin(): ?string
    {
        return $this->get('f_afm_ergodoti');
    }

    public function setEmployerTin(string $employerTin): static
    {
        return $this->set('f_afm_ergodoti', $employerTin);
    }

    public function getBranchCode(): ?string
    {
        return $this->get('f_aa');
    }

    public function setBranchCode(string $branchCode): static
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
     * @param  CardDetail[]  $cardDetails
     * @return $this
     */
    public function setDetails(array $cardDetails): static
    {
        return $this->set('Details', ['CardDetails' => $cardDetails]);
    }

    /**
     * @return CardDetail[]|null
     */
    public function getDetails(): ?array
    {
        $cards = $this->get('Details');
        return $cards['Details']['CardDetails'] ?? null;
    }

    /**
     * Adds a card detail to the current list of details.
     *
     * @param  CardDetail|CardDetail[]  $cardDetail  The card detail object to be added.
     * @return static
     */
    public function addCardDetail(CardDetail|array $cardDetail): static
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
