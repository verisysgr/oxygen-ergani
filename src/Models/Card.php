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
        return $this->set('Cards', ['Card' => $cardDetails]);
    }

    public function addDetail(CardDetail $cardDetail): static
    {
        $details = $this->getDetails() ?? [];
        $details[] = $cardDetail;
        return $this->set('Cards', $details);
    }

    /**
     * @return CardDetail[]|null
     */
    public function getDetails(): ?array
    {
        $cards = $this->get('Cards');
        return $cards['Card'] ?? null;
    }
}
