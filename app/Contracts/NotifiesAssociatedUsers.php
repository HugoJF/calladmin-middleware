<?php

namespace App\Contracts;

use Illuminate\Support\Collection;

interface NotifiesAssociatedUsers
{
    /**
     * @return Collection|array
     */
    public function getAssociatedUsers();

    /**
     * @return mixed
     */
    public function getNotification();
}
