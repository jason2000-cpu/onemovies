
<?php 
 if(isset($_GET['id'])){
    $plan = $conn->query("SELECT * FROM plan_list where plan_id = '{$_GET['id']}' ");
    $res = $plan->fetchArray();
    if($res){
        foreach($res as $k=> $v){
            $$k = $v;
        }
    }
}else{
    echo '<script>alert("Plan ID is required on this page.");location.replace("./")</script>';
}
?>
<div class="col-12 pb-5">
    <div class="card rounded-0 shadow">
        <div class="card-body rounded-0">
            <h2 class="text-center fs-1"><?php echo $title ?></h2>
            <center><hr class="bg-primary opacity-100" width="50px"></center>
            <center>
                <span class="fw-bold fs-4 plan-currency"><sup>$</sup></span>
                <span class="fw-bolder fs-2 plan-price"><?php echo number_format($current_price) ?></span>
                <span class="text-muted"><sub>/<?php echo $subscription_type ?></sub></span>
                <?php if($before_price > 0): ?>
                <span class="text-muted">
                    <span class="fw-bold fs-6"><sup>$</sup></span>
                    <span class="fw-bolder fs-5  text-decoration-line-through"><?php echo number_format($before_price) ?></span>
                    <span class="text-muted"><sub>Before</sub></span>
                </span>
                <?php endif; ?>
                <br>
                <hr>
            </center>
            <div>
            <?php echo html_entity_decode($description) ?>
            </div>
        </div>
        <div class="card-footer rounded-0 bg-white text-center">
            <a href="./?page=apply&id=<?php echo $_GET['id'] ?>" class="btn btn-primary btn-lg rounded-pill col-md-4">Apply Now</a><br>
            <span class="text-muted"><small><i>subscribe</i></small></span>
        </div>
    </div>
</div>


