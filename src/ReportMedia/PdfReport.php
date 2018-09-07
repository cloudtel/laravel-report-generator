<?php

namespace Cloudtel\ReportGenerator\ReportMedia;

use Cloudtel\ReportGenerator\ReportGenerator;

class PdfReport extends ReportGenerator
{
	public function make()
	{
		$headers = $this->headers;
		$query = $this->query;
		$columns = $this->columns;
		$limit = $this->limit;
		$groupByArr = $this->groupByArr;
		$orientation = $this->orientation;
		$editColumns = $this->editColumns;
		$showTotalColumns = $this->showTotalColumns;
		$styles = $this->styles;
		$showHeader = $this->showHeader;
		$showMeta = $this->showMeta;
		$applyFlush = $this->applyFlush;
		$totals_only = $this->totals_only;
		$totals_inline = $this->totals_inline;
		$orderByArr = $this->orderByArr;
	
		//$this->debugQuery($query);
		if ($this->withoutManipulation) {
			$html = \View::make('report-generator-view::without-manipulation-pdf-template', compact('headers', 'columns', 'showTotalColumns', 'query', 'limit', 'orientation', 'showHeader', 'showMeta', 'applyFlush'))->render();
		} else {
			$html = \View::make('report-generator-view::report-pdf-template', compact('headers', 'columns', 'editColumns', 'showTotalColumns', 'styles', 'query', 'limit', 'groupByArr', 'orientation', 'showHeader', 'showMeta', 'applyFlush', 'totals_only', 'totals_inline', 'orderByArr', 'grand_total'))->render();
		
		}
//xx($html);
		try {
			$pdf = \App::make('snappy.pdf.wrapper');
			$pdf->setOption('footer-font-size', 10);
			$pdf->setOption('footer-left', 'Page [page] of [topage]');
			$pdf->setOption('footer-right', 'Date Printed: ' . date('d M Y H:i:s'));
			$pdf->setOption('enable-javascript', true);
			$pdf->setOption('window-status', 'jsdone');
			$pdf->setOption('no-stop-slow-scripts', true);
		} catch (\ReflectionException $e) {
			try {
				$pdf = \App::make('dompdf.wrapper');
			} catch (\ReflectionException $e) {
				throw new \Exception('Please install either barryvdh/laravel-snappy or laravel-dompdf to generate PDF Report!');
			}
		}
		return $pdf->loadHTML($html)->setPaper($this->paper, $orientation);
	}

	public function stream($filename)
	{
		return $this->make()->stream($filename.'.pdf');
	}

	public function download($filename)
	{
		return $this->make()->download($filename . '.pdf');
	}
	
	function debugQuery($query)
	{
	    $params = array_map(function ($item) {
	        return "'{$item}'";
	    }, $query->getBindings());
	    $result = str_replace_array('?', $params, $query->toSql());
	    xx($result);
	}
}