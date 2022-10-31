<?php 
 if(!isset($_SESSION['system_info'])){
    echo '<script>alert("An error occured. Please Refreshed the page");</script>';
    exit;
}
?>
<h2 class="text-center fs-1"><?php echo isset($_SESSION['system_info']['company_name']) ? $_SESSION['system_info']['company_name'] : "Sample Company" ?></h2>
<center><hr class="bg-primary opacity-100" width="50px"></center>
<div class="col-12 py-3">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow rounded-0">
                <div class="card-body rounded-0 py-5">
                    <h2 class="text-center">ABOUT US</h2>
                    <center><hr class="bg-primary opacity-100" width="75px"></center>
                    <div>
                        <?php include 'about.html' ?>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card rounded-0 shadow">
                <div class="card-header rounded-0 bg-white">
                    <h5 class="card-title">
                    Information
                    </h5>
                </div>
                <div class="card-body rounded-0 py-2">
                    <dl>
                        <?php 
                        $system = $conn->query("SELECT * FROM `system_info`");
                        while($row=$system->fetchArray()):
                            if($row['meta_field'] == 'logo_path' || $row['meta_field'] == 'website_cover')
                                continue;
                        ?>  
                            <dt class="text-muted"><?php echo ucwords(str_replace('_',' ',$row['meta_field'])) ?></dt>
                            <dd class="ps-3">
                                <?php echo $row['meta_value'] ?>
                            </dd>
                        <?php endwhile; ?>  
                    </dl>
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