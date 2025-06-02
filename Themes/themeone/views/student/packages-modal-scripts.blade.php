<script type="text/javascript">
    function suggestPackage(slug, type) {
        $('#packagesModal').modal('hide');
        $('.modal-backdrop').remove() // removes the grey overlay.
        $('#packagesModal').modal('show');
        getPackages()
    }

    function getPackages()
    {
        route = '{{url("pricing/student-packages")}}';

        var token = $('[name="_token"]').val();

        data= {_method: 'get', '_token':token};
        $.ajax({
        url:route,
        dataType: 'json',
        data: data,
        success:function(result) {
            // console.log(result.items)
            //console.log( 'test');
            $('#pack-data').html( result.items );

        }
        });
    }
</script>