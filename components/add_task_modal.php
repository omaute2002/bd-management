<div class="modal fade" id="taskModal" tabindex="-1" aria-labelledby="taskModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="taskModalLabel">Add New Task</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form id="taskForm">
                    <div class="mb-3">
                        <label for="task_title" class="form-label">Task Title</label>
                        <input type="text" class="form-control" id="task_title" name="task_title" required>
                    </div>

                    <div class="mb-3">
                        <label for="platform" class="form-label">Platform</label>
                        <select name="platform" id="platform" class="form-select">
                            <option value="Instagram">Instagram</option>
                            <option value="Facebook">Facebook</option>
                            <option value="Twitter">X (Twitter)</option>
                            <option value="Linkedin">LinkedIn</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">Description</label>
                        <textarea class="form-control" id="description" name="description" rows="3"></textarea>
                    </div>
                    <div class="mb-3">
                        <label for="status" class="form-label">status</label>
                        <select name="status" id="status" class="form-select">
                            <option value="Pending">Pending</option>
                            <option value="In-progress">In Progress</option>
                            <option value="Done">Done</option>

                        </select>
                    </div>
                    <div class="d-flex justify-content-between mb-3">
                        <div class="flex-fill me-2">
                            <label for="due_date" class="form-label">Due Date</label>
                            <input type="date" class="form-control" id="due_date" name="due_date">
                        </div>
                        <div class="flex-fill ms-2">
                            <label for="uploading_date" class="form-label">Uploading Date</label>
                            <input type="date" class="form-control" id="uploading_date" name="uploading_date">
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="created_by" class="form-label">Created By</label>
                        <select name="created_by" id="created_by" class="form-select">
                            <option default disabled value="">--select--</option>
                            <?php
                            $users = "SELECT * FROM `users` WHERE user_type = 'Admin'  ORDER BY `username` ASC";
                            $result_user = mysqli_query($conn, $users);
                            while ($row_select = mysqli_fetch_assoc($result_user)) {
                                echo "<option default value='{$row_select['user_id']}'>{$row_select['username']}</option>";
                            }
                            ?>
                        </select>

                    </div>

                    <div class="mb-3">
                        <label for="content_type" class="form-label">Content Type</label>
                        <select name="content_type" id="content_type" class="form-select">
                            <option value="" selected>--select--</option>
                            <option value="Post">Post</option>
                            <option value="Reel">Reel</option>
                            <option value="Image-story">Image Story</option>
                            <option value="Carousel">Carousel</option>
                            <option value="Video-story">Video Story</option>
                            <option value="Polls">Polls</option>
                            <option value="Articles">Articles</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="content_size" class="form-label">Content Size</label>
                        <input type="number" class="form-control" id="content_size" name="content_size">
                    </div>

                    <div class="mb-3">
                        <label for="company" class="form-label">Company</label>
                        <select name="company" id="company" class="form-select">
                            <option value="" selected>--select--</option>
                            <?php
                            $companies = "SELECT * FROM `companies`";
                            $result_companies = mysqli_query($conn, $companies);
                            while ($row_select = mysqli_fetch_assoc($result_companies)) {
                                echo "<option value='{$row_select['company_id']}'>{$row_select['company_name']}</option>";
                            }
                            ?>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="assign_to" class="form-label">Assign to</label>
                        <select name="assign_to" id="assign_to" class="form-select">
                            <option value="" disabled selected>Select</option>
                            <?php
                            $users = "SELECT * FROM `users` WHERE user_type = 'Artist' OR user_type = 'Production Head' ORDER BY `username` ASC";
                            $result_user = mysqli_query($conn, $users);
                            while ($row_select = mysqli_fetch_assoc($result_user)) {
                                echo "<option value='{$row_select['user_id']}'>{$row_select['username']}</option>";
                            }
                            ?>
                        </select>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                <button type="button" class="btn btn-primary" id="submitTask">Submit</button>
            </div>
        </div>
    </div>
</div>