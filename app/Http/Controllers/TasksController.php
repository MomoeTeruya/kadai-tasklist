<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Controllers\Controller;

use App\Task;

class TasksController extends Controller
{
     public function index()
    {
        $data = [];
        if (\Auth::check()) {
            $user = \Auth::user();
            $tasks = $user->tasks()->orderBy('created_at', 'desc')->paginate(10);
            

            $data = [
                'user' => $user,
                'tasks' => $tasks,
            ];
            //$data += $this->counts($user);
            return view('tasks.index', $data);
        }else {
            return view('welcome');
        }
    }
    
    public function show($id)
    {
          $task = task::find($id);
          
           if (\Auth::user()->id !== $task->user_id) {
              return redirect('/');
         }

        return view('tasks.show', [
            'task' => $task,
            ]);

    }
    
    public function create () 
    {
         $task = new Task;

        return view('tasks.create', [
            'task' => $task,
        ]);
    }
    
     public function store(Request $request)
    {
        $this->validate($request, [
            'status' => 'required|max:10',
            'content' => 'required|max:191',
        ]);
        
        /*
        $request->user()->tasks()->create([
            'content' => $request->content,
        ]);
        */
        
        $task = new task;
            $task->status = $request->status; 
            $task->content = $request->content;
            $task->user_id = \Auth::user()->id;
            $task->save();

        return redirect('/');
    }
    
    
    public function edit($id)
    {
        $task = Task::find($id);
        
         if (\Auth::user()->id !== $task->user_id) {
              return redirect('/');
         }
             
        
        return view('tasks.edit', [
            'task' => $task,
        ]);
    }
    
    public function update(Request $request, $id)
    {
        
         $this->validate($request, [
            'status' => 'required|max:10',
            'content' => 'required|max:10',
        ]);

        
        $task = task::find($id);
         if (\Auth::user()->id === $task->user_id) 
        {
        $task->status = $request->status;  
        $task->content = $request->content;
        $task->save();
        }

        return redirect('/');
    }
    
    
    
      public function destroy($id)
    {
        $task = Task::find($id);

        if (\Auth::user()->id === $task->user_id) {
            $task->delete();
        }

        return redirect('/');
    }
}
