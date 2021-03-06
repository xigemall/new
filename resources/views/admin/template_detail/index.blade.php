@extends('admin.layouts.app')
@section('title','模板')
@section('content')
    <div class="layui-fluid">
        <div class="layui-row layui-col-space15">

            <div class="layui-col-md12">

                <div class="layui-card">
                    <div class="layui-card-header">

                        <button class="layui-btn layui-btn-danger" onclick="delAll()">
                            <i class="layui-icon"></i>批量删除
                        </button>
                        <a href="{{url('admin/template-detail/'.$data->id.'/create')}}" class="layui-btn">
                            <i class="layui-icon"></i>添加
                        </a>
                    </div>
                    <div class="layui-card-body ">
                        <table class="layui-table layui-form">
                            <thead>
                            <tr>
                                <th>
                                    <input type="checkbox" name="" lay-skin="primary"></th>
                                <th>模板</th>
                                <th>模板文件</th>
                                <th>操作</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($files as $v)
                                <tr>
                                    <td>
                                        <input type="checkbox" name="" lay-skin="primary"></td>
                                    <td>{{$data->name}}</td>
                                    <td>uploads/{{$v}}</td>
                                    <td class="td-manage">
                                        <a title="编辑"
                                           href="{{url('admin/template-detail/'.$data->id.'/edit'.'?file='.$v)}}">
                                            <i class="layui-icon">&#xe63c;</i></a>
                                        <a title="删除" onclick="member_del(this,'{{$v}}')" href="javascript:;">
                                            <i class="layui-icon">&#xe640;</i></a>
                                    </td>
                                </tr>
                            @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="layui-card-body ">
                        <div class="page">
                            <div>
                                <a class="prev" href="">&lt;&lt;</a>
                                <a class="num" href="">1</a>
                                <span class="current">2</span>
                                <a class="num" href="">3</a>
                                <a class="num" href="">489</a>
                                <a class="next" href="">&gt;&gt;</a></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('js')
    <script>layui.use(['laydate', 'form'],
            function () {
                var laydate = layui.laydate;

                //执行一个laydate实例
                laydate.render({
                    elem: '#start' //指定元素
                });

                //执行一个laydate实例
                laydate.render({
                    elem: '#end' //指定元素
                });
            });

        /*用户-停用*/
        function member_stop(obj, id) {
            layer.confirm('确认要停用吗？',
                function (index) {

                    if ($(obj).attr('title') == '启用') {

                        //发异步把用户状态进行更改
                        $(obj).attr('title', '停用');
                        $(obj).find('i').html('&#xe62f;');

                        $(obj).parents("tr").find(".td-status").find('span').addClass('layui-btn-disabled').html('已停用');
                        layer.msg('已停用!', {
                            icon: 5,
                            time: 1000
                        });

                    } else {
                        $(obj).attr('title', '启用');
                        $(obj).find('i').html('&#xe601;');

                        $(obj).parents("tr").find(".td-status").find('span').removeClass('layui-btn-disabled').html('已启用');
                        layer.msg('已启用!', {
                            icon: 5,
                            time: 1000
                        });
                    }

                });
        }

        /*用户-删除*/
        function member_del(obj, path) {
            layer.confirm('确认要删除吗？',
                function (index) {
                    $.ajax({
                        type: 'post',
                        url: '/admin/template-detail-delete',
                        dataType: 'text',
                        data: {'path': path},
                        headers: {
                            'X-CSRF-TOKEN': "{{csrf_token()}}"
                        },
                        success: function (data) {
                            if (data === 1) {
                                //
                            }
                        }
                    });
                    //发异步删除数据
                    $(obj).parents("tr").remove();
                    layer.msg('已删除!', {
                        icon: 1,
                        time: 1000
                    });
                });
        }

        function delAll(argument) {

            var data = tableCheck.getData();

            layer.confirm('确认要删除吗？' + data,
                function (index) {
                    //捉到所有被选中的，发异步进行删除
                    layer.msg('删除成功', {
                        icon: 1
                    });
                    $(".layui-form-checked").not('.header').parents('tr').remove();
                });
        }</script>

@endsection