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
                // Format the due date to YYYY-MM-DD for HTML date input
                if (taskDetails.due_date) {
                    var dueDate = new Date(taskDetails.due_date);
                    var formattedDate = dueDate.toISOString().split("T")[0];
                    $("#task_due_date").val(formattedDate);
                } else {
                    $("#task_due_date").val("");
                }
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

                // Set the task_id in the form
                $("#task_id").val(taskDetails.id);
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

        var formData = new FormData($(this)[0]);

        // Get task_id from the form
        var taskId = $(this).find("[name='task_id']").val();

        var targetUrl = $(this)
            .find("[name='target_url']")
            .val()
            .replace(":id", taskId);

        // Add CSRF token to the header
        $.ajaxSetup({
            headers: {
                "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr("content"),
            },
        });

        $.ajax({
            url: targetUrl,
            type: "POST",
            data: formData,
            processData: false,
            contentType: false,
            success: function (response) {
                // console.log("Task updated successfully:", response);
                $("#editTaskModal").modal("hide");

                alert("Task updated successfully!");
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
// Delete task confirmation using SweetAlert
$(document).on("click", "#deleteButton", function (e) {
    e.preventDefault();
    var targetUrl = $(this).data("target-url");

    Swal.fire({
        title: "Are you sure?",
        text: "You won't be able to revert this!",
        icon: "warning",
        showCancelButton: true,
        confirmButtonColor: "#3085d6",
        cancelButtonColor: "#d33",
        confirmButtonText: "Yes, delete it!",
        showLoaderOnConfirm: true,
        preConfirm: () => {
            // Add CSRF token to the header
            $.ajaxSetup({
                headers: {
                    "X-CSRF-TOKEN": $('meta[name="csrf-token"]').attr(
                        "content"
                    ),
                },
            });

            return $.ajax({
                url: targetUrl,
                type: "DELETE",
                dataType: "json",
            }).catch((error) => {
                console.error("Error deleting task:", error);
                Swal.showValidationMessage(`Request failed: ${error}`);
            });
        },
        allowOutsideClick: () => !Swal.isLoading(),
    }).then((result) => {
        if (result.isConfirmed) {
            Swal.fire(
                "Deleted!",
                "Resource has been deleted successfully.",
                "success"
            );
            // Optionally, refresh the task list or update the UI
            location.reload();
        }
    });
});
// View task details
$(document).on("click", "#viewTaskBtn", function () {
    var targetUrl = $(this).data("target-url");
    // Fetch task details from the server
    $.ajax({
        url: targetUrl,
        type: "GET",
        dataType: "json",
        success: function (response) {
            // Populate the modal with task details
            var taskDetails = response.html;
            $("#viewTaskModal").modal("show");

            $("#viewTaskModal .modal-body").html(taskDetails);
        },
        error: function (xhr, status, error) {
            console.error("Error fetching task details:", error);
            alert("Failed to load task details. Please try again.");
        },
    });
});
