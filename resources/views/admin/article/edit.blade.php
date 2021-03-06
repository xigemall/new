<div>
    {{--    网站--}}
    <div class="form-group  ">
        <label for="site" class="col-sm-2  control-label">网站</label>
        <div class="col-sm-8">
            <select class="form-control site" style="width: 100%;" name="site_id" data-value="" id="site"
                    onchange="change(this.value)">
                @foreach($site as $v)
                    <option value="{{$v->id}}"  @if($v->id == $data->site_id) selected @endif>{{$v->title}}</option>
                @endforeach
            </select>
        </div>
    </div>
    {{--栏目--}}
    <div class="form-group">
        <label for="navigation" class="col-sm-2  control-label">栏目</label>
        <div class="col-sm-8">
            <select class="form-control navigation" style="width: 100%;" name="navigation_id" data-value="">

            </select>
        </div>
    </div>
</div>
<script type="text/javascript" src="/js/app.js"></script>
<script>
    $(function () {
        var id = $('#site').val();
        change(id);
    });

    function change(id) {
        $.ajax({
            type: 'get',
            url: '/admin/site-navigation/' + id,
            dataType: 'json',
            headers: {
                'X-CSRF-TOKEN': "{{csrf_token()}}"
            },
            success: function (data) {
                var navigationId = "{{$data->navigation_id}}";
                var html = '';
                $.each(data, function (k, v) {
                    var selected = (v.id == navigationId)? 'selected': '';
                    html += '<option '+selected+' value="' + v.id + '">' + v.name + '</option>'
                });
                $('.navigation').html(html);
            }
        })

    }
</script>