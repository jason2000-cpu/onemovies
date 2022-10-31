<?php
require_once("../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `plan_list` where plan_id = '{$_GET['id']}'");
    foreach($qry->fetchArray() as $k => $v){
        $$k = $v;
    }
}
?>
<div class="container-fluid">
    <form action="" id="plan-form">
        <input type="hidden" name="id" value="<?php echo isset($plan_id) ? $plan_id : '' ?>">
        <div class="col-12">
            <div class="row">
                <div class="col-md-12">
                    <div class="form-group">
                        <label for="title" class="control-label">Title</label>
                        <input type="text" name="title" autofocus id="title" required class="form-control form-control-sm rounded-0" value="<?php echo isset($title) ? $title : '' ?>">
                    </div>
                    <div class="form-group">
                        <label for="description" class="control-label">Description</label>
                        <textarea name="description" id="description" cols="30" rows="4" required class="form-control rounded-0 summernote"><?php echo isset($description) ? html_entity_decode($description) : '' ?></textarea>
                    </div>
                    <div class="form-group">
                        <label for="current_price" class="control-label">Current Price</label>
                        <input type="text" pattern="[0-9.]+" name="current_price" id="current_price" required class="form-control form-control-sm rounded-0" value="<?php echo isset($current_price) ? $current_price : 0 ?>">
                    </div>
                    <div class="form-group">
                        <label for="before_price" class="control-label">Old Price</label>
                        <input type="text" pattern="[0-9.]+" name="before_price" id="before_price" required class="form-control form-control-sm rounded-0" value="<?php echo isset($before_price) ? $before_price : 0 ?>">
                    </div>
                    <div class="form-group">
                        <label for="subscription_type" class="control-label">Subscription Type</label>
                        <input type="text" name="subscription_type" id="subscription_type" required class="form-control form-control-sm rounded-0" value="<?php echo isset($subscription_type) ? $subscription_type : "" ?>" placeholder="(Monthly, Annually)">
                    </div>
                    <div class="form-group">
                        <label for="status" class="control-label">Status</label>
                        <select name="status" id="status" class="form-select form-select-sm rounded-0">
                            <option value="1" <?php echo (isset($status) && $status == 1 ) ? 'selected' : '' ?>>Active</option>
                            <option value="0" <?php echo (isset($status) && $status == 0 ) ? 'selected' : '' ?>>Inactive</option>
                        </select>
                    </div>
                </div>
            </div>
        </div>
    </form>
</div>

<script>
    $(function(){
        $('#plan-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled',true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'../Actions.php?a=save_plan',
                data: new FormData($(this)[0]),
                cache: false,
                contentType: false,
                processData: false,
                method: 'POST',
                type: 'POST',
                dataType: 'json',
                error:err=>{
                    console.log(err)
                    _el.addClass('alert alert-danger')
                    _el.text("An error occurred.")
                    _this.prepend(_el)
                    _el.show('slow')
                     
                    $('html, body,.modal').animate({scrollTop:0},'fast')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Save')
                },
                success:function(resp){
                    if(resp.status == 'success'){
                        _el.addClass('alert alert-success')
                        $('#uni_modal').on('hide.bs.modal',function(){
                            location.reload()
                        })
                        if("<?php echo isset($plan_id) ?>" != 1)
                        _this.get(0).reset();
                    }else{
                        _el.addClass('alert alert-danger')
                    }
                    _el.text(resp.msg)

                    _el.hide()
                    _this.prepend(_el)
                    _el.show('slow')
                     
                    $('html, body,.modal').animate({scrollTop:0},'fast')
                     $('#uni_modal button').attr('disabled',false)
                     $('#uni_modal button[type="submit"]').text('Save')
                }
            })
        })
    })
</script>