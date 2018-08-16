<script src="http://ajax.googleapis.com/ajax/libs/jquery/1.11.0/jquery.min.js"></script>
<?php
  $metafield_id = $metafields[0]->metafields[0]->id;
	$description_tag = $metafields[0]->metafields[0]->key;
	$description_value = $metafields[0]->metafields[0]->value;
	$title_tag = $metafields[0]->metafields[1]->key;	
	$title_value = $metafields[0]->metafields[1]->value;
  $product = $metafields[0]->metafields[0]->owner_id;
?>
<form name="post_meta" id="post_meta">
  <div class="form-group">
    <label for="meta_title" class="float-left">Meta Title:</label>
    <input type="meta_title" class="form-control" id="meta_title" name="meta_title" value="<?php if($title_tag=='title_tag'){	echo $title_value;	} ?>">
  </div>
  <div class="form-group">
    <label for="meta_desc" class="float-left">Meta Description:</label>
    <textarea name="meta_desc"  class="form-control" id="meta_desc"><?php if($description_tag=='description_tag'){	echo $description_value;	} ?></textarea>
  </div>
  <input type="hidden" name="product" id="product" value="<?php echo $product; ?>">
  <input type="hidden" name="shop" id="shop" value="<?php echo $shopifyData['shop']; ?>">
  <input type="hidden" name="metafield_id" id="metafield_id" value="<?php echo $metafield_id; ?>">
  <input type="hidden" name="updated_at" value="">
  <input type="hidden" name="updated_at" value="">
  <button type="submit" class="btn btn-default" id="post_meta">Submit</button>
</form>

<script type="text/javascript">
$(document).ready(function() {
    $('#post_meta').submit(function(e) {
      var meta_title = $('#meta_title').val();
      var meta_desc = $('#meta_desc').val();
      var metafield = $('#metafield_id').val();
            e.preventDefault();
          var form_url="/auth/update_meta";
          $.ajax({
            url: form_url,
            method: "POST",
            data: {
              shop: "<?php echo $shopifyData['shop']; ?>", 
              product: "<?php echo $product; ?>", 
              meta_title: meta_title, 
              meta_desc: meta_desc,
              metafield: metafield
            },
            //dataType: 'json',
            //async:true,
            success: function(data)
            { 
                alert('Meta Values Changed');
            }
          })
        });
    }( jQuery ));
</script>