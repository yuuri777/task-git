<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Validation\Rule;
use App\Models\Task;

class UpdateTaskRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\Rule|array|string>
     */
    public function rules(): array
    {
        $myTaskStatusRule = Rule::in(array_keys(Task::TASK_STATUS_STRING));
        // inメソッドは値が指定された配列の中に存在するかどうかを検証するためのヘルパーメソッド
        // inメソッドはIlluminate\Validation\Ruleクラスで提供されている。主にフォームリクエストクラスやバリデーションルールセット内で使用されて、値のバリデーションに利用される。
        // Task::TASK_STATUS_STRINGの中には'未対応','処理中' ,'処理済み','完了',などが入っている
        // array_keysに関してsejuku.net/blog/22704 でわる
        //array_keys(Task::TASK_STATUS_STRING)で0~3の配列番号が取得できる。inメソッドを使ってルールの文字列を作成してる。
        // つまりRule::in(array_keys(Task::TASK_STATUS_STRING));と記述することでin(0,1,2,3)となる。そしてそれが$myTaskStatusRuleに代入されている。
        //結果として出力されるルールは'task_status' => 'required|in(0, 1, 2, 3)'になる。



        return [
            //
            'task_name' => 'required|max:100|string',
            'task_status'=>['required',$myTaskStatusRule],
            // 入力された値が0~2以外の場合はエラー文が出るためのバリデーション
            // task_statusには入力値が0~3(完了、処理ずみ、処理中、未対応)に含まれているか検証するinを使用する
            //こちらのバリデーション文の意味についてhttps://qiita.com/miriwo/items/1dc1c3b4076412f1a013 こちらURLから
            'due_date' =>'required|date',
            // バリデーションルールについて  https://codelikes.com/laravel-validation-rule/#toc2
        ];
    }
    public function attributes()
    {
        return[
            'task_name'=>'タスク名',
            'task_status' => '進捗',
            'due_date' => '期限',
        ];
    }
    public function messages()
    {
        $statuses = implode('、',array_values(Task::TASK_STATUS_STRING));

        return[
            'task_status.in' => ':attributeには'.$statuses.'のいずれかを選択してください。',
            //進捗(task_status)には、入力値が0~3(完了、処理済み、処理中、未対応)に含まれているかを検証する。inを使用する。
        ];
    }
}
