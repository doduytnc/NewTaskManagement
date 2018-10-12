<?php

namespace App\Http\Controllers;

use App\Model\TaskModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class TaskController extends Controller
{
    public function index() {
       /* $tasks = TaskModel::all();
        return view('index' ,compact('tasks'));*/

       $tasks = DB::table('tasks')-> paginate(5);
       return view('index',['tasks' => $tasks]);
    }

    public function create(){
        return view('add');
    }

    public function store(Request $request){
        //Khởi tạo mới đối tượng task tham chiếu bằng biến $task
        $task = new TaskModel();
        //gán các field tương ứng với request gửi lên từ form trình duyệt
        $task->title = $request->input('inputTitle');
        $task->content = $request->input('inputContent');
        $task->due_date = $request->input('inputDueDate');

        // Nếu file không tồn tại thì field image gán bằng NULL
        if (!$request->hasFile('inputFile')) {
            $task->image = $request->input('inputFile');
        } else {
            //Lấy tên file được upload gán vào $file
            $file = $request->file('inputFile');

            //Lấy ra định dạng và tên mới của file từ request
            $fileExtension = $file->getClientOriginalExtension();
            //Lấy tên file do người dùng đặt từ form request lên
            $fileName = $request->input('inputFileName');

            // Gán tên mới cho file trước khi lưu lên server
            $newFileName = "$fileName.$fileExtension";

            //Lưu file vào thư mục storage/app/public/image với tên mới
            $request->file('inputFile')->storeAs('public/images', $newFileName);

            // Gán trường image của đối tượng task với tên mới
            $task->image = $newFileName;
        }
        //Lưu vào database
        $task->save();
        //Chuyển hướng về trang hiển thị index
        return redirect()->route('tasks.index');



//        $task = new TaskModel();
//        $task->title = $request->input('inputTitle');
//        $task->content = $request->input('inputContent');
//
//        //upload file
//        if ($request->hasFile('inputFile')) {
//            $image = $request->file('inputFile');
//            $path = $image->store('images', 'public');
//            $task->image = $path;
//        }
//
//        $task->due_date = $request->input('inputDueDate');
//        $task->save();

        //dung session de dua ra thong bao
//        Session::flash('success', 'Tạo mới thành công');
//        //tao moi xong quay ve trang danh sach task
        /*return redirect()->route('tasks.index');*/
    }

    public function search(Request $request){
        $tasks = TaskModel::where('title','like',"%".$request->input('search')."%")
        ->orWhere('content','like',"%".$request->input('search')."%")
        ->orWhere('due_date','like',"%".$request->input('search')."%")
        ->paginate(2);
        return view('index',compact('tasks'));
    }

    public function destroy($id)
    {
        $tasks = TaskModel::find($id);
        $tasks->delete();
        return redirect()->route('tasks.index');
    }

    public function edit($id) {
        $task = TaskModel::findOrFail($id);
        return view('edit',compact('task'));
    }

    public function update(Request $request,$id) {
        $task = TaskModel::findOrFail($id);
        $task->title = $request->input('inputTitle');
        $task->content = $request->input('inputContent');
        $task->due_date = $request->input('inputDueDate');
        if ($request->hasFile('inputFile')) {

            //xoa anh cu neu co
            $currentImg = $task->image;
            if ($currentImg) {
                Storage::delete('/public/' . $currentImg);
            }
            // cap nhat anh moi
            $image = $request->file('inputFile');
            $path = $image->store('images', 'public');
            $task->image = $path;
        }
//        dd($task);
        $task->save();

        //dung session de dua ra thong bao
//        Session::flash('success', 'Cập nhật thành công');
        //tao moi xong quay ve trang danh sach task
        return redirect()->route('tasks.index');

    }

}
