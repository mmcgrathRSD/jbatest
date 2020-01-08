<?php foreach($specs as $key => $specArray) : ?>
<?php $id = str_replace(' ', '', $key); 
$base = \Dsc\Url::full(false);
$i=0;
?>

<div class="panel ">
            <div class="panel-heading paddingLNone">
                <h5 class="panel-title">

                    <a data-toggle="collapse"  href="#collapse<?php echo $id; ?>" class="accordion-toggle collapse in"><?php echo $key; ?></a>

                </h5>
            </div>
            <div id="collapse<?php echo $id; ?>" class="panel-collapse collapse in">
                <div class="panel-body paddingLNone paddingRNone">
                    <ul class="list-unstyled">
                    <?php foreach($specArray['options'] as $option) :  
                    $link = $_GET;
                    
                    ?>
                    
                   
                    
	                <?php 
	                if(!empty($link['filter']['spec'][$key]) && $link['filter']['spec'][$key] == $option) : ?>
	                	
	                <?php   unset($link['filter']['spec'][$key]); ?>	
	                 <li  <?php if ($i >= 9) { echo 'style="display:none;"'; } ?>><label><a class="filterable checked" href="<?php echo $base; ?>?<?php echo http_build_query($link);?>"><?php echo $option;?></a></label></li>
	                 
	                 <?php  else : 
	               

	                $link['filter']['spec'][$key] = $option;
	                
	                ?>			
	                			 
	                			
                    <li <?php if ($i >= 9) { echo 'style="display:none;"'; } ?>><label><a class="filterable notChecked" href="<?php echo $base; ?>?<?php echo http_build_query($link);?>"><?php echo $option;?></a></label></li>
                   <?php $i++; endif; endforeach; ?>   
                   <?php  if ($i >=9 ): ?>
                   <li>
                   	<label>
                   		<a href="#" class="viewAllCatSpec"><strong>View All</strong></a>
                   	</label>
                   </li>
                   <?php endif; ?>
                    <?php /*
                    		$selected = (array) $state->get('filter.specs');
                    		
                    		foreach ($brands as $brand) : ?>
                    		<?php $link = $args; ?>
                			<li>
                				<label>
                    		 <?php if(in_array($brand, $selected)) : ?>
	                			 <?php if(($key = array_search($brand, @$link['filter']['manufacturers'])) !== false) {unset($link['filter']['manufacturers'][$key]);}?>
	                			 <a class="filterable checked" href="<?php echo $base; ?>?<?php echo http_build_query($link);?>"><?php echo  $brand; ?></a>		
	                			
	                			 <?php else : ?>
	                			 <?php $link['filter']['manufacturers'][] = $brand; 
	                			 $link['filter']['manufacturers'] = array_unique($link['filter']['manufacturers']);
	                			 ?> 
	                			 <a class="filterable notChecked" href="<?php echo $base; ?>?<?php echo http_build_query($link);?>"><?php echo  $brand; ?></a>
	                			 <?php endif;?>
	                			</label>
                			</li>     
                			<?php endforeach; */?>   
                			                       			       			               			
                	</ul>
			    </div>
            </div>
        </div>   
<?php endforeach;?>