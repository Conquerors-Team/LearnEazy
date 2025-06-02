<link href="https://cdn.datatables.net/1.10.16/css/jquery.dataTables.min.css" type="text/css">
<link href="https://cdn.datatables.net/buttons/1.4.2/css/buttons.dataTables.min.css" type="text/css">



<script src="<?php echo e(themes('js/bootstrap-toggle.min.js')); ?>"></script>
	<script src="<?php echo e(themes('js/jquery.dataTables.min.js')); ?>"></script>
	<script src="<?php echo e(themes('js/dataTables.bootstrap.min.js')); ?>"></script>
<?php if(checkRole(getUserGrade(2))): ?>
<script src="https://cdn.datatables.net/buttons/1.4.2/js/dataTables.buttons.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.flash.min.js"></script>
<?php endif; ?>

<script src="//cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/pdfmake.min.js"></script>
<script src="//cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.32/vfs_fonts.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.html5.min.js"></script>
<script src="//cdn.datatables.net/buttons/1.4.2/js/buttons.print.min.js"></script>

	<?php 	$routeValue= $route; ?>

	<?php if(!isset($route_as_url)): ?>
		<?php
		$pageLength = '';
		if ( ! empty( $search_columns ) ) {
			$routeValue =  route($route, $search_columns);
			if ( in_array('callfrom', array_keys($search_columns))) {
				$pageLength = 200;
			}
		} else {
			$routeValue =  route($route);
		}
		// dd( $routeValue );
		?>
	<?php else: ?>
		<?php
		$pageLength = '';
		if ( ! empty( $search_columns ) ) {
			$routeValue =  url($route) . '?' . http_build_query($search_columns);
			if ( in_array('callfrom', array_keys($search_columns))) {
				$pageLength = 200;
			}
		}
		?>
	<?php endif; ?>

	<?php
	$setData = array();
		if(isset($table_columns))
		{
			foreach($table_columns as $col) {
				$temp['data'] = $col;
				$temp['name'] = $col;
				array_push($setData, $temp);
			}
			$setData = json_encode($setData);
		}

	?>


  <script>

  var tableObj;

    $(document).ready(function(){
    	$.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        	}
		});

   		 tableObj = $('.datatable').DataTable({
	            processing: true,
	            serverSide: true,
	            cache: true,

	            <?php if(!empty($pageLength)): ?>
	            pageLength: '<?php echo e($pageLength); ?>',
	            <?php endif; ?>
	            type: 'GET',
	            ajax: '<?php echo $routeValue; ?>',
	            dom: 'Bfrtip',
	            "info":     false,
	            buttons: [
				            'copy', 'csv', 'excel', 'pdf', 'print'

				        ],
	            <?php if(isset($table_columns)): ?>
	            columns: <?php echo $setData; ?>

	            <?php endif; ?>

	    });


    });
  </script>