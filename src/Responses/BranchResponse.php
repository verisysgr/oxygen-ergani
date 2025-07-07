<?php

namespace OxygenSuite\OxygenErgani\Responses;

use OxygenSuite\OxygenErgani\Models\Services\Branch;

class BranchResponse extends Response
{
    public array $branches = [];

    protected function processData(): void
    {
        if (!isset($this->attributes['EX_BASE_02'])) {
            $this->attributes = [];
            return;
        }

        if (!isset($this->attributes['EX_BASE_02']['Pararthma'])) {
            $this->attributes = [];
            return;
        }

        $this->attributes = $this->attributes['EX_BASE_02']['Pararthma'];

        // Determine if the response contains multiple branches or a single branch
        if (array_is_list($this->attributes)) {
            // Multiple branches response
            foreach ($this->attributes as $branchAttributes) {
                $this->branches[] = $this->createBranchFromAttributes($branchAttributes);
            }
        } else {
            // Single branch response
            $this->branches[] = $this->createBranchFromAttributes($this->attributes);
        }
    }

    public function getBranches(): array
    {
        return $this->branches;
    }

    private function createBranchFromAttributes(array $attributes): Branch
    {
        return (new Branch())
            ->setAa($attributes['Aa'] ?? null)
            ->setAddress($attributes['Address'] ?? null);
    }
}
