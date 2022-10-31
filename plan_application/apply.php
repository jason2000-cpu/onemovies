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
<h2 class="text-center fs-1">Subscription Application Form</h2>
<center><hr class="bg-primary opacity-100" width="50px"></center>
<div class="col-12 py-5">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow rounded-0">
                <div class="card-body rounded-0 py-5">
                    <form action="" id="application-form">
                        <input type="hidden" name="plan_id" value="<?php echo $plan_id ?>">
                        <fieldset>
                            <legend class="text-center">Applicant Details</legend>
                            <center><hr class="bg-primary opacity-100" width="100px"></center>
                            <div class="form-group mb-4">
                                <div class="row mx-0">
                                    <div class="col-md-4">
                                        <label for="lastname" class="control-label text-muted">Last Name</label>
                                        <input type="text" class="form-control rounded-0 border-0 border-bottom" id="lastname" name="lastname" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="firstname" class="control-label text-muted">First Name</label>
                                        <input type="text" class="form-control rounded-0 border-0 border-bottom" id="firstname" name="firstname" required>
                                    </div>
                                    <div class="col-md-4">
                                        <label for="middlename" class="control-label text-muted">Middle Name</label>
                                        <input type="text" class="form-control rounded-0 border-0 border-bottom" id="middlename" name="middlename" placeholder="(optional)">
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <div class="row mx-0">
                                    <div class="col-md-6">
                                        <label for="gender" class="control-label text-muted">Gender</label>
                                        <select class="form-select rounded-0 border-0 border-bottom" id="gender" name="gender" required> 
                                            <option>Male</option>
                                            <option>Female</option>
                                        </select>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="date_of_birth" class="control-label text-muted">Date of Birth</label>
                                        <input type="date" class="form-control rounded-0 border-0 border-bottom" id="date_of_birth" name="date_of_birth" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <div class="row mx-0">
                                    <div class="col-md-6">
                                        <label for="email" class="control-label text-muted">Email</label>
                                        <input type="text" class="form-control rounded-0 border-0 border-bottom" id="email" name="email" required>
                                    </div>
                                    <div class="col-md-6">
                                        <label for="contact" class="control-label text-muted">Contact #</label>
                                        <input type="text" class="form-control rounded-0 border-0 border-bottom" id="contact" name="contact" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <div class="row mx-0">
                                    <div class="col-md-12">
                                        <label for="address" class="control-label text-muted">Address</label>
                                        <textarea rows="3" class="form-control rounded-0 border-0 border-bottom" id="address" name="address" required placeholder="i.e. Block 6 Lot 23, Here Village, There City" style="resize:none"></textarea>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group mb-4">
                                <div class="row mx-0">
                                    <div class="col-md-12">
                                        <label for="occupation" class="control-label text-muted">Occupation</label>
                                        <input type="text" class="form-control rounded-0 border-0 border-bottom" id="occupation" name="occupation" required>
                                    </div>
                                </div>
                                <div class="row mx-0">
                                    <div class="col-md-12">
                                        <label for="company_name" class="control-label text-muted">Company Name</label>
                                        <input type="text" class="form-control rounded-0 border-0 border-bottom" id="company_name" name="company_name" required>
                                    </div>
                                </div>
                            </div>
                            <div class="form-group text-center mt-5">
                                    <button class="btn btn-lg btn-primary w-50 rounded-pill" type="submit">Submit Application</button>
                            </div>
                        </fieldset>

                    </form>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card rounded-0 shadow">
                <div class="card-header rounded-0 bg-white">
                    <h5 class="card-title">
                    Plan to Apply
                    </h5>
                </div>
                <div class="card-body rounded-0 py-5">
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
</div>
<script>
    $(function(){
        $('#application-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            _this.find('button').attr('disabled',true)
            var btn_otxt = _this.find('button[type="submit"]').text()
            _this.find('button[type="submit"]').text('Please Wait...')
            $.ajax({
                url:'Actions.php?a=save_application',
                method:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                     _this.find('button').attr('disabled',false)
                     _this.find('button[type="submit"]').text(btn_otxt)
                },
                success:function(resp){
                    if(resp.status == 'success'){
                            location.href='./?page=view_plan&id=<?php echo $_GET['id'] ?>';
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                    $('html,body').animate({scrollTop:$('form').offset().top - 50},'fast')
                     _this.find('button').attr('disabled',false)
                     _this.find('button[type="submit"]').text(btn_otxt)
                }
            })
        })
    })
</script>