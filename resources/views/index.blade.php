@extends('layouts.baseTask')

@section('title')
    Task List
@endsection

@section('title_table')
    List of tasks
    @endsection

@section('body')

    <div id="search">
        <form class="searchform" action="{{ route('search') }}" method="get">
            @csrf
            <input type="text" name="search" placeholder="Search in here"/>
            <input name="searchsubmit" type="submit" value="Search" />
        </form>
    </div>

    @if(!isset($tasks))
        <h5 class="text-primary">Dữ liệu không tồn tại!</h5>
    @else
        <table class="table table-bordered">
            <thead>
            <tr>
                <th scope="col">#</th>
                <th scope="col">Task title</th>
                <th scope="col">Content</th>
                <th scope="col">Created</th>
                <th scope="col">Due Date</th>
                <th scope="col">Image</th>
                <th scope="col">Action</th>
            </tr>
            </thead>
            <tbody>

            <!-- Kiểm tra, nếu biến tasks có số lượng bằng 0 (Không có task nào) thì trả về thông báo -->
            @if(count($tasks) == 0)
                <h5 class="text-primary">Hiện tại chưa có task nào được tạo!</h5>
            @else

                <!-- Duyệt mảng $tasks, lấy ra từng trường của từng task để hiển thị ra bảng -->
                @foreach($tasks as $key => $task)
                    <tr>
                        <th scope="row">{{ ++$key }}</th>
                        <td>{{ $task->title }}</td>
                        <td>{{ $task->content }}</td>
                        <td>{{ $task->created_at }}</td>
                        <td>{{ $task->due_date }}</td>
                        <td>
                            @if($task->image)
                                <img src="{{ asset('storage/'.$task->image) }}" alt="" style="width: 50px; height: 50px">
                                <img src="{{ asset('storage/images/' . $task->image) }}" alt="" style="width: 48px ; height: 48px ;overflow: hidden; ">
                            @else
                                {{'Chưa có ảnh'}}
                            @endif
                        </td>
                        <td><a class="delete-link" href="{{route('task.delete',$task->id)}}" id="{{ $task->id }}">Xóa</a> |
                            <a class="delete-link" href="{{route('task.edit',$task->id)}}" id="{{ $task->id }}">Sửa</a>

                        </td>

                    </tr>
                @endforeach
            @endif
            </tbody>
        </table>
        {{ $tasks->appends(request()->query()) }} <!-- Phân trang (nối chuỗi truy vấn-->
    @endif
    <a href="{{ route('task.create') }}"> Tạo task mới</a>

{{--    <script>
        $(document).ready(function () {
            var taskId = $(this).attr('id');
            $.ajax({
                url:"http:localhost:8000/delete/" + taskId,
                type: "GET",
                data: { 'id': taskId}
            },
            success:: function (data) {
                window.location = "/";
            }

        });

        });
        })


    </script>--}}
    <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
@endsection