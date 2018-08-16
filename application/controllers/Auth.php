<?php

class Auth extends CI_Controller{

    
    public function __construct()
    {
        parent::__construct();
        //Do your magic here
        $this->load->helper('url');
    }

    public function access(){
        
        $shop = $this->input->get('shop');

        if(isset($shop)){
            $this->session->set_userdata($shop);
        }
        

        if(($this->session->userdata('access_token'))){
            $data = array(
                'api_key' => $this->config->item('shopify_api_key'),
                'shop' => $shop
            );
            $this->load->view('welcome',$data);
        }
        else{
            $this->auth($shop);
        }
    }

    public function show_products(){

             $this->load->helper('url');
            
            $shop = $this->input->get('shop');

            $this->db->select('access_token');
            $this->db->where('shop', $shop);
            $query = $this->db->get('authentication');

            $results = $query->result();

            if(!empty($results)){
                $access_token = $results[0]->access_token; 
                $data = array(
                    'API_KEY' => $this->config->item('shopify_api_key'),
                    'API_SECRET' => $this->config->item('shopify_secret'),
                    'SHOP_DOMAIN' => $shop,
                    'ACCESS_TOKEN' => $access_token
                ); 

                $this->load->library('Shopify' , $data);

                $user_data = array(
                    'CHARSET'       => 'UTF-8',
                    'METHOD'        => 'GET',
                    'URL'           => '/admin/products.json',
                    'HEADERS'       => array(),
                    'DATA'          => array(),
                    'FAILONERROR'   => TRUE,
                    'RETURNARRAY'   => FALSE,
                    'ALLDATA'       => FALSE
                );

                $products = $this->shopify->call($user_data, $data);

                $products = array($products);

                $data = array(
                    'api_key' => $this->config->item('shopify_api_key'),
                    'shop' => $shop
                );
                
                $two_arr = array();

                if($products){
                    $two_arr['products'] = $products;
                }
                if($data){
                    $two_arr['shopifyData'] = $data;
                }
                
                $this->load->view('show_products', $two_arr);
            }
    }

    public function get_meta() {

            $shop = $_POST['shop'];
            $product = $_POST['product'];

            $this->db->select('access_token');
            $this->db->where('shop', $shop);
            $query = $this->db->get('authentication');

            $results = $query->result();

            if(!empty($results)){
                $access_token = $results[0]->access_token; 
                $data = array(
                    'API_KEY' => $this->config->item('shopify_api_key'),
                    'API_SECRET' => $this->config->item('shopify_secret'),
                    'SHOP_DOMAIN' => $shop,
                    'ACCESS_TOKEN' => $access_token
                ); 

                $this->load->library('Shopify' , $data);

                $shop_data = array();

                $shop_data[] = $shop;
                $shop_data[] = $product; 

                $user_data = array(
                    'CHARSET'       => 'UTF-8',
                    'METHOD'        => 'GET',
                    'URL'           => '/admin/products/'.$product.'/metafields.json',
                    'HEADERS'       => array(),
                    'DATA'          => array(),
                    'FAILONERROR'   => TRUE,
                    'RETURNARRAY'   => FALSE,
                    'ALLDATA'       => FALSE
                );

                $metafields = $this->shopify->call($user_data, $data);

                $metafields = array($metafields);

                $data = array(
                    'api_key' => $this->config->item('shopify_api_key'),
                    'shop' => $shop
                );
                
                $two_arr = array();

                if($metafields){
                    $two_arr['metafields'] = $metafields;
                }
                if($data){
                    $two_arr['shopifyData'] = $data;
                }
                
                $this->load->view('change_meta', $two_arr);
            }
        
    }
    
    public function update_meta() {

            $shop = $_POST['shop'];
            $product = $_POST['product'];
            $metafield = $_POST['metafield'];
            $meta_title = $_POST['meta_title'];
            $meta_desc = $_POST['meta_desc'];

            $this->db->select('access_token');
            $this->db->where('shop', $shop);
            $query = $this->db->get('authentication');

            $results = $query->result();

            if(!empty($results)){
                $access_token = $results[0]->access_token; 
                $data = array(
                    'API_KEY' => $this->config->item('shopify_api_key'),
                    'API_SECRET' => $this->config->item('shopify_secret'),
                    'SHOP_DOMAIN' => $shop,
                    'ACCESS_TOKEN' => $access_token
                ); 

                $this->load->library('Shopify' , $data);

                $metafields_values = array();

                $metafields_values['id'] = $metafield;
                $metafields_values['value'] = $meta_desc;
                $metafields_values['value_type'] = 'string';

                $metafields_data['metafield'] = $metafields_values;

                $user_data = array(
                    'CHARSET'       => 'UTF-8',
                    'METHOD'        => 'PUT',
                    'URL'           => '/admin/products/'.$product.'/metafields/'.$metafield.'.json',
                    'HEADERS'       => array(),
                    'DATA'          => $metafields_data,
                    'FAILONERROR'   => TRUE,
                    'RETURNARRAY'   => FALSE,
                    'ALLDATA'       => FALSE
                );

                $metafields = $this->shopify->call($user_data, $data);
            }

            return $metafields;
        
    }
    

    public function auth($shop){
        $data = array(
            'API_KEY' => $this->config->item('shopify_api_key'),
            'API_SECRET' => $this->config->item('shopify_secret'),
            'SHOP_DOMAIN' => $shop,
            'ACCESS_TOKEN' => ''
        );

        $this->load->library('Shopify' , $data); //load shopify library and pass values in constructor

        $scopes = array('read_orders','write_orders', 'read_products', 'write_products', 'read_content', 'write_content', 'read_product_listings', 'read_customers', 'write_customers', 'read_analytics'); //what app can do
        $redirect_url = $this->config->item('redirect_url'); //redirect url specified in app setting at shopify

        $paramsforInstallURL = array(
            'scopes' => $scopes,
            'redirect' => $redirect_url
        );

        $permission_url = $this->shopify->installURL($paramsforInstallURL);
         
        $this->load->view('auth/escapeIframe', ['installUrl' => $permission_url]);
        
    }

    public function authCallback(){

        $code = $this->input->get('code');
        $shop = $this->input->get('shop');

            if(isset($code)){
                $data = array(
                'API_KEY' => $this->config->item('shopify_api_key'),
                'API_SECRET' => $this->config->item('shopify_secret'),
                'SHOP_DOMAIN' => $shop,
                'ACCESS_TOKEN' => ''
            );
            $this->load->library('Shopify' , $data); //load shopify library and pass values in constructor
        }

        $accessToken = $this->shopify->getAccessToken($code);
        $this->session->set_userdata(['shop' => $shop , 'access_token' => $accessToken]);

        if($accessToken){

            $shopdata = array(
                'shop'=>$shop,
                'access_token'=>$accessToken
            );

            $this->db->insert('authentication', $shopdata);
               
        }

        redirect('https://'.$shop.'/admin/apps');
    }

    
}