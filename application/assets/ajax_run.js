$( document ).ready(function() {
    $("#getmeta<?php echo $products[0]->products[$i]->id; ?>").click(function(e){
        e.preventDefault();
        var form_url="http://shopifysrm.allinonetutorials.com/auth/change_meta";

      $.ajax({
        url: form_url,
        method: "POST",
        data: {shop: "<?php echo $shopifyData['shop']; ?>", product: "<?php echo $products[0]->products[$i]->id; ?>"},
        dataType: 'json',
        success: function(data)
        {
            alert('success');
            //jQuery("#dynamic-data").text(data);
            console.log('HTMNL');
            console.log(data);
            //jQuery("#Modal<?php echo $products[0]->products[$i]->id; ?>").modal('show');
        }
      })
    }); 
}); 