@extends('layouts.admin.adminlayout')
@section('header_scripts')
<link href="{{CSS}}ajax-datatables.css" rel="stylesheet">
@stop
@section('content')


<div id="page-wrapper">
			<div class="container-fluid">
				<!-- Page Heading -->
				<div class="row">
					<div class="col-lg-12">
						<ol class="breadcrumb">
							<li><a href="{{PREFIX}}"><i class="mdi mdi-home"></i></a> </li>
							<li>{{ $title }}</li>
						</ol>
					</div>
				</div>

				<!-- /.row -->
				<div class="panel panel-custom">
					<div class="panel-heading">
						<div class="pull-right messages-buttons">
							<a href="{{URL_INSTITUTE_REGISTER}}" class="btn  btn-primary button" >{{ getPhrase('add_institute')}}</a>
						</div>

						<h1>{{ $title }}</h1>
					</div>
					<div class="panel-body packages">
						<div >
						<table class="table table-striped table-bordered datatable" cellspacing="0" width="100%">
							<thead>
								<tr>

									<th>{{ getPhrase('name')}}</th>
									<th>{{ getPhrase('institute_name')}}</th>
									<th>{{ getPhrase('address')}}</th>
									<th>{{ getPhrase('status')}}</th>
								 	<th>{{ getPhrase('action')}}</th>

								</tr>
							</thead>

						</table>
						</div>

					</div>
				</div>
			</div>
			<!-- /.container-fluid -->


	<div id="myModal" class="modal fade" role="dialog">
          <div class="modal-dialog modal-sm">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title text-center">{{getPhrase('approve_institute')}}</h4>
      </div>
      <div class="modal-body">

      {!!Form::open(array('url'=> URL_UPDATE_INSTITUTE_STATUS,'method'=>'POST','name'=>'userstatus'))!!}

      <h4 class="text-center">{{getPhrase('are_you_sure_to_approve_this_institute')}}</h4>

        <input type="hidden" name="institute_id" id="institute_id" >
        <input type="hidden" name="status" value="approve" >

          <fieldset class="form-group col-sm-12">

             {{ Form::label('comments', getphrase('comments')) }}

             {{ Form::textarea('comments', $value = null , $attributes = array('class'=>'form-control','rows'=>3, 'cols'=>'15', 'placeholder' => getPhrase('please_enter_your_address')
             )) }}

      </fieldset>


      </div>
      <div class="modal-footer">
        <button type="submit" class="btn btn-primary pull-right" >Yes</button>
        <button type="button" class="btn btn-default pull-right" data-dismiss="modal">No</button>
      </div>
      {!!Form::close()!!}

    </div>

  </div>
</div>

		</div>
@endsection


@section('footer_scripts')

 @include('common.datatables', array('route'=>URL_INSTITUTES_GETDATATABLE ,'route_as_url'=>TRUE,  'search_columns' => ['type' => $type],'table_columns' => ['user_id','institute_name','institute_address','status','action'] ))

 <script>

 		function approveInstitute(institute_id){


           $('#institute_id').val(institute_id);

           $('#myModal').modal('show');
 		}

 </script>


@stop
