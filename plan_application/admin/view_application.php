<?php
require_once("../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT a.*,p.title,p.current_price,p.subscription_type FROM `application_list` a inner join `plan_list` p on a.plan_id = p.plan_id where a.application_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
?>
<style>
    #uni_modal .modal-footer{
        display:none;
    }
</style>

<div class="container-fluid" id="plan-details">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="card rounded-0 shadow">
                <div class="card-header rounded-0 bg-white">
                    <h5 class="card-title">
                    Plan to Apply
                    </h5>
                </div>
                <div class="card-body rounded-0">
                    <h2 class="text-center fs-4"><?php echo $title ?></h2>
                    <center><hr class="bg-primary opacity-100" width="50px"></center>
                    <center>
                        <span class="fw-bold fs-4 plan-currency"><sup>$</sup></span>
                        <span class="fw-bolder fs-2 plan-price"><?php echo number_format($current_price) ?></span>
                        <span class="text-muted"><sub>/<?php echo $subscription_type ?></sub></span>
                    </center>
                </div>
            </div>
        </div>
    </div>
    <div class="col-12 mt-3">
        <?php 
            $meta_qry = $conn->query("SELECT * FROM application_meta where meta_field not in ('firstname','lastname','middlename') and application_id ='{$application_id}'");
            while($row = $meta_qry->fetchArray()):
                $meta[$row['meta_field']] = $row['meta_value'];
            endwhile; 
        ?>
        <div class="row">
            <div class="col-md-6">
            <dl>
                <dt class="text-muted">Application Code</dt>
                <dd class="lh-1 ps-4"><?php echo $application_code ?></dd>
                <dt class="text-muted">Applicant Name</dt>
                <dd class="lh-1 ps-4"><?php echo ucwords($fullname) ?></dd>
                <dt class="text-muted">Gender</dt>
                <dd class="lh-1 ps-4"><?php echo isset($meta['gender']) ? $meta['gender'] : 'N/A' ?></dd>
                <dt class="text-muted">Email</dt>
                <dd class="lh-1 ps-4"><?php echo isset($meta['email']) ? $meta['email'] : 'N/A' ?></dd>
                <dt class="text-muted">Contact #</dt>
                <dd class="lh-1 ps-4"><?php echo isset($meta['contact']) ? $meta['contact'] : 'N/A' ?></dd>
            </dl>
            </div>
            <div class="col-md-6">
            <dl>
                <dt class="text-muted">Address</dt>
                <dd class="lh-1 ps-4"><?php echo isset($meta['address']) ? $meta['address'] : 'N/A' ?></dd>
                <dt class="text-muted">Occupation</dt>
                <dd class="lh-1 ps-4"><?php echo isset($meta['occupation']) ? $meta['occupation'] : 'N/A' ?></dd>
                <dt class="text-muted">Company Name</dt>
                <dd class="lh-1 ps-4"><?php echo isset($meta['company_name']) ? $meta['company_name'] : 'N/A' ?></dd>
                <dt class="text-muted">Status</dt>
                <dd class="lh-1 ps-4">
                    <?php if($status == 0): ?>
                        <span class="badge bg-secondary rounded-pill"><small>Pending</small></span>
                    <?php elseif($status == 1): ?>
                        <span class="badge bg-primary rounded-pill"><small>Confirmed</small></span>
                    <?php else: ?>
                        <span class="badge bg-danger rounded-pill"><small>Cancelled</small></span>
                    <?php endif; ?>
                    <a href="javascript:void(0)" id="edit_status" class="text-decoration-none text-dark"><i class="fa fa-edit"></i> Edit Status</a>
                </dd>
            </dl>
            </div>
        </div>
        
    </div>
    <div class="col-12">
        <div class="row justify-content-end">
            <div class="col-1">
                <div class="btn btn btn-dark btn-sm rounded-0" type="button" data-bs-dismiss="modal">Close</div>
            </div>
        </div>
    </div>
</div>
<script>
    $(function(){
        $('#edit_status').click(function(){
            uni_modal_secondary('Application Details',"manage_application_status.php?id=<?php echo $application_id ?>&status=<?php echo $status ?>")
        })
        
    })
</script>