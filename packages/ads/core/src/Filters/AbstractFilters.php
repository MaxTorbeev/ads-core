<?php

namespace Ads\Core\Filters;

abstract class AbstractFilters
{
    private array $filters = [];

    /**
     * Apply filters to the builder.
     *
     * @return self
     */
    protected function apply(): self
    {
        foreach ($this->getFilters() as $filter => $value) {
            if (method_exists($this, $filter)) {
                $this->$filter($value);
            }
        }

        return $this;
    }

    public function setFilters(array $filters): self
    {
        $this->filters = $filters;

        return $this;
    }

    public function getFilters(): array
    {
        return $this->filters;
    }
}
