<?php

namespace App\Cruds\Concerns;

trait IsLivewireForm
{
    protected bool $isLivewire = false;

    public function setLivewire(bool $isLivewire = true): static
    {
        $this->isLivewire = $isLivewire;

        return $this;
    }
}



