<?php

namespace OpenAdmin\Admin\GridSortable;

use OpenAdmin\Admin\Extension;
use OpenAdmin\Admin\Grid;
use OpenAdmin\Admin\Grid\Tools\ColumnSelector;
use Spatie\EloquentSortable\Sortable;

class GridSortable extends Extension
{
    public $name = 'grid-sortable';

    protected $column = '__sortable__';

    public function install()
    {
        ColumnSelector::ignore($column = $this->column);

        Grid::macro('sortable', function () use ($column) {
            $this->tools(function (Grid\Tools $tools) {
                $tools->append(new SaveOrderBtn());
            });

            $sortName = $this->model()->getSortName();

            if (!request()->has($sortName)
                && $this->model()->eloquent() instanceof Sortable
            ) {
                $this->model()->ordered();
            }

            $this->column($column, ' ')
                ->displayUsing(SortableDisplay::class);
        });
    }
}
