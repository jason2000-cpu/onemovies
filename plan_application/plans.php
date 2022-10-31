<h2 class="text-center fs-1">Plans</h2>
<center><hr class="bg-primary opacity-100" width="50px"></center>

<div class="row row-cols-1 row-cols-sm-1 row-cols-md-3 row-cols-xl-3 gx-5 gy-5">
    <?php 
    $plans = $conn->query("SELECT * FROM plan_list ");
    while($row = $plans->fetchArray()):
    ?>
    <div class="col">
        <div class="card shadow rounded-0 h-100">
            <div class="card-body rounded-0">
                <h5 class="card-title text-muted text-center"><?php echo $row['title'] ?></h5>
                <center><hr class="bg-primary opacity-100" width="30px"></center>
                <center>
                    <span class="fw-bold fs-4 plan-currency"><sup>Ksh</sup></span>
                    <span class="fw-bolder fs-2 plan-price"><?php echo number_format($row['current_price']) ?></span>
                    <span class="text-muted"><sub>/<?php echo $row['subscription_type'] ?></sub></span>
                    <?php if($row['before_price'] > 0): ?>
                    <br>
                    <span class="text-muted">
                        <span class="fw-bold fs-6"><sup>Ksh</sup></span>
                        <span class="fw-bolder fs-5  text-decoration-line-through"><?php echo number_format($row['before_price']) ?></span>
                        <span class="text-muted"><sub>Before</sub></span>
                    </span>
                    <?php endif; ?>
                </center>
                <hr>
                <div class="text-muted truncate-8">
                    <?php echo html_entity_decode($row['description']) ?>
                </div>
            </div>
            <div class="card-footer rounded-0 bg-white text-center">
                    <a href="./?page=view_plan&id=<?php echo $row['plan_id'] ?>" class="btn btn-primary rounded-pill w-75">Learn More</a>
            </div>
        </div>
    </div>
    <?php endwhile; ?>
</div>