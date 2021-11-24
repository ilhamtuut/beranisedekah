<script src="{{asset('assets/plugins/jquery/jquery-3.1.0.min.js')}}"></script>
<script src="{{asset('assets/plugins/bootstrap/js/bootstrap.min.js')}}"></script>
<script src="{{asset('assets/plugins/jquery-slimscroll/jquery.slimscroll.min.js')}}"></script>
<script src="{{asset('assets/plugins/uniform/js/jquery.uniform.standalone.js')}}"></script>
<script src="{{asset('assets/plugins/switchery/switchery.min.js')}}"></script>
<script src="{{asset('assets/plugins/summernote-master/summernote.min.js')}}"></script>
<script src="{{asset('assets/js/space.min.js')}}"></script>
<script>
    @if (Session::has('flash_success'))
        $('#myModal').modal('show');
    @endif
    function submit(){
        $('#form-search').submit();
    }

    $('#change-picture').on('click', function(){
        $('#picture').trigger("click");
    });

    $("#picture").on("change", function (e) {
        $('#upload').submit();
    });

    function addCommas(nStr) {
        nStr += '';
        x = nStr.split('.');
        x1 = x[0];
        x2 = x.length > 1 ? '.' + x[1] : '';
        var rgx = /(\d+)(\d{3})/;
        while (rgx.test(x1)) {
            x1 = x1.replace(rgx, '$1' + '.' + '$2');
        }
        return x1 + x2;
    }
</script>
