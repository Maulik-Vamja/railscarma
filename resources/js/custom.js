$(document).ready(function () {
    $(document).on("click", "#editTaskBtn", function () {
        console.log("Edit Task button clicked");
        var targetUrl = $(this).data("target-url");
        // Fetch task details from the server
        $.ajax({
            url: targetUrl,
            type: "GET",
            dataType: "json",
            success: function (response) {
                // Populate the modal with task details
                var taskDetails = response.task;
                $("#editTaskModal").modal("show");

                // Populate form fields with task data
                $("#task_name").val(taskDetails.name);
                $("#task_description").val(taskDetails.description);
                $("#task_due_date").val(taskDetails.due_date);
                // Set the priority dropdown value
                $("#task_priority").val(taskDetails.priority);
                // Ensure the correct option is selected
                $(
                    "#task_priority option[value='" +
                        taskDetails.priority +
                        "']"
                ).prop("selected", true);
                $("#task_status").val(taskDetails.status);
                // Ensure the correct option is selected
                $(
                    "#task_status option[value='" + taskDetails.status + "']"
                ).prop("selected", true);
                $("#task_url").val(taskDetails.url);
                // Image preview for the task
                if (taskDetails.image) {
                    $("#task_image_preview").attr("src", taskDetails.image);
                } else {
                    $("#task_image_preview").addClass("d-none");
                }

                // Set the form action to update this specific task
                $("#edit-task-form").attr("action", "/tasks/" + taskDetails.id);
            },
            error: function (xhr, status, error) {
                console.error("Error fetching task details:", error);
                alert("Failed to load task details. Please try again.");
            },
        });
    });

    // File preview
    $(document).on("change", "#task_image", function () {
        console.log("File input changed");
        var file = this.files[0];
        console.log("Selected file:", file);

        if (file) {
            var reader = new FileReader();
            reader.onload = function (e) {
                $("#task_image_preview").attr("src", e.target.result);
                $("#task_image_preview").removeClass("d-none");
            };
            reader.readAsDataURL(file);
        } else {
            $("#task_image_preview").html("");
        }
    });

    // Form validation for the edit task form
    $("#editTaskForm").validate({
        rules: {
            task_name: {
                required: true,
                minlength: 3,
            },
            task_description: {
                required: true,
                minlength: 5,
            },
            task_due_date: {
                required: true,
                date: true,
            },
            task_priority: {
                required: true,
            },
            task_status: {
                required: true,
            },
        },
        messages: {
            task_name: {
                required: "Please enter the task name",
                minlength: "Task name must be at least 3 characters long",
            },
            task_description: {
                required: "Please enter the task description",
                minlength:
                    "Task description must be at least 5 characters long",
            },
            task_due_date: {
                required: "Please select a due date",
                date: "Please enter a valid date",
            },
            task_priority: {
                required: "Please select a priority level",
            },
            task_status: {
                required: "Please select a status",
            },
        },
    });

    // Edit task form submission
    $(document).on("submit", "#editTaskForm", function (e) {
        e.preventDefault();
        if (!$(this).valid()) {
            return; // If the form is not valid, do not proceed
        }

        var formData = new FormData(this);
        var actionUrl = $(this).attr("action");
        // Get task_id from the form
        var taskId = $(this).find("[name='task_id']").val();

        $.ajax({
            url: "tasks/update/" + taskId,
            type: "PUT",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // console.log("Task updated successfully:", response);
                $("#editTaskModal").modal("hide");
                // Optionally, refresh the task list or update the UI
                location.reload();
            },
            error: function (xhr, status, error) {
                console.error("Error updating task:", error);
                alert("Failed to update task. Please try again.");
            },
        });
    });
});
