<?php

namespace Cloudtel\ReportGenerator;

use Config;
use Illuminate\Contracts\Config\Repository as ConfigRepository;

class ReportGenerator
{
	protected $applyFlush;
	protected $headers;
	protected $columns;
	protected $query;
	protected $limit = null;
	protected $groupByArr = [];
	protected $paper = 'a4';
	protected $orientation = 'portrait';
	protected $editColumns = [];
	protected $showTotalColumns = [];
	protected $styles = [];
	protected $simpleVersion = false;
	protected $withoutManipulation = false;
    protected $showMeta = true;
    protected $showHeader = true;
	protected $totals_only;
	protected $totals_inline;
	protected $orderByArr = [];

	public function __construct()
	{
		$this->applyFlush = (bool) Config::get('report-generator.flush', true);
	}

	public function of($headers, $query, $columns)
	{
		$this->headers = $headers;

		$this->query = $query;
		$this->columns = $this->mapColumns($columns);

		return $this;
	}

    public function showHeader($value = true)
    {
        $this->showHeader = $value;

        return $this;
    }

    public function showMeta($value = true)
    {
        $this->showMeta = $value;

        return $this;
    }

	public function simple()
	{
		$this->simpleVersion = true;

		return $this;
	}

	public function withoutManipulation()
	{
		$this->withoutManipulation = true;

		return $this;
	}

	private function mapColumns($columns)
	{
		$result = [];

		foreach ($columns as $name => $data) {
			if (is_int($name)) {
				$result[$data] = snake_case($data);
			} else {
				$result[$name] = $data;
			}
		}

		return $result;
	}

	public function setPaper($paper)
	{
		$this->paper = strtolower($paper);

		return $this;
	}

	public function editColumn($columnName, $options)
	{
		foreach ($options as $option => $value) {
			$this->editColumns[$columnName][$option] = $value;
		}

		return $this;
	}

	public function editColumns($columnNames, $options)
	{
		foreach ($columnNames as $columnName) {
			$this->editColumn($columnName, $options);
		}

		return $this;
	}

	public function showTotal($columns)
	{
		$this->showTotalColumns = $columns;

		return $this;
	}

	public function groupBy($column)
	{
		if (is_array($column)) {
			$this->groupByArr = $column;
		} else {
			array_push($this->groupByArr, $column);
		}

		return $this;
	}

	public function limit($limit)
	{
		$this->limit = $limit;

		return $this;
	}

	public function setOrientation($orientation)
	{
		$this->orientation = strtolower($orientation);

		return $this;
	}

	public function setCss($styles)
	{
		foreach ($styles as $selector => $style) {
			array_push($this->styles, [
				'selector' => $selector,
				'style' => $style
			]);
		}

		return $this;
	}
	
	public function setOrderBy($column)
	{
		array_push($this->orderByArr, $column);

		return $this;
	}
	
	public function setTotalsOnly($show_totals_only)
	{
		$this->totals_only = $show_totals_only;

		return $this;
	}
	
	public function setTotalsInline($totals_inline)
	{
		$this->totals_inline = $totals_inline;

		return $this;
	}

}
