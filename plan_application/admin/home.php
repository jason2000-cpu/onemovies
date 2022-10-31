<h3>Welcome to <?php echo $_SESSION['system_info']['company_name'] ?></h3>
<hr>
<div class="col-12">
    <div class="row gx-3 row-cols-4">
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center">
                        <div class="col-auto pe-1">
                            <span class="fa fa-th-list fs-3 text-success"></span>
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="fs-5"><b>Active Plans</b></div>
                            <div class="fs-6 text-end fw-bold">
                                <?php 
                                $plans = $conn->query("SELECT count(plan_id) as `count` FROM `plan_list` where `status` = 1 ")->fetchArray()['count'];
                                echo $plans > 0 ? number_format($plans) : 0 ;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center">
                        <div class="col-auto pe-1">
                            <span class="fa fa-th-list fs-3 text-muted"></span>
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="fs-5"><b>Inactive Plans</b></div>
                            <div class="fs-6 text-end fw-bold">
                                <?php 
                                $plans = $conn->query("SELECT count(plan_id) as `count` FROM `plan_list` where `status` = 0 ")->fetchArray()['count'];
                                echo $plans > 0 ? number_format($plans) : 0 ;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center">
                        <div class="col-auto pe-1">
                            <span class="fa fa-file-alt fs-3 text-secondary"></span>
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="fs-5"><b>Pending Application</b></div>
                            <div class="fs-6 text-end fw-bold">
                                <?php 
                                $applications = $conn->query("SELECT count(application_id) as `count` FROM `application_list` where `status` = 0 ")->fetchArray()['count'];
                                echo $applications > 0 ? number_format($applications) : 0 ;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col">
            <div class="card">
                <div class="card-body">
                    <div class="w-100 d-flex align-items-center">
                        <div class="col-auto pe-1">
                            <span class="fa fa-file-alt fs-3 text-primary"></span>
                        </div>
                        <div class="col-auto flex-grow-1">
                            <div class="fs-5"><b>Confirmed Application</b></div>
                            <div class="fs-6 text-end fw-bold">
                                <?php 
                                $applications = $conn->query("SELECT count(application_id) as `count` FROM `application_list` where `status` = 1 ")->fetchArray()['count'];
                                echo $applications > 0 ? number_format($applications) : 0 ;
                                ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>