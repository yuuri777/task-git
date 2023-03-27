<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Project;
use App\Models\Task;
use App\Http\Requests\StoreTaskRequest;
use App\Http\Requests\UpdateTaskRequest;
use Illuminate\Support\Facades\DB;
USE Illuminate\Support\Facades\Log;

class TaskController extends Controller
{
    /**
     * プロジェクトに紐づくタスク一覧
     */
    public function index($id)
    {
        // URLで送られてきたプロジェクトID
        $currentProjectId = $id;

        // プロジェクト取得
        $project = Project::find($currentProjectId);

        // 取得したプロジェクトに紐づくタスクを取得
        $tasks = $project->tasks->all();

        return view('tasks.index', compact(
            'currentProjectId',
            'tasks',
        ));
    }
    /**
     * タスク作成画面
     */
    public function create($id)
    {
        //URLで送られてきたプロジェクトID
        $currentProjectId =$id;

        return view('tasks.create',compact(
            'currentProjectId',
        ));
    }

     /**
     * タスク作成処理
     */
    public function store(storeTaskRequest $request,$id)
    {
     //URLで送られてきたプロジェクトID
        $currentProjectId  = $id;

        //トランザクション開始
        DB::beginTransaction();

        try{
        //タスク作成処理
        $task = Task::create([
            'project_id' => $currentProjectId,
            'task_name' => $request->task_name,
            'due_date' => $request->due_date,
        ]);

        //トランザクションコミット
        DB::commit();
        }catch(\Exception $e){

        //トランザクションロールバック
        DB::rollBack();

        //ログ出力
        Log::debug($e);

        //エラー画面遷移
        abort(500);
    
    }

        return redirect()->route('tasks.index',[
            'id' => $currentProjectId,
        ]);
        //redirectしてindex.blade.php＋渡されたIDに飛ぶ
    }

     /**
     * タスク編集画面
     */
    public function edit($id,$taskId)
    //ルートパラメータが２つ。一つがプロジェクトIDでもう一つがタスクID。それぞれ$idと$taskIdという引数で受け取っている。
    {
    
        //タスクを取得
        $task =Task::find($taskId);
        //findメソッドで編集するタスクを取得
        
        //進捗のテキスト(Taskモデルの定数取得)
        $taskStatusStrings = Task::TASK_STATUS_STRING;
        //進捗がデータベース上で数値として保存されているから、数値に結びつくテキストをTaskモデルから取得できる。
        // Taskモデルから定数であるTASK_STATUS_STRINGを取得するためにはTask::TASK_STATUS_STRINGと記述する。

    

        //進捗のクラス(taskモデルの定数取得)
        $taskStatusClasses = Task::TASK_STATUS_CLASS;
        //Bootstrapで使用するクラス名も定数かしていたからそっちもTask::TASK_STATUS_CLASSとすることで取得できる。

        
        return view('tasks.edit',compact(
            'task',
            'taskStatusStrings',
            'taskStatusClasses',
        ));
    }

    /**
     * タスク編集処理
     */
    public function update(UpdateTaskRequest $request,$id,$taskId)
    //フォームで入力された値は$requestで受け取っている。
    // UpdateTaskRequestにしたためtask_statusに0~3以外の数字を入力されていた場合はmessagesメソッドで定義している:attributeには$statusesのいずれかを選択してください。というバリデーションメッセージが出る。
    // $statusesには完了、処理済み、処理中、未対応という文字列が入っている。

    {
        //URLで送られてきたプロジェクトID
        $currentProjectId = $id;

        //タスクを取得
        $task = Task::find($taskId);

        //トランザクション開始
        DB::beginTransaction();
        try{

        

        //タスク編集処理（fill）
        $task->fill([
            'task_name'=> $request->task_name,
            'task_status'=> $request->task_status,
            'due_date' => $request->due_date,
        ]);
        //データを更新するためのメソッドはfillメソッド＋saveメソッドを使用している。
        // task_name,tasl_status,due_dateにはそれぞれのフォームで入力された値を入れている

        //タスク編集処理(save)
        $task->save();

        //トランザクションコミット
        DB::commit();

    }catch(\Exception $e) {

        //トランザクションロールバック
        DB::rollBack();

        //ログ出力
        Log::debug($e);

        //エラー画面遷移
        abort(500);
    }

        return redirect()->route('tasks.index',[
            'id' =>$currentProjectId,
        ]);
    }
}