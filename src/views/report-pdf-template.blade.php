<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8">
		<style>
			body {
			    font-family: Arial, Helvetica, sans-serif;
			}
			.wrapper {
				margin: 0 -20px 0;
				padding: 0 15px;
			}
		    .middle {
		        text-align: center;
		    }
		    .title {
			    font-size: 25px;
			    text-transform: capitalize;
		    }
		    .subtitle {
			    font-size: 15px;
			    text-transform: capitalize;
		    }
		    .pb-10 {
		    	padding-bottom: 10px;
		    }
		    .pb-5 {
		    	padding-bottom: 5px;
		    }
		    .head-content-left{
		    	float:left;
		    	padding-bottom: 4px;
		    }
		    .head-content-right{
		    	float:right;
		    	padding-bottom: 4px;
		    	font-size: 18px;
		    }
		    .group-header{
		    	background-color:#fff;
		    	color:#000;
		    	font-size: 15px;
		    	height: 20px;
		    }
		    .grand_total{
                background-color: #3b5998;
		    	color:#fff;   
		    	font-size: 16px;
		    	height: 25px;
		    }
		    .group_total b{
				border-top: 1px solid #000;
				padding-top: 2px;
		    }
		    .group-title-header{
		    	color:#fff;    
		    	background-color: #4267b2;
   				border-color: #29487d;
		    	font-size: 15px;
		    	height: 35px;
		    	text-transform: underline;
		    }
		    .group-subtitle-header{
		    	color:#212121;    
		    	background-color: #a3d0e4;
   				border-color: #29487d;
		    	font-size: 15px;
		    	height: 35px;
		    	text-transform: underline;
		    }
		    .group-title-header-indented {
		    	color:#fff;    
		    	background-color: #4267b2;
   				border-color: #29487d;
		    	font-size: 15px;
		    	height: 35px;
		    	text-transform: underline;
		    }
		    .td-indented{
		    	background-color: #fff;
		    }
		    tr.tr-inline{
		    	height:0px !important;
		    }
		    .tr-inline td{
		    	padding: 0 10px;
			    position: relative;
			    top: -35px;
		    }
		    table.table {
		    	font-size: 14px;
		    }
			.page-break {
		        page-break-after: always;
		        page-break-inside: avoid;
			}
			tr{
		    	height: 0px;
			}
			tr.even {
				background-color: #d9ddeb;
			}
			table .left {
				text-align: left;
			}
			table .right {
				text-align: right;
			}
			table .bold {
				font-weight: 600;
			}
			.bg-black {
				background-color: #000;
			}
			.f-white {
				color: #fff;
			}
            td {
                padding:5px;
            }
            #tbodyheader{
                font-weight: bold;     
                background-color: #3b5998;
			    background-image: linear-gradient(#4e69a2, #3b5998 50%);
			    border-bottom: 1px solid #133783;
			    color: #fff;
		    	font-size: 16px;
            }
            #tbodyheader tr{
            	
		    	height: 40px;
            }
            
			table {
			border-collapse: collapse;
			}
			table td, table th {
			border: 0;
			}
			table tr:first-child th {
			border-top: 0;
			}
			table tr:last-child td {
			border-bottom: 0;
			}
			table tr td:first-child,
			table tr th:first-child {
			border-left: 0;
			}
			table tr td:last-child,
			table tr th:last-child {
			border-right: 0;
			}
			
			@foreach ($styles as $style)
			{{ $style['selector'] }} {
				{{ $style['style'] }}
			}
			@endforeach
		</style>
		<head>
		<title> {{ $headers['title'] }}</title>
		</head>
	</head>
	<body>
		<?php
		$ctr = 1;
		$no = 1;
		$currentGroupByData = [];
		$total = [];
		$grand_total = [];
		$group_total = [];
		$isOnSameGroup = true;
		$diffGroup = [];
		$main_groups = [];
		$grandTotalSkip = 0;

		foreach ($showTotalColumns as $column => $type) {
			$total[$column] = 0;
		}

		if ($showTotalColumns != []) {
			foreach ($columns as $colName => $colData) {
				if (!array_key_exists($colName, $showTotalColumns)) {
					$grandTotalSkip++;
				} else {
					break;
				}
			}
		}
		?>
		<div class="wrapper">
		    <div class="pb-5">
		    	<div class="head-content-left">
			    <div class="pb-10 title">
			        {{ $headers['title'] }}
			    </div>
			    <div class="pb-10 subtitle">
			      <?php echo nl2br($headers['subtitle']); ?>
			    </div>
			    </div>
    			@if ($showMeta)
				<div class="head-content-right">
					<table cellpadding="0" cellspacing="0" width="100%" border="0">
						<?php $metaCtr = 0; ?>
						@foreach($headers['meta'] as $name => $value)
							@if ($metaCtr % 2 == 0)
							<tr>
							@endif
								<td>{{ ucwords($value) }}</td>
							@if ($metaCtr % 2 == 1)
							</tr>
							@endif
							<?php $metaCtr++; ?>
						@endforeach
					</table>
				</div>
				@endif
		    </div>
		    <div class="content">
		    	<table width="100%" class="table">
		    		@if ($showHeader)
		    		<tbody id="tbodyheader">
			    		<tr>
			    		@if(count($showTotalColumns) > 0 && count($columns)==1)
			    		<td></td>
			    		@endif	
			    		<?php $hidden_columns = 0; ?>
			    			@foreach ($columns as $colName => $colData)
			    				<?php $colName = ($colData == 'Line')?'':$colName; ?>
			    				@if (array_key_exists($colName, $editColumns))
				    				@if(!empty($editColumns[$colName]['class']) && strpos($editColumns[$colName]['class'], 'hidden') !== false)
			    						<?php $hidden_columns++; ?>
				    				@else
			    					<td class="{{ isset($editColumns[$colName]['class']) ? $editColumns[$colName]['class'] : 'left' }}">{{ $colName }}</td>
			    					@endif
			    				@else
				    				<td class="left">{{ $colName }}</td>
			    				@endif
			    			@endforeach
			    		</tr>
		    		</tbody>
		    		@endif
		    		<?php
		    		$chunkRecordCount = ($limit == null || $limit > 50000) ? 50000 : $limit + 1;
		    		$__env = isset($__env) ? $__env : null;
				//	xx($columns); xx($editColumns); xx($showTotalColumns);
				//xx($totals_inline);
					$query->chunk($chunkRecordCount, function($results) use(&$ctr, &$no, &$total, &$diffGroup, &$currentGroupByData, &$isOnSameGroup, $grandTotalSkip, $columns, $limit, $editColumns, $showTotalColumns, $groupByArr, $applyFlush, $__env, $totals_only, $totals_inline, $orderByArr, &$grand_total, &$group_total) {
		
						$post_result_sort = false;
						if(!empty($orderByArr)){
							foreach($orderByArr as $order){
								if($order['type'] == 'function')
								$post_result_sort = true;
							}
						}
						
						if($post_result_sort){
							$arr_results = json_decode(json_encode($results), true);
							usort($arr_results, function($a, $b) use ($editColumns,$columns, $groupByArr, $orderByArr) {
							    $asort = 1;
								$bsort = 1;
								
								if(!empty($groupByArr)){
									$groupby_field = strtolower(str_replace(' ','',$groupByArr[0]));
									$asort = $a[$groupby_field];
									$bsort = $b[$groupby_field];
								}
								
                                foreach($orderByArr as $ordersort){
                                    if($asort === $bsort){
                                    	if($ordersort['type'] == 'function'){
                                    		if($ordersort['direction'] == 'desc'){
												$bsort = $columns[$ordersort['column']]($a,$editColumns[$ordersort['column']]['field']);
												$asort = $columns[$ordersort['column']]($b,$editColumns[$ordersort['column']]['field']);
											}else{
												$asort = $columns[$ordersort['column']]($a,$editColumns[$ordersort['column']]['field']);
												$bsort = $columns[$ordersort['column']]($b,$editColumns[$ordersort['column']]['field']);
											}
                                    	}else{
	                                    	if(strpos($ordersort['column'],'.') !== false)
											$orderby_field = $ordersort['column'].'_group';
	                                    	else
	                                        $orderby_field = strtolower(str_replace(' ','',$ordersort['column']));
	                                        $asort = $a[$orderby_field];
	                                        $bsort = $b[$orderby_field];
                                    	}
                                    }
                                }
                                
								return $asort <=> $bsort;
							});
							
							$results = json_decode(json_encode($arr_results));
						}
					?>
				
		    		@foreach($results as $result)
						<?php
							if ($limit != null && $ctr == $limit + 1) return false;
							if ($groupByArr) {
								$isOnSameGroup = true;
								if(!empty($hidden_columns))
		    					$colspan_count = intval(count($columns) - $hidden_columns);
		    					else
		    					$colspan_count = intval(count($columns));
		    					
								if(count($showTotalColumns) > 0 && count($columns)==1){
									$colspan_count = 2;
								}	
		    					
		    					$group_headers = [];
								foreach ($groupByArr as $groupBy) {
									$group_title = (!empty($columns[$groupBy]))?$groupBy:'';
									if(empty($group_title)){
										$title_arr = explode('.', $groupBy);
										$group_title = ucwords(str_replace(['_group','_'],['',' '],$title_arr[1]));
									}
								
									if(empty($columns[$groupBy])){
										$thisGroupByData[$groupBy] = (!empty($result->$groupBy))?$result->$groupBy:'';	
									}else if (is_object($columns[$groupBy]) && $columns[$groupBy] instanceof Closure) {
				    					$thisGroupByData[$groupBy] = $columns[$groupBy]($result,$editColumns[$groupBy]['field']);	
				    				} else {
				    					$thisGroupByData[$groupBy] =  (!empty($result->{$columns[$groupBy]}))?$result->{$columns[$groupBy]}:"";
				    				}
				    				
				    				if (isset($currentGroupByData[$groupBy])) {
				    					if ($thisGroupByData[$groupBy] != $currentGroupByData[$groupBy]) {
				    						$isOnSameGroup = false;
				    						$diffGroup[$groupBy] =  true;
				    					}else{
				    						$diffGroup[$groupBy] = false;
				    					}
				    				}
				    				
				    				
				    				
		    						$group_headers[$groupBy] = $thisGroupByData[$groupBy];
				    				

				    				$currentGroupByData[$groupBy] = $thisGroupByData[$groupBy];
				    			}
				    		
				    			if($ctr == 1 && !empty($group_headers)){
				    				$i = 0;
					    			foreach($group_headers as $group_header){
					    				if($i == 0){
	    									echo '<tr class="group-title-header"><td colspan="' . intval($colspan_count)  . '"><b>'.$group_header.'</b></td></tr>';
					    				}else{
	    									echo '<tr class="group-subtitle-header"><td colspan="' . intval($colspan_count)  . '">'.$group_header.'</td></tr>';
					    				}
					    				
					    				$i++;
					    				
					    			}
				    			}
				    			
    							
    							
				    			if (empty($isOnSameGroup)) {
				    		
					    				if(!empty($showTotalColumns)){
					    					$tr_class = ($totals_inline == 1)?'tr-inline':'';
			    							echo '<tr class="group-header '.$tr_class.'">
			    							<td colspan="' . $grandTotalSkip . '"></td>';
											$dataFound = false;
			    							foreach ($columns as $colName => $colData) {
			    								if(!empty($editColumns[$colName]['class']) && strpos($editColumns[$colName]['class'], 'hidden') !== false){
			    						
				    							}else{
			    								if (array_key_exists($colName, $showTotalColumns)) {
			    									if ($showTotalColumns[$colName] == 'sum') {
			    										echo '<td class="right"><b>' . number_format($total[$colName], 2, '.', ',') . '</b></td>';
			    									} else {
			    										echo '<td class="left"><b>' . $total[$colName] . '</b></td>';
			    									}
			    									$dataFound = true;
			    								} else {
			    									if ($dataFound) {
				    									echo '<td></td>';
				    								}
			    								}
			    							}
			    							}
			    						echo '</tr>';//<tr style="height: 10px;"><td colspan="99">&nbsp;</td></tr>';
					    				}
						    			if(!empty($group_headers)){
						    				$i = 0;
							    			foreach($group_headers as $k => $v){
							    				if($i == 0){
							    					
							    					if($diffGroup[$k]){
							    			
							    			if(!empty($group_total)){	
			    							echo '<tr class="group_total">
			    							<td colspan="' . $grandTotalSkip . '"></td>';
											$dataFound = false;
			    							foreach ($columns as $colName => $colData) {
			    							
			    								if (array_key_exists($colName, $showTotalColumns)) {
			    									if ($showTotalColumns[$colName] == 'sum') {
			    										echo '<td class="right"><b>' . number_format($group_total[$colName], 2, '.', ',') . '</b></td>';
			    									} else {
			    										echo '<td class="left"><b>' . $group_total[$colName] . '</b></td>';
			    									}
			    								}
				    							
			    							}
			    							echo '</tr>';
							    			}
			    							$group_total = [];
			    										echo '<tr class="group-title-header"><td colspan="' . intval($colspan_count)  . '"><b>'.$v.'</b></td></tr>';
							    					}
							    				}else{
							    					if($diffGroup[$k])
			    									echo '<tr class="group-subtitle-header"><td colspan="' . intval($colspan_count)  . '">'.$v.'</td></tr>';
							    				}
							    				$i++;
							    				
							    			}
						    			}
					    			
									// Reset No, Reset Grand Total
		    						$no = 1;
		    						foreach ($showTotalColumns as $showTotalColumn => $type) {
		    							$total[$showTotalColumn] = 0;
		    						}
		    						$isOnSameGroup = true;
		    					}
			    			}
			    		
						?>
						
						@if($totals_only == 0)
			    		<tr align="center" class="{{ ($no % 2 == 0) ? 'even' : 'odd' }}">
		    			@endif
			    			
			    			@foreach ($columns as $colName => $colData)	
			    			@if(!empty($editColumns[$colName]['class']) && strpos($editColumns[$colName]['class'], 'hidden') !== false)
			    						
				    		@else
			    				<?php
			    					
				    				$class = 'left';
				    				// Check Edit Column to manipulate class & Data
				    				if (is_object($colData) && $colData instanceof Closure) {
				    				
				    					$generatedColData = $colData($result,$editColumns[$colName]['field']);
				    				} else {
				    					$generatedColData = $result->{$colData};
				    				}
				    				$displayedColValue = $generatedColData;
				    				if (array_key_exists($colName, $editColumns)) {
				    					if (isset($editColumns[$colName]['class'])) {
				    						$class = $editColumns[$colName]['class'];
				    					}

				    					if (isset($editColumns[$colName]['displayAs'])) {
				    						$displayAs = $editColumns[$colName]['displayAs'];
					    					if (is_object($displayAs) && $displayAs instanceof Closure) {
					    					
					    						$displayedColValue = $displayAs($result,$editColumns[$colName]['field']);
					    					} elseif (!(is_object($displayAs) && $displayAs instanceof Closure)) {
					    						$displayedColValue = $displayAs;
					    					}
					    				}
				    				}

				    				if (array_key_exists($colName, $showTotalColumns)) {
				    					
				    					if($showTotalColumns[$colName] == 'sum')
		    							$total[$colName] += $generatedColData;
		    							elseif($showTotalColumns[$colName] == 'count')
		    							$total[$colName] += 1;
				    					
		    							if(empty($group_total[$colName]))
				    					$group_total[$colName] = 0;
				    					if($showTotalColumns[$colName] == 'sum')
		    							$group_total[$colName] += $generatedColData;
		    							elseif($showTotalColumns[$colName] == 'count')
		    							$group_total[$colName] += 1;
				    					
				    					
				    					if(empty($grand_total[$colName]))
				    					$grand_total[$colName] = 0;
				    					if($showTotalColumns[$colName] == 'sum')
		    							$grand_total[$colName] += $generatedColData;
		    							elseif($showTotalColumns[$colName] == 'count')
		    							$grand_total[$colName] += 1;
				    				}
			    				?>
			    				@if($totals_only == 0)
			    				<td class="{{ $class }}">{{ $displayedColValue }}</td>
		    					@endif
		    					@endif
			    			@endforeach
		    			@if($totals_only == 0)
			    		</tr>
			    		@endif
		    			<?php $ctr++; $no++; ?>
		    		@endforeach
		            <?php
		            if ($applyFlush) flush();
		            });
		            ?>
					@if (!empty($currentGroupByData) && $showTotalColumns != [] && $ctr > 1)
					    	<?php				
					    	$tr_class = ($totals_inline == 1)?'tr-inline':'';
							echo '<tr class="group-header '.$tr_class.'">';
							?>
							<td colspan="{{ $grandTotalSkip }}"><b></b></td> {{-- For Number --}}
							<?php $dataFound = false; ?>
							@foreach ($columns as $colName => $colData)
								@if(!empty($editColumns[$colName]['class']) && strpos($editColumns[$colName]['class'], 'hidden') !== false)
			    						
				    			@else
								@if (array_key_exists($colName, $showTotalColumns))
									<?php $dataFound = true; ?>
								
									@if ($showTotalColumns[$colName] == 'sum')
										<td class="right"><b>{{ number_format($total[$colName], 2, '.', ',') }}</b></td>
									@else
										<td class="left"><b>{{ $total[$colName] }}</b></td>
									@endif
								@else
									@if ($dataFound)
										<td></td>
									@endif
								@endif
								@endif
							@endforeach
						</tr>
						<?php
						if(!empty($group_total)){
						echo '<tr class="group_total">
						<td colspan="' . $grandTotalSkip . '"></td>';
						$dataFound = false;
						foreach ($columns as $colName => $colData) {
						if (array_key_exists($colName, $showTotalColumns)) {
							if ($showTotalColumns[$colName] == 'sum') {
							echo '<td class="right"><b>' . number_format($group_total[$colName], 2, '.', ',') . '</b></td>';
							}else {
								echo '<td class="left"><b>' . $group_total[$colName] . '</b></td>';
							}
						}
						}
						echo '</tr>';
						}
						$group_total = [];
						?>
						
					@endif
					@if (count($showTotalColumns) > 0 && $grand_total > 0)
						<tr class="grand_total">
							<td colspan="{{ $grandTotalSkip }}"><b></b></td>
							<?php $j = 1;?>
							@foreach ($columns as $colName => $colData)
								@if(!empty($editColumns[$colName]['class']) && strpos($editColumns[$colName]['class'], 'hidden') !== false)
			    						
				    			@else
								@if (array_key_exists($colName, $showTotalColumns))
								
									@if ($showTotalColumns[$colName] == 'sum')
										<td class="right"><b>{{ number_format($grand_total[$colName], 2, '.', ',') }}</b></td>
									@else
										<td class="left"><b>{{ $grand_total[$colName] }}</b></td>
									@endif
								@elseif($j>$grandTotalSkip)
								<td></td>
								@endif
								<?php $j++; ?>
								@endif
							@endforeach
						</tr>
					@endif
		    	</table>
			</div>
		</div>
		
		<script src="https://code.jquery.com/jquery-2.2.4.min.js"></script>
		<script type="text/javascript">
		jQuery(document).ready(function(){
			
				jQuery('.tr-inline').each(function(){
					var colspan = jQuery(this).prev('tr').find('td:first').attr('colspan');
					var new_colspan = colspan - 1;
					jQuery(this).prev('tr').find('td:first').attr('colspan',new_colspan);
					jQuery(this).prev('tr').find('td:last').after('<td class=\'right\'>'+ jQuery(this).find('td:last-child').text()+'</td>');
				}).promise().done( function(){ 	jQuery('.tr-inline').remove();window.status = 'jsdone'; } );
		});
		</script>
	    <script type="text/php">
	    	@if (strtolower($orientation) == 'portrait')
	        if ( isset($pdf) ) {
	            $pdf->page_text(30, ($pdf->get_height() - 26.89), "Date Printed: " . date('d M Y H:i:s'), null, 10);
	        	$pdf->page_text(($pdf->get_width() - 84), ($pdf->get_height() - 26.89), "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10);
	        }
		    @elseif (strtolower($orientation) == 'landscape')
		    if ( isset($pdf) ) {
		        $pdf->page_text(30, ($pdf->get_height() - 26.89), "Date Printed: " . date('d M Y H:i:s'), null, 10);
		    	$pdf->page_text(($pdf->get_width() - 84), ($pdf->get_height() - 26.89), "Page {PAGE_NUM} of {PAGE_COUNT}", null, 10);
		    }
		    @endif
	    </script>
	</body>
</html>