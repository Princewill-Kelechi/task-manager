<!DOCTYPE html>
<html lang="en">
  <head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Coalition Technologies Test Task App</title>
    <link
      href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/css/bootstrap.min.css"
      rel="stylesheet"
      integrity="sha384-9ndCyUaIbzAi2FUVXJi0CjmCapSmO7SnpJef0486qhLnuZ2cdeRhO02iuK6FUUVM"
      crossorigin="anonymous"
    />
    <link
      rel="stylesheet"
      href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css"
      integrity="sha512-iecdLmaskl7CVkqkXNQ/ZH/XLlvWZOJyj7Yy7tcenmpD1ypASozpmT/E0iPtmFIB46ZmdtAc9eNBvH0H/ZpiBw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    />
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.12.1/themes/base/jquery-ui.css">


    <link
      rel="stylesheet"
      href="//cdn.datatables.net/1.13.5/css/jquery.dataTables.min.css"
    />
    <link rel="stylesheet" href="{{ asset('style/style.css') }}" />

  </head>
  <body>
    <div class="container-fluid py-2">
      <div class="row">
        <div
          class="col-xl-2 openSideMenu"
          id="sidemenu"
          style="background-color: white"
        >
          <h3
            class="text-center fw-bolder"
            style="font-family: 'pacifico', cursive; letter-spacing: 2px"
          >
            Task Manager
          </h3>
          <hr />
          <form action="{{ route('projects.store') }}" class="my-4" method="POST">
            @csrf
            <input
                type="text"
                class="add_project"
                id="create_project"
                placeholder="Enter Project Name..."
                name="name" 
            />
            <button type="submit" class="btn btn-dark my-3">
                <i class="fas fa-plus"></i> Create Project
            </button>
            </form>
          <div class="container-fluid p-0">
            <h5 class="fw-bold">Projects</h5>
            <ul class="project_list p-0">
                @foreach ($projects as $project)
                <li>
                <a href="{{ route('tasks.filterByProject', $project->id) }}" class="" id="{{ $project->id }}">{{ $project->name }}</a>
              </li>
                @endforeach


            </ul>
          </div>


        </div>
        <div class="col-xl-10" id="main">
          <div
            class="row py-3 mb-2 m-0 rounded-3"
            style="background-color: white"
          >
            <div class="col-sm-1 d-flex justify-content-center my-2">
              <buttom id="hamburger">
                <i class="fas fa-bars"></i>
              </buttom>
            </div>

            <div class="col-sm-9 d-flex justify-content-center my-2">
              <h2 class="m-0">Tasks Dashboard</h2>
            </div>

          </div>

          <div class="dashboard p-4" style="background-color: white">
        <form
            @if ($editMode)
            action="{{ route('tasks.update', $task->id) }}"
            @else
            action="{{ route('tasks.store') }}"
            @endif
            method="POST"
            class="add_task w-100 my-4 row align-items-center"
        >
            @csrf
            @if ($editMode)
            @method('PUT')
            @endif
            <div class="col-md-3">
            <input
                type="text"
                class="task_input"
                id="addTask"
                required
                name="name"
                placeholder="Enter Task Name"
                @if ($editMode)
                value="{{ $task->name }}"
                @endif
            />
            </div>

            <div class="col-md-3">
            <input
                type="number"
                class="task_input"
                id="addTask"
                name="priority"
                required
                placeholder="Priority level"
                @if ($editMode)
                value="{{ $task->priority }}"
                @endif
            />
            </div>

        <div class="col-md-3">
        <select class="task_input" name="project_id" id="project_id">
            <option value="">No Project</option>
            @foreach ($projects as $project)
            <option
            value="{{ $project->id }}"
            @if ($editMode && $task->project_id == $project->id)
            selected
            @endif
            >
            {{ $project->name }}
            </option>
            @endforeach
        </select>
        </div>
        <div class="col-md-3">
        <button type="submit" class="btn btn-dark">
            <i class="fas fa-plus-square me-2"></i>
            @if ($editMode)
            Update Task
            @else
            Add Task
            @endif
        </button>
        </div>
    </form>
    </div>
    <table class="table table-borderless my-4" id="myTable">
    <thead>
        <tr class="table-dark">
        <th scope="col">Priority</th>
        <th scope="col">Task</th>
        <th scope="col">Project</th>
        <th scope="col">Created Date</th>
        <th scope="col">Action</th>
        </tr>
    </thead>
    <tbody class="sortable-list">
    @foreach ($tasks as $task)
        <tr class="sortable-item" data-task-id="{{ $task->id }}">
        <th scope="row">{{ $task->priority }}</th>
        <td>{{ $task->name }}</td>
        <td>
        @if ($task->project)
        {{ $task->project->name }}
    @else
        --
    @endif                  
    </td>
    <td>{{$task->created_at}}</td>
    <td>
    <a href="{{ route('tasks.edit', $task->id) }}">
        <button  class="btn btn-info text-light">
            <i class="fas fa-pen-alt"></i>
        </button>
    </a>
            <form action="{{ route('tasks.destroy', $task->id) }}" method="POST" style="display: inline-block;">
            @csrf
            @method('DELETE')

            <button class="btn btn-danger">
                <i class="fas fa-trash-alt"></i>
            </button>                
        </form>
            </td>
        </tr>
        @endforeach
        </tbody>
        </table>
        </div>
        </div>
        </div>
        </div>
    <script>
      document.getElementById("hamburger").addEventListener("click", () => {
        let sidemenu = document.getElementById("sidemenu");
        if (sidemenu.className == "col-xl-2 openSideMenu") {
          sidemenu.className = "closeSideMenu";
          document.getElementById("main").className = "col-xl-12";
        } else {
          sidemenu.className = "col-xl-2 openSideMenu";
          document.getElementById("main").className = "col-xl-10";
        }
      });

      const mobile_responsive = (x) => {
        if (x.matches) {
          sidemenu.className = "closeSideMenu";
          document.getElementById("main").className = "col-xl-12";
        } else {
          sidemenu.className = "col-xl-2 openSideMenu";
          document.getElementById("main").className = "col-xl-10";
        }
      };
      var x = window.matchMedia("(max-width : 700px)");
      mobile_responsive(x);
      x.addListener(mobile_responsive);
    </script>

    <script
      src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
      integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
      crossorigin="anonymous"
    ></script>
    
    <script
      src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.7.0/jquery.min.js"
      integrity="sha512-3gJwYpMe3QewGELv8k/BX9vcqhryRdzRMxVfq6ngyWXwo03GFEzjsUm8Q7RZcHPHksttq7/GFoxjCVUjkjvPdw=="
      crossorigin="anonymous"
      referrerpolicy="no-referrer"
    ></script>
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.js"></script>


<script>
    $(document).ready(function() {
        $('.sortable-list').sortable({
            update: function(event, ui) {
                var taskIds = [];

                $('.sortable-item').each(function(index) {
                    var taskId = $(this).data('task-id');
                    taskIds.push(taskId);
                });

                $.ajax({
                    url: "{{ route('tasks.reorder') }}",
                    type: "POST",
                    dataType: 'json',
                    data: {
                        _token: "{{ csrf_token() }}",
                        taskIds: taskIds
                    },
                    success: function(response) {
                        console.log(response);
                        location.reload()
                    }
                });
            },
            placeholder: "sortable-placeholder"
        });
    });
</script>

  </body>
</html>



