<!DOCTYPE html>
<html>

    <head>
        <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>

        <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.1.1/jquery.min.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css">
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.7/js/bootstrap.min.js"></script>
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap3-dialog/1.34.7/js/bootstrap-dialog.min.js"></script>

        
        <script type="text/javascript">
            ShopifyApp.init({                
                    apiKey : '<?php echo $shopifyData['api_key']; ?>',
                    shopOrigin : '<?php echo 'https://'  . $shopifyData['shop']; ?>' 
            });
        </script>
        <script type="text/javascript">
            ShopifyApp.ready(function(){
                ShopifyApp.Bar.initialize({
                buttons: {
                    primary: {
                    label: 'Save',
                    message: 'unicorn_form_submit',
                    loading: true
                    }
                }
                });
            });
        </script>
        <style type="text/css">
            .card-body a.btn {
                color: #fff;
            }
        </style>
    </head>

    <body>
        <div class="container-fluid">
            <h3>Change Meta Tags in Products</h3>
            <div class="section">
                <div class="section-summary">
                </div>
                <div class="section-content">
                    <div class="section-row">
                        <div class="section-cell">
                            <div class="row">
                            <?php
                                for($i=0;$i<count($products[0]->products);$i++){ ?>
                                    <div class="card" style="width: 50%;">
                                        <div class="row">
                                            <div class="col-md-3">
                                                <img class="card-img-top" src="<?php echo $products[0]->products[$i]->images[0]->src; ?>" alt="<?php echo $products[0]->products[$i]->title; ?>">
                                            </div>
                                            <div class="col-md-9">
                                              <div class="card-body text-center">
                                                <h5 class="card-title"><?php echo $products[0]->products[$i]->title; ?></h5>
                                                <p class="card-text"><?php echo $products[0]->products[$i]->vendor; ?>, <?php echo $products[0]->products[$i]->product_type; ?></p>
                                                <a id="getmeta<?php echo $products[0]->products[$i]->id; ?>" class="btn btn-primary">Change Meta Tags</a>
                                                
                                                <!-- Modal -->
                                                    <div class="modal" id="Modal<?php echo $products[0]->products[$i]->id; ?>" role="dialog">
                                                      <div class="modal-dialog" role="document">
                                                        <div class="modal-content">
                                                          <div class="modal-header">
                                                            <h5 class="modal-title" id="exampleModalLabel">Meta Tags of <?php echo $products[0]->products[$i]->title; ?> Product</h5>
                                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                              <span aria-hidden="true">&times;</span>
                                                            </button>
                                                          </div>
                                                          <div class="modal-body">
                                                            <div id="dynamic-data<?php echo $products[0]->products[$i]->id; ?>"></div>
                                                          </div>
                                                        </div>
                                                      </div>
                                                    </div>
                                              </div>
                                            </div>
                                        </div>
                                    </div>

                                    <script type="text/javascript">
                                        $( document ).ready(function($) {
                                            $("#getmeta<?php echo $products[0]->products[$i]->id; ?>").click(function(e){
                                                e.preventDefault();
                                              var form_url="/auth/get_meta";
                                              $.ajax({
                                                url: form_url,
                                                method: "POST",
                                                data: {shop: "<?php echo $shopifyData['shop']; ?>", product: "<?php echo $products[0]->products[$i]->id; ?>"},
                                                dataType: 'html',
                                                async:true,
                                                success: function(data)
                                                {
                                                    console.log(data);
                                                    $("#Modal<?php echo $products[0]->products[$i]->id; ?>").modal('show');
                                                    $("#dynamic-data<?php echo $products[0]->products[$i]->id; ?>").html(data);
                                                }
                                              })
                                            });
                                        }( jQuery ));
                                    </script>
                                <?php } ?>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>