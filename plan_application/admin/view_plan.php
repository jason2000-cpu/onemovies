<?php
require_once("../DBConnection.php");
if(isset($_GET['id'])){
$qry = $conn->query("SELECT * FROM `plan_list` where plan_id = '{$_GET['id']}'");
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
    <div class="col-12">
        <dl>
            <dt class="text-muted">Title</dt>
            <dd class="lh-1 ps-4"><?php echo $title ?></dd>
            <dt class="text-muted">Description</dt>
            <dd class="lh-1 ps-4"><?php echo html_entity_decode($description) ?></dd>
            <dt class="text-muted">Status</dt>
            <dd class="lh-1 ps-4">
                <?php if($status == 1): ?>
                    <span class="badge bg-success rounded-pill"><small>Active</small></span>
                <?php else: ?>
                    <span class="badge bg-danger rounded-pill"><small>Inactive</small></span>
                <?php endif; ?>
            </dd>
        </dl>
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
        $('.select-img').click(function(){
            var imgPath = $(this).find('img').attr('src')
            $('#selected-image').attr('src',imgPath)
        })
        $('.img-del-btn>.btn').click(function(e){
            e.preventDefault()
            _conf("Are you sure to delete this plan image?","delete_img",["'"+$(this).attr('data-path')+"'"])
        })
        if('<?php echo isset($_GET['order_id']) ?>' == 1){
            $('#uni_modal').on('hidden.bs.modal',function(){
                if($('#uni_modal #plan-details').length > 0)
                uni_modal('Order Details',"view_order.php?id=<?php echo isset($_GET['order_id']) ? $_GET['order_id'] : '' ?>",'large')
            })
        }
    })
    function delete_img($path){
        $('#confirm_modal button').attr('disabled',true)
        $.ajax({
            url:"../Actions.php?a=delete_img",
            method:"POST",
            data:{path:$path},
            dataType:'json',
            error:err=>{
                console.log(err)
                alert("An error occurred.")
            },
            success:function(resp){
                if(resp.status == 'success'){
                    $('.img-del-btn>.btn[data-path="'+$path+'"]').closest('.img-item').remove()
                    $('#confirm_modal').modal('hide')
                }else{
                    console.log(resp)
                    alert("An error occurred.")
                }
            $('#confirm_modal button').attr('disabled',false)
            }
        })
    }
</script>