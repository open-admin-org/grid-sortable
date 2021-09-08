<?php

namespace OpenAdmin\Admin\GridSortable;

use Illuminate\Support\ServiceProvider;

class GridSortableServiceProvider extends ServiceProvider
{
    /**
     * {@inheritdoc}
     */
    public function boot(GridSortable $extension)
    {
        if (! GridSortable::boot()) {
            return ;
        }

        GridSortable::routes(__DIR__.'/../routes/web.php');

        $extension->install();
    }
}
