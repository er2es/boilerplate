<?php

namespace Sebastienheyd\Boilerplate\Datatables;

use Exception;
use Illuminate\Http\JsonResponse;
use Yajra\DataTables\DataTables;

abstract class Datatable
{
    public    $slug       = '';
    public    $datasource;
    protected $attributes = [
        'filters'      => true,
        'info'         => true,
        'lengthChange' => true,
        'order'        => [],
        'ordering'     => true,
        'pageLength'   => 10,
        'paging'       => true,
        'pagingType'   => 'simple_numbers',
        'searching'    => true,
        'stateSave'    => false,
        'lengthMenu'   => [10, 25, 50, 100],
    ];

    abstract public function datasource();
    abstract public function columns(): array;
    public function setUp() { }

    /**
     * Renders the DataTable Json that will be used by the ajax call.
     *
     * @throws Exception
     * @return JsonResponse
     */
    public function make(): JsonResponse
    {
        $datatable = DataTables::of($this->datasource());

        $raw = [];
        foreach ($this->columns() as $column) {
            if ($column->filter) {
                $datatable->filterColumn($column->name ?? $column->data, $column->filter);
            }

            if ($column->raw) {
                $raw[] = $column->data;
                $datatable->editColumn($column->data, $column->raw);
            }
        }

        if (! empty($raw)) {
            $datatable->rawColumns($raw);
        }

        return $datatable->make(true);
    }

    /**
     * Sets the DataTable order by column name and direction.
     *
     * @param $column
     * @param  string  $order
     * @return $this
     */
    public function order($column, string $order = 'asc'): Datatable
    {
        if (! is_array($column)) {
            $column = [$column => $order];
        }

        foreach ($column as $c => $o) {
            $this->attributes['order'][] = [$this->getColumnIndex($c), $o];
        }

        return $this;
    }

    /**
     * Gets the column index number by column name.
     *
     * @param $column
     * @return int|string
     */
    private function getColumnIndex($column)
    {
        if (is_int($column)) {
            return $column;
        }

        foreach ($this->columns() as $k => $c) {
            if ($c->data === $column) {
                return $k;
            }
        }

        return 0;
    }

    /**
     * Defines the array of values to use for the length selection menu.
     *
     * @param  array  $value
     * @return $this
     */
    public function lengthMenu(array $value): Datatable
    {
        $this->attributes['lengthMenu'] = $value;

        return $this;
    }

    /**
     * Sets the paging type to use. Can be numbers, simple, simple_numbers, full, full_numbers, first_last_numbers.
     *
     * @param $type
     * @return $this
     */
    public function pagingType($type): Datatable
    {
        if (in_array($type, ['numbers', 'simple', 'simple_numbers', 'full', 'full_numbers', 'first_last_numbers'])) {
            $this->attributes['pagingType'] = $type;
        }

        return $this;
    }

    /**
     * Sets the default page length.
     *
     * @param  int  $length
     * @return $this
     */
    public function pageLength(int $length = 10): Datatable
    {
        $this->attributes['pageLength'] = $length;

        return $this;
    }

    /**
     * Enable state saving. Stores state information such as pagination position, display length, filtering and sorting.
     *
     * @return $this
     */
    public function stateSave(): Datatable
    {
        $this->attributes['stateSave'] = true;

        return $this;
    }

    /**
     * Disables filters bar.
     *
     * @return $this
     */
    public function noFilters(): Datatable
    {
        $this->attributes['filters'] = false;

        return $this;
    }

    /**
     * Disables the paging.
     *
     * @return $this
     */
    public function noPaging(): Datatable
    {
        $this->attributes['paging'] = false;

        return $this;
    }

    /**
     * Disables the user's ability to change the paging display length.
     *
     * @return $this
     */
    public function noLengthChange(): Datatable
    {
        $this->attributes['lengthChange'] = false;

        return $this;
    }

    /**
     * Disable the ordering (sorting).
     *
     * @return $this
     */
    public function noOrdering(): Datatable
    {
        $this->attributes['ordering'] = false;

        return $this;
    }

    /**
     * Alias of noOrdering.
     *
     * @return $this
     */
    public function noSorting(): DataTable
    {
        return $this->noOrdering();
    }

    /**
     * Disables the searching.
     *
     * @return $this
     */
    public function noSearching(): Datatable
    {
        $this->attributes['searching'] = false;

        return $this;
    }

    /**
     * Disables the table information.
     *
     * @return $this
     */
    public function noInfo(): Datatable
    {
        $this->attributes['info'] = false;

        return $this;
    }

    /**
     * Magic method to get property or attribute.
     *
     * @param $name
     * @return false|mixed|string|null
     */
    public function __get($name)
    {
        if (property_exists($this, $name)) {
            return $this->$name;
        }

        if (in_array($name, ['order', 'lengthMenu'])) {
            return json_encode($this->attributes[$name]);
        }

        if (isset($this->attributes[$name])) {
            return $this->attributes[$name];
        }

        return null;
    }

    /**
     * Magic method to check if property or attribute is set or not.
     *
     * @param $name
     * @return bool
     */
    public function __isset($name)
    {
        if (property_exists($this, $name)) {
            return true;
        }

        if (isset($this->attributes[$name])) {
            return true;
        }

        return false;
    }
}