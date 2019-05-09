<div>
    <select id="site" onchange="change(this.value)">
        @foreach($data as $v)
            <option value="{{$v->id}}">{{$v->title}}</option>
        @endforeach
    </select>
    {{--栏目--}}
    <select class="navigation">

    </select>
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
                var html = '';
                $.each(data, function (k, v) {
                    html += '<option value="' + v.id + '">' + v.name + '</option>'
                });
                $('.navigation').html(html);
            }
        })

    }
</script>