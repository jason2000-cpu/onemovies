<?php
require_once("../DBConnection.php");
$qry = $conn->query("SELECT * FROM `featured_list`");
$ids = array();
    while($row = $qry->fetchArray()){
        $ids[] = $row['plan_id'];
    }
?>
<div class="container-fluid">
    <form action="" id="stock-form">
        <div class="form-group">
            <label for="plan_id" class="control-label">Plan</label>
            <select name="plan_id[]" id="plan_id" class="form-select form-select-sm select2-multiple" multiple required>
                <?php 
                $plan = $conn->query("SELECT * FROM `plan_list` where status = 1 ".(isset($ids) && count($ids) > 0 ? " OR plan_id in ('".(implode(',',$ids))."')" : '')." order by `title` asc ");
                while($row = $plan->fetchArray()):
                ?>
                <option value="<?php echo $row['plan_id'] ?>" <?php echo in_array($row['plan_id'],$ids) ? "selected" : "" ?>><?php echo $row['title'] ?></option>
                <?php endwhile; ?>
            </select>
        </div>
    </form>
</div>

<script>
    $(function(){
        $('#stock-form').submit(function(e){
            e.preventDefault();
            $('.pop_msg').remove()
            var _this = $(this)
            var _el = $('<div>')
                _el.addClass('pop_msg')
            $('#uni_modal button').attr('disabled',true)
            $('#uni_modal button[type="submit"]').text('submitting form...')
            $.ajax({
                url:'../Actions.php?a=save_featured',
                method:'POST',
                data:$(this).serialize(),
                dataType:'JSON',
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
                        if("<?php echo isset($stock_id) ?>" != 1)
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