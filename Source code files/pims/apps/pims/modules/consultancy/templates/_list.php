 <?php
echo link_to('First', $action.'?page=' .$pager->getFirstPage())?>    	        
    	        <?php
            echo link_to('Previous', $action.'?page=' .$pager->getPreviousPage())?>
    			<?php
    if ($pager->haveToPaginate()) :
        ?>
            	        <?php
        $links =$pager->getLinks();
        foreach ($links as $page) :
            echo ($page ==$pager->getPage()) ? $page : link_to($page, $action.'?page=' .$page);
        endforeach
        ?>
            	
    <?php endif ?>
            	<?php
            echo link_to('Next', $action.'?page=' .$pager->getNextPage())?>            	
    	        <?php
            echo link_to('Last', $action.'?page=' .$pager->getLastPage())?>