<div class="mb-3 row align-items-start">
    <hr>
    <!-- generated -->
    <div class="container">
        <h4 class="fw-bolder text-success">Program Configuration</h4>
        <div class="position-relative p-2">
            <div class="card-body position-relative">
                <form method="post" id="configureProgramForm">
                    <input type="hidden" id="action_indicator" name="action" value="updateProgram">
                    <input type="hidden" name="program_id" value="<?php echo $retrieved_program_ss['data'][0]['program_id'] ?>">
                    <section class="mb-4 position-relative">
                        <div class="row mb-3">
                            <h5 class="display-7 text-start">Program Information</h5>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <h6>Program Image</h6>
                                <div>
                                    <img style="width: 200px;" src="<?= !empty($retrieved_program_ss['data'][0]['program_image']) ? 'data:image/jpeg;base64,' . base64_encode($retrieved_program_ss['data'][0]['program_image']) : 'https://via.placeholder.com/200?text=No+Image' ?>" alt="">
                                </div>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-sm-6 col-md-4 col-lg-5">
                                <h6>Program Name<code class="fs-6">*</code></h6>
                                <input name="input_programName" class="form-control" type="text" value="<?= htmlspecialchars($retrieved_program_ss['data'][0]['program_name']) ?>">
                            </div>
                            <div class="col-sm-6 col-md-4 col-lg-3">
                                <h6>Program Code<code class="fs-6">*</code></h6>
                                <input name="input_programCode" class="form-control" type="text" value="<?= htmlspecialchars($retrieved_program_ss['data'][0]['program_code']) ?>">
                            </div>
                            <div class="col-sm-12 col-md-4 col-lg-4">
                                <h6>Educational Level</h6>
                                <select class="form-select" disabled>
                                    <option selected value="<?= htmlspecialchars($retrieved_program_ss['data'][0]['educational_level']) ?>"><?= htmlspecialchars($retrieved_program_ss['data'][0]['educational_level']) ?></option>
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <h6>Description<code class="fs-6">*</code></h6>
                                <textarea name="input_programDescription" class="form-control" rows="8" placeholder="No Description given"><?= htmlspecialchars($retrieved_program_ss['data'][0]['program_description']) ?></textarea>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12 d-flex justify-content-end gap-2">
                                <button class="btn btn-success" type="submit">
                                    <i class="bi bi-floppy-fill"></i>
                                    Update
                                </button>
                                <span class="btn btn-danger" id="btnDeleteProgram">
                                    <i class="bi bi-trash"></i>
                                    Delete Program
                                </span>
                                <script>
                                    document.getElementById("configureProgramForm").addEventListener("submit", function(event) {
                                        // Only proceed if the submit button is clicked
                                        if (event.submitter && event.submitter.type === "submit") {
                                            // Allow the form submission if it's the submit button
                                            return true;
                                        }

                                        // If it's the delete button
                                        event.preventDefault(); // Prevent form submission
                                        if (confirm("Are you sure you want to delete this item?")) {
                                            // If user clicks "Yes", change the action to delete and submit the form
                                            // Add a hidden field or change the form action to handle delete
                                            document.getElementById("action_indicator").value = "deleteProgram";

                                            let form = event.target;
                                            form.submit(); // Submit the form with delete action
                                        } else {
                                            // If user clicks "No", just do nothing
                                            console.log("Delete action canceled.");
                                        }
                                    });

                                    document.getElementById("btnDeleteProgram").addEventListener("click", function() {
                                        // Simulate form submission for delete
                                        document.querySelector("form").requestSubmit();
                                    });
                                </script>
                            </div>
                        </div>
                    </section>
                </form>

            </div>
        </div>
    </div>
</div>