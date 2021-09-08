<?php

namespace OpenAdmin\Admin\GridSortable;

use OpenAdmin\Admin\Admin;
use OpenAdmin\Admin\Grid\Displayers\AbstractDisplayer;

class SortableDisplay extends AbstractDisplayer
{
    protected function script()
    {
        $id = $this->getGrid()->tableID;

        $route = route('open-admin-grid-sortable');
        $class = addslashes(get_class($this->getGrid()->model()->getOriginalModel()));

        $script = <<<SCRIPT


    let sortableSettings = {
        animation: 150,
        fallbackOnBody: false,
        swapThreshold: 0.65,
        handle : '.grid-sortable-handle',
        onUpdate: function (evt) {
            document.querySelector(".grid-save-order-btn").classList.remove("d-none");
        },
    }
    let setSortable = new Sortable(document.querySelector("#{$id} tbody"), sortableSettings);

    document.querySelector(".grid-save-order-btn").addEventListener("click",function () {

        let sorts = [];
        document.querySelectorAll('.grid-sortable-handle').forEach(elm => {
            sorts.push(elm.dataset);
        });

        admin.ajax.post('{$route}', {
            _model: '{$class}',
            _sort: sorts,
        },
        function(result){
            if (result.data.status) {
                admin.toastr.success(result.data.message);
                admin.ajax.reload();
            } else {
                admin.toastr.error(result.data.message);
            }
        });
    });

SCRIPT;

        Admin::script($script);
    }

    protected function getRowSort()
    {
        $column = data_get($this->row->sortable, 'order_column_name', 'order_column');

        return $this->row->{$column};
    }

    public function display()
    {
        $this->script();

        $key = $this->getKey();
        $sort = $this->getRowSort();

        return <<<HTML
<a class="grid-sortable-handle" style="cursor: move;" data-key="{$key}" data-sort="{$sort}">
    <i class="icon-arrows-alt-v"></i>
</a>
HTML;
    }
}
