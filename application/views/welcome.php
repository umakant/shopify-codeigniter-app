<!DOCTYPE html>
<html>

    <head>
        <link href="<?php echo site_url(); ?>assets/css/seaff.css" type="text/css" rel="stylesheet">
        <script src="https://cdn.shopify.com/s/assets/external/app.js"></script>
        <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.3/css/bootstrap.min.css" integrity="sha384-MCw98/SFnGE8fJT3GXwEOngsV7Zt27NXFoaoApmYm81iuXoPkFOJwJ8ERdknLPMO" crossorigin="anonymous">
        <script type="text/javascript">
            ShopifyApp.init({
                apiKey : '<?php echo $api_key; ?>',
                shopOrigin : '<?php echo 'https://'  . $shop; ?>' 
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
    </head>

    <body>
        <div class="container-fluid">
            
            <div class="section">
                <div class="section-summary">
                    <h3>Shopify App is working.</h3>
                </div>
                <div class="section-content">
                    <div class="section-row">
                        <div class="section-cell">
                            <a href="/auth/show_products?shop=<?php echo $shop; ?>">Show Products</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </body>

</html>