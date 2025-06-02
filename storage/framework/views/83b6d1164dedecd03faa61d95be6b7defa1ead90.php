<div class="panel-heading">
		<h2><?php echo e(getPhrase('saved_items')); ?></h2>
	<div ng-if="savedSeries.length>0" class="crearfix selected-questions-details">

		<span class="pull-right"><?php echo e(getPhrase('total_items')); ?>: {{ savedSeries.length }}</span>
	</div>
	<div ng-if="savedSeries.length==0" class="crearfix selected-questions-details">
		<span class="pull-left"><?php echo e(getPhrase('no_data_available')); ?></span>
	</div>
	</div>
	<?php echo Form::open(array('url' => URL_LMS_SERIES_UPDATE_SERIES.$record->slug, 'method' => 'POST')); ?>

					 	<input type="hidden" name="saved_series" value="{{savedSeries}}">
	<div class="panel-body">
		<div class="row">
			<div class="col-md-12 clearfix">
				<div ng-if="savedSeries!=''" class="vertical-scroll" >

					 				<a class="remove-all-questions text-red" ng-click="removeAll()"><?php echo e(getPhrase('remove_all')); ?></a>
					 				<table
								  class="table table-hover">
								  <thead>
								  <tr>
									<th><?php echo e(getPhrase('title')); ?></th>
									<th><?php echo e(getPhrase('type')); ?></th>
									<th><?php echo e(getPhrase('code')); ?></th>
									<th></th>
									</tr>
									</thead>
									<tbody>
										<tr ng-repeat="i in savedSeries track by $index">
										<td>{{ savedSeries[$index].title}}</td>
										<td title="{{ savedSeries[$index].content_type}}">{{ savedSeries[$index].content_type  }}</td>
										<td>{{ savedSeries[$index].code}}</td>
										<td><a ng-click="removeItem(i)" class="btn-outline btn-close text-red"><i class="fa fa-close"></i></a></td>
										</tr>
									</tbody>
									</table>
					 			</div>

					 			<div class="buttons text-center" >
							<button class="btn btn-lg btn-success button"><?php echo e(getPhrase('update')); ?></button>
						</div>
			</div>
		</div>
	</div>

<?php echo Form::close(); ?>




