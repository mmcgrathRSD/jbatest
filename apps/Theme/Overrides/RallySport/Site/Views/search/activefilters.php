<?php  $filters = (array) $state->get('filter');
unset($filters['published_today']);
unset($filters['publication_status']);
$base = \Dsc\Url::full(false);

$args = $_REQUEST;
$activeFilters = array();
foreach($filters as $key => $value) {
		if($key == 'categories') {
			if(!empty($value)) {

				foreach($value as $catKey => $catId) {
					$link = $_REQUEST;
					if(is_array(@$link['filter']['categories']) ) {
					//look up category name from id;
					$name = (new \RallyShop\Models\Categories)->collection()->findOne(array('_id' => new \MongoDB\BSON\ObjectID($catId)), [
                        'projection' => [
                            'title' => true,
                            '_id' => false
                        ]
                    ]);			
					if(($found = array_search($catId, @$link['filter']['categories'])) !== false) {
						 
						unset($link['filter']['categories'][$found]);
					}
					$activeFilters[] = 
					'<a role="button" class="filterable removeFilter" href="'.$base.'?'.http_build_query($link).'">'.$name['title'].'&nbsp;<i class="glyphicon glyphicon-remove-2"></i></a>';	
					}
				}
			}
		}
		elseif ($key == 'universal') {
				if($value== 'yes') {
					@$link['filter']['universal'] = 'no';
					$activeFilters[] =
					'<a role="button" class="filterable removeFilter" href="'.$base.'?'.http_build_query($link).'">Show Universals&nbsp;<i class="glyphicon glyphicon-remove-2"></i></a>';
				}
		}
		elseif ($key == 'overall') {
			$link = $_GET;
		
			if(is_array($value) || is_object($value)) {
				foreach($value as $stateid => $statevalue) {
					$link = $_GET;
					if(($found = array_search($statevalue, @$link['filter']['overall'])) !== false) {
						unset($link['filter']['overall'][$found]);
					}
					$activeFilters[] =
					'<a role="button" class="filterable removeFilter" href="'.$base.'?'.http_build_query($link).'">'.$statevalue.' Stars&nbsp;<i class="glyphicon glyphicon-remove-2"></i></a>';
				}
			}
		}
		elseif ($key == 'category') {
			
		}
		elseif($key == 'ymm') {
			
		} elseif($key == 'spec') {
			$link = $_GET;
			
			if(is_array($value) || is_object($value)) {
				foreach($value as $stateid => $statevalue) {
					
						unset($link['filter'][$key][$stateid]);
					
					$activeFilters[] =
					'<a role="button" class="filterable removeFilter" href="'.$base.'?'.http_build_query($link).'">'.$stateid. ': '.$statevalue.'&nbsp;<i class="glyphicon glyphicon-remove-2"></i></a>';
				}
			} 
			
		} else {
			if(!empty($value)) {
				$link = $_GET;
				if(is_array($value) || is_object($value)) {
					foreach($value as $stateid => $statevalue) {
						if(($found = array_search($statevalue, @$link['filter'][$key])) !== false) {
							unset($link['filter'][$key][$found]);
						}
						$activeFilters[] =
						'<a role="button" class="filterable removeFilter" href="'.$base.'?'.http_build_query($link).'">'.$statevalue.'&nbsp;<i class="glyphicon glyphicon-remove-2"></i></a>';
					}
				} else {
					unset($link['filter'][$key]);
					$activeFilters[] =
					'<a role="button" class="filterable removeFilter" href="'.$base.'?'.http_build_query($link).'">'.$value.'&nbsp;<i class="glyphicon glyphicon-remove-2"></i></a>';
				}
			}	
		}
		
		
}


?>
<div class="row">
	<div class="col-sm-12 paddingTopMd">
	<ol class="breadcrumb">
  <li>Your Selections</li>

<?php 
foreach($activeFilters as $link) {
	echo "<li>". $link . "</li>";
}
?>
</ol>

	</div>
	
</div>